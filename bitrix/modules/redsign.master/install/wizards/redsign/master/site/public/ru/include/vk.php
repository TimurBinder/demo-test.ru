<!-- Put this script tag to the <head> of your page -->
<?php \Bitrix\Main\Page\Asset::getInstance()->addJs('//vk.com/js/api/openapi.js'); ??>
<script type="text/javascript">
  VK.init({apiId: <?=$arParams['VK_API_ID']?>, onlyWidgets: true});
</script>
<!-- Put this div tag to the place, where the Comments block will be -->
<div id="vk_comments"></div>
<script type="text/javascript">
  VK.Widgets.Comments("vk_comments", {limit: <?=$arParams['VK_LIMIT']?>, attach: "*"});
</script>
ASDASDAS