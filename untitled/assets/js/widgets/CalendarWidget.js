(function ($) {

AjaxSolr.CalendarWidget = AjaxSolr.AbstractFacetWidget.extend({
  afterRequest: function () {
    var self = this;
    $(this.target).datepicker('destroy').datepicker({
      dateFormat: 'yy-mm-dd',
      defaultDate: new Date(2015, 11, 20),
      maxDate: $.datepicker.parseDate('yy-mm-dd', this.manager.store.get('facet.range.end').val().substr(0, 10)),
      minDate: $.datepicker.parseDate('yy-mm-dd', this.manager.store.get('facet.range.start').val().substr(0, 10)),
      nextText: '&gt;',
      prevText: '&lt;',
      beforeShowDay: function (date) {
        var value = $.datepicker.formatDate('yy-mm-dd', date) + 'T00:00:00Z';
        var count = self.manager.response.facet_counts.facet_ranges.tweet_created_at.counts[value];
        return [ parseInt(count) > 0, '', count + ' documents found!' ];
      },
      onSelect: function (dateText, inst) {
        if (self.add('[' + dateText + 'T00:00:00Z TO ' + dateText + 'T23:59:59Z]')) {
          self.doRequest();
        }
      }
    });
  }
});

})(jQuery);