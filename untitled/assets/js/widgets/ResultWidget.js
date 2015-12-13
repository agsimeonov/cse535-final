(function ($) {

AjaxSolr.ResultWidget = AjaxSolr.AbstractWidget.extend({
  start: 0,

  beforeRequest: function () {
    $(this.target).html($('<img>').attr('src', 'images/ajax-loader.gif'));
  },

  facetLinks: function (facet_field, facet_values) {
    var links = [];
    if (facet_values) {
      for (var i = 0, l = facet_values.length; i < l; i++) {
        if (facet_values[i] !== undefined) {
          links.push(
            $('<a href="#"></a>')
            .text(facet_values[i])
            .click(this.facetHandler(facet_field, facet_values[i]))
          );
        }
        else {
          links.push('no items found in current selection');
        }
      }
    }
    return links;
  },

  facetHandler: function (facet_field, facet_value) {
    var self = this;
    return function () {
      self.manager.store.remove('fq');
      self.manager.store.addByValue('fq', facet_field + ':' + AjaxSolr.Parameter.escapeValue(facet_value));
      self.doRequest(0);
      return false;
    };
  },

  afterRequest: function () {
    $(this.target).empty();

    var data = JSON.stringify(this.manager.response);

    $.post('http://istanbul.cse.buffalo.edu:9886',data,function(data){
        console.log('successfull');
    });

    //this.manager.response.grouped.signatureField.groups[0].doclist.docs
    //this.manager.response.response.docs.length
    for (var k=0;k<this.manager.response.grouped.signatureField.groups.length;k++){
      //for (var i = 0, l = this.manager.response.grouped.signatureField.groups[k].doclist.docs.length; i < l; i++) {
        //var doc = this.manager.response.response.docs[i];
        var doc = this.manager.response.grouped.signatureField.groups[k].doclist.docs[0];
        $(this.target).append(this.template(doc));
        var items = [];
        items = items.concat(this.facetLinks('organisations', doc.organisations));
        items = items.concat(this.facetLinks('exchanges', doc.exchanges));

        var $links = $('#links_' + doc.id);
        $links.empty();
        for (var j = 0, m = items.length; j < m; j++) {
          $links.append($('<li></li>').append(items[j]));
        }
      //}
    }
  },

  template: function (doc) {
    //var snippet = '';
    //if (doc.text.length > 300) {
    //  snippet += doc.dateline + ' ' + doc.text.substring(0, 300);
    //  snippet += '<span style="display:none;">' + doc.text.substring(300);
    //  snippet += '</span> <a href="#" class="more">more</a>';
    //}
    //else {
    //  snippet += doc.dateline + ' ' + doc.text;
    //}
    //
    //var output = '<div><h2>' + doc.title + '</h2>';
    //output += '<p id="links_' + doc.id + '" class="links"></p>';
    //output += '<p>' + snippet + '</p></div>';
    //return output;

    var snippet = '';
    var url='';
    if( ('tweet_urls' in  doc) && doc.tweet_urls.length > 0){
      url = doc.tweet_urls[0];
    }

    var  tweetPane = '<div class="tweetPane">';

    /* HASH TAGS */
    var hashTags = '<span class="hashTags">';
    for(var tag in doc.tweet_hashtags){
      //hash_tags += '<span class="switch has-switch"><div class="switch-on switch-animate"><input type="checkbox" checked="" data-toggle="switch"><span class="switch-left">'+ doc.tweet_hashtags[tag] +'</span><label>&nbsp;</label><span class="switch-right">OFF</span></div></span>';
      hashTags += '<span>'+ doc.tweet_hashtags[tag] +'</span>';

    }
    hashTags += '</span>';
    /* HASH TAGS */

    /* RE-TWEET*/
    var reTweet = '<span class="retweet">';
    reTweet += '<span class="fa fa-retweet"></span><span>'+ doc.tweet_retweet_count +'</span>';
    reTweet += '</span>';
    /* RE-TWEET */

    /* FAVOURITE COUNT */
    var favCount = '<span class="favCount">';
    favCount += '<span class="fa fa-heart"></span><span>'+ doc.tweet_favorite_count +'</span>';
    favCount += '</span>';
    /* FAVOURITE COUNT */


    //var userPic = "<?php $url = 'https://api.twitter.com/1.1/users/lookup.json';";
    //userPic += "$getfield = '?screen_name="+ doc.user_screen_name + "';$requestMethod = 'GET';$twitter = new TwitterAPIExchange($settings);";
    //userPic += "$json = json_decode($twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest());";
    //userPic += "echo $json[0]['profile_image_url'] ?>"


    /* USER */
    var user = '<span class="user">';
    user += '<span class="thumb"><img class="img-circle" src="assets/img/ui-divya.jpg" width="35px" height="35px" align=""></span>';
    user += '<span>'+ doc.user_name +'</span>';
    user += '</span>';
    /* USER */

    tweetPane += hashTags + user + reTweet + favCount ;
    tweetPane += '</div>';



    var src = '<span class="badge bg-theme res"><i class="fa fa-twitter"></i></span>';
    var output = '<div>';
    output += '<p id="links_' + url + '" class="links"></p>';
    var txt = 'tweet_text_' + doc.tweet_lang;
    output += '<p class="result"><span>'+ src +'</span>' + doc[txt] +'</p>';
    output += tweetPane;
    output += '</div>';

    return output;

  },



  init: function () {
    $(document).on('click', 'a.more', function () {
      var $this = $(this),
          span = $this.parent().find('span');

      if (span.is(':visible')) {
        span.hide();
        $this.text('more');
      }
      else {
        span.show();
        $this.text('less');
      }

      return false;
    });
  }
});

})(jQuery);