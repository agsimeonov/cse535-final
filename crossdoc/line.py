from json import loads
from os.path import splitext
from sys import argv, exit

from highcharts.highcharts.highcharts import Highchart


DOCS       = 'docs'
RESPONSE   = 'response'
CREATED_AT = 'tweet_created_at'
HASHTAGS   = 'tweet_hashtags'
TITLE      = 'title'
TEXT       = 'text'
TOP        = 5

# Make sure we have the correct command line arguments
if len(argv) != 3:
  print "Please provide command line arguments as follows:"
  print "python pie.py <JSON Query Results> <Line Chart Output File>"
  exit(0)

jsonInput = loads(argv[1])
jsonInput = loads('{"response":{"numFound":3,"docs":[{"tweet_created_at":"2015-11-28T14:32:44Z","tweet_hashtags":["a","b","c"]},{"tweet_created_at":"2015-11-28T14:32:44Z","tweet_hashtags":["a","b","c"]},{"tweet_created_at":"2015-11-28T14:32:44Z","tweet_hashtags":["a","b","c"]},{"tweet_created_at":"2015-11-29T14:32:44Z","tweet_hashtags":["b","c","d"]},{"tweet_created_at":"2015-11-30T14:32:44Z","tweet_hashtags":["c","d","e"]},{"tweet_created_at":"2015-11-30T14:32:44Z","tweet_hashtags":["c","d","e"]}]}}')
if RESPONSE in jsonInput:
  if DOCS in jsonInput[RESPONSE]:
    docs = jsonInput[RESPONSE][DOCS]
  else:
    print "'docs' list not found in JSON 'response'!"
    exit(0)
else:
  print "'response' dictionary not found in JSON!"
  exit(0)    

data = {}

for doc in docs:
  if not CREATED_AT in doc:
    continue
  if not HASHTAGS in doc:
    continue
  
  createdAt = doc[CREATED_AT][:10]
  hashtags = doc[HASHTAGS]
  
  if not hashtags:
    continue
  
  if createdAt in data:
    counts = data[createdAt]
  else:
    counts = {}
    
  for hashtag in hashtags:
    if hashtag in counts:
      counts[hashtag] += 1
    else:
      counts[hashtag] = 1
  
  data[createdAt] = counts
  
chart = Highchart()
# TODO: LOOK HERE http://www.highcharts.com/demo/line-basic/dark-unica
options = {TITLE : {TEXT : 'Results per Language'}, 'xAxis' : {'categories' : ['cat','dog','fish']}} 
chart.set_dict_options(options)
chart.add_data_set([1,2,3], series_type='line', name='Results')
chart.save_file(splitext(argv[2])[0]) 
