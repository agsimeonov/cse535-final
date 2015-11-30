from sys import argv, exit


# Make sure we have the correct command line arguments
if len(argv) != 3:
  print "Please provide command line arguments as follows:"
  print "python pie.py <JSON Query Results> <Line Chart Output File>"
  exit(0)

# TODO  
