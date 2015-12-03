from itertools import combinations, product
from json import dumps, loads
from os.path import isfile
from random import shuffle
from sys import argv, exit


DOCS     = 'docs'
RESPONSE = 'response'
HASHTAGS = 'tweet_hashtags'
ID       = 'id'
LABEL    = 'label'
SIZE     = 'size'
SOURCE   = 'source'
TARGET   = 'target'
NODES    = 'nodes'
EDGES    = 'edges'

# Make sure we have the correct command line arguments
if len(argv) != 3:
  print "Please provide command line arguments as follows:"
  print "python graph.py <JSON Query Results> <Graph JSON File>"
  exit(0)

if isfile(argv[1]):
  with open(argv[1], 'r') as jsonFile:
    jsonInput = loads(jsonFile.read())
else:
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

nodes = {}
edgeSet = set([])

for doc in docs:
  if not HASHTAGS in doc:
    continue
  
  hashtags = sorted(doc[HASHTAGS])
  
  if not hashtags:
    continue
  
  for hashtag in hashtags:
    if hashtag in nodes:
      nodes[hashtag][SIZE] += 1
    else:
      node = {}
      node[ID] = hashtag
      node[LABEL] = '#' + hashtag
      node[SIZE] = 1
      nodes[hashtag] = node
  
  for item in combinations(hashtags, 2):
    edgeSet.add(item)

nodes = [item[1] for item in nodes.items()]
edges = []

i = 0
for element in edgeSet:
  edge = {}
  edge[ID] = str(i)
  edge[SOURCE] = element[0]
  edge[TARGET] = element[1]
  edges.append(edge)
  i += 1

width = len(nodes)
height = width
coordinates = list(product(xrange(width), xrange(height)))
shuffle(coordinates)

for node in nodes:
  xy = coordinates.pop()
  node['x'] = xy[0]
  node['y'] = xy[1]
 
with open(argv[2], 'w') as output:
  output.write(dumps({NODES : nodes, EDGES : edges}))
  