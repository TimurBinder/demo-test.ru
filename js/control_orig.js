


$('.nav_btn').click(function(){
    $('nav').addClass('nav_active');
    $('.non_active').addClass('active_over');
    $('.section_1_bottom').css('z-index', '0');
$('.section_2').addClass('display_none');

});
$('div.non_active').click(function(){
    $('nav').removeClass('nav_active');
    $('#bc1').removeClass('content_active');
    $('#bc2').removeClass('content_active_2');
    $('#bc3').removeClass('content_active_3');
    $('#bc4').removeClass('content_active_4');
    $('#bc5').removeClass('content_active_5');
    $('#bc6').removeClass('content_active_6');
    $(this).removeClass('active_over');
        $('.section_1_bottom').css('z-index', '1000');
});
$('div.non_active_2').click(function(){
    $('nav').removeClass('nav_active');
    $('#bc2').removeClass('content_active_2');
    $(this).removeClass('active_over');
        $('.section_1_bottom').css('z-index', '1000');
});
$('div.non_active_3').click(function(){
    $('nav').removeClass('nav_active');
    $('#bc3').removeClass('content_active_3');
    $(this).removeClass('active_over');
        $('.section_1_bottom').css('z-index', '1000');
});
$('div.non_active_4').click(function(){
    $('nav').removeClass('nav_active');
    $('#bc4').removeClass('content_active_4');
    $(this).removeClass('active_over');
        $('.section_1_bottom').css('z-index', '1000');
});
$('div.non_active_5').click(function(){
    $('nav').removeClass('nav_active');
    $('#bc5').removeClass('content_active_5');
    $(this).removeClass('active_over');
        $('.section_1_bottom').css('z-index', '1000');
});
$('div.non_active_6').click(function(){
    $('nav').removeClass('nav_active');
    $('#bc6').removeClass('content_active_6');
    $(this).removeClass('active_over');
        $('.section_1_bottom').css('z-index', '1000');
});
$('.nav_btn_close').click(function(){
    $('nav').removeClass('nav_active');
    $('div.non_active').removeClass('active_over');
     $('.section_1_bottom').css('z-index', '1000');
      $('.section_2').removeClass('display_none');
});



$('.nav_item').hover(function(){
    $('#list_active').addClass('nav_a_act');
    $('#list_active').removeClass('nav_a_act_3');
    $('#list_active').removeClass('nav_a_act_4');
    $('#list_active').removeClass('nav_a_act_5');
    $('#list_active').removeClass('nav_a_act_2');
    $('#list_active').removeClass('nav_click_act_2');
    $('#list_active').removeClass('nav_click_act_3');
    $('#list_active').removeClass('nav_click_act_4');
    $('#list_active').removeClass('nav_click_act_5');
});
$('.nav_item').mouseout(function(){
    $('#list_active').addClass('nav_a_act');
    $('#list_active').removeClass('nav_a_act_3');
    $('#list_active').removeClass('nav_a_act_4');
    $('#list_active').removeClass('nav_a_act_5');
    $('#list_active').removeClass('nav_a_act_2');
});
$('.nav_item_2').hover(function(){
    $('#list_active').addClass('nav_a_act_2');
    $('#list_active').removeClass('nav_a_act_3');
    $('#list_active').removeClass('nav_a_act_4');
    $('#list_active').removeClass('nav_a_act_5');
    $('#list_active').removeClass('nav_a_act');
    $('#list_active').removeClass('nav_click_act');
    $('#list_active').removeClass('nav_click_act_5');
    $('#list_active').removeClass('nav_click_act_3');
    $('#list_active').removeClass('nav_click_act_4');
});
$('.nav_item_2').mouseout(function(){
    $('#list_active').addClass('nav_a_act');
    $('#list_active').removeClass('nav_a_act_3');
    $('#list_active').removeClass('nav_a_act_4');
    $('#list_active').removeClass('nav_a_act_5');
    $('#list_active').removeClass('nav_a_act_2');
});
$('.nav_item_3').hover(function(){
    $('#list_active').addClass('nav_a_act_3');
    $('#list_active').removeClass('nav_a_act_2');
    $('#list_active').removeClass('nav_a_act_4');
    $('#list_active').removeClass('nav_a_act_5');
    $('#list_active').removeClass('nav_a_act');
    $('#list_active').removeClass('nav_click_act');
    $('#list_active').removeClass('nav_click_act_2');
    $('#list_active').removeClass('nav_click_act_5');
    $('#list_active').removeClass('nav_click_act_4');
});
$('.nav_item_3').mouseout(function(){
    $('#list_active').addClass('nav_a_act');
    $('#list_active').removeClass('nav_a_act_3');
    $('#list_active').removeClass('nav_a_act_4');
    $('#list_active').removeClass('nav_a_act_5');
    $('#list_active').removeClass('nav_a_act_2');
});
$('.nav_item_4').hover(function(){
    $('#list_active').addClass('nav_a_act_4');
    $('#list_active').removeClass('nav_a_act_2');
    $('#list_active').removeClass('nav_a_act_3');
    $('#list_active').removeClass('nav_a_act_5');
    $('#list_active').removeClass('nav_a_act');
    $('#list_active').removeClass('nav_click_act');
    $('#list_active').removeClass('nav_click_act_2');
    $('#list_active').removeClass('nav_click_act_3');
    $('#list_active').removeClass('nav_click_act_5');
});
$('.nav_item_4').mouseout(function(){
    $('#list_active').addClass('nav_a_act');
    $('#list_active').removeClass('nav_a_act_3');
    $('#list_active').removeClass('nav_a_act_4');
    $('#list_active').removeClass('nav_a_act_5');
    $('#list_active').removeClass('nav_a_act_2');
});
$('.nav_item_5').hover(function(){
    $('#list_active').addClass('nav_a_act_5');
    $('#list_active').removeClass('nav_a_act_2');
    $('#list_active').removeClass('nav_a_act_3');
    $('#list_active').removeClass('nav_a_act_4');
    $('#list_active').removeClass('nav_a_act');
    $('#list_active').removeClass('nav_click_act');
    $('#list_active').removeClass('nav_click_act_2');
    $('#list_active').removeClass('nav_click_act_3');
    $('#list_active').removeClass('nav_click_act_4');
});
$('.nav_item_5').mouseout(function(){
    $('#list_active').addClass('nav_a_act');
    $('#list_active').removeClass('nav_a_act_3');
    $('#list_active').removeClass('nav_a_act_4');
    $('#list_active').removeClass('nav_a_act_5');
    $('#list_active').removeClass('nav_a_act_2');
});


$('.nav_item').click(function(){
     $('#list_active').addClass('nav_click_act');
});
$('.nav_item_2').click(function(){
     $('#list_active').addClass('nav_click_act_2');
});
$('.nav_item_3').click(function(){
     $('#list_active').addClass('nav_click_act_3');
});
$('.nav_item_4').click(function(){
     $('#list_active').addClass('nav_click_act_4');
});
$('.nav_item_5').click(function(){
     $('#list_active').addClass('nav_click_act_5');
});










$('#bott1').click(function(){
    $('#bc1').toggleClass('content_active');
    $('#bc2').removeClass('content_active_2');
    $('#bc3').removeClass('content_active_3');
    $('#bc4').removeClass('content_active_4');
    $('#bc5').removeClass('content_active_5');
    $('#bc6').removeClass('content_active_6');
    $('.non_active').toggleClass('active_over');
    $('.non_active_2').removeClass('active_over');
    $('.non_active_3').removeClass('active_over');     $('.non_active_4').removeClass('active_over');
    $('.non_active_5').removeClass('active_over');
    $('.non_active_6').removeClass('active_over');
    
});

$('#bott2').click(function(){
    $('#bc2').toggleClass('content_active_2');
    $('#bc3').removeClass('content_active_3');
    $('#bc4').removeClass('content_active_4');
    $('#bc5').removeClass('content_active_5');
    $('#bc6').removeClass('content_active_6');
    $('#bc1').removeClass('content_active');
    $('.non_active_2').toggleClass('active_over');
    $('.non_active_3').removeClass('active_over');
    $('.non_active_4').removeClass('active_over');     $('.non_active_5').removeClass('active_over');
    $('.non_active_6').removeClass('active_over');
    $('.non_active').removeClass('active_over');

});
$('#bott3').click(function(){
    $('#bc3').toggleClass('content_active_3');
    $('#bc4').removeClass('content_active_4');
    $('#bc5').removeClass('content_active_5');
    $('#bc6').removeClass('content_active_6');
    $('#bc1').removeClass('content_active');
    $('#bc2').removeClass('content_active_2');
    $('.non_active_3').toggleClass('active_over');     $('.non_active_4').removeClass('active_over');
    $('.non_active_5').removeClass('active_over');
    $('.non_active_6').removeClass('active_over');     $('.non_active').removeClass('active_over');     $('.non_active_2').removeClass('active_over');
});
$('#bott4').click(function(){
    $('#bc4').toggleClass('content_active_4');
    $('#bc5').removeClass('content_active_5');
    $('#bc6').removeClass('content_active_6');
    $('#bc1').removeClass('content_active');
    $('#bc2').removeClass('content_active_2');
    $('#bc3').removeClass('content_active_3');
    $('.non_active_4').toggleClass('active_over');       $('.non_active_5').removeClass('active_over');
    $('.non_active_6').removeClass('active_over');   $('.non_active').removeClass('active_over');
    $('.non_active_2').removeClass('active_over');
    $('.non_active_3').removeClass('active_over');
});
$('#bott5').click(function(){
    $('#bc5').toggleClass('content_active_5');
    $('#bc6').removeClass('content_active_6');
    $('#bc1').removeClass('content_active');
    $('#bc2').removeClass('content_active_2');
    $('#bc3').removeClass('content_active_3');
    $('#bc4').removeClass('content_active_4');
    $('.non_active_5').toggleClass('active_over');
    $('.non_active_6').removeClass('active_over');
    $('.non_active').removeClass('active_over');
    $('.non_active_2').removeClass('active_over');
    $('.non_active_3').removeClass('active_over');     $('.non_active_4').removeClass('active_over');
});
$('#bott6').click(function(){
    $('#bc6').toggleClass('content_active_6');       $('#bc1').removeClass('content_active');
    $('#bc2').removeClass('content_active_2');
    $('#bc3').removeClass('content_active_3');
    $('#bc4').removeClass('content_active_4');
    $('#bc5').removeClass('content_active_5');
    $('.non_active_6').toggleClass('active_over');
    $('.non_active').removeClass('active_over');
    $('.non_active_2').removeClass('active_over');
    $('.non_active_3').removeClass('active_over');
    $('.non_active_4').removeClass('active_over');     $('.non_active_5').removeClass('active_over');
});






$('.slider').slick({
      dots: false,
  infinite: true,
  slidesToShow: 1,
  slidesToScroll: 0.64,
     responsive: [
    {
      breakpoint: 421,
      settings: {
       slidesToScroll: 0.74
      }
    },
           {
      breakpoint: 341,
      settings: {
       slidesToScroll: .83
      }
    }
         ]
    


});

//$('.mobile_slider').slick({
//      dots: true,
//  infinite: true,
//  slidesToShow: 1,
//  slidesToScroll: 1,
//   
    


//});
$(function () {
    var slickOpts = {
        dots: true,
        infinite: true
    };

    // Init the slick    
    $('.mobile_slider').slick(slickOpts);
});

    