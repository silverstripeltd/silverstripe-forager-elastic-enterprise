<?php

namespace SilverStripe\SearchServiceElastic\Tests\Fake;

use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\DataObject;
use SilverStripe\SearchService\Extensions\SearchServiceExtension;

class ImageFake extends DataObject implements TestOnly
{

    private static string $table_name = 'Elastic_ImageFake';

    private static array $db = [
        'URL' => 'Varchar',
    ];

    private static array $has_one = [
        'Parent' => DataObjectFake::class,
    ];

    private static array $many_many = [
        'Tags' => TagFake::class,
    ];

    private static array $extensions = [
        SearchServiceExtension::class,
    ];

}
