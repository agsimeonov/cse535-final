from glob import glob
from json import loads, dumps
from os import walk, path
from sys import exit
from time import sleep

from geopy.geocoders import Nominatim


filePaths = [y for x in walk('../data/raw') for y in glob(path.join(x[0], '*.json'))]
userLocations = {}
userCoordinates = {}

for filePath in filePaths:
  with open (filePath, 'r') as tweetFile:
    tweet = loads(tweetFile.read())
    if tweet['coordinates']:
      userCoordinates[tweet['id']] = str(tweet['coordinates']['coordinates'])[1:-1]
    else:
      if tweet['user']['location']:
        userLocations[tweet['id']] = tweet['user']['location']

def apiCall(message, geolocator, reverse):
  if reverse:
    return geolocator.reverse(message, language='en', exactly_one=True, timeout=10)
  else:
    return geolocator.geocode(message, language='en', exactly_one=True, timeout=10)
  
def tryApiCall(message, geolocator, reverse, retry, timeout):
  try:
    return apiCall(message, geolocator, reverse)
  except KeyboardInterrupt:
    print "Goodbye!"
    exit(0)
  except:
    if retry > 0:
      retry = retry - 1
      if timeout == 0:
        timeout = 30
      else:
        timeout = timeout * 2
      print "Timed out - retrying after a short pause!"
      sleep(timeout)
      tryApiCall(message, Nominatim(), reverse, retry, timeout)

def locator(dictionary, geolocator, reverse):
  output = {}
  for item in dictionary.items():
    location = tryApiCall(item[1], geolocator, reverse, 5, 0)
    if location:
      if location.address:
        output[item[0]] = location.address.split(',')[-1].strip()
        print output[item[0]]
    sleep(1)
  return output

userCoordinates = locator(userCoordinates, Nominatim(), True)
userLocations = locator(userLocations, Nominatim(), False)

for item in userCoordinates.items():
  userLocations[item[0]] = item[1]

with open('../data/geocoder.json', 'w') as outputFile:
  outputFile.write(dumps(userLocations, ensure_ascii=False).encode('utf8'))
