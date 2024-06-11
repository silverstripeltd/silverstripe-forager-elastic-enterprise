<?php

namespace SilverStripe\ForagerElasticEnterprise\Tests\Fake;

use SilverStripe\Dev\TestOnly;
use SilverStripe\Forager\Extensions\SearchServiceExtension;
use SilverStripe\ORM\DataObject;
use SilverStripe\Versioned\Versioned;

/**
 * @property string $Title
 * @mixin SearchServiceExtension
 * @mixin Versioned
 */
class TagFake extends DataObject implements TestOnly
{

    private static array $db = [
        'Title' => 'Varchar',
    ];

    private static array $extensions = [
        SearchServiceExtension::class,
        Versioned::class,
    ];

}
