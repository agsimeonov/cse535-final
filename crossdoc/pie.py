from json import loads
from os.path import splitext
from sys import argv, exit

from highcharts import Highchart


DOCS     = 'docs'
RESPONSE = 'response'
LANG     = 'tweet_lang'
COUNTRY  = 'user_location'
TITLE    = 'title'
TEXT     = 'text'

# Make sure we have the correct command line arguments
if len(argv) != 4:
  print "Please provide command line arguments as follows:"
  print "python pie.py <JSON Query Results> <By Language Output File> <By Country Output File>"
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

langs = {}
countries = {}

for doc in docs:
  if LANG in doc:
    lang = doc[LANG]
    if lang:
      lang = lang.upper()
      if lang in langs:
        langs[lang] += 1
      else:
        langs[lang] = 1
  if COUNTRY in doc:
    country = doc[COUNTRY]
    if country:
      if country in countries:
        countries[country] += 1
      else:
        countries[country] = 1

chart = Highchart()
options = {TITLE : {TEXT : 'Results per Language'}} 
chart.set_dict_options(options)
chart.add_data_set(langs.items(), series_type='pie', name='Results')
chart.save_file(splitext(argv[2])[0])

chart = Highchart()
options = {TITLE : {TEXT : 'Results per Country'}} 
chart.set_dict_options(options)
chart.add_data_set(countries.items(), series_type='pie', name='Results')
chart.save_file(splitext(argv[3])[0])
