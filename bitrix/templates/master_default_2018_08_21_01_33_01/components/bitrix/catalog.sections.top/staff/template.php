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
?>

<?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
    <div class="container">
<?php endif; ?>

    <?php if ($arParams['SHOW_DESCRIPTION'] == 'Y' && strlen($arResult['SECTION']['DESCRIPTION']) > 0): ?>
        <div class="b-note-text"><?=$arResult['SECTION']['DESCRIPTION']?></div>
    <?php endif; ?>
    

<?php foreach ($arResult['SECTIONS'] as $arSection): ?>
<div class="l-staff">

    <h3 class="l-staff__title"><a href="<?=$arSection['SECTION_PAGE_URL']?>"><?=$arSection['NAME']?></a></h3>

    <?php if ($arParams['SHOW_DESCRIPTION'] == 'Y' && strlen($arSection['DESCRIPTION']) > 0): ?>
        <div class="b-note-text"><?=$arSection['DESCRIPTION']?></div>
    <?php endif; ?>

    <?php if ($arParams['USE_OWL'] == 'Y'): ?>
        <div class="owl owl-carousel owl-theme" data-slider="true" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arParams['OWL_PARAMS'])?>' itemscope itemtype="http://schema.org/ItemList">
    <?php else: ?>
        <div class="row" itemscope itemtype="http://schema.org/ItemList">
    <?php endif; ?>

        <?php foreach ($arSection['ITEMS'] as $arElement): ?>
            <?
            $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCST_ELEMENT_DELETE_CONFIRM')));

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
                <div class="b-employee" id="<?=$this->GetEditAreaId($arElement['ID'])?>" itemprop="itemListElement">

                    <?php
                    $arPicture = array();
                    if ($arElement['PREVIEW_PICTURE']) {
                        $arPicture = $arElement['PREVIEW_PICTURE'];
                    } elseif (is_array($arElement['DETAIL_PICTURE'])) {
                        $arPicture = $arElement['DETAIL_PICTURE'];
                    } else {
                        $arPicture = array(
                            'SRC' => $templateFolder.'/images/no_photo.png'
                        );
                    }
                    // $arPicture = is_array($arElement['PREVIEW_PICTURE'])
                        // ? $arElement['PREVIEW_PICTURE']
                        // : is_array($arElement['DETAIL_PICTURE'])
                            // ? $arElement['DETAIL_PICTURE']
                            // : array(
                                // 'SRC' => $templateFolder.'/images/no_photo.png'
                            // );
                    ?>

                    <div class="b-employee__picture">
                        <?php if ($arParams['DISPLAY_PICTURE'] != 'N' && is_array($arPicture)): ?>
                            <?php if (!$arParams['HIDE_LINK_WHEN_NO_DETAIL'] || $arElement['DETAIL_TEXT']): ?>
                                <img src="<?=$arPicture['SRC']?>" alt="<?=$arPicture['ALT']?>" title="<?=$arPicture['TITLE']?>">
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>


                    <div class="b-employee__name">
                        <?php if (isset($arElement['DISPLAY_PROPERTIES'][$arParams['PROP_NAME']])): ?>
                        <?=$arElement['DISPLAY_PROPERTIES'][$arParams['PROP_NAME']]['DISPLAY_VALUE']?>
                        <?php endif; ?>
                    </div>

                    <div class="b-employee__position">
                        <?php if (isset($arElement['DISPLAY_PROPERTIES'][$arParams['PROP_POSITION']])): ?>
                        <?=$arElement['DISPLAY_PROPERTIES'][$arParams['PROP_POSITION']]['DISPLAY_VALUE']?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="b-employee__description">
                        <?php if (isset($arElement['DISPLAY_PROPERTIES'][$arParams['PROP_DESCRIPTION']])): ?>
                        <?=$arElement['DISPLAY_PROPERTIES'][$arParams['PROP_DESCRIPTION']]['DISPLAY_VALUE']?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="b-employee__contacts">
                      <?php if (isset($arElement['DISPLAY_PROPERTIES'][$arParams['PROP_CONTACTS']])): ?>
                      <?=$arElement['DISPLAY_PROPERTIES'][$arParams['PROP_CONTACTS']]['DISPLAY_VALUE']?>
                      <?php endif; ?>
                    </div>
                    
                    <div class="b-employee__social">
                    <?php if (isset($arElement['DISPLAY_PROPERTIES'][$arParams['PROP_SOCIAL']])): ?>
                        <?php foreach ($arElement['DISPLAY_PROPERTIES'][$arParams['PROP_SOCIAL']]['VALUE'] as $index => $sLink): ?>
                          <a class="b-employee-social-icon" href="<?=$sLink?>">
                              <svg class="icon-svg"><use xlink:href="#svg-<?=$arElement['DISPLAY_PROPERTIES'][$arParams['PROP_SOCIAL']]['DESCRIPTION'][$index]?>"></use></svg>
                          </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </div>

                    <div class="b-employee__quest">
                        <?php if (isset($arElement['DISPLAY_PROPERTIES'][$arParams['PROP_IS_ASK']]) && $arElement['DISPLAY_PROPERTIES'][$arParams['PROP_IS_ASK']]['DISPLAY_VALUE'] == 'Y'): ?>
                        <a href="<?=str_replace('#ELEMENT_ID#', $arElement['ID'], $arParams['ASK_LINK'])?>" data-type="ajax"><?=Loc::getMessage('RS.ASK_QUESTION');?></a>
                        <?php endif; ?>
                    </div>

                    <?php /*if (is_array($arElement['DISPLAY_PROPERTIES']) && count($arElement['DISPLAY_PROPERTIES']) > 0): ?>
                    
                        <?php
                        $arPropSkip = array();
                        if (isset($arElement['DISPLAY_PROPERTIES'][$arParams['SITE_URL_PROP']])) {
                            $arPropSkip[] = $arParams['SITE_DOMAIN_PROP'];
                        }
                        ?>

                        <div class="partner__props">
                            <?php foreach($arElement["DISPLAY_PROPERTIES"] as $pid => $arProperty): ?>
                                <?php
                                if (in_array($pid, $arPropSkip))
                                    continue;
                                ?>
                                <div class="partner__prop">
                                    
                                    <?php
                                    if (
                                        $pid == $arParams['SITE_URL_PROP']
                                        && isset($arElement['DISPLAY_PROPERTIES'][$arParams['SITE_URL_PROP']])
                                        && strlen($arElement['DISPLAY_PROPERTIES'][$arParams['SITE_URL_PROP']]['VALUE']) > 0
                                    ):
                                    ?>
                                        <a href="<?=$arElement['DISPLAY_PROPERTIES'][$arParams['SITE_URL_PROP']]['VALUE']?>" rel="nofollow" target="_blank"><?php
                                            echo isset($arElement['DISPLAY_PROPERTIES'][$arParams['SITE_DOMAIN_PROP']]) && strlen($arElement['DISPLAY_PROPERTIES'][$arParams['SITE_DOMAIN_PROP']]['VALUE']) > 0
                                                ? $arElement['DISPLAY_PROPERTIES'][$arParams['SITE_DOMAIN_PROP']]['VALUE']
                                                : $arElement['DISPLAY_PROPERTIES'][$arParams['SITE_URL_PROP']]['VALUE'];
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
                            <?php endforeach ?>
                        </div>
                    <?php endif; */?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<?php endforeach ?>

<?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
    </div>
<?php endif; ?>