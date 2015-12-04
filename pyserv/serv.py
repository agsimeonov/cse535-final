from BaseHTTPServer import BaseHTTPRequestHandler
from SocketServer import TCPServer
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

def masterRunner(data):
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
