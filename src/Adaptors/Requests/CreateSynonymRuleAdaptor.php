<?php

namespace SilverStripe\ForagerElasticEnterprise\Adaptors\Requests;

use BadMethodCallException;
use Elastic\EnterpriseSearch\AppSearch\Request\CreateSynonymSet;
use Elastic\EnterpriseSearch\AppSearch\Schema\SynonymSet;
use Elastic\EnterpriseSearch\Client;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forager\Interfaces\Requests\CreateSynonymRuleAdaptor as PostSynonymRuleAdaptorInterface;
use SilverStripe\Forager\Service\Query\SynonymRule as SynonymRuleQuery;
use SilverStripe\Forager\Service\Results\SynonymRule as SynonymRuleResult;

class CreateSynonymRuleAdaptor implements PostSynonymRuleAdaptorInterface
{

    private ?Client $client = null;

    private static array $dependencies = [
        'client' => '%$' . Client::class . '.managementClient',
    ];

    public function setClient(?Client $client): void
    {
        $this->client = $client;
    }

    public function process(int|string $synonymCollectionId, SynonymRuleQuery $synonymRule): SynonymRuleResult
    {
        if ($synonymRule->getType() === SynonymRuleResult::TYPE_DIRECTIONAL) {
            throw new BadMethodCallException('Explicit synonyms are not supported in Elastic Enterprise Search');
        }

        $synonyms = Injector::inst()->create(SynonymSet::class, $synonymRule->getSynonyms());
        $request = Injector::inst()->create(CreateSynonymSet::class, $synonymCollectionId, $synonyms);

        // Should either be successful or throw an exception, which we'll let fly
        $body = $this->client->appSearch()->createSynonymSet($request)->asString();
        $body = json_decode($body, true);

        $synonymRuleResult = SynonymRuleResult::create($body['id']);
        $synonymRuleResult->setType(SynonymRuleResult::TYPE_EQUIVALENT);
        $synonymRuleResult->setSynonyms($body['synonyms']);

        return $synonymRuleResult;
    }

}
