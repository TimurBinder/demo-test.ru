<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
/**
 * @var $arResult array
 * @var $arParams array
 * @var $this CBitrixComponentTemplate
 */

$fVerComposite = (defined("SM_VERSION") && version_compare(SM_VERSION, "14.5.0") >= 0 ? true : false);
if ($fVerComposite) {
    $this->setFrameMode(true);
    $frame = $this->createFrame()->begin("");
}

?>
    <style type="text/css">
        .al-dl-docs-list .al-dl-docs-item {
            background: <?=$arParams["COLOR_BG_EVEN"]?>;
            border-bottom: 1px solid<?=$arParams["COLOR_BORDER"]?>;
        }

        .al-dl-sect-list {
            border: 1px solid<?=$arParams["COLOR_BORDER"]?>;
        }

        .al-dl-docs-list .al-dl-doc-section-name {
            border-bottom: 2px solid<?=$arParams["COLOR_BORDER_TOP"]?>;
        }

        .al-dl-sect-list {
            border-top: 2px solid <?=$arParams["COLOR_BORDER_TOP"]?> !important;
        }

        .al-dl-sect-list ul li {
            border-top: 1px solid<?=$arParams["COLOR_BORDER"]?>;
        }

        .al-dl-sect-list ul li.al-dl-sect-sel > .al-dl-link-area {
            background-color: <?=$arParams["COLOR_BG_HOVER"]?> !important;
            margin-bottom: -1px;
        }

        .al-dl-sect-list ul li.al-dl-sect-sel > .al-dl-link-area.al-dl-has-child {
            border-radius: 0 0 0 12px;
        }

        .al-dl-docs-list .al-dl-docs-item:hover {
            background: <?=$arParams["COLOR_BG_HOVER"]?>;
        }

        .al-dl-docs-list .al-dl-docs-item .docs-date-time {
            color: <?=$arParams["COLOR_DATE"]?>;
        }
    </style>
    <div class="al-dl-docs-list<? if ($arParams["DISPLAY_LIST_SECTION"] && $arParams['DISPLAY_DOCSSECTION'] != "Y"): ?> al-dl-docs-list-float<? endif; ?>">
        <? if (!$arParams["DISPLAY_DOCSSECTION"] && $arParams["DISPLAY_TOP_PAGER"]): ?>
            <?= $arResult["NAV_STRING"] ?><br/>
        <? endif; ?>
        <? $i_bg = 0; ?>
        <? foreach ($arResult["ITEMS"] as $arItems):
            if ($arParams["DISPLAY_DOCSSECTION"]):?>
                <div class="al-dl-doc-section-name"><?= $arItems["NAME"] ?></div>
            <? endif; ?>
            <?
            foreach ($arItems["ELEMENTS"] as $arItem):
                $arFile = $arItem["DOCFILE"];
                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
                if (isset($arItem["PREVIEW_PICTURE"])) {
                    if ($arItem["PREVIEW_PICTURE"]["WIDTH"] > 49 || $arItem["PREVIEW_PICTURE"]["HEIGHT"] > 51) {
                        $iconResizeIMG = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 49, 'height' => 51), BX_RESIZE_IMAGE_PROPORTIONAL, false);
                        $iconPath = $iconResizeIMG["src"];
                    } else {
                        $iconPath = $arItem["PREVIEW_PICTURE"]["SRC"];
                    }
                    $iconTitle = $arItem["PREVIEW_PICTURE"]["TITLE"];
                    $iconAlt = $arItem["PREVIEW_PICTURE"]["ALT"];
                } else {
                    $iconPath = file_exists($_SERVER["DOCUMENT_ROOT"] . $this->GetFolder() . '/images/' . $arFile["FILE_TYPE"] . '.png') ? $this->GetFolder() . '/images/' . $arFile["FILE_TYPE"] . '.png' : $this->GetFolder() . '/images/icon.png';
                }
                ?>
                <div class="al-dl-docs-item<?
                if ($i_bg % 2 == 0):?> al-dl-docs-item-bg<? endif ?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
                    <div class="al-dl-doc-icon"><img src="<?= $iconPath ?>"<?
                        echo(!empty($iconTitle) ? ' title="' . $iconTitle . '"' : "") ?><?
                        echo(!empty($iconAlt) ? ' alt="' . $iconAlt . '"' : "") ?>></div>
                    <? if ($arParams["DISPLAY_SIZE"] != "N" || $arItem["DOWNLOAD_COUNT"] != "N"): ?>
                        <div class="al-dl-doc-info">
                            <div class="al-dl-load-link"><a
                                        href="?EID=<?= $arItem["ID"] ?>"><?= GetMessage("ALTASIB_DOWNLOAD_FILE") ?></a>
                            </div>
                            <div class="al-dl-info-size">
                                (<?= $arFile["FILE_TYPE"] ?><? if ($arParams["DISPLAY_SIZE"] != "N"): ?>, <?= $arFile["FILE_SIZE"] ?><? endif; ?>
                                )
                            </div>
                        </div>
                    <? endif; ?>
                    <? if (($arParams["DISPLAY_DATE"] != "N" && ($arItem["CREATE_DATE"] || $arItem["UPDATE_DATE"])) || $arParams["DOWNLOAD_COUNT"] != "N"): ?>
                        <div class="docs-date-time"><?
                            if ($arParams["DISPLAY_DATE"] != "N" && ($arItem["CREATE_DATE"] || $arItem["UPDATE_DATE"])):
                                echo $arItem["CREATE_DATE"] ? GetMessage("ALTASIB_SHOW_DATA_CREATE") : GetMessage("ALTASIB_SHOW_DATA_UPDATE");
                                echo $arItem["CREATE_DATE"] ? $arItem["CREATE_DATE"] : $arItem["UPDATE_DATE"];
                                if ($arParams["DOWNLOAD_COUNT"] != "N"):?> | <?endif;
                            endif;
                            if ($arParams["DOWNLOAD_COUNT"] != "N"):?><?= GetMessage("ALTASIB_SHOW_DATA_DOWNLOAD") ?>: <?
                                if ($arItem["SHOW_COUNTER"] == '') {
                                    echo '0';
                                } else {
                                    echo $arItem["SHOW_COUNTER"];
                                } ?><?endif;
                            ?></div>
                    <? endif ?>
                    <div class="al-dl-doc-name"><a href="?EID=<?= $arItem["ID"] ?>"><?= $arItem["NAME"] ?></a></div>
                    <? if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arItem["PREVIEW_TEXT"] != ''): ?>
                        <div class="al-dl-discribe"><?= $arItem["PREVIEW_TEXT"]; ?></div>
                    <? endif; ?>
                    <div class="al-dl-clear">&nbsp;</div>
                </div>
                <?
                $i_bg++;
            endforeach;
        endforeach; ?>
        <? if (!$arParams["DISPLAY_DOCSSECTION"] && $arParams["DISPLAY_BOTTOM_PAGER"]): ?>
            <br/><?= $arResult["NAV_STRING"] ?>
        <? endif; ?>
    </div>
<? if ($arParams["DISPLAY_LIST_SECTION"] == "Y" && $arParams["DISPLAY_DOCSSECTION"] != "Y"): ?>
    <div class="al-dl-sect-list">
    <div class="al-dl-sec-title"><?= GetMessage("ALTASIB_SECTIONS_DESC") ?></div>
    <ul>
    <? if (count($arResult["SECTIONS"]) > 1): ?>
        <li<? if (!$arParams["USER_SECTION"]): ?> class="al-dl-sect-sel"<? endif ?>><?
            ?>
            <div class="al-dl-link-area"><a href="<? echo $APPLICATION->GetCurPageParam("", array("SID")); ?>"
                                            class="parent_a"><?= GetMessage("ALTASIB_ALL_SECTIONS_DESC") ?></a></div>
        </li>
    <? endif; ?>
    <? if (!($arParams["SORT_BY2"] == 'LEFT_MARGIN' && $arParams["SORT_ORDER2"] == 'ASC')):
        foreach ($arResult["SECTIONS"] as $arSection):
            ?>
            <li<? if ($arSection['ID'] == $arParams["USER_SECTION"]): ?> class="al-dl-sect-sel"<? endif; ?>>
                <div class="al-dl-link-area"><a
                            href="<? echo $APPLICATION->GetCurPageParam("SID=" . $arSection['ID'], array("SID")); ?>"
                            class="parent_a"><?= $arSection['NAME'] ?></a></div>
            </li>
        <? endforeach;
    else:?>
        <?
        $previousLevel = 0;
        foreach ($arResult["SECTIONS"] as $key => $arSect):

            $bLevel1 = ($arSect["DEPTH_LEVEL"] == 1);
            if ($previousLevel && $arSect["DEPTH_LEVEL"] < $previousLevel): // out of the recursion
                if ($bLevel1)
                    echo str_repeat("</ul></li>", ($previousLevel - $arSect["DEPTH_LEVEL"] - 1)) . "</ul></div></li>";
                else
                    echo str_repeat("</ul></li>", ($previousLevel - $arSect["DEPTH_LEVEL"]));
            endif;

            $strClass = "";
            if ($arSect['ID'] == $arParams["USER_SECTION"])
                $strClass = " al-dl-sect-sel";

            if ($arSect["IS_PARENT"]):    // there are subsections
                $i = $key;
                $bHasSelected = $arSect['SELECTED'];
                $childSelected = false;
                if (!$bHasSelected)    // if do not select a parent, check to see if the children are selected
                {
                    while ($arResult["SECTIONS"][++$i]['DEPTH_LEVEL'] > $arSect['DEPTH_LEVEL']) {
                        if ($arResult["SECTIONS"][$i]['SELECTED']) {
                            $bHasSelected = $childSelected = true;
                            break;    // if select a child, then choose parents
                        }
                    }
                }


                if ($arSect['DEPTH_LEVEL'] > 1 && !$childSelected && $bHasSelected) {    // subsection, but no children
                    ?>
                    <li class="current lvl<?= $arSect['DEPTH_LEVEL'] ?><?= $strClass ?>"><?
                        ?>
                        <div class="open_menu open_<?= $arSect['DEPTH_LEVEL'] ?>"></div>
                        <a href="<?
                        echo $APPLICATION->GetCurPageParam("SID=" . $arSect['ID'], array("SID"));
                        ?>" class="<?
                        if ($bLevel1):?>level1 <? endif ?>parent_a"><?= $arSect["NAME"] ?></a></li></ul>
                    <ul>

                    <?
                } else {
                    $className = $bHasSelected ? 'current ' : '';
                    $className .= "lvl" . $arSect['DEPTH_LEVEL']; ?>
                    <li class="<?= $className ? $className . $strClass : $strClass ?>"><?
                    ?>
                    <div class="open_menu open_<?= $arSect['DEPTH_LEVEL'] ?>"></div>
                    <div class="al-dl-link-area al-dl-has-child"><a href="<?
                        echo $APPLICATION->GetCurPageParam("SID=" . $arSect['ID'], array("SID")); ?>" class="<?
                        if ($bLevel1):?>level1 <? endif ?>parent_a"><?= $arSect["NAME"] ?></a></div>
                    <div class="inner_menu">
                    <ul>
                <? } ?>
            <?

            else:    // no subsection
                $className = $arSect['SELECTED'] ? 'current ' : '';
                $className .= "lvl" . $arSect['DEPTH_LEVEL'];
                ?>
                <li class="<?
                echo($className ? '' . $className . $strClass : $strClass); ?>"><?
                    ?>
                    <div class="open_menu open_<?= $arSect['DEPTH_LEVEL'] ?>"></div>
                    <div class="al-dl-link-area"><a href="<?
                        echo $APPLICATION->GetCurPageParam("SID=" . $arSect['ID'], array("SID")); ?>"<?
                        if ($bLevel1):?> class="level1"<? endif ?>><?= $arSect["NAME"] ?></a></div>
                </li>
            <?
            endif;


            $previousLevel = $arSect["DEPTH_LEVEL"];
        endforeach;

        if ($previousLevel > 1): // close last item tags
            echo str_repeat("</ul></li>", ($previousLevel - 2)) . "</ul></div></li>";
        endif;
        ?>
        </ul>
    <? endif; ?>

    </div>
<?endif;
?>

<? if ($arParams["DISPLAY_LIST_SECTION"] == "Y"): ?>
    <script type="text/javascript">
        $(".open_menu").on('click', function (e) {
            var clickitem = $(this);
            var block = clickitem.parent('li').find('.inner_menu');
            if (block.css('display') == 'none') {
                block.css({display: 'block'});
                if (!block.parent('li').hasClass('dl_shown'))
                    block.parent('li').addClass("dl_shown");
            } else {
                block.css({display: 'none'});
                if (block.parent('li').hasClass('dl_shown'))
                    block.parent('li').removeClass("dl_shown");
            }
        });
    </script>
<? endif ?>

    <div class="al-dl-clear">&nbsp;</div>
<? if ($fVerComposite) $frame->end(); ?>