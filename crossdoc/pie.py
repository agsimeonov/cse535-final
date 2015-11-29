from json import loads
from os.path import splitext
from sys import argv, exit

from highcharts import Highchart


# Make sure we have the correct command line arguments
if len(argv) != 4:
  print "Please provide command line arguments as follows:"
  print "python pie.py <JSON Query Results> <By Language Output File> <By Country Output File>"
  exit(0)

jsonInput = loads(argv[1])
docs = jsonInput['response']['docs']
langs = {}
countries = {}

for doc in docs:
  lang = doc['lang']
  lang = lang.upper()
  country = doc['country']
  if lang in langs:
    langs[lang] += 1
  else:
    langs[lang] = 1
  if country:
    if country in countries:
      countries[country] += 1
    else:
      countries[country] = 1

chart = Highchart()
options = {'title' : {'text' : 'Results per Language'}} 
chart.set_dict_options(options)
chart.add_data_set(langs.items(), series_type='pie', name='Results')
chart.save_file(splitext(argv[2])[0])

chart = Highchart()
options = {'title' : {'text' : 'Results per Country'}} 
chart.set_dict_options(options)
chart.add_data_set(countries.items(), series_type='pie', name='Results')
chart.save_file(splitext(argv[3])[0])
