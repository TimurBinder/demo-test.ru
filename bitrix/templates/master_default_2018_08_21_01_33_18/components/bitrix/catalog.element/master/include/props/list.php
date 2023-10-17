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

<div class="props_group">
    <div class="props_group__name"><?=$arrValue['GROUP']['NAME']?></div>
    <table class="props_group__props">
        <tbody>
        <?php foreach ($arResult['PROPERTIES'][$sPropCode]['VALUE'] as $iPropKey => $sProp): ?>
            <tr>
                <th><?=$arResult['PROPERTIES'][$sPropCode]["DESCRIPTION"][$iPropKey]?></th>
                <td><span><?=$sProp?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
