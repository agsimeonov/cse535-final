from highcharts import Highchart


chart = Highchart()

data = {"United States" : 2 , "Greece" : 4, "France" : 3}
data = data.items()
chart.add_data_set(data, series_type='pie', name='Results per Country')
chart.save_file("pie")
