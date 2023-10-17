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
<dl class="product-item-detail-properties">
    <?
    foreach ($arDisplayProperties as $property)
    {
        ?>
        <dt><?=$property['NAME']?></dt>
        <dd><?=(
            is_array($property['DISPLAY_VALUE'])
                ? implode(' / ', $property['DISPLAY_VALUE'])
                : $property['DISPLAY_VALUE']
            )?>
        </dd>
        <?
    }
    unset($property);
    ?>
</dl>
<?