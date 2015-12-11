(function (callback) {
  if (typeof define === 'function' && define.amd) {
    define(['core/AbstractManager'], callback);
  }
  else {
    callback();
  }
}(function () {

/**
 * @see http://wiki.apache.org/solr/SolJSON#JSON_specific_parameters
 * @class Manager
 * @augments AjaxSolr.AbstractManager
 */
AjaxSolr.Manager = AjaxSolr.AbstractManager.extend(
  /** @lends AjaxSolr.Manager.prototype */
  {

  search: function(){
      var searchQuery = $('#searchBox').val();
      Manager.store.addByValue('q',searchQuery);
      this.doRequest();
      //$('#searchBox').val(searchQuery);
  } ,
  executeRequest: function (servlet, string, handler, errorHandler) {
    var self = this,
        options = {dataType: 'jsonp'};
    string = string || this.store.string();
    handler = handler || function (data) {
      self.handleResponse(data);
    };
    errorHandler = errorHandler || function (jqXHR, textStatus, errorThrown) {
      self.handleError(textStatus + ', ' + errorThrown);
    };
    if (this.proxyUrl) {
      options.url = this.proxyUrl;
      options.data = {query: string};
      options.type = 'POST';
    }
    else {
      options.url = this.solrUrl + servlet + '?' + string + '&wt=json&rows=100&json.wrf=?';
    }
    jQuery.ajax(options).done(handler).fail(errorHandler);
  }
});

}));
