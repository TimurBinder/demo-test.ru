window.RS = window.RS || {};

RS.loadMore = (function($) {

  var loadingClass = 'is-loading';

  return function(url, placeSelector, data, replace) {
    var d = $.Deferred(),
      replace = replace || false,
      data = data || {},
      url;

    data['is_ajax'] = 'Y';

    $.get({
        url: url,
        data: data,
        dataType: 'html'
      })
      .done(function(data) {
        if (replace) {
          $(placeSelector).html(data);
        } else {
          $(placeSelector).append(data);
        }

        d.resolve();
      })
      .fail(d.reject);

    return d.promise();
  };
  
  $(".fakeloader").fakeLoader({
      
    });
}(jQuery));