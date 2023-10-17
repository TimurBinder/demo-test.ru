<div class="b-map-contacts__content">
    <?$APPLICATION->IncludeComponent(
          "bitrix:main.include",
          "",
          array(
              "AREA_FILE_SHOW" => "file",
              "PATH" => "#SITE_DIR#include/index/map_contacts.php",
              "EDIT_TEMPLATE" => ""
          ),
        false
    );?>
</div>
<a href="#SITE_DIR#contacts/" class="b-map-contacts__link">Контакты</a>