<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

if (count($arResult['ITEMS'])):
?>
<section class="l-files-list">
    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
    <div class="container">
    <?php endif; ?>

    <?php if (!empty($arParams['RS_TITLE'])):?>
        <h2 class="l-files-list__title"><?=$arParams['RS_TITLE']?></h2>
    <?php endif; ?>
    <div class="row l-files-list__row">
    <?php foreach ($arResult['ITEMS'] as $arItem): ?>
        <?php
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="col-xs-<?=$arParams['COL_XS']?> col-sm-<?=$arParams['COL_SM']?> col-md-<?=$arParams['COL_MD']?> col-lg-<?=$arParams['COL_LG']?>">
            <div class="b-file"  id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                <?php if (isset($arParams['USE_GALLERY']) && $arParams['USE_GALLERY'] == 'Y'):
                    $detailImgSrc = '';
                    if (!empty($arItem['DETAIL_PICTURE'])) {
                        $detailImgSrc = $arItem['DETAIL_PICTURE']['SRC'];
                    } elseif (!empty($arItem['PREVIEW_PICTURE'])) {
                        $detailImgSrc = $arItem['PREVIEW_PICTURE']['SRC'];
                    }
                ?>
                <a class="b-file__image" href="<?=$detailImgSrc?>" data-fancybox="gallery_<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <?php if (!empty($arItem['PREVIEW_PICTURE'])): ?>
                    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
                    <?php endif; ?>
                </a>
                <?php else: ?>
                <a class="b-file__image" href="<?=$arItem['FILE_SRC']?>">
                    <?php if (!empty($arItem['PREVIEW_PICTURE'])): ?>
                    <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
                    <?php endif; ?>
                </a>
                <?php endif; ?>
                <a class="b-file__name" href="<?=$arItem['FILE_SRC']?>"><?=$arItem['NAME']?></a>
                <?php if ($arItem['HAS_FILE']): ?>
                <a class="b-file__ext" href="<?=$arItem['FILE_SRC']?>">
                    <?=Loc::getMessage('RS.FILE_DOWNLOAD');?>:
                    <?=isset($arItem['FILE_EXTENSION']) ? $arItem['FILE_EXTENSION'] : ''?>
                      <?=isset($arItem['FILE_SIZE']) ? $arItem['FILE_SIZE'] : ''?>
                </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
    </div>
    <?php endif; ?>
</section>
<?php
endif;
