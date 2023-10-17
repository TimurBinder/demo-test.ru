<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use \Bitrix\Main\Localization\Loc;
?>
<a class="prodcut-item-detail__cheaper cheaper-link" id="<?=$itemIds['CHEAPER_LINK_ID']?>"
    data-type="ajax" data-fancybox="cheaper"
    title="<?=Loc::getMessage('RS.MASTER.BCE_MASTER.LOWER_PRICE')?>"
    href="<?=str_replace('#ELEMENT_ID#', $actualItem['ID'], $arParams['CHEAPER_FORM_URL'])?>">
    <span class="cheaper-link__icon"></span>
    <span class="cheaper-link__text">
        <span class="anchor"><?=Loc::getMessage('RS.MASTER.BCE_MASTER.WANT_CHEAPER')?></span>&nbsp; 
        <span class="anchor"><?=Loc::getMessage('RS.MASTER.BCE_MASTER.LOWER_PRICE')?></span>
    </span>
</a>
<?