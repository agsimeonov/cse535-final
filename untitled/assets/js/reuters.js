var Manager;

(function ($) {

  $(function () {
    Manager = new AjaxSolr.Manager({
      //solrUrl: 'http://reuters-demo.tree.ewdev.ca:9090/reuters/'
      //solrUrl: 'http://localhost:8983/solr/core_BM25/select?q=*%3A*&wt=json&indent=true'
      solrUrl : "http://istanbul.cse.buffalo.edu:8983/solr/UntitledSolr/"
      //solrUrl: 'http://localhost:8983/solr/UntitledSolr/'
      // If you are using a local Solr instance with a "reuters" core, use:
      // solrUrl: 'http://localhost:8983/solr/reuters/'
      // If you are using a local Solr instance with a single core, use:
      // solrUrl: 'http://localhost:8983/solr/'
    });



    Manager.addWidget(new AjaxSolr.ResultWidget({
      id: 'result',
      target: '#docs'
    }));

    Manager.addWidget(new AjaxSolr.PagerWidget({
      id: 'pager',
      target: '#pager',
      prevLabel: '&lt;',
      nextLabel: '&gt;',
      innerWindow: 1,
      renderHeader: function (perPage, offset, total) {
        $('#pager-header').html($('<span></span>').text('Showing ' + Math.min(total, offset + 1) + ' to ' + Math.min(total, offset + perPage) + ' of ' + total + ' results found.'));
      },
    }));
    var fields = [ 'tweet_lang', 'tweet_hashtags', 'user_location' ];
    for (var i = 0, l = fields.length; i < l; i++) {
      Manager.addWidget(new AjaxSolr.TagcloudWidget({
        id: fields[i],
        target: '#' + fields[i],
        field: fields[i]
      }));
    }
    Manager.addWidget(new AjaxSolr.CurrentSearchWidget({
      id: 'currentsearch',
      target: '#selection'
    }));
    Manager.addWidget(new AjaxSolr.AutocompleteWidget({
      id: 'text',
      target: '.autoComplete',
      fields: [ 'tweet_lang', 'tweet_hashtags', 'user_location' ]
    }));
    Manager.addWidget(new AjaxSolr.CountryCodeWidget({
      id: 'countries',
      target: '#countries',
      field: 'user_location'
    }));
    Manager.addWidget(new AjaxSolr.CalendarWidget({
     id: 'calendar',
     target: '#calendar',
     field: 'tweet_created_at'
    }));
    Manager.init();
    Manager.store.addByValue('q', '*:*');
    var params = {
      'facet': true,
      'facet.field': [ 'tweet_lang', 'tweet_hashtags', 'user_location' ],
      'facet.limit': 20,
      'facet.mincount': 1,
      'json.nl': 'map',
      'facet.range': 'tweet_created_at',
      'facet.range.start': '2015-11-20T00:00:00.000Z/DAY',
      'facet.range.end': '2015-11-26T00:00:00.000Z/DAY+1DAY',
      'facet.range.gap': '+1DAY',
    };
    //var params = {
    //  'facet': true,
    //  'facet.field': [ 'tweet_lang', 'tweet_hashtags', 'user_location' ],
    //  'facet.limit': 20,
    //  'facet.mincount': 1,
    //  'f.topics.facet.limit': 50,
    //  'f.countryCodes.facet.limit': -1,
    //  'facet.date': 'date',
    //  'facet.date.start': '1987-02-26T00:00:00.000Z/DAY',
    //  'facet.date.end': '1987-10-20T00:00:00.000Z/DAY+1DAY',
    //  'facet.date.gap': '+1DAY',
    //  'json.nl': 'map'
    //};
    for (var name in params) {
      Manager.store.addByValue(name, params[name]);
    }
    Manager.doRequest();
  });

  $.fn.showIf = function (condition) {
    if (condition) {
      return this.show();
    }
    else {
      return this.hide();
    }
  }

})(jQuery);
