import json

from highcharts import Highchart


jsonInput = '{"response":{"numFound":3,"start":0,"docs":[{"lang":"en"},{"lang":"ru"},{"lang":"de"},{"lang":"de"},{"lang":"de"},{"lang":"ar"},{"lang":"ru"},{"lang":"fr"},{"lang":"ru"}]}}'

jsonInput = json.loads(jsonInput)
docs = jsonInput['response']['docs']
langs = {}

for doc in docs:
  lang = doc['lang']
  lang = lang.upper()
  if lang in langs:
    langs[lang] += 1
  else:
    langs[lang] = 1

chart = Highchart()
options = {'title' : {'text' : 'Results per Language'}} 
chart.set_dict_options(options)
data = langs.items()
chart.add_data_set(data, series_type='pie', name='Results')
chart.save_file("pie")
