RS.Application().ready(function() {

    $('.animate img').waypoint({
        handler: function(event, direction) {
            $(this.element).animate({opacity:1, "visibility":"visible"},500);
        },
        offset: '75%'        
    });


    if ($('.landing__setting').length > 0) {
        var setIcon = $('.landing__crop_settings').html(),
        link = "", setting, iblockId, iblockType, idElem, sectionId;
        $(document).on('mouseenter', '.landing__block', function(){
            if ($(this).find('.landing__setting').length > 0) {
                $(this).find('.landing__setting').show();
            } else {
                $(this).append(setIcon);
                $setting = $(this).find('.landing__setting');
                $setting.show();
                iblockId = $setting.data("iblockid");
                iblockType = $setting.data("iblocktype");
                idElem = $(this).data("id");
                sectionId = $(this).data("sectionid");
                link = "/bitrix/admin/iblock_element_edit.php?IBLOCK_ID="+iblockId+"&type="+iblockType+"&ID="+idElem+"&lang=ru&find_section_section="+sectionId;
                //link = "/bitrix/admin/iblock_element_edit.php?IBLOCK_ID="+iblockId+"&type="+iblockType+"&ID="+idElem+"&force_catalog=&filter_section=49&lang=ru&find_section_section="+sectionId+'&bxpublic=Y&from_module=iblock&bxsender=core_window_cadmindialog';
                $setting.find("a").attr("href", link);
            }                
        }).on('mouseleave', '.landing__block', function(){
            $(this).find('.landing__setting').hide();
        });

        /*$(document).on('click', '.landing__setting', function(){
            $link = $(this).find('a');
            $.ajax({
                url: $link.attr('href'),
                success: function(e) {
                    $('.landing_setting_inner').html(e);
                }
            });

            return false;
        });*/
    }

    $(document).on('click', '.js-buy .js-buy__add2cart', function(){
        var idItem = $(this).parent('.js-buy').data('productid');
        Basket.add(idItem, 1);
    });
    //Basket.add
});
