from glob import glob
from json import loads, dumps
from os import walk, path
from time import sleep

from geopy.geocoders import Nominatim


filePaths = [y for x in walk('../data/raw') for y in glob(path.join(x[0], '*.json'))]
userLocations = {}

for filePath in filePaths:
  with open (filePath, 'r') as tweetFile:
    tweet = loads(tweetFile.read())
    if tweet['user']['location']:
      userLocations[tweet['id']] = tweet['user']['location']
    break;

geolocator = Nominatim()

for item in userLocations.items():
  if item[1]:
    location = geolocator.geocode(item[1], language='en')
    if location:
      userLocations[item[0]] = location.address.split(',')[-1].strip()
    else:
      del userLocations[item[0]]
    sleep(1)

with open('../data/geocoder.json', 'w') as outputFile:
  outputFile.write(dumps(userLocations, ensure_ascii=False).encode('utf8'))
