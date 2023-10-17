<div class="col-sm-4">
    <?$APPLICATION->IncludeComponent(
        'bitrix:menu',
        'bottom',
        array(
            'ROOT_MENU_TYPE' => 'bottom_services',
            'MAX_LEVEL' => '2',
            "CHILD_MENU_TYPE" => "bottom_services_sub", 
            'USE_EXT' => 'Y',
            'DELAY' => 'N',
            'ALLOW_MULTI_SELECT' => 'N',
            'MENU_CACHE_TYPE' => 'N',
            'MENU_CACHE_TIME' => '3600',
            'MENU_CACHE_USE_GROUPS' => 'Y',
            'MENU_CACHE_GET_VARS' => ''
        )
    ); ?>
</div>
<div class="col-sm-4">
    <?$APPLICATION->IncludeComponent(
        'bitrix:menu',
        'bottom',
        array(
            'ROOT_MENU_TYPE' => 'bottom_catalog',
            'MAX_LEVEL' => '2',
            "CHILD_MENU_TYPE" => "bottom_catalog_sub", 
            'USE_EXT' => 'Y',
            'DELAY' => 'N',
            'ALLOW_MULTI_SELECT' => 'N',
            'MENU_CACHE_TYPE' => 'N',
            'MENU_CACHE_TIME' => '3600',
            'MENU_CACHE_USE_GROUPS' => 'Y',
            'MENU_CACHE_GET_VARS' => ''
        )
    ); ?>
</div>
<div class="col-sm-4">
    <?$APPLICATION->IncludeComponent(
        'bitrix:menu',
        'bottom',
        array(
            'ROOT_MENU_TYPE' => 'bottom_additional',
            'MAX_LEVEL' => '2',
            "CHILD_MENU_TYPE" => "bottom_additional_sub", 
            'USE_EXT' => 'Y',
            'DELAY' => 'N',
            'ALLOW_MULTI_SELECT' => 'N',
            'MENU_CACHE_TYPE' => 'N',
            'MENU_CACHE_TIME' => '3600',
            'MENU_CACHE_USE_GROUPS' => 'Y',
            'MENU_CACHE_GET_VARS' => ''
        )
    ); ?>
</div>