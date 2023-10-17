<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var CMain $APPLICATION
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();

?>
<div class="catalog-section-sorter">
    <?php
    if ($arParams['ALFA_CHOSE_TEMPLATES_SHOW'] == 'Y') {
        ?>
        <span><?=Loc::getMessage('MSG_TEMPLATE')?></span>
        <ul>
            <?php
            foreach ($arResult['CTEMPLATE'] as $template) {
                if ($template['USING'] == 'Y') {
                    ?>
                    <li><a href="<?=$template['URL']?>"><strong>
                        <?=($template['NAME_LANG'] != '' ? $template['NAME_LANG'] : $template['VALUE'])?>
                    </strong></a></li>
                    <?php
                } else {
                    ?>
                    <li><a href="<?=$template['URL']?>">
                        <?=($template['NAME_LANG'] != '' ? $template['NAME_LANG'] : $template['VALUE'])?>
                    </a></li>
                    <?php
                }
            }
            ?>
        </ul>
        <?php
    }

    if ($arParams['ALFA_SHORT_SORTER'] == 'Y') {
        $arrUsed = [];
        $arrUsed[] = $arResult['USING']['CSORTING']['ARRAY']['GROUP'];
        ?>
        <span><?=Loc::getMessage('MSG_SORT')?></span>
        <ul>
            <?php
            if (isset($arResult['USING']['CSORTING']) && is_array($arResult['USING']['CSORTING'])) {
                ?><li><a href="<?=$arResult['USING']['CSORTING']['ARRAY']['URL2']?>">
                    <strong><?=($arResult['USING']['CSORTING']['ARRAY']['NAME_LANG'] != '' ? $arResult['USING']['CSORTING']['ARRAY']['NAME_LANG'] : $arResult['USING']['CSORTING']['ARRAY']['VALUE'])?></strong>
                </a></li>
                <?php
            }
            foreach ($arResult['CSORTING'] as $sort) {
                if (!in_array($sort['GROUP'], $arrUsed)) {
                    $arrUsed[] = $sort['GROUP'];
                    ?>
                    <li><a href="<?=$sort['URL']?>"><?=($sort['NAME_LANG'] != '' ? $sort['NAME_LANG'] : $sort['VALUE'])?></a></li>
                    <?php
                }
            }
            ?>
        </ul>
        <?php
    } else {
        if ($arParams['ALFA_SORT_BY_SHOW'] == 'Y') {
            ?>
            <span><?=Loc::getMessage('MSG_SORT')?></span>
            <ul>
                <?php
                foreach ($arResult['CSORTING'] as $sort) {
                    if ($sort['USING'] == 'Y') {
                        ?>
                            <li><a href="<?=$sort['URL']?>"><strong>
                            <?=($sort['NAME_LANG'] != '' ? $sort['NAME_LANG'] : $sort['VALUE'])?>
                            </strong></a></li>
                            <?php
                    } else {
                        ?>
                            <li><a href="<?=$sort['URL']?>">
                            <?=($sort['NAME_LANG'] != '' ? $sort['NAME_LANG'] : $sort['VALUE'])?>
                            </a></li>
                            <?php
                    }
                }
                ?>
            </ul>
            <?php
        }
    }

    if ($arParams['ALFA_OUTPUT_OF_SHOW'] == 'Y') {
        ?>
        <span><?=Loc::getMessage('MSG_OUTPUT')?></span>
        <ul>

        <?php
        foreach ($arResult['COUTPUT'] as $output) {
            if ($output['USING'] == 'Y') {
                ?>
                <li><a href="<?=$output['URL']?>"><strong>
                    <?=($output['NAME_LANG'] != '' ? $output['NAME_LANG'] : $output['VALUE'])?>
                </strong></a></li>
                <?php
            } else {
                ?>
                <li><a href="<?=$output['URL']?>">
                    <?=($output['NAME_LANG'] != '' ? $output['NAME_LANG'] : $output['VALUE'])?>
                </a></li>
                <?php
            }
        }
        ?>
        </ul>
        <?php
    }
    ?>
</div>
<?php
