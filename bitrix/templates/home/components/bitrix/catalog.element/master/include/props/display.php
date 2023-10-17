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
<div class="product-item-properties tab-item-properties">
    <?
    foreach ($arDisplayProperties as $property)
    {
        ?>
		<div class="line">
			<span class="prop-name"><?=$property['NAME']?>:</span>
			<div class="dotted-line"></div>
       		<span class="prop-value"><?=(
            	is_array($property['DISPLAY_VALUE'])
                	? implode(' / ', $property['DISPLAY_VALUE'])
                	: $property['DISPLAY_VALUE']
            	)?>
        	</span>
	</div>
        <?
    }
    unset($property);
    ?>
</div>
<?

if ($arResult['SHOW_OFFERS_PROPS'])
{
    ?>
    <dl class="product-item-properties" id="<?=$itemIds['DISPLAY_PROP_DIV']?>"></dl>
    <?
}
?>