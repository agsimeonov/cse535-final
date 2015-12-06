from BaseHTTPServer import BaseHTTPRequestHandler
from SocketServer import TCPServer
from json import dumps, loads
from subprocess import call
from threading import Thread


PORT     = 9886
HGRAPH   = 'graph.html'
COUNTRY  = 'country.html'
LANG     = 'lang.html'
TRENDING = 'trending.html'
JGRAPH   = 'graph.json'
DEFAULT  = 'default.html'
INPUT    = 'input.json'
PARSER   = 'sigma.parsers.json.js'

GROUPED  = 'grouped'
SIGF     = 'signatureField'
GROUPS   = 'groups'
DOCLIST  = 'doclist'
DOCS     = 'docs'
RESPONSE = 'response'

def masterRunner(data):
  data = loads(data)
  output = []
  
  if RESPONSE in data:
    if DOCS in data[RESPONSE]:
      data = dumps(data)
  else: 
    if not GROUPED in data:
      return 
    if not SIGF in data[GROUPED]:
      return
    if not GROUPS in data[GROUPED][SIGF]:
      return
  
    for group in data[GROUPED][SIGF][GROUPS]:
      if not group:
        continue
      if not DOCLIST in group:
        continue
      if not DOCS in group[DOCLIST]:
        continue
      if not group[DOCLIST][DOCS]:
        continue
    
      doc = group[DOCLIST][DOCS][0]
      if not doc:
        continue
      output.append(doc)
      
    data = dumps({RESPONSE: {DOCS : output}})
  
  with open(INPUT, 'w') as inputData:
    inputData.write(data)

  Thread(target=runner, args=[['python', '../crossdoc/pie.py', INPUT, LANG, COUNTRY]]).start()
  Thread(target=runner, args=[['python', '../crossdoc/line.py', INPUT, '5', TRENDING]]).start()
  Thread(target=runner, args=[['python', '../graph/graph.py', INPUT, JGRAPH]]).start()

def runner(commands):
  call(commands)

class Server(BaseHTTPRequestHandler):
  def _set_headers(self):
    self.send_response(200)
    self.send_header('content-type', 'text/html')
    self.end_headers()
  
  def do_GET(self):
    self._set_headers()
    if HGRAPH in self.path:
      output = open(HGRAPH, 'r')
    elif JGRAPH in self.path:
      output = open(JGRAPH, 'r')
    elif COUNTRY in self.path:
      output = open(COUNTRY, 'r')
    elif LANG in self.path:
      output = open(LANG, 'r')
    elif TRENDING in self.path:
      output = open(TRENDING, 'r')
    elif PARSER in self.path:
      output = open(PARSER, 'r')
    else:
      output = open(DEFAULT, 'r')
    self.wfile.write(output.read())
    self.end_headers()
    output.close()
    
  def do_HEAD(self):
    self._set_headers()
    self.end_headers()
    
  def do_POST(self):
    self._set_headers()
    data = self.rfile.read(int(self.headers['content-length']))
    self.send_response(200)
    self.end_headers()
    
    with open(INPUT, 'w') as inputData:
      inputData.write(data)

    Thread(target=masterRunner, args=[data]).start()

handler = Server
httpd = TCPServer(('', PORT), handler)
httpd.serve_forever()
