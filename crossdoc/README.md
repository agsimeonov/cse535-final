## Pie Chart Generator

Prerequisites:
```
pip install python-highcharts
```

In order to run:
```
python pie.py <JSON Query Results>
```

## Geocoder

The geocoder script is used in order to generate a country designation in association with tweet IDs.  It uses user location as well as tweet coordinates and a geocoding API to acquire a country name where possible.  Please note only about 1% of tweets should contain coordinates.  Around 50% of tweets contain user location.  Since user location is specified by the user and can be anything at all the geocoding API will not always be able to produce a country designation.  However the geocoder should be able to produce a designation for perhaps around 25-30% of all tweets.

Prerequisites:
```
pip install geopy
```

In order to run:
```
python geocoder.py
```

The geocoder will output file "geocoder.json" in "../data/geocoder.json"
