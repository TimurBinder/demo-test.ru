<?php

namespace Redsign\Tuning;

class Line2BorderOption extends TuningOption
{
    protected string $name = 'line2border';
    protected string $description = 'Separation block';

    public function showOption(array $options = []): void
    {
        ?>
<div class="rstuning__option rstuning-col-12 <?=$options['CSS_CLASS']?> js-rs_option_info">
    <div class="rstuning__option__line2border"></div>
</div>
        <?php
    }

    public function onload(array $options = []): void
    {
        \Bitrix\Main\UI\Extension::load('redsign.tuning.options.line2border');
    }
}
