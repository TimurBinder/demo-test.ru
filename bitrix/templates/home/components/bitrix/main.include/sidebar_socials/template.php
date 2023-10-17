<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);
?>
<div class="l-side-item">
    <?php if (!empty($arParams['BLOCK_TITLE'])): ?>
    <h3 class="l-side-item__title"><?=$arParams['BLOCK_TITLE']?></h3>
    <?php endif; ?>
    <div class="l-side-item__content">
      <?php if (!empty($arParams['RS_VK_GROUP_ID'])): ?>
      <div class="l-side-item__block">
          <script src="https://vk.com/js/api/openapi.js?139" type="text/javascript"></script>
          <div id="vk_widget"></div>

          <script type="text/javascript">
              function vkWidgetInit() {
                  document.getElementById('vk_widget').innerHTML = '<div id="vk_groups"></div>';
                  VK.Widgets.Group("vk_groups", {mode: 3, no_cover: 1, width: "auto", color1: 'ffffff', color2: '585f69'}, <?=$arParams['RS_VK_GROUP_ID']?>);
              }

              if (window.addEventListener) {
                  window.addEventListener('load', vkWidgetInit, false);
                  window.addEventListener('resize', vkWidgetInit, false);
              }
          </script>
      </div>
      <?php endif; ?>

      <?php if (!empty($arParams['RS_FB_PAGE'])): ?>
      <div class="l-side-item__block">
          <script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/ru_RU/sdk.js#xfbml=1&version=v2.9";
          fjs.parentNode.insertBefore(js, fjs);
          }(document, 'script', 'facebook-jssdk'));</script>
          <div class="fb-page" data-href="<?=$arParams['RS_FB_PAGE']?>" data-height="230" data-width="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="true"><blockquote cite="https://www.facebook.com/redsignRU/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/redsignRU/">Facebook</a></blockquote></div>
      </div>
      <?php endif; ?>
    </div>
</div>
