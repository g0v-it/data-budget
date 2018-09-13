data-budget APIs for g0v budget visualization applications
-------------------

The APIs query a graph database containing data about balance accounts and transform data in format suitable to be used with  
vue components defined in [vue-budget repository]().

## usage

### /accounts/{schema}

returns data in format suitable to be used with budget-bubbles vue component


{schema} indicates the data model provided. The following schemas are supported:

- **bubbles**: provides data in a format suitable for the BudgetBubbles vue component [default]
- **tree**: provides data in a format suitable for the BudgetTreeMap vue component

In next releases new schemas model could be supported.


### /account/{id}/{schema}/


returns data suitable to be used with AccountDetails vue component


{schema} indicates the data model provided. The following schemas are supported:

- **full**: provides data in a format suitable for the BudgetBubbles vue component  [default]


### /partition_labels/

returns data suitable to be used in partitioning in budget-bubbles vue component


## Test

To test api server you need:

- run the data store and api server provider 
- populate the graph database with example data (see how in [sdaas component](../sdaas/README.md))
- test api using a browser or any api client pointing it to:


```
curl http://localhost:8080/
```




