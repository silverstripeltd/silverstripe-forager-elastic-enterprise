<?php

namespace SilverStripe\ForagerElasticEnterprise\Adaptors\Requests;

use Elastic\EnterpriseSearch\AppSearch\Request\ListSynonymSets;
use Elastic\EnterpriseSearch\Client;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forager\Interfaces\Requests\GetSynonymRulesAdaptor as GetSynonymRulesAdaptorInterface;
use SilverStripe\Forager\Service\Results\SynonymRule;
use SilverStripe\Forager\Service\Results\SynonymRules;

class GetSynonymRulesAdaptor implements GetSynonymRulesAdaptorInterface
{

    private ?Client $client = null;

    private static array $dependencies = [
        'client' => '%$' . Client::class . '.managementClient',
    ];

    public function setClient(?Client $client): void
    {
        $this->client = $client;
    }

    public function process(int|string $synonymCollectionId): SynonymRules
    {
        $request = Injector::inst()->create(ListSynonymSets::class, $synonymCollectionId);

        // Should either be successful or throw an exception, which we'll let fly
        $body = $this->client->appSearch()->listSynonymSets($request)->asString();
        $body = json_decode($body, true);
        $results = $body['results'] ?? [];

        $synonymRules = SynonymRules::create();

        foreach ($results as $result) {
            $synonymRule = SynonymRule::create($result['id']);
            $synonymRule->setType(SynonymRule::TYPE_EQUIVALENT);
            $synonymRule->setSynonyms($result['synonyms']);

            $synonymRules->add($synonymRule);
        }

        return $synonymRules;
    }

}
