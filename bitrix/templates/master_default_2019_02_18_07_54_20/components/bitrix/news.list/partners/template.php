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
$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (count($arResult['ITEMS'])):
?>
<div class="l-block">

    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
        <div class="container">
    <?php endif; ?>
    
    <?php if ($arParams['SHOW_TITLE'] == 'Y'): ?>
        <h3 class="l-block__title"><a href=""><?=$arResult['NAME']?></a></h3>
    <?php endif; ?>

    <?php if ($arParams['SHOW_DESCRIPTION'] == 'Y'): ?>
        <?php if (intval($arParams['PARENT_SECTION']) > 0 && strlen($arResult['PARENT_SECTION']['DESCRIPTION']) > 0): ?>
            <div class="b-note-text"><?=$arResult['PARENT_SECTION']['DESCRIPTION']?></div>
        <?php elseif (count($arResult['DESCRIPTION']) > 0): ?>
            <div class="b-note-text"><?=$arResult['DESCRIPTION']?></div>
        <?php endif; ?>
    <?php endif; ?>
    
    <?php if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0): ?>


        <?php if ($arParams['USE_OWL'] == 'Y'): ?>
            <div class="owl owl-carousel owl-theme" data-slider="true" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arParams['OWL_PARAMS'])?>' itemscope itemtype="http://schema.org/ItemList">
        <?php else: ?>
            <div class="row" itemscope itemtype="http://schema.org/ItemList">
        <?php endif; ?>

            <?php foreach ($arResult['ITEMS'] as $arItem):?>
                <?php
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        
                $sGridClass = '';
                
                if ($arParams['USE_OWL'] != 'Y') {

                   $sGridClass .= ' col';
                    if (strlen($arParams['COL_XS']) > 0) {
                        $sGridClass .= ' col-xs-'.$arParams['COL_XS'];
                    }
                    if (strlen($arParams['COL_SM']) > 0) {
                        $sGridClass .= ' col-sm-'.$arParams['COL_SM'];    
                    }
                    if (strlen($arParams['COL_MD']) > 0) {
                        $sGridClass .= ' col-md-'.$arParams['COL_MD'];    
                    }
                    if (strlen($arParams['COL_LG']) > 0) {
                        $sGridClass .= ' col-lg-'.$arParams['COL_LG'];    
                    }

                }
                ?>
                
                <div class="<?=$sGridClass?>">

                    <div class="partner" id="<?=$this->GetEditAreaId($arItem['ID']);?>" itemprop="itemListElement">
                        <div class="partner__head">

                            <?php if ($arParams['DISPLAY_PICTURE'] != 'N' && is_array($arItem['PREVIEW_PICTURE'])): ?>
                                <div class="partner__pic">
                                    <?php if (!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || ($arItem['DETAIL_TEXT'] && $arResult['USER_HAVE_ACCESS'])):?>
                                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                                            <span class="partner__img" style="background-image: url(<?=$arItem['PREVIEW_PICTURE']['SRC']?>);" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>" itemprop="image"></span>
                                        </a>
                                    <?php else: ?>
                                        <span class="partner__img" style="background-image: url(<?=$arItem['PREVIEW_PICTURE']['SRC']?>);" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>" itemprop="image"></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        
                            <?php if ($arParams['DISPLAY_NAME'] != 'N' && $arItem['NAME']): ?>
                                <div class="partner__name" itemprop="name">
                                    <?php if (!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || ($arItem['DETAIL_TEXT'] && $arResult['USER_HAVE_ACCESS'])):?>
                                        <a href="<?echo $arItem['DETAIL_PAGE_URL']?>"><?echo $arItem['NAME']?></a>
                                    <?php else: ?>
                                        <?echo $arItem['NAME']?>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($arParams['DISPLAY_PREVIEW_TEXT'] != 'N' && $arItem['PREVIEW_TEXT']): ?>
                                <div class="partner__description" itemprop="description"><?=$arItem['PREVIEW_TEXT']?></div>
                            <?php endif; ?>
                            
                        </div>

                        <?php if (is_array($arItem['DISPLAY_PROPERTIES']) && count($arItem['DISPLAY_PROPERTIES']) > 0): ?>
                        
                            <?php
                            $arPropSkip = array();
                            if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['SITE_URL_PROP']])) {
                                $arPropSkip[] = $arParams['SITE_DOMAIN_PROP'];
                            }
                            ?>
                            <div class="partner__props">
                                <?php foreach($arItem['DISPLAY_PROPERTIES'] as $pid => $arProperty): ?>
                                    <?php
                                    if (in_array($pid, $arPropSkip))
                                        continue;
                                    ?>
                                    <div class="partner__prop">
                                        <?php
                                        if (
                                            $pid == $arParams['SITE_URL_PROP']
                                            && isset($arItem['DISPLAY_PROPERTIES'][$arParams['SITE_URL_PROP']])
                                            && strlen($arItem['DISPLAY_PROPERTIES'][$arParams['SITE_URL_PROP']]['VALUE']) > 0
                                        ):
                                        ?>
                                            <a href="<?=$arItem['DISPLAY_PROPERTIES'][$arParams['SITE_URL_PROP']]['VALUE']?>" rel="nofollow" target="_blank"><?php
                                                echo isset($arItem['DISPLAY_PROPERTIES'][$arParams['SITE_DOMAIN_PROP']]) && strlen($arItem['DISPLAY_PROPERTIES'][$arParams['SITE_DOMAIN_PROP']]['VALUE']) > 0
                                                    ? $arItem['DISPLAY_PROPERTIES'][$arParams['SITE_DOMAIN_PROP']]['VALUE']
                                                    : $arItem['DISPLAY_PROPERTIES'][$arParams['SITE_URL_PROP']]['VALUE'];
                                            ?></a>
                                        <?php
                                        else:
                                            echo $arProperty['NAME'].':&nbsp;';
                                            
                                            if (is_array($arProperty['DISPLAY_VALUE'])):
                                                echo implode('&nbsp;/&nbsp;', $arProperty['DISPLAY_VALUE']);
                                            else:
                                                echo $arProperty['DISPLAY_VALUE'];
                                            endif;
                                        endif;
                                        ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
        </div>
    <?php endif; ?>
    
</div>
<?php endif;
