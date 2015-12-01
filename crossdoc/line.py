from datetime import datetime
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
XAXIS      = 'xAxis'
YAXIS      = 'yAxis'
CATEGORIES = 'categories'

# Make sure we have the correct command line arguments
if len(argv) != 4:
  print "Please provide command line arguments as follows:"
  print "python line.py <JSON Query Results> <Number of Trending Topics> <Line Chart Output File>"
  exit(0)

if not argv[2].isdigit():
  print "The number of tranding must be a positive integer!"
  exit(0)
if int(argv[2]) <= 0:
  print "The number of tranding topics must be >= 0!"
  exit(0)

jsonInput = loads(argv[1])

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
top = {}

for doc in docs:
  if not CREATED_AT in doc:
    continue
  if not HASHTAGS in doc:
    continue
  
  createdAt = datetime.strptime(doc[CREATED_AT], '%Y-%m-%dT%H:%M:%SZ')
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
    
    if hashtag in top:
      top[hashtag] += 1
    else:
      top[hashtag] = 1

  data[createdAt] = counts

top = sorted(top.items(), key=lambda x: x[1], reverse=True)
sortedItems = sorted([item for item in data.items()])

series = {}
for i in range(min(int(argv[2]), len(top))):
  hashtag = top[i][0]
  dataSet = []
  for item in sortedItems:
    if hashtag in item[1]:
      dataSet.append(item[1][hashtag])
    else:
      dataSet.append(0)
  series[hashtag] = dataSet

categories = [item[0].date().isoformat() for item in sortedItems]

chart = Highchart()
chart.set_options(TITLE, {TEXT : "Top Trending Topics Over Time"})
chart.set_options(XAXIS, {CATEGORIES : categories})
chart.set_options(YAXIS, {TITLE : {TEXT: "Results containing Hashtag"}})

for item in series.items():
  chart.add_data_set(item[1], series_type='line', name=item[0])

chart.save_file(splitext(argv[3])[0]) 
