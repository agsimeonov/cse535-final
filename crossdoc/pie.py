from highcharts import Highchart


chart = Highchart()

data = range(1,20)
chart.add_data_set(data, series_type='pie', name='Results per Country')
chart.save_file("pie")
