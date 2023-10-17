
RS.Application().ready(function() {

	var $filterTop = $('.filter_top');
	$filterTop.find('.filter_top__box').each(function(){

    $(this).children('.filter_top__scroll').scrollbar({
      showArrows: true,
      scrollx: $(this).find('.filter_top__nav'),
      scrollStep: 350
    });

	});

});