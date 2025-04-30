<?php

namespace SilverStripe\ForagerElasticEnterprise\Adaptors\Requests;

use BadMethodCallException;
use Elastic\EnterpriseSearch\AppSearch\Request\PutSynonymSet;
use Elastic\EnterpriseSearch\AppSearch\Schema\SynonymSet;
use Elastic\EnterpriseSearch\Client;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forager\Interfaces\Requests\UpdateSynonymRuleAdaptor as PatchSynonymRuleAdaptorInterface;
use SilverStripe\Forager\Service\Query\SynonymRule as SynonymRuleQuery;
use SilverStripe\Forager\Service\Results\SynonymRule as SynonymRuleResult;

class UpdateSynonymRuleAdaptor implements PatchSynonymRuleAdaptorInterface
{

    private ?Client $client = null;

    private static array $dependencies = [
        'client' => '%$' . Client::class . '.managementClient',
    ];

    public function setClient(?Client $client): void
    {
        $this->client = $client;
    }

    public function process(
        int|string $synonymCollectionId,
        int|string $synonymRuleId,
        SynonymRuleQuery $synonymRule
    ): string|int {
        if ($synonymRule->getType() === SynonymRuleResult::TYPE_DIRECTIONAL) {
            throw new BadMethodCallException('Explicit synonyms are not supported in Elastic Enterprise Search');
        }

        $synonyms = Injector::inst()->create(SynonymSet::class, $synonymRule->getSynonyms());
        // Even though the Elastic Enterprise Search SDK request is called PUT, it's actually a PATCH, as it requires
        // the record to already exist
        $request = Injector::inst()->create(
            PutSynonymSet::class,
            $synonymCollectionId,
            $synonymRuleId,
            $synonyms
        );

        // Should either be successful or throw an exception, which we'll let fly
        $body = $this->client->appSearch()->putSynonymSet($request)->asString();
        $body = json_decode($body, true);

        return $body['id'];
    }

}
