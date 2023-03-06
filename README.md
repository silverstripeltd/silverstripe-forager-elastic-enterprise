# Silverstripe Search Service > Elastic

Elastic Service provider for [Silverstripe Search Service](https://github.com/silverstripe/silverstripe-search-service).

## Requirements

* php: ^8.1
* silverstripe/framework: ^5
* silverstripe/silverstripe-search-service: ^3
* elastic/enterprise-search: ^8.3
* guzzlehttp/guzzle: ^7

## Installation

`composer require silverstripe/silverstripe-search-service-elastic`

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

* `text` (default)
* `date`
* `number`
* `geolocation`

You can specify these data types in the `options` node of your fields.

```yaml
SilverStripe\SearchService\Service\IndexConfiguration:
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
change the data type of a field.

## Additional documentation

Majority of documentation is provided by the Silverstripe Search Service module. A couple in particular that might be
useful to you are:

* [Configuration](https://github.com/silverstripe/silverstripe-search-service/blob/2/docs/en/configuration.md)
* [Customisation](https://github.com/silverstripe/silverstripe-search-service/blob/2/docs/en/customising.md)
