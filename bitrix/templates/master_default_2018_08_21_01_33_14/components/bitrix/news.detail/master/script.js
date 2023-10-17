RS.Application().ready(function() {
  var app = RS.Application(),
      owlOptions = {
        margin: 0,
        nav: true,
        navClass: ['owl-prev', 'owl-next'],
        navText: [
            '<svg class="icon-svg icon-svg-chevron-left"><use xlink:href="#svg-chevron-left"></use></svg>',
            '<svg class="icon-svg icon-svg-chevron-right"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-chevron-right"></use></svg>'
        ],
        dots: true,
        dotsData: true,
        dotsContainer: '.gallery_slider__dots',
        items: 1,
        onInitialized: function() { 
          this.$element.addClass('owl-carousel owl-theme');

          var $gallery = this.$element.closest('.gallery_slider');
          
          $gallery.find('.gallery_slider__dots').scrollbar({
            //showArrows: true,
            scrollx: $gallery.find('.gallery_slider__scroll-x'),
            scrollStep: 350
          });
          
          this.$stage.find('.cloned > .gallery_slider__canvas').removeAttr('data-fancybox');
        },
        onChange: function () {
          this.$stage.find('.cloned > .gallery_slider__canvas').removeAttr('data-fancybox');
        },
        onTranslated: function (e) {
          
          var nav = this._plugins.navigation,
              $dots = nav._controls.$absolute;
          
          if ($dots.length > 0) {
            var scrollbar = $dots.data('scrollbar'),
                $itemActive = $dots.find('.active'),
                itemPosition = 0,
                containerWidth = scrollbar.container.outerWidth() - 17;

            $itemActive.prevAll().each(function(){ itemPosition += $(this).outerWidth() });

            if (itemPosition + $itemActive.outerWidth() >= containerWidth + scrollbar.container.scrollLeft()) {
              scrollbar.container.scrollLeft(itemPosition + $itemActive.outerWidth() - containerWidth);
            } else if (itemPosition <= scrollbar.container.scrollLeft()) {
              scrollbar.container.scrollLeft(itemPosition);
            }
            
          }
        },
      };
  
  $('.gallery_slider__photo').each(function(key, item) {
    var name = $(item).data('slider-name') || 'slider_' + Object.keys(app.sliders).length + 1;
    app.addSlider(name, item, owlOptions);
    
    app.sliders[name].init();
  });

});