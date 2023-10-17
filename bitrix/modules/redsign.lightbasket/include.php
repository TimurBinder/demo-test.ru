<?php

use Bitrix\Main\Loader;

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

$pathJS = getLocalPath('js/redsign/lightbasket');

CJSCore::RegisterExt('rs_lightbasket', [
    'js' => $pathJS . '/basket.js',
]);
