# 🧺 Silverstripe Forager > <img src="https://www.elastic.co/android-chrome-192x192.png" style="height:40px; vertical-align:middle"/> Elastic Enterprise Search Provider

Elastic Enterprise Search provider for [Silverstripe Forager](https://github.com/silverstripeltd/silverstripe-forager).

This module uses Elastic's [Enterprise Search PHP library](https://github.com/elastic/enterprise-search-php) to provide
the ability to index content for an Elastic Enterprise Search (AKA App Search) engine.

This module **does not** provide any method for performing searches on your engines - we've added some [suggestions](#searching) though.

## Requirements

-   php: ^8.1
-   silverstripe/framework: ^5
-   silverstripe/silverstripe-forager: ^1
-   elastic/enterprise-search: ^8.3
-   guzzlehttp/guzzle: ^7

## Installation

`composer require silverstripe/silverstripe-forager-elastic-enterprise`

## Activating EnterpriseSearch

To start using EnterpriseSearch, define environment variables containing your private API key, endpoint, and prefix.

```
ENTERPRISE_SEARCH_ENDPOINT="https://abc123.app-search.ap-southeast-2.aws.found.io"
ENTERPRISE_SEARCH_API_KEY="private-abc123"
ENTERPRISE_SEARCH_ENGINE_PREFIX="value-excluding-index-name"
```

## Configuring EnterpriseSearch

The most notable configuration surface for EnterpriseSearch is the schema, which determines how data is stored in your
EnterpriseSearch index (engine). There are four types of data in EnterpriseSearch:

-   `text` (default)
-   `date`
-   `number`
-   `geolocation`

You can specify these data types in the `options` node of your fields.

```yaml
SilverStripe\Forager\Service\IndexConfiguration:
    indexes:
        myindex:
            includeClasses:
                SilverStripe\CMS\Model\SiteTree:
                    fields:
                        title: true
                        summary_field:
                            property: SummaryField
                            options:
                                type: text
```

**Note**: Be careful about whimsically changing your schema. EnterpriseSearch may need to be fully reindexed if you
change the name of a field. Fields cannot be deleted so re-naming one will leave any previously created fields around.
At the time of writing there is a limit of 64 fields per engine.

## Additional documentation

Majority of documentation is provided by the Silverstripe Forager module. A couple in particular that might be
useful to you are:

-   [Configuration](https://github.com/silverstripeltd/silverstripe-forager/blob/1/docs/en/configuration.md)
-   [Customisation](https://github.com/silverstripeltd/silverstripe-forager/blob/1/docs/en/customising.md)

## Searching

### PHP

To search via PHP you can use the [silverstripe-discoverer](https://github.com/silverstripeltd/silverstripe-discoverer) along with the [silverstripe-discoverer-elastic-enterprise](https://github.com/silverstripeltd/silverstripe-discoverer-elastic-enterprise) provider module.

### JS

Elastic themselves provide a headless [Search UI](https://docs.elastic.co/search-ui/overview) JS library, which can
be used with vanilla JS or any framework like React, Vue, etc.

There are two main libraries:

-   [@elastic/search-ui-app-search-connector](https://www.npmjs.com/package/@elastic/search-ui-app-search-connector)
    -   Provides a class to help you connect to your Elastic App Search API.
-   [@elastic/search-ui](https://www.npmjs.com/package/@elastic/search-ui)
    -   Provides a class that allows you to perform searches and manage your search state.

If you are using React, then there is also
[@elastic/react-search-ui](https://www.npmjs.com/package/@elastic/react-search-ui), which provides interface components.

If you are not using React, then the creation of the view will be up to you.
