<?php

session_start();

require "autoload.php";
use Abraham\TwitterOAuth\TwitterOAuth;

if(isset($_SESSION['status']) && $_SESSION['status']=='verified')
{
	// user is logged in
    define("CONSUMER_KEY",'MxxrVLC1F1ijE3jDSfuoODUqY');
    define("CONSUMER_SECRET",'FKlNDelDS9Vo6Eu8DhkMVTsrhh4VSA3DntzJVoZHa3KvMhkZAa');
    define("ACCESS_TOKEN_KEY",'165390377-stwO6RMGMkCsIegBH3mdADZpTt2Qs0GbEAM0V9gN');
    define("ACCESS_TOKEN_SECRET",'cipEHo8hjlS7RGax9vjjjRrwFKWP1HXgZf3hp2BSqMVfL');
    
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN_KEY,ACCESS_TOKEN_SECRET);
    $content = $connection->get("account/verify_credentials");

    $user_scrn_name = $content->screen_name;
    
    $status = $connection->get("statuses/home_timeline", array("count" => 5));

    $ustatus = $status = $connection->get("statuses/user_timeline", array("screen_name" => $user_scrn_name , "count" => 5));

    $count = count($ustatus);
    // print_r($messages);

}else{
	//show login button
	session_destroy();
	header('Location: ./login.php');
}
?>

<?php

// ini_set('display_errors', 1);

// /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
// $settings = array(
//     'oauth_access_token' => "165390377-stwO6RMGMkCsIegBH3mdADZpTt2Qs0GbEAM0V9gN",
//     'oauth_access_token_secret' => "cipEHo8hjlS7RGax9vjjjRrwFKWP1HXgZf3hp2BSqMVfL",
//     'consumer_key' => "MxxrVLC1F1ijE3jDSfuoODUqY",
//     'consumer_secret' => "FKlNDelDS9Vo6Eu8DhkMVTsrhh4VSA3DntzJVoZHa3KvMhkZAa"
// );


// $url = 'https://api.twitter.com/1.1/statuses/home_timeline.json';
// $getfield = '?count=5';
// $requestMethod = 'GET';
// $twitter = new TwitterAPIExchange($settings);
// $json = json_decode($twitter->setGetfield($getfield)
//              ->buildOauth($url, $requestMethod)
//              ->performRequest());



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Untitled">
    <meta name="keyword"
          content="Solr,Twitter,Graphs,Analytics,News,Media,Lucene,Indexing,Search Engine,Tweets,Tweepy">

    <title>Untitled : Search</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">

    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="assets/css/zabuto_calendar.css">
    <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css"/>
    <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/themes/smoothness/jquery-ui.css">


    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <script src="assets/js/chart-master/Chart.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<section id="container">
    <!-- **********************************************************************************************************************************************************
    TOP BAR CONTENT & NOTIFICATIONS
    *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg">
        <div class="sidebar-toggle-box">
            <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
        </div>
        <!--logo start-->
        <a href="index.php" class="logo"><b>UNTITLED</b></a>
        <!--logo end-->
        <div class="nav notify-row" id="top_menu">
            <!--  notification start -->
            <ul class="nav top-menu">
                <!-- inbox dropdown start-->
                <li id="header_inbox_bar" class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="index.php#">
                        <i class="fa fa-envelope-o"></i>
                        <span class="badge bg-theme"><?=$count; ?></span>
                    </a>
                    <ul class="dropdown-menu extended inbox">
                        <div class="notify-arrow notify-arrow-green"></div>
                        <li>
                            <p class="green">You have <?=$count; ?> new messages</p>
                        </li>
                            <?php
                                foreach($ustatus as $msg){?>
                                    <li>
                                        <a href="index.php#">
                                            <span class="photo"><img alt="avatar" src="<?=$msg->sender->profile_image_url?>"></span>
                                                <span class="subject">
                                                <!-- <span class="from"><?=$msg->screen_name?></span> -->
                                                <span class="from">dfdf</span>
                                                <span class="time"><?=$msg->created_at?></span>
                                                </span>
                                                <span class="message">
                                                    <?=$msg->text?>
                                                </span>
                                        </a>
                                    </li>                                        
                        <?php }?>
                        <!-- <li>
                            <a href="index.php#">
                                <span class="photo"><img alt="avatar" src="assets/img/ui-zac.jpg"></span>
                                    <span class="subject">
                                    <span class="from">Zac Snider</span>
                                    <span class="time">Just now</span>
                                    </span>
                                    <span class="message">
                                        Hi mate, how is everything?
                                    </span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php#">
                                <span class="photo"><img alt="avatar" src="assets/img/ui-divya.jpg"></span>
                                    <span class="subject">
                                    <span class="from">Divya Manian</span>
                                    <span class="time">40 mins.</span>
                                    </span>
                                    <span class="message">
                                     Hi, I need your help with this.
                                    </span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php#">
                                <span class="photo"><img alt="avatar" src="assets/img/ui-danro.jpg"></span>
                                    <span class="subject">
                                    <span class="from">Dan Rogers</span>
                                    <span class="time">2 hrs.</span>
                                    </span>
                                    <span class="message">
                                        Love your new Dashboard.
                                    </span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php#">
                                <span class="photo"><img alt="avatar" src="assets/img/ui-sherman.jpg"></span>
                                    <span class="subject">
                                    <span class="from">Dj Sherman</span>
                                    <span class="time">4 hrs.</span>
                                    </span>
                                    <span class="message">
                                        Please, answer asap.
                                    </span>
                            </a>
                        </li>
                        <li>
                            <a href="index.php#">See all messages</a>
                        </li> -->
                    </ul>
                </li>
                <!-- inbox dropdown end -->
            </ul>
            <!--  notification end -->
        </div>
        <div class="top-menu">
            <ul class="nav pull-right top-menu">
                <li><a class="logout" href="login.php">Logout</a></li>
            </ul>
        </div>
    </header>
    <!--header end-->

    <!-- **********************************************************************************************************************************************************
    MAIN SIDEBAR MENU
    *********************************************************************************************************************************************************** -->
    <!--sidebar start-->
    <aside>
        <div id="sidebar" class="nav-collapse ">
            <!-- sidebar menu start-->
            <ul class="sidebar-menu" id="nav-accordion">

                <p class="centered"><a href="profile.php"><img src="assets/img/logo.png" class="img-circle" width="60"></a>
                </p>
                <h5 class="centered">Untitled</h5>

                <li class="mt">
                     <a href="index.php">
                            <i class="fa fa-search"></i>
                            <span>Search</span>
                     </a>
                </li>

                <!--<li class="mt">-->
                    <!--<a class="active" href="index.php">-->
                        <!--<i class="fa fa-search"></i>-->
                        <!--<span>Search</span>-->
                    <!--</a>-->
                <!--</li>-->
                <li>
                    <a href="analytics.php">
                        <i class="fa fa-bar-chart-o"></i>
                        <span>Analytics</span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-twitter"></i>
                        <span>My Twitter</span>
                    </a>
                    <ul class="sub">
                        <li><a href="calendar.php">Calendar</a></li>
                        <li><a href="gallery.php">Gallery</a></li>
                        <li><a href="profile.php">Profile</a></li>
                    </ul>
                </li>



                <div>Most Popular Languages</div>                    
                <div class="tags" style="margin-bottom: 40px;">
                    <div class="tagcloud" id="tweet_lang"></div>
                </div>

                <div>Most Popular Hashtags</div>                    
                <div  class="tags">
                    <div class="tagcloud" id="tweet_hashtags"></div>
                </div>

                <div>Demographs</div>                    
                <div class="tags">
                    <div class="tagcloud" id="user_location"></div>
                </div>

            </ul>
            <!-- sidebar menu end-->
        </div>
    </aside>
    <!--sidebar end-->

    <!-- **********************************************************************************************************************************************************
    MAIN CONTENT
    *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">

            <div class="row">
                <div class="col-lg-9 main-chart">

                    <!--<div class="row mt">-->
                        <!--<div class="col-md-12 col-sm-12 mb">-->
                            <!--<div class="white-panel pn">-->

                            <!--</div>-->
                        <!--</div>&lt;!&ndash; /col-md-4&ndash;&gt;-->
                    <!--</div>-->

                    <div class="panel" style="padding:10px">
                        <form class="form-inline" role="form">
                            <div class="form-group autoComplete">
                                <label class="search">Search : </label>
                                <input class="form-control" type="text" id="searchBox" placeholder="" style="width:600px"/>
                                <!--<button class="btn btn-theme" onclick="alert($('#searchBox').val())">Go</button>-->
                                <span id="searchBtn" onclick="Manager.search()">Go</span>
                            </div>
                        </form>
                    </div>

                    <div class="panel" id="result">
                        <!--<div class="col-md-12 col-sm-12 mb" >-->
                            <!--<p id="docs">-->
                            <!--</p>-->
                        <!--</div>-->
                        <div id="navigation">
                            <ul id="pager"></ul>
                            <div id="pager-header"></div>
                        </div>

                    </div>

                    <div class="panel" style="background-color: transparent;box-shadow: none;">
                        <div class="col-md-12 col-sm-12 mb" id="docs">
                        </div>
                    </div>





                    <!--<div class="row mt">-->
                        <!--&lt;!&ndash; SERVER STATUS PANELS &ndash;&gt;-->
                        <!--<div class="col-md-12 col-sm-12 mb">-->
                            <!--<div class="white-panel pn">-->
                                <!--<div class="row">-->
                                    <!--<div class="col-sm-6 col-xs-6 goleft">-->
                                        <!--<p><i class="fa fa-database"></i> 70%</p>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<! &#45;&#45;/grey-panel &ndash;&gt;-->
                        <!--</div>&lt;!&ndash; /col-md-4&ndash;&gt;-->
                    <!--</div>&lt;!&ndash; /row &ndash;&gt;-->

                    <!--<div class="row mt">-->
                        <!--&lt;!&ndash; SERVER STATUS PANELS &ndash;&gt;-->
                        <!--<div class="col-md-12 col-sm-12 mb">-->
                            <!--<div class="white-panel pn">-->
                                <!--<div class="row">-->
                                    <!--<div class="col-sm-6 col-xs-6 goleft">-->
                                        <!--<p><i class="fa fa-database"></i> 70%</p>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<! &#45;&#45;/grey-panel &ndash;&gt;-->
                        <!--</div>&lt;!&ndash; /col-md-4&ndash;&gt;-->
                    <!--</div>&lt;!&ndash; /row &ndash;&gt;-->

                    <!--<div class="row mt">-->
                        <!--&lt;!&ndash; SERVER STATUS PANELS &ndash;&gt;-->
                        <!--<div class="col-md-12 col-sm-12 mb">-->
                            <!--<div class="white-panel pn">-->
                                <!--<div class="row">-->
                                    <!--<div class="col-sm-6 col-xs-6 goleft">-->
                                        <!--<p><i class="fa fa-database"></i> 70%</p>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<! &#45;&#45;/grey-panel &ndash;&gt;-->
                        <!--</div>&lt;!&ndash; /col-md-4&ndash;&gt;-->
                    <!--</div>&lt;!&ndash; /row &ndash;&gt;-->

                    <!--<div class="row mt">-->
                        <!--&lt;!&ndash; SERVER STATUS PANELS &ndash;&gt;-->
                        <!--<div class="col-md-12 col-sm-12 mb">-->
                            <!--<div class="white-panel pn">-->
                                <!--<div class="row">-->
                                    <!--<div class="col-sm-6 col-xs-6 goleft">-->
                                        <!--<p><i class="fa fa-database"></i> 70%</p>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<! &#45;&#45;/grey-panel &ndash;&gt;-->
                        <!--</div>&lt;!&ndash; /col-md-4&ndash;&gt;-->
                    <!--</div>&lt;!&ndash; /row &ndash;&gt;-->

                    <!--<div class="row mt">-->
                        <!--&lt;!&ndash; SERVER STATUS PANELS &ndash;&gt;-->
                        <!--<div class="col-md-12 col-sm-12 mb">-->
                            <!--<div class="white-panel pn">-->
                                <!--<div class="row">-->
                                    <!--<div class="col-sm-6 col-xs-6 goleft">-->
                                        <!--<p><i class="fa fa-database"></i> 70%</p>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<! &#45;&#45;/grey-panel &ndash;&gt;-->
                        <!--</div>&lt;!&ndash; /col-md-4&ndash;&gt;-->
                    <!--</div>&lt;!&ndash; /row &ndash;&gt;-->

                    <!--<div class="row mt">-->
                        <!--&lt;!&ndash; SERVER STATUS PANELS &ndash;&gt;-->
                        <!--<div class="col-md-12 col-sm-12 mb">-->
                            <!--<div class="white-panel pn">-->
                                <!--<div class="row">-->
                                    <!--<div class="col-sm-6 col-xs-6 goleft">-->
                                        <!--<p><i class="fa fa-database"></i> 70%</p>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<! &#45;&#45;/grey-panel &ndash;&gt;-->
                        <!--</div>&lt;!&ndash; /col-md-4&ndash;&gt;-->
                    <!--</div>&lt;!&ndash; /row &ndash;&gt;-->

                    <!--<div class="row mt">-->
                        <!--&lt;!&ndash; SERVER STATUS PANELS &ndash;&gt;-->
                        <!--<div class="col-md-12 col-sm-12 mb">-->
                            <!--<div class="white-panel pn">-->
                                <!--<div class="row">-->
                                    <!--<div class="col-sm-6 col-xs-6 goleft">-->
                                        <!--<p><i class="fa fa-database"></i> 70%</p>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<! &#45;&#45;/grey-panel &ndash;&gt;-->
                        <!--</div>&lt;!&ndash; /col-md-4&ndash;&gt;-->
                    <!--</div>&lt;!&ndash; /row &ndash;&gt;-->

                    <!--<div class="row mt">-->
                        <!--&lt;!&ndash; SERVER STATUS PANELS &ndash;&gt;-->
                        <!--<div class="col-md-12 col-sm-12 mb">-->
                            <!--<div class="white-panel pn">-->
                                <!--<div class="row">-->
                                    <!--<div class="col-sm-6 col-xs-6 goleft">-->
                                        <!--<p><i class="fa fa-database"></i> 70%</p>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                            <!--<! &#45;&#45;/grey-panel &ndash;&gt;-->
                        <!--</div>&lt;!&ndash; /col-md-4&ndash;&gt;-->
                    <!--</div>&lt;!&ndash; /row &ndash;&gt;-->



                </div><!-- /col-lg-9 END SECTION MIDDLE -->


                <!-- **********************************************************************************************************************************************************
                RIGHT SIDEBAR CONTENT
                *********************************************************************************************************************************************************** -->


                <div class="col-lg-3 ds">

                    <!-- Map -->
                    <div id="countries"></div>
                    <div id="preview"></div> <!-- Map -->
                    <!-- CALENDAR-->
                                        <div id="calendar" class="mb">

                                        </div>

                                        <!-- / calendar -->

                    <!--COMPLETED ACTIONS DONUTS CHART-->
                    <h3>NOTIFICATIONS</h3>

                    <?php
                    foreach($status as $obj){?>
                            <div class="desc">
                                            <div class="thumb">
                                                <span class="badge bg-theme"><i class="fa fa-twitter"></i></span>
                                            </div>
                                            <div class="details">
                                                <p>
                                                    <muted><?=$obj->created_at;?></muted>
                                                    <br/>
                                                    <a href="#"><?=$obj->user->screen_name?></a> <?=$obj->text;?><br/>
                                                </p>
                                            </div>
                                        </div>
                    <?php }?>

                <!-- USERS ONLINE SECTION -->
                    <h3>TWEETS LEADERBOARD</h3>
                    <!-- First Member -->
                    <div class="desc">
                        <div class="thumb">
                            <img class="img-circle" src="assets/img/ui-divya.jpg" width="35px" height="35px" align="">
                        </div>
                        <div class="details">
                            <p><a href="#">DIVYA MANIAN</a><br/>
                                <muted>Available</muted>
                            </p>
                        </div>
                    </div>
                    <!-- Second Member -->
                    <div class="desc">
                        <div class="thumb">
                            <img class="img-circle" src="assets/img/ui-sherman.jpg" width="35px" height="35px" align="">
                        </div>
                        <div class="details">
                            <p><a href="#">DJ SHERMAN</a><br/>
                                <muted>I am Busy</muted>
                            </p>
                        </div>
                    </div>
                    <!-- Third Member -->
                    <div class="desc">
                        <div class="thumb">
                            <img class="img-circle" src="assets/img/ui-danro.jpg" width="35px" height="35px" align="">
                        </div>
                        <div class="details">
                            <p><a href="#">DAN ROGERS</a><br/>
                                <muted>Available</muted>
                            </p>
                        </div>
                    </div>
                    <!-- Fourth Member -->
                    <div class="desc">
                        <div class="thumb">
                            <img class="img-circle" src="assets/img/ui-zac.jpg" width="35px" height="35px" align="">
                        </div>
                        <div class="details">
                            <p><a href="#">Zac Sniders</a><br/>
                                <muted>Available</muted>
                            </p>
                        </div>
                    </div>
                    <!-- Fifth Member -->
                    <div class="desc">
                        <div class="thumb">
                            <img class="img-circle" src="assets/img/ui-sam.jpg" width="35px" height="35px" align="">
                        </div>
                        <div class="details">
                            <p><a href="#">Marcel Newman</a><br/>
                                <muted>Available</muted>
                            </p>
                        </div>
                    </div>




                </div><!-- /col-lg-3 -->
            </div>
            <! --/row -->
        </section>
    </section>

    <!--main content end-->
    <!--footer start-->
    <footer class="site-footer">
        <div class="text-center">
            2015 - Untitled.us
            <a href="index.php#" class="go-top">
                <i class="fa fa-angle-up"></i>
            </a>
        </div>
    </footer>
    <!--footer end-->
</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="assets/js/jquery.js"></script>
<script src="assets/js/jquery-1.8.3.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.24/jquery-ui.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script class="include" type="text/javascript" src="assets/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="assets/js/jquery.sparkline.js"></script>

<!--   Solr Spec  Libraries -->
<script src="assets/js/reuters.js"></script>
<script src="assets/js/core/Core.js"></script>
<script src="assets/js/core/AbstractManager.js"></script>
<script src="assets/js/managers/Manager.jquery.js"></script>
<script src="assets/js/core/Parameter.js"></script>
<script src="assets/js/core/ParameterStore.js"></script>
<script src="assets/js/core/AbstractWidget.js"></script>
<script src="assets/js/widgets/ResultWidget.js"></script>
<script src="assets/js/widgets/jquery/PagerWidget.js"></script>
<script src="assets/js/core/AbstractFacetWidget.js"></script>
<script src="assets/js/widgets/TagcloudWidget.js"></script>
<script src="assets/js/widgets/CurrentSearchWidget.9.js"></script>
<script src="assets/js/core/AbstractTextWidget.js"></script>
<script src="assets/js/widgets/AutocompleteWidget.js"></script>
<script src="assets/js/widgets/CountryCodeWidget.js"></script>
<script src="assets/js/widgets/CalendarWidget.js"></script>


<!--common script for all pages-->
<script src="assets/js/common-scripts.js"></script>

<script type="text/javascript" src="assets/js/gritter/js/jquery.gritter.js"></script>
<script type="text/javascript" src="assets/js/gritter-conf.js"></script>

<!--script for this page-->
<script src="assets/js/sparkline-chart.js"></script>
<script src="assets/js/zabuto_calendar.js"></script>
<!-- 
<script type="text/javascript">
    $(document).ready(function () {
        var unique_id = $.gritter.add({
            // (string | mandatory) the heading of the notification
            title: 'Welcome to Untitled!',
            // (string | mandatory) the text inside the notification
            text: 'Search Untitled for the latest news that made headlines.<br/>Make untitled your homepage.<p style="margin-top:5px"> \
                   <button type="submit" class="btn btn-theme">Yes, Sure.</button></p>',
            // (string | optional) the image to display on the left
            image: 'assets/img/logo.png',
            // (bool | optional) if you want it to fade out on its own or just sit there
            sticky: true,
            // (int | optional) the time you want it to be alive for before fading out
            time: '',
            // (string | optional) the class name you want to apply to that specific message
            class_name: 'my-sticky-class'
        });

        return false;
    });
</script>
 -->
<script type="application/javascript">
    $(document).ready(function () {
        $("#date-popover").popover({html: true, trigger: "manual"});
        $("#date-popover").hide();
        $("#date-popover").click(function (e) {
            $(this).hide();
        });

        $('#searchBtn').bind('keypress', function(e) {
        var code = e.keyCode || e.which;
        if(code == 13) { //Enter keycode
          Manager.search()
          }
        });

        $('form').bind("keypress", function(e) {
          if (e.keyCode == 13) {               
            e.preventDefault();
            Manager.search();
            //Manager.search()
            return false;

          }
        });

    /*    $("#my-calendar").zabuto_calendar({
            action: function () {
                return myDateFunction(this.id, false);
            },
            action_nav: function () {
                return myNavFunction(this.id);
            },
//            ajax: {
//                url: "show_data.php?action=1",
//                modal: true
//            },
            legend: [
                {type: "text", label: "Special event", badge: "00"},
                {type: "block", label: "Regular event",}
            ]
        });*/
    });


    function myNavFunction(id) {
        $("#date-popover").hide();
        var nav = $("#" + id).data("navigation");
        var to = $("#" + id).data("to");
        console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
    }

    function myDateFunction(id) {
        var date = $("#" + id).data("date");
        var hasEvent = $("#" + id).data("hasEvent");
    }
</script>


</body>
</html>
