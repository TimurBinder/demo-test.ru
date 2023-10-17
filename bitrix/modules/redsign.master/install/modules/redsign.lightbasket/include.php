<?php

use \Bitrix\Main\Loader;
use \Redsign\LightBasket;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

Loader::registerAutoLoadClasses('redsign.lightbasket', array(
    "\Redsign\LightBasket\Interfaces\Arrayble" => 'lib/interfaces/arrayble.php',
    "\Redsign\LightBasket\Interfaces\BasketCollectionInterface" => 'lib/interfaces/basket_collection.php',
    "\Redsign\LightBasket\Interfaces\BasketItemInterface" => 'lib/interfaces/basket_item.php',
    "\Redsign\LightBasket\Interfaces\BasketProviderInterface" => 'lib/interfaces/basket_provider.php',

    "\Redsign\LightBasket\Entity\BasketItemTable" => 'lib/entity/basket_item_table.php',
    "\Redsign\LightBasket\Entity\BasketUserTable" => 'lib/entity/basket_user_table.php',

    "\Redsign\LightBasket\BasketItem" => 'lib/basket_item.php',
    "\Redsign\LightBasket\BasketCollection" => 'lib/basket_collection.php',
    "\Redsign\LightBasket\BasketBuilder" => 'lib/basket_builder.php',
    "\Redsign\LightBasket\BasketSessionProvider" => 'lib/basket_session_provider.php',
    "\Redsign\LightBasket\BasketDatabaseProvider" => 'lib/basket_database_provider.php',
    "\Redsign\LightBasket\Basket" => 'lib/basket.php',
    "\Redsign\LightBasket\Tools" => 'lib/tools.php',
));

$basketProviderName = Option::get('redsign.lightbasket', 'basket_provider');
if ($basketProviderName == 'database') {
    $basketProvider = new LightBasket\BasketDatabaseProvider();
} else {
    $basketProvider = new LightBasket\BasketSessionProvider();
}
LightBasket\Basket::setProvider($basketProvider);


CJSCore::RegisterExt("rs_lightbasket", array(
    'js' => "/bitrix/js/redsign.lightbasket/rs.basket.js"
));