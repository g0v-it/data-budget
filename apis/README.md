data-budget APIs for g0v budget visualization applications
-------------------

The APIs query a graph database containing data about balance accounts and transform data in format suitable to be used with  
vue components defined in [vue-budget repository]().

## usage

### /v1/{lang}/accounts/{schema}

returns  data in format suitable to be used with budget-bubbles vue component

{lang} indicates the preferred language for data string litterals.


{schema} indicates the data model provided. The following schemas are supported:

- **bubbles**: provides data in a format suitable for the BudgetBubbles vue component [default]
- **tree**: provides data in a format suitable for the BudgetTreeMap vue component

In next releases new schemas model could be supported.


### /v1/{lang}/account/{id}/{schema}/


returns data suitable to be used with AccountDetails vue component


{lang} indicates the preferred language for data string litterals.

{schema} indicates the data model provided. The following schemas are supported:

- **full**: provides data in a format suitable for the BudgetBubbles vue component  [default]


## Test

To test api server you need:

- run the data store and api server provider 
- populate the graph database with data (see example data in [datastore](../datasore/README.MD))
- test api using a browser or any api client.


