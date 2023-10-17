function callbackSecTimer(seconds, Esec, num) {
    var test = (seconds/num)*100;
    $(Esec).data('easyPieChart').update(test);
}

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


    dateNow = BX.message('SERVER_TIME');
    
    //Basket.add
    $(document).on('click', '.js-buy__add2cart', function(){
        var idItem = 0;
        if (!!$(this).parent('.js-buy').data('productid'))
            idItem = $(this).parent('.js-buy').data('productid');
        else
            idItem = $(this).data('productid');
        Basket.add(idItem, 1);
    });

    if ($('.js-landing__nlist__number').length > 0) {
        $(document).on('click', '.js-landing__nlist__number', function(){
            var num = $(this).data('num');
            $(this).parents('.landing__nlist').find('.landing__nlist__cur').removeClass('landing__nlist__cur');
            $(this).parents('.landing__nlist').find('.landing__nlist__'+num).addClass('landing__nlist__cur');
        });
    }

    if ($('.landing__form').length > 0) {
        $(document).on('submit', '.landing__form .rsfrom__form', function(){
            var form = $(this).serialize(),
            url = $(this).attr('action'),
            idForm = $(this).parents('.landing__block').data('id');
            $.post (url, form)
                .done (function( data ) {
                    $("#form_"+idForm).html( data );
                });

            return false;
        });
    }
});
