<?php

namespace Redsign\Tuning;

class CheckboxOption extends TuningOption
{
    protected string $name = 'checkbox';
    protected string $description = 'Checkbox button';

    public function showOption(array $options = []): void
    {
        ?>
<div class="rstuning__option rstuning-col-12 <?=$options['CSS_CLASS']?> js-rs_option_info" data-reload="<?=($options['RELOAD'] == 'Y' ? 'Y' : 'N')?>">
    <div class="rstuning__option__checkbox">
        <input
            type="checkbox"
            name="<?=$options['CONTROL_NAME']?>"
            value="N"
            <?=($options['DEFAULT'] == 'N' ? 'checked="checked"' : '')?>
        >
        <input
            type="checkbox"
            id="<?=$options['CONTROL_ID']?>"
            name="<?=$options['CONTROL_NAME']?>"
            value="Y"
            <?=($options['HTML_VALUE'] == $options['VALUE'] ? 'checked="checked"' : '')?>
            <?=$options['ATTR']?>
            <?php if (!empty($options['MACROS']) && $options['MACROS'] != '') : ?>
                data-macros="<?=$options['MACROS']?>"
            <?php endif; ?>
        >
        <label for="<?=$options['CONTROL_ID']?>"><?=$options['NAME']?></label>
    </div>
</div>
        <?php
    }

    public function onload(array $options = []): void
    {
        \Bitrix\Main\UI\Extension::load('redsign.tuning.options.checkbox');
    }
}
