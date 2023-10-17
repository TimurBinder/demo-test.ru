<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$templateData['ITEMS'] = $arResult['ITEMS'];

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0) {
    $arPictures = array_map(
        function($arItem){
            return intval($arItem['PREVIEW_PICTURE']['RESIZE']['height']);
        },
        array_filter($arResult['ITEMS'], function($arItem) {
            return (
                is_array($arItem['PREVIEW_PICTURE'])
            );
        })
    );
    
    if (count($arPictures) > 0) {
        $iPicrureHeightMax = max($arPictures);
    }
}
?>

<?php if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0):?>

<section class="carousel">

    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
        <div class="container">
    <?php endif; ?>

    <?php
    if ($arParams['USE_OWL'] == 'Y'):

        $arSliderOptions = array(
            'changespeed' => intval($arParams['OWL_CHANGE_SPEED']) < 1
                ? 2000
                : $arParams['OWL_CHANGE_SPEED'],
            'changedelay' => intval($arParams['OWL_CHANGE_DELAY']) < 1
                ? 8000
                : $arParams['OWL_CHANGE_DELAY'],
            'loop' => false,
            'nav' => '',
            'margin' => 0,
            'responsive' => array(
                0 => array(
                    'items' => intval($arParams['OWL_PHONE']) > 0 ? $arParams['OWL_PHONE'] : 1,
                ),
                768 => array(
                    'items' => intval($arParams['OWL_TABLET']) > 0 ? $arParams['OWL_TABLET'] : 1,
                ),
                991 => array(
                    'items' => intval($arParams['OWL_PC']) > 0 ? $arParams['OWL_PC'] : 1,
                ),
            ),
        );
    ?>
        <div class="carousel__list owl" data-slider="true" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arSliderOptions)?>'>
    <?php else: ?>
        <div class="carousel__list">
            <div class="row">
    <?php endif; ?>

        <?php foreach ($arResult["ITEMS"] as $arItem): ?>

            <?php
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            
            $sItemClass = 'feature';
            
            if ($arParams['USE_OWL'] != 'Y') {

               $sItemClass .= ' feature-col';
                if (strlen($arParams['COL_XS']) > 0) {
                    $sItemClass .= ' col-xs-'.$arParams['COL_XS'];
                }
                if (strlen($arParams['COL_SM']) > 0) {
                    $sItemClass .= ' col-sm-'.$arParams['COL_SM'];    
                }
                if (strlen($arParams['COL_MD']) > 0) {
                    $sItemClass .= ' col-md-'.$arParams['COL_MD'];    
                }
                if (strlen($arParams['COL_LG']) > 0) {
                    $sItemClass .= ' col-lg-'.$arParams['COL_LG'];    
                }

            } else {
                $sItemClass .= ' carousel__item';
            }
            ?>

            <div class="<?=$sItemClass?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">

                <a href="<?=$arItem['PROPERTIES'][$arParams['LINK_PROP']]['VALUE']?>"<?php if ($arItem['PROPERTIES'][$arParams['TARGET_PROP']]['VALUE'] != ''): ?> target="_blank"<?php endif; ?>>

                    <?php
					$sIconPath = SITE_TEMPLATE_PATH.'/assets/images/icons/svg/'.$arItem['PROPERTIES'][$arParams['ICON_BODYMOVIN_PROP'][$arItem['IBLOCK_ID']]]['VALUE'].'.json';

					if (
						$arItem['PROPERTIES'][$arParams['ICON_BODYMOVIN_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] != ''
						&& file_exists(Application::getDocumentRoot().$sIconPath)
					):
					?>
                        <span class="feature__icon" id="<?=$this->GetEditAreaId($arItem['ID']);?>__icon"></span>
                        <script>
                            var anim
								params = {
                                container: document.getElementById('<?=$this->GetEditAreaId($arItem['ID']);?>__icon'),
                                renderer: 'svg',
                                loop: false,
                                autoplay: true,
                                autoloadSegments: true,
                                rendererSettings: {
                                    progressiveLoad:false
                                },
                                path: '<?=$sIconPath?>'
                            };
                            anim = bodymovin.loadAnimation(params);
                        </script>
                    <?php elseif ($arItem['PROPERTIES'][$arParams['ICON_PROP'][$arItem['IBLOCK_ID']]]['VALUE'] != ''): ?>
                        <span class="feature__icon">
                            <svg class="icon-svg"><use xlink:href="#svg-<?=$arItem['PROPERTIES'][$arParams['ICON_PROP'][$arItem['IBLOCK_ID']]]['VALUE']?>"></use></svg>
                        </span>
                    <?php elseif (is_array($arItem['PREVIEW_PICTURE'])): ?>
                        <span class="feature__pic"<?php if ($iPicrureHeightMax > 0): ?> style="height:<?=$iPicrureHeightMax?>px"<?php endif; ?>>
                            <img class="feature__img" src="<?=$arItem['PREVIEW_PICTURE']['RESIZE']['src']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
                        </span>
                    <?php endif; ?>

                    <span class="feature__name"><?=$arItem['NAME']?></span>
                </a>

            </div>
        <?php endforeach; ?>

    <?php if ($arParams['USE_OWL'] == 'Y'): ?>
        </div>
    <?php else: ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <?=$arResult["NAV_STRING"]?>
    <?php endif; ?>

    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
        </div>
    <?php endif; ?>
</section>

<?php endif; ?>
