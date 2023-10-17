$(document).on('click', '.js-catalog_refresh a', function(e){

  var $link = $(this),
      sUrl = $link.attr('href')
        .replace('+', '%2B')/*.replace(' ', '+')*/, // url fix
      $loadElement = $link.closest('.js-catalog_refresh'),
      ajaxId = $loadElement.data('ajax-id');
      $refreshArea = $('#' + ajaxId),
      sorterAjaxExec = false;

  var ajaxRequest = {
        type: 'POST',
        url: sUrl,
        success: function(data) {
          var json = BX.parseJSON(data);

          if ($loadElement.data('history-push') != undefined) {
            history.pushState(null, null, sUrl);
          }

          if (json == null) {
            $refreshArea.html(data);
          } else {
            for (var id in json) {
              $('#' + ajaxId + '_' + id).html(json[id]);
            }
          }

          $link.parent().addClass('active').siblings().removeClass('active');
          //appSLine.setProductItems()
        },
        error: function() {
          console.warn('sorter - change template -> error responsed');
        },
        complete: function() {
          sorterAjaxExec = false;
          $refreshArea.removeClass('overlay is-loading');
        }
      };

  if (
    $loadElement.length > 0 &&
    !sorterAjaxExec &&
    ajaxRequest.url != '#' && ajaxRequest.url != undefined
  ) {

    $refreshArea.addClass('overlay is-loading');

    sorterAjaxExec = true;

    ajaxRequest.url += (
      ajaxRequest.url.indexOf('?') < 0
        ? '?'
        : ajaxRequest.url.slice(-1) != '&'
          ? '&'
          : ''
    ) + 'action=catalogRefresh';

    if (ajaxId != undefined) {
      ajaxRequest.url += '&ajax_id=' + ajaxId;
    }

    $.ajax(ajaxRequest);
  }

  e.preventDefault();
});