<?php \Bitrix\Main\Page\Asset::getInstance()->addJs('//vk.com/js/api/openapi.js'); ?>
<script type="text/javascript">
  VK.init({apiId: <?=$arParams['VK_API_ID']?>, onlyWidgets: true});
</script>
<div id="vk_comments" style="margin-top: 25px;"></div>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: <?=$arParams['VK_LIMIT']?>, attach: "*"});
</script>