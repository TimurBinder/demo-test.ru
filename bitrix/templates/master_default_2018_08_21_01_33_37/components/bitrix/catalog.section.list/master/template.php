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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LINE' => array(
		'CONT' => 'catalog_sections catalog_sections-line',
		'TITLE' => 'catalog_sections__title h3',
		'DESCRIPTION' => 'catalog_sections__descr',
		'LIST' => 'catalog_sections__list-line row',
		'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	),
	'BANNER' => array(
		'CONT' => 'catalog_sections',
		'TITLE' => 'catalog_sections__title h3',
        'DESCRIPTION' => 'catalog_sections__descr',
		'LIST' => 'catalog_sections__list-banners row',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	),
	'TILE' => array(
		'CONT' => 'catalog_sections',
		'TITLE' => 'catalog_sections__title h3',
        'DESCRIPTION' => 'catalog_sections__descr',
		'LIST' => 'catalog_sections__list row',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	),
	'THUMB' => array(
		'CONT' => 'catalog_sections',
		'TITLE' => 'catalog_sections__title h3',
        'DESCRIPTION' => 'catalog_sections__descr',
		'LIST' => 'catalog_sections__list-banners row',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	),
);
$arCurView = $arViewStyles[$arParams['SECTIONS_VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

$strSectionPageUrl = '';

if (isset($arParams['PARENT_URL'])) {
    $strSectionPageUrl = $arParams['PARENT_URL'];
} elseif (isset($arResult['SECTION']['SECTION_PAGE_URL'])) {
    $strSectionPageUrl = $arResult['SECTION']['SECTION_PAGE_URL'];
}


?><section class="<?=$arCurView['CONT']?>">

    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
        <div class="container">
    <?php endif; ?>

<?php
if ('Y' == $arParams['SHOW_PARENT_NAME'])
{
	$this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
	$this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

?>
    <h2
		class="<?=$arCurView['TITLE']?>"
		id="<?=$this->GetEditAreaId($arResult['SECTION']['ID'])?>"
	><a href="<?=$strSectionPageUrl?>"><?
		echo (
			isset($arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) && $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
			? $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
			: $arResult['SECTION']['NAME']
		);
	?></a></h2>

    <?php if (strlen($arResult['SECTION']['DESCRIPTION']) > 0): ?>
        <div class="<?=$arCurView['DESCRIPTION']?> row">
            <div class="col-md-8"><?=$arResult['SECTION']['DESCRIPTION']?></div>
        </div>
    <?php endif; ?>

<?php
}
if (0 < $arResult["SECTIONS_COUNT"])
{
?>
<ul class="<?=$arCurView['LIST']?>">
<?
	switch ($arParams['SECTIONS_VIEW_MODE'])
	{
		case 'LINE':
			$intCurrentDepth = 1;
			$boolFirst = true;
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL'])
				{
					if (0 < $intCurrentDepth) {
                        if ($arSection['RELATIVE_DEPTH_LEVEL'] == 2) {
                            echo '<ul class="catalog_line__sub">';
                        } else {
                            echo '<ul>';
                        }
                    }
				}
				elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL'])
				{
					if (!$boolFirst)
						echo '</li>';
				}
				else
				{
					while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL'])
					{
						echo '</li></ul>';
						$intCurrentDepth--;
					}
                    if ($arSection['RELATIVE_DEPTH_LEVEL'] == 1) {
                        echo '</span></span>'; // .catalog_line .catalog_line__body
                    }
					echo '</li>';
				}
				?>
                <?php if ($arSection['RELATIVE_DEPTH_LEVEL'] == 1): ?>
                    <?php
                    $sItemClass = 'col';
                    
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
                    ?>
                    <li class="<?=$sItemClass?>" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
                        <?php
                        if (false === $arSection['PICTURE'])
                            $arSection['PICTURE'] = array(
                                'SRC' => $arCurView['EMPTY_IMG'],
                                'ALT' => (
                                    '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                                    ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
                                    : $arSection["NAME"]
                                ),
                                'TITLE' => (
                                    '' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                                    ? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
                                    : $arSection["NAME"]
                                )
                            );
                        ?>
                        <span class="catalog_line">
                            <span class="row">
                                <span class="col-xs-12 col-md-4">
                                    <span class="catalog_line__pic">
                                        <a href="<?=$arSection['SECTION_PAGE_URL']?>">
                                            <img
                                                class="catalog_line__img"
                                                src="<?=$arSection['PICTURE']['SRC']?>"
                                                alt="<?$arSection['PICTURE']['ALT']?>"
                                                title="<?$arSection['PICTURE']['TITLE']?>"
                                            >
                                        </a>
                                    </span>
                                </span>
                                <span class="col-xs-12 col-md-8">
                                    <span class="catalog_line__body">
                                        <h2 class="catalog_line__title" title="<?=$arSection["NAME"]?>">
                                            <a href="<?=$arSection["SECTION_PAGE_URL"]?>">
                                                <?php
                                                echo $arSection["NAME"];
                                                if ($arParams["COUNT_ELEMENTS"]) {
                                                    ?> <span>(<?=$arSection["ELEMENT_CNT"]?>)</span><?
                                                }
                                                ?>
                                            </a>
                                        </h2>
                                        <?php if ('' != $arSection['DESCRIPTION']): ?>
                                            <p class="catalog_line__descr"><?=$arSection['DESCRIPTION']?></p>
                                        <?php endif ?>

                <?php else: ?>
                    <li id="<?=$this->GetEditAreaId($arSection['ID']);?>">
                        <a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"];?><?
                        if ($arParams["COUNT_ELEMENTS"]) {
                            ?> <span>(<?=$arSection["ELEMENT_CNT"]?>)</span><?
                        }
                        ?></a>
                <?php endif; ?>

                    <?php

				$intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
				$boolFirst = false;
			}
			unset($arSection);
			while ($intCurrentDepth > 1)
			{
				echo '</li></ul>';
				$intCurrentDepth--;
			}
			if ($intCurrentDepth > 0)
			{
				echo '</span></span></span></span>'; // .catalog_line, .row, .col-xs-12, .catalog_line__body
				echo '</li>';
			}
			break;

		case 'BANNER':
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
                if (false === $arSection['PICTURE'])
                    continue;

				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

                if (
                    is_array($arSection[$arParams['BANNER_TYPE'][$arSection['IBLOCK_ID']]])
                    && $arSection[$arParams['BANNER_TYPE'][$arSection['IBLOCK_ID']]]['XML_ID'] == 'wide'
                ) {
                    $sGridClass = 'mini_banner col-xs-6 col-md-3 col-lg-2-5';
                } else {
                    $sGridClass = 'mini_banner col-xs-6 col-md-3 col-lg-1-5';
                }

				?><li class=" <?=$sGridClass?>" id="<?=$this->GetEditAreaId($arSection['ID'])?>">
                    <span class="mini_banner__inner">
                        <span class="mini_banner__imgdiv" style="background-image:url('<?=$arSection['PICTURE']['SRC']?>')" title="<?=$arSection['PICTURE']['TITLE']?>">
                            <?php if ('' != $arSection['DESCRIPTION']): ?>
                                <span class="mini_banner__body">
                                    <span class="mini_banner__descr"><?=$arSection['DESCRIPTION']?></span>
                                    <span class="mini_banner__more">
                                        <a href="<?=$arSection['SECTION_PAGE_URL']?>"><?=Loc::getMessage('RS.MASTER.BCSL_MASTER.MORE')?></a>
                                    </span>
                                </span>
                            <?php endif ?>
                        </span>
                        <?/*
                        <span class="mini_banner__imgdiv">
                            <img class="mini_banner__img" src="<?=$arSection['PICTURE']['SRC']?>" alt="<?=$arSection['PICTURE']['ALT']?>" title="<?=$arSection['PICTURE']['TITLE']?>">

                            <?php if ('' != $arSection['DESCRIPTION']): ?>
                                <span class="mini_banner__body">
                                    <span class="mini_banner__descr"><?=$arSection['DESCRIPTION']?></span>
                                    <span class="mini_banner__more">
                                        <a href="<?=$arSection['SECTION_PAGE_URL']?>"><?=Loc::getMessage('RS.MASTER.BCSL_MASTER.MORE')?></a>
                                    </span>
                                </span>
                            <?php endif ?>
                        </span>
                        */?>
                        <a class="mini_banner__head" href="<?=$arSection['SECTION_PAGE_URL']?>">
                            <h2 class="mini_banner__title">
                                <?php
                                echo $arSection["NAME"];
                                if ($arParams["COUNT_ELEMENTS"]) {
                                    ?> <span>(<?=$arSection["ELEMENT_CNT"]?>)</span><?
                                }
                                ?>
                            </h2>
                        </a>
                    </span>
				</li><?
			}
			unset($arSection);
			break;

		case 'THUMB':

			foreach ($arResult['SECTIONS'] as &$arSection)
			{
                if (false === $arSection['PICTURE'])
                    continue;

				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

                if (
                    is_array($arSection[$arParams['BANNER_TYPE'][$arSection['IBLOCK_ID']]])
                    && $arSection[$arParams['BANNER_TYPE'][$arSection['IBLOCK_ID']]]['XML_ID'] == 'wide'
                ) {
                    $sGridClass = 'mini_banner mini_banner-thumb col-xs-12 col-sm-6 col-md-3 col-lg-2-5';
                } else {
                    $sGridClass = 'mini_banner mini_banner-thumb col-xs-12 col-sm-6 col-md-3 col-lg-1-5';
                }

				?><li class=" <?=$sGridClass?>" id="<?=$this->GetEditAreaId($arSection['ID'])?>">
                    <a class="mini_banner__inner" href="<?=$arSection['SECTION_PAGE_URL']?>">
                        <span class="mini_banner__imgdiv" style="background-image:url('<?=$arSection['PICTURE']['SRC']?>')" title="<?=$arSection['PICTURE']['TITLE']?>"></span>
<?/*
                        
                        <span class="mini_banner__imgdiv">
                            <img class="mini_banner__img" src="<?=$arSection['PICTURE']['SRC']?>" alt="<?=$arSection['PICTURE']['ALT']?>" title="<?=$arSection['PICTURE']['TITLE']?>">
                        </span>
*/?>
                        <span class="mini_banner__head">
                            <h2 class="mini_banner__title">
                                <?php
                                echo $arSection["NAME"];
                                if ($arParams["COUNT_ELEMENTS"]) {
                                    ?> <span>(<?=$arSection["ELEMENT_CNT"]?>)</span><?
                                }
                                ?>
                            </h2>
                        </span>

                    </a>
                    <?php if ('' != $arSection['DESCRIPTION']): ?>
                        <span class="mini_banner__body">
                            <span class="mini_banner__descr"><?=$arSection['DESCRIPTION']?></span>
                            <span class="mini_banner__more">
                                <a href="<?=$arSection['SECTION_PAGE_URL']?>"><?=Loc::getMessage('RS.MASTER.BCSL_MASTER.MORE')?></a>
                            </span>
                        </span>
                    <?php endif ?>
				</li><?
			}
			unset($arSection);
			break;

/*
		case 'TEXT':
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				?><li id="<?=$this->GetEditAreaId($arSection['ID'])?>"><h2 class="bx_catalog_text_title"><a href="<?=$arSection['SECTION_PAGE_URL']?>"><?=$arSection['NAME']?></a><?
				if ($arParams["COUNT_ELEMENTS"])
				{
					?> <span>(<?=$arSection['ELEMENT_CNT']?>)</span><?
				}
				?></h2></li><?
			}
			unset($arSection);
			break;
*/
		case 'TILE':

            switch ($arParams['LINE_ELEMENT_COUNT']) {
                case '6':
                    $sGridClass = 'col-xs-12 col-sm-6 col-md-4 col-lg-2';
                    break;
                case '4':
                    $sGridClass = 'col-xs-12 col-sm-6 col-md-4 col-lg-3';
                    break;
                case '3':
                default:
                    $sGridClass = 'col-xs-12 col-sm-6 col-md-4';
                    break;
            }

			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if (false === $arSection['PICTURE'])
					$arSection['PICTURE'] = array(
						'SRC' => $arCurView['EMPTY_IMG'],
						'ALT' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							: $arSection["NAME"]
						),
						'TITLE' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							: $arSection["NAME"]
						)
					);

				?><li class="<?=$sGridClass?>" id="<?=$this->GetEditAreaId($arSection['ID'])?>">
                    <span class="tile">
                        <span class="tile__pic" style="background-image:url('<?=$arSection['PICTURE']['SRC']?>')" title="<?=$arSection["PICTURE"]["TITLE"]?>"></span>
<?/*
                        <span class="tile__pic">
                            <img
                                class="tile__img"
                                src="<?=$arSection["PICTURE"]["SRC"]?>"
                                alt="<?=$arSection["PICTURE"]["ALT"]?>"
                                title="<?=$arSection["PICTURE"]["TITLE"]?>"
                            />
                        </span>
*/?>
                        <span class="tile__body">

                            <?php if ('' != $arSection['DESCRIPTION']): ?>
                                <span class="tile__descr"><?=$arSection['DESCRIPTION']?></span>
                            <?php endif ?>

                            <?php if ('Y' != $arParams['HIDE_SECTION_NAME']): ?>
                                <h2 class="tile__name"><a class="btn btn-primary" href="<?=$arSection['SECTION_PAGE_URL']?>"><?=$arSection['NAME'];
                                if ($arParams["COUNT_ELEMENTS"])
                                {
                                    ?> <span>(<?=$arSection['ELEMENT_CNT']?>)</span><?
                                }
                                ?>
                                </a></h2>
                            <?php endif; ?>

                        </span>
                    </span>
                </li><?
			}
			unset($arSection);

			break;
/*
		case 'LIST':
			$intCurrentDepth = 1;
			$boolFirst = true;
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL'])
				{
					if (0 < $intCurrentDepth)
						echo "\n",str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']),'<ul>';
				}
				elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL'])
				{
					if (!$boolFirst)
						echo '</li>';
				}
				else
				{
					while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL'])
					{
						echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
						$intCurrentDepth--;
					}
					echo str_repeat("\t", $intCurrentDepth-1),'</li>';
				}

				echo (!$boolFirst ? "\n" : ''),str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']);
				?><li id="<?=$this->GetEditAreaId($arSection['ID']);?>"><h2 class="bx_sitemap_li_title"><a href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"];?><?
				if ($arParams["COUNT_ELEMENTS"])
				{
					?> <span>(<?=$arSection["ELEMENT_CNT"]?>)</span><?
				}
				?></a></h2><?

				$intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
				$boolFirst = false;
			}
			unset($arSection);
			while ($intCurrentDepth > 1)
			{
				echo '</li>',"\n",str_repeat("\t", $intCurrentDepth),'</ul>',"\n",str_repeat("\t", $intCurrentDepth-1);
				$intCurrentDepth--;
			}
			if ($intCurrentDepth > 0)
			{
				echo '</li>',"\n";
			}
			break;
*/
	}
?>
</ul>
<?
}
?>

    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
        </div>
    <?php endif; ?>

</section>

<?php
if (strlen($arResult['SECTION']['DESCRIPTION']) > 0) {
    
	$this->SetViewTarget('catalog_description');
		echo $arResult['SECTION']['DESCRIPTION'];
	$this->EndViewTarget();
}
