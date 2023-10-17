$(function () {
    $('body').on('change', 'body', function () {
        $(".fancybox-container").fancybox({
            touch: false,
            dragToClose: false
        });
    })
})