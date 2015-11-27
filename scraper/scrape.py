from datetime import datetime
from json import loads, dumps
from os import path, makedirs
from threading import Thread
from time import sleep
from urllib2 import quote

from tweepy.api import API
from tweepy.auth import OAuthHandler
from tweepy.cursor import Cursor
from tweepy.error import TweepError


cfgJson = open('keys.cfg').read()
cfgDict = loads(cfgJson)

consumerKeys    = cfgDict['consumer_keys']
consumerSecrets = cfgDict['consumer_secrets']
accessTokens    = cfgDict['access_tokens']
accessSecrets   = cfgDict['access_secrets']
langs           = cfgDict['langs']
queries         = cfgDict['queries']
numDays         = cfgDict['days']    # Number of days (until today)
perDay          = cfgDict['per_day'] # Total tweets per day
outDir          = cfgDict['out_dir']

currentTime = datetime.now()
year        = str(currentTime.year)
month       = str(currentTime.month)
day         = int(currentTime.day)
sinces      = []
untils      = []

if not path.exists(outDir): 
    makedirs(outDir)

for i in range(day - numDays, day):
    sinces.append(year + '-' + month + '-' + str(i))
    untils.append(year + '-' + month + '-' + str(i + 1)) 
    datePath = path.join(outDir, sinces[len(sinces) - 1])
    if not path.exists(datePath):
        makedirs(datePath)
    for lang in langs:
        dateLangPath = path.join(datePath, lang)
        if not path.exists(dateLangPath):
            makedirs(dateLangPath)

def scrapeThread(index):
    auth = OAuthHandler(consumerKeys[index], consumerSecrets[index])
    auth.set_access_token(accessTokens[index], accessSecrets[index])
    api = API(auth)
  
    try:
        api.verify_credentials()
    except TweepError:
        print "Failed to authenticate - most likely reached rate limit/incorrect credentials!"
        return
    else:
        print "You have successfully logged on as: " + api.me().screen_name
  
    for i in range(0, numDays):
        for query in queries[index]:
            count = 0
            cursor = Cursor(api.search,
                            q=quote(query.encode('utf-8')),
                            lang=langs[index],
                            since=sinces[i],
                            until=untils[i],
                            include_entities=True).items()
            while True:
                try:
                    tweet = cursor.next()
                    utc = datetime.now().strftime('%Y%m%dT%H%M%S%f')
                    outPath = path.join(outDir, sinces[i], langs[index], utc + '.json')
                    with open(outPath, 'w') as output:
                        output.write(dumps(tweet._json, ensure_ascii=False).encode('utf8'))
                    count += 1
                    if count == int(perDay / len(queries[index])):
                        break
                except TweepError:
                    print langs[index] + " - rate limit reached! Pausing thread for 15 minutes."
                    sleep(60 * 15)
                    continue
                except StopIteration:
                    break
            print str(count) + " tweets stored in " + outPath

threads = []
threadCount = len(consumerKeys)

for index in range(0, threadCount):
    thread = Thread(target=scrapeThread, args=[index])
    thread.start()
    threads.append(thread)

for thread in threads:
    thread.join()
