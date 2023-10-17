
; /* Start:"a:4:{s:4:"full";s:109:"/bitrix/templates/master_default_2019_02_18_07_54_20/components/bitrix/catalog/master/script.js?1552743774300";s:6:"source";s:95:"/bitrix/templates/master_default_2019_02_18_07_54_20/components/bitrix/catalog/master/script.js";s:3:"min";s:0:"";s:3:"map";s:0:"";}"*/

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


/* End */
;; /* /bitrix/templates/master_default_2019_02_18_07_54_20/components/bitrix/catalog/master/script.js?1552743774300*/
