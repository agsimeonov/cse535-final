# Python Script Server

####Main Requirement:
Python 2.7

####Prerequisites:
```
pip install python-highcharts
```

####Start with:
```
python serv.py
```

####Instructions:
This simple Python script server is used in order to run the Python scripts with the query result data.
To execute the Python scripts simply pass the JSON data within a POST request.
Here is an example run:
```
curl -X POST http://localhost:9886 -H “Content-Type: application/json” --data "@test.json"
```

The server runs on port 9886. Here are the available pages:
```
http://localhost:9886/lang.html
http://localhost:9886/country.html
http://localhost:9886/trending.html
http://localhost:9886/graph.html
```

The pages will get updated every time a POST request with new JSON data comes in.
