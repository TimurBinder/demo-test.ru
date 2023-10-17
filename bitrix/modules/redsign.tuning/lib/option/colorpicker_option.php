<?php

namespace Redsign\Tuning;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ColorPickerOption extends TuningOption
{
    protected string $name = 'colorpicker';
    protected string $description = 'Color picker';

    public function showOption(array $options = []): void
    {
        if (!is_array($options['VALUES']) || empty($options['VALUES']))
            return;

        $jsParams = [
            'containerId' => 'rstuning__option__colorpicker_' . $options['CONTROL_ID'],
        ];
        ?>
<div
    id="rstuning__option__colorpicker_<?=$options['CONTROL_ID']?>"
    class="rstuning__option rstuning-col-12 <?=$options['CSS_CLASS']?> js-rs_option_info"
    data-reload="<?=($options['RELOAD'] == 'Y' ? 'Y' : 'N')?>"
>
    <div class="rstuning__option__colorpicker">

        <!-- row --><div class="rstuning-row"><div class="rstuning-col-12">

            <?php
            if (is_array($options['SETS']) && !empty($options['SETS'])) {
                $arTmpValue = [];

                foreach ($options['VALUES'] as $valKey => $arValue) {
                    $arTmpValue[$valKey] = $options['VALUE'][$valKey] != ''
                        ? $options['VALUE'][$valKey]
                        : $arValue['HTML_VALUE'];
                }
                $arTmpValue = \Bitrix\Main\Web\Json::encode($arTmpValue);

                if ($options['SETS']['NAME']) {
                    ?><div class="rstuning__option-opname"><?=$options['SETS']['NAME']?></div><?php
                }
                ?>
                <div class="rstuning__option__colorpicker__sets clearfix">
                    <?php
                    foreach ($options['SETS']['VALUES'] as $arSetValue) {
                        if (!is_array($arSetValue['VALUES']) || empty($arSetValue['VALUES']))
                            continue;

                        $dataValue = \Bitrix\Main\Web\Json::encode($arSetValue['VALUES']);

                        $setItemClass = 'rstuning__option__colorpicker__alone-color mod-sets';
                        if ($arTmpValue == $dataValue)
                            $setItemClass .= ' active';
                        ?>
                        <div
                            class="<?=$setItemClass?>"
                            data-entity="set-item"
                            data-value="<?=htmlspecialcharsbx($dataValue)?>"
                        >
                            <div class="rstuning__option__colorpicker__alone-color__a mod-sets">
                                <div class="rstuning__option__colorpicker__before-paint">
                                    <div
                                        class="rstuning__option__colorpicker__incircle"
                                        style="background:<?=$arSetValue['BACKGROUND']?>"
                                    ></div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }

            if ($options['NAME']) {
                ?><div class="rstuning__option-opname"><?=$options['NAME']?></div><?php
            }

            $count = 0;
            ?>
            <div class="rstuning__option__colorpicker__values">
                <div class="rstuning-row">
                    <?php
                    foreach ($options['VALUES'] as $valKey => $arValue) {
                        $value = $options['VALUE'][$valKey] != ''
                            ? $options['VALUE'][$valKey]
                            : $arValue['HTML_VALUE'];
                        ?>
                        <div class="rstuning-col-12 rstuning-col-md-4">
                            <div
                                class="rstuning__option__colorpicker__alone-color<?=($count < 1 ? ' active' : '')?>"
                                data-entity="variant"
                                data-valkey="<?=$valKey?>"
                            >
                                <input
                                    type="text"
                                    class="rstuning__option__colorpicker__hide-me"
                                    name="<?=$options['CONTROL_NAME']?>[<?=$arValue['CONTROL_NAME']?>]"
                                    id="<?=$arValue['CONTROL_ID']?>"
                                    value="<?=$value?>"
                                    <?=$options['ATTR']?>
                                    <?php if (!empty($arValue['MACROS']) && $arValue['MACROS'] != '') : ?>
                                        data-macros="<?=$arValue['MACROS']?>"
                                        <?php if (array_key_exists('TUNING_COLOR', $arValue)) : ?>
                                            data-tuning-color-macros="<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>"
                                        <?php endif; ?>
                                    <?php endif; ?>
                                />
                                <div class="rstuning__option__colorpicker__alone-color__a">
                                    <div class="rstuning__option__colorpicker__before-paint">
                                        <div
                                            class="rstuning__option__colorpicker__incircle"
                                            style="background-color: #<?=$value?>"
                                            data-entity="variant-paint"
                                        ></div>
                                    </div>
                                    <div class="rstuning__option__colorpicker__name"><?=$arValue['NAME']?></div>
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($count == 0) {
                            $jsParams['color'] = $value;
                        }

                        $count++;
                    }
                    ?>
                </div>
            </div>

        </div></div><!-- .row -->
        <!-- row --><div class="rstuning-row"><div class="rstuning-col-12">

            <div class="rstuning__spectrum" data-entity="colorpicker"></div>
            <div class="colorPickerValues">
                <div class="field r mod-dnone-sm">
                    <span class="name"><?=Loc::getMessage('RS.TUNING.COLORPICKER.COLOR.R')?></span>
                    <span class="val">
                        <input
                            type="number"
                            value=""
                            min="0" max="255"
                            data-entity="field-red"
                        />
                    </span>
                </div>
                <div class="field g mod-dnone-sm">
                    <span class="name"><?=Loc::getMessage('RS.TUNING.COLORPICKER.COLOR.G')?></span>
                    <span class="val">
                        <input
                            type="number"
                            value=""
                            min="0" max="255"
                            data-entity="field-green"
                        />
                    </span>
                </div>
                <div class="field b mod-dnone-sm">
                    <span class="name"><?=Loc::getMessage('RS.TUNING.COLORPICKER.COLOR.B')?></span>
                    <span class="val">
                        <input
                            type="number"
                            value=""
                            min="0" max="255"
                            data-entity="field-blue"
                        />
                    </span>
                </div>
                <div class="field hex">
                    <span class="name"><?=Loc::getMessage('RS.TUNING.COLORPICKER.COLOR.HEX')?></span>
                    <span class="val">
                        <input
                            type="text"
                            value=""
                            data-entity="field-hex"
                        />
                    </span>
                </div>
            </div>

        </div></div><!-- .row -->

        <script>new RedsignTuningOptionColor(<?=\Bitrix\Main\Web\Json::encode($jsParams)?>);</script>
    </div>
</div>
        <?php
    }

    public function onload(array $options = []): void
    {
        \Bitrix\Main\UI\Extension::load('redsign.tuning.options.colorpicker');
    }

    public function getPainting(): string
    {
        ob_start();
        ?>
body .rstuning__option__colorpicker__alone-color:hover .rstuning__option__colorpicker__before-paint,
body .rstuning__option__colorpicker__alone-color.active .rstuning__option__colorpicker__before-paint {
    border-color: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
        <?php
        $css = ob_get_contents();
        ob_end_clean();

        return $css ?: '';
    }
}
