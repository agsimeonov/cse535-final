from colorsys import hsv_to_rgb
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
COLOR    = 'color'
X        = 'x'
Y        = 'y'
MINSIZE  = 50

def toHex(rgb):
  return '#%02x%02x%02x' % rgb

# Heat map red (lowest) to blue (highest)
def heatmap(minimum, maximum, value):
  if (minimum == maximum):
    maximum = maximum + 1
  hsv = (float(value-minimum) / (maximum-minimum)) * 240
  r, g, b = hsv_to_rgb(hsv/360, 1, 1)
  r = int(r*255)
  g = int(g*255)
  b = int(b*255)
  return toHex((r, g, b))

# Make sure we have the correct command line arguments
if len(argv) != 3:
  print "Please provide command line arguments as follows:"
  print "python graph.py <JSON Query Results> <JSON Graph Output>"
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
counter = {}
top = 0

for doc in docs:
  if not HASHTAGS in doc:
    continue
  
  hashtags = sorted(doc[HASHTAGS])
  
  if not hashtags:
    continue
  
  for hashtag in hashtags:
    if hashtag in counter:
      counter[hashtag] +=1
    else:
      counter[hashtag] = 1

    if counter[hashtag] > top:
      top = counter[hashtag]

if top < MINSIZE:
  minsize = top
else:
  minsize = MINSIZE

for doc in docs:
  if not HASHTAGS in doc:
    continue
  
  hashtags = sorted(doc[HASHTAGS])
  
  if not hashtags:
    continue
  
  for hashtag in hashtags:
    if counter[hashtag] < minsize:
      continue

    if hashtag in nodes:
      nodes[hashtag][SIZE] += 1
    else:
      node = {}
      node[ID] = hashtag
      node[LABEL] = '#' + hashtag
      node[SIZE] = 1
      nodes[hashtag] = node
  
  for item in combinations(hashtags, 2):
    if counter[item[0]] < minsize or counter[item[1]] < minsize:
      continue
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
  node[X] = xy[0]
  node[Y] = xy[1]
  node[COLOR] = heatmap(minsize, top, int(node[SIZE]))

with open(argv[2], 'w') as output:
  output.write(dumps({NODES : nodes, EDGES : edges}))
