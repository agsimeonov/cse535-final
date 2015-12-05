(function ($) {

AjaxSolr.ResultWidget = AjaxSolr.AbstractWidget.extend({
  afterRequest: function () {
    $(this.target).empty();
    for (var i = 0, l = this.manager.response.response.docs.length; i < l; i++) {
      var doc = this.manager.response.response.docs[i];
      $(this.target).append(this.template(doc));
    }
  },

  template: function (doc) {
    //new
    var snippet = '';
    var url='';
    if(doc.tweet_urls.length() > 0){
      url = doc.tweet_urls[0];
    }

    var output = '<div>';
    output += '<p id="links_' + url + '" class="links"></p>';
    if('text_de' in doc)
      output += '<p>' + doc.text_de + '</p></div>';
    if('text_ru' in doc)
      output += '<p>' + doc.text_ru + '</p></div>';
    if('text_en' in doc)
      output += '<p>' + doc.text_en + '</p></div>';

    return output;

    //new

    /*
    var snippet = '';
    if (doc.text.length > 300) {
      snippet += doc.dateline + ' ' + doc.text.substring(0, 300);
      snippet += '<span style="display:none;">' + doc.text.substring(300);
      snippet += '</span> <a href="#" class="more">more</a>';
    }
    else {
      snippet += doc.dateline + ' ' + doc.text;
    }

    var output = '<div><h2>' + doc.title + '</h2>';
    output += '<p id="links_' + doc.id + '" class="links"></p>';
    output += '<p>' + snippet + '</p></div>';
    return output;

    */
  }
});

})(jQuery);