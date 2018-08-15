# g0v-data rdf repository


this implementation is based on [Docker Blazegraph](https://github.com/lyrasis/docker-blazegraph)

## Quickstart

```bash
docker run --name blazegraph -d -p 9999:8080 lyrasis/blazegraph:2.1.4
docker logs -f blazegraph
```

to access the administrative control panel open a browser and point to http://localhost:9999/bigdata/

