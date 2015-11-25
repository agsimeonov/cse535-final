# <p align="center">CSE 435/535 Information Retrieval<br/>Fall 2015<br/>Project Part C: Multilingual Search System</p>
###### <p align="center">Due Date: 23:59, Dec 11th, 2015</p>
###### <p align="center">Demo Proposal: Dec 4 , Demo Day: Dec 8 (Subject to Acceptance - Bonus :))</p>

## Our Search System (Projected Design)
![Search_UI_Part_1] (https://cloud.githubusercontent.com/assets/11690982/11394811/63e58d9c-9335-11e5-847c-4d5ef01fafb9.PNG)
![Search_UI_Part_2] (https://cloud.githubusercontent.com/assets/11690982/11394825/750533c0-9335-11e5-975d-607701e41a69.PNG)

###### <p align="center">Each Search Result will look like this</p>
![Search_UI_Part_3] (https://cloud.githubusercontent.com/assets/11690982/11411178/b2fa2698-939b-11e5-96cc-6dbdf8e16599.png) 
<p align="justify">1. where Title in big Font and Blue color will be an excerpt from retreived text only.</p>
<p align="justify">2. Since we are adding a feature of boosting docs based on user clicks. So showing score will help to understand how the document score is changing.</p>

## Overview
<p align="justify">The goal of this project is to build a multilingual faceted search system, including a front end that allows users to search and browse multilingual data based on various criteria:  topic, location, person, etc.</p>

The following sections describe the various tasks involved, evaluation criteria and submission guideline.

## Data
<p align="justify">The data will be multilingual social media data (including but not restricted to Twitter) in multiple languages.  You may reuse some of the data from the previous project, but you must include new data as well.  The new data could reflect:</p>
*  New languages:  French, Arabic, especially Syrian dialects are especially interesting
*  New topics:  extend the topic to include the Paris attacks, and relevance to Syrian refugee crisis

<p align="justify">We enforce the following minimum requirements with regards to the data however:</p>
*  Minimum data in four languages
*  At least 200 posts per language
*  At least 10000 posts in total

## Index
<p align="justify">In this step, you will need to index the data as you have done in part A and B.   You are free to choose whatever IR model, query processing that you wish.</p>

## Front-end User Interface
<p align="justify">In this project, you will be required to build a user interface in order to do one of the following (i) accept queries from a user, (ii) display search results, (iii) display analytics based on indexed data.  The user interface will depend on which of the project components (see below) you have implemented.  In summary, you will need to create a working site where users can try out your system.</p>

## Project Components
<p align="justify">Your project must reflect at least two of the components listed below.  The demonstration of your project should clearly showcase the components/features you have implemented.</p>

### (i)  Content Tagging (Monolingual)
<p align="justify">Everyone is encouraged to attempt some level of content tagging of the data.  Content taggingcould include tagging of named entities (names of people, places, organizations), topics,contact information, etc.  Tools such as Alchemy or the Stanford NLP Toolkit may beleveraged for this purpose.  The tagged data can subsequently be used in faceted search,analytics, graph analysis, etc.</p>

### (ii)  Faceted Search
<p align="justify">This option involves leveraging the faceted search capability provided by Solr to allowvarious types of drill-down.  This assumes some level of content tagging (see above).  Facetscould include people, topics, locations etc. You are encouraged to experiment with differentUI options including hierarchies, graphs, etc.</p>

### (iii)  Cross-Document Analytics
<p align="justify">This option involves computing various analytics that provide insight into the data.<br/><br/>
Examples include:  volume of tweets by region/topic/hashtag, sentiment analysis, analyticsillustrating cultural differences, etc.   The ability to identify and display trending topics (on adaily/weekly basis) would also be interesting.

Analytics should be presented using intuitive visual graphs – several widget libraries areavailable.  Map visualization is also encouraged.</p>

### (iv)  Topic Models and/or LSI
<p align="justify">In this option, you will implement Latent Semantic Indexing on the corpus of data you havecollected to demonstrate “semantic search”, rather than traditional keyword search.  For thoseof you familiar with advanced machine learning techniques such as topic models (LDA), youare encouraged to apply such techniques to the data in order to discover and group tweetsbased on different topics. You may not use the in-built Carrot clustering to complete this section however.</p>

### (v)  Cross-Lingual Retrieval/Analysis
<p align="justify">In this option, you will demonstrate cross-lingual capabilities.  This can take on manyaspects:  one example involves cross-lingual queries, and automatic translation of resultingforeign language snippets.  For example, a search for a particular individual/place/organization should take place simultaneously in multiple languages –achieved by automatically tagging and normalizing entities across languages.</p>

### (vi)  Ranking tweets
<p align="justify">This option involves coming up with a novel ranking algorithm for tweets that balancesrecency with importance of content when presenting tweets.  It could also take into account thepopularity of a tweet, or the influence of a person tweeting, the location of the user, theirinterests etc...</p>

### (vii) Summarization
<p align="justify">This option focuses on summarizing tweets through the use of news/Wikipedia articles. You canpick a particular hashtag or a named entity like a person, place, etc. and provide a summarybased on your index. The task would involve partitioning your data into sub-topics or sub-eventsbased on tagged information and then choosing a summary for each sub-topic. Since we do notexpect language generation, you could use news headlines or extracts from Wikipedia articles asbullet points in this summary.</p>

### (viii)  Graphical Analysis
<p align="justify">This option involves inferring some graphical structure from the tweets, based on entities mentioned, topics discussed etc.  Graph structures (or relationships between tweets) could also beinferred through connection of topics reflected in the tweets:  wikification may be helpful in thisprocess.  Once a graph is constructed, use graph algorithms to find important tweets, entities etc.</p>

## What to submit
1.  Your report in pdf format. File name is report.pdf (no other file format is allowed)
2.  A video (no more than 3 minutes) that demonstrates the key features of your system.  Make sureto include a voiceover and/or text that helps the viewer appreciate what they are seeing.
3.  Your source file (java files) of any customized functions in src folder.

<p align="justify">Compress these files into a tar file. File name is project_partc_[ubitname].tar ( no other compressed format is allowed)</p>

<p align="justify">For example my ubit name is ruhan then I should use following command to submit.</p>

<p align="center"><b>submit_cse535 project_partc_ruhan.tar</b></p>

<p align="justify">Choose cse435 or cse535 based on your own course level. Although multiple submissions can be made till the deadline, we recommend that one team member make all submissions to ease the grading process.</p>

## Grading
Grading for this project will be based on (i) sophistication of techniques implemented, (ii) demonstration of features in video, (iii) project report,  and (iv) functioning demo site.</p>

## Presentation
<p align="justify">We will select projects for in-class presentation on Dec 8th.  More on this forthcoming.</p>

