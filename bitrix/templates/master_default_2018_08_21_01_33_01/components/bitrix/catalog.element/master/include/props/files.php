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
<div class="row">
    <?php foreach ($arResult['PROPERTIES'][$sPropCode]['VALUE'] as $arDoc): ?>
        <div class="col-xs-12 col-sm-6 col-md-4 ">
            <div class="doc">
                <div class="doc__left">
                    <div class="doc__icon icon-<?=$arDoc['FILE_ICON']?>">
                        <svg class="icon-svg"><use xlink:href="#svg-folder-max"></use></svg>
                        <div class="doc__type"><span><?=$arDoc['FILE_EXT']?></span></div>
                    </div>
                </div>
                <div class="doc__body">
                    <a class="doc__name" href="<?=$arDoc['FULL_PATH']?>" target="_blank" rel="nofollow">
                        <?=($arDoc['DESCRIPTION'] == '' ? $arDoc['ORIGINAL_NAME'] : $arDoc['DESCRIPTION'])?>
                    </a>
                    <div class="doc__size">
                        <?=Loc::getMessage('RS.MASTER.BCE_MASTER.DOWNLOAD_FILE')?>:
                        <?if ($arDoc['FILE_EXT'] != ''){ echo strtoupper($arDoc['FILE_EXT']).', '; } echo $arDoc['SIZE']?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
