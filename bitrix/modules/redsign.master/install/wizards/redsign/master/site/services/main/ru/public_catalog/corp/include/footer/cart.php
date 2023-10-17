<?$APPLICATION->IncludeComponent(
    "redsign:lightbasket.basket",
    "flying",
    array(
        "COMPONENT_TEMPLATE" => "master",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "#CATALOG_CATALOG_IBLOCK_ID#",
        "PROPS" => array(
            0 => "CML2_ARTICLE",
            1 => "",
        ),
        "PATH_TO_ORDER" => "#SITE_DIR#cart/order/",
        "AJAX_MODE" => "N",
        "PATH_TO_CART" => "#SITE_DIR#cart/",
        "PATH_TO_CATALOG" => "#SITE_DIR#catalog/",
        "COMPOSITE_FRAME_MODE" => "A",
        "COMPOSITE_FRAME_TYPE" => "AUTO"
    ),
    false
);?>