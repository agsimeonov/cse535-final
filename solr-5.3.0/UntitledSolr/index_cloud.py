from commands import *
import os

cmd_1="curl 'http://ramanpreet1990.koding.io:8983/solr/UntitledSolr/update?commit=true' --data '<delete><query>*:*</query></delete>' -H 'Content-type:text/xml; charset=utf-8'"
os.system(cmd_1)

cmd_2="~/solr/solr-5.3.0/bin/solr stop -all"
os.system(cmd_2)

cmd_3="~/solr/solr-5.3.0/bin/solr start -s ~/solr/solr-5.3.0/UntitledSolr/solr"
os.system(cmd_3)
os.system(cmd_1)


status, text = getstatusoutput('ls solr-5.3.0/UntitledSolr/extracted_tweet_data/*.json')
file_names=text.split("\n")

for each_file in file_names:
	print("Indexing : ")
	print(each_file)

	cmd_4="curl 'http://ramanpreet1990.koding.io:8983/solr/UntitledSolr/update/json?commit=true' --data-binary @$(echo ~/solr/solr-5.3.0/"+each_file+") -H 'Content-type:application'"

	os.system(cmd_4)


cmd_5="curl 'http://ramanpreet1990.koding.io:8983/solr/UntitledSolr/select?q=*:*&rows=0'"
os.system(cmd_5)
