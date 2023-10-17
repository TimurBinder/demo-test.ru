<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */
 
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

if (Loader::includeModule('redsign.devfunc'))
{
	Redsign\DevFunc\Sale\Location\Region::editCatalogStores($arResult);
}

$arResult['JS']['CLASSES'] = array(
    'NOT_MUCH_GOOD' => 'is-limited',
    'ABSENT' => 'is-outofstock',
    'LOT_OF_GOOD' => 'is-instock',
);

$arResult['JS']['ICONS'] = array(
    'NOT_MUCH_GOOD' => '<svg class="icon-svg"><use xlink:href="#svg-available"></use></svg>',
    'ABSENT' => '<svg class="icon-svg"><use xlink:href="#svg-not-available"></use></svg>',
    'LOT_OF_GOOD' => '<svg class="icon-svg"><use xlink:href="#svg-available"></use></svg>',
);

$arResult['JS']['MESSAGES']['NOT_MUCH_GOOD'] = strlen(Loc::getMessage('RS.MASTER.BCSA_DETAIL.NOT_MUCH_GOOD')) > 0
	? Loc::getMessage('RS.MASTER.BCSA_DETAIL.NOT_MUCH_GOOD')
	: $arResult['JS']['MESSAGES']['NOT_MUCH_GOOD'];
$arResult['JS']['MESSAGES']['ABSENT'] = strlen(Loc::getMessage('RS.MASTER.BCSA_DETAIL.ABSENT')) > 0
	? Loc::getMessage('RS.MASTER.BCSA_DETAIL.ABSENT')
	: $arResult['JS']['MESSAGES']['ABSENT'];
$arResult['JS']['MESSAGES']['LOT_OF_GOOD'] = strlen(Loc::getMessage('RS.MASTER.BCSA_DETAIL.LOT_OF_GOOD')) > 0
	? Loc::getMessage('RS.MASTER.BCSA_DETAIL.LOT_OF_GOOD')
	: $arResult['JS']['MESSAGES']['LOT_OF_GOOD'];
