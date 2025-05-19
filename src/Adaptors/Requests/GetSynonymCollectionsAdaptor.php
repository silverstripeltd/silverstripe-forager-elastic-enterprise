<?php

namespace SilverStripe\ForagerElasticEnterprise\Adaptors\Requests;

use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forager\Interfaces\IndexingInterface;
use SilverStripe\Forager\Interfaces\Requests\GetSynonymCollectionsAdaptor as GetSynonymSetsAdaptorInterface;
use SilverStripe\Forager\Service\IndexConfiguration;
use SilverStripe\Forager\Service\Results\SynonymCollection;
use SilverStripe\Forager\Service\Results\SynonymCollections;

class GetSynonymCollectionsAdaptor implements GetSynonymSetsAdaptorInterface
{

    private ?IndexConfiguration $configuration = null;

    private static array $dependencies = [
        'configuration' => '%$' . IndexConfiguration::class,
    ];

    public function setConfiguration(IndexConfiguration $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function process(): SynonymCollections
    {
        $indexService = Injector::inst()->get(IndexingInterface::class);
        $synonymCollections = SynonymCollections::create();

        foreach (array_keys($this->configuration->getIndexes()) as $engineSuffix) {
            $synonymCollections->add(SynonymCollection::create($indexService->environmentizeIndex($engineSuffix)));
        }

        return $synonymCollections;
    }

}
