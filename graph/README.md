## Graph Generator

In order to run:
```
python graph.py <JSON Query Results>
```

At minimum the JSON Query Result needs to have a structure akin to the following example:
```json
{"reponse":{"numFound":6,"docs":[{"tweet_hashtags":["a","b","c"]},{"tweet_hashtags":["a","b","c"]},{"tweet_hashtags":["a","b","c"]},{"tweet_hashtags":["b","c","d"]},{"tweet_hashtags":["c","d","e"]},{"tweet_hashtags":["c","d","e"]}]}}
```

The script itself will output and overwrite the file graph.json which is a JSON representation of the graph.

To see the results open graph.html which will display the results found in graph.json

A sample graph.json is provided so that graph.html will display a sample result page.
