<?php

namespace Redsign\Tuning;

class DDOption extends TuningOption
{
    protected string $name = 'Dran&Drop';
    protected string $description = 'Dran&Drop wrapper';

    public function showOption(array $options = []): void
    {
        $tuning = TuningCore::getInstance();

        if (!$tuning)
            return;

        $instanceOption = TuningOption::getInstance();

        $arResult['OPTIONS'] = [];
        $arResult['TABS'] = [];

        $jsParams = [
            'containerId' => 'rstuning__option__dd_' . $options['CONTROL_ID'],
            'controlName' => $options['CONTROL_NAME'],
            'optionId' => $options['ID'],
            'ajaxReload' => $options['RELOAD'] == 'Y',
        ];
        $optionList = $tuning->getInstanceOptionMananger()->getOptionsByIds($options['CHILDREN']);

        foreach ($optionList as $id => $arOption) {
            $optionObj = $instanceOption->getOptionObjectByName($arOption['TYPE']);

            if ($optionObj != null) {
                $optionObj->onload();
                if (!$optionObj->isSortable())
                    continue;

                $arOption['SORTABLE'] = $options;
                $arOption['ID'] = $id;

                $arOption['VALUE'] = $tuning->getOptionValue($id);

                ob_start();
                $optionObj->showOption($arOption);
                $out = ob_get_contents();
                ob_end_clean();

                $arResult['OPTIONS'][$id] = $arOption;
                $arResult['OPTIONS'][$id]['DISPLAY_HTML'] = $out;
            }
        }
        ?>
<div
    class="rstuning__option rstuning-col-12 js-rs_option_info"
    id="rstuning__option__dd_<?=$options['CONTROL_ID']?>"
    data-reload="<?=($options['RELOAD'] == 'Y' ? 'Y' : 'N')?>"
>
    <div class="rstuning__option__dd">
        <?php if ($options['NAME']) : ?>
            <div class="rstuning__option-opname"><?=$options['NAME']?></div>
        <?php endif; ?>
        <div class="rstuning-row js-rstuning-sortable rstuning__option__dd__counter"><?php
        if (!empty($arResult['OPTIONS']) && !empty($options['VALUE'])) :
            foreach ($options['VALUE'] as $id) :
                if (!empty($arResult['OPTIONS'][$id]))
                echo $arResult['OPTIONS'][$id]['DISPLAY_HTML'];
            endforeach;
        endif;
        ?></div>
    </div>
    <script>new RedsignTuningOptionDnD(<?=\Bitrix\Main\Web\Json::encode($jsParams)?>);</script>
</div>
        <?php
    }

    public function onload(array $options = []): void
    {
        \Bitrix\Main\UI\Extension::load('redsign.tuning.options.dd');
    }

    /**
     * @deprecated
     */
    public function prepareValueAfterGet(array $params = []): void
    {
        if (is_array($params['VALUE'])) {
            $params['OPTION']['VALUE'] = $params['VALUE'];
        } else {
            $params['OPTION']['VALUE'] = explode(',', $params['VALUE']);
        }
    }

    public function getPainting(): string
    {
        ob_start();
        ?>
body .rstuning .rstuning__option__dd__fallback .rstuning__option__dd__drag svg {
    stroke: ##<?=\Redsign\Tuning\WidgetPainting::MACROS_NAME?>#; }
        <?php
        $css = ob_get_contents();
        ob_end_clean();

        return $css ?: '';
    }
}
