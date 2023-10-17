<?php

namespace Redsign\Tuning;

class HtmlOption extends TuningOption
{
    protected string $name = 'html';
    protected string $description = 'Html block';

    public function showOption(array $options = []): void
    {
        ?>
<div class="rstuning__option rstuning-col-12 <?=$options['CSS_CLASS']?> js-rs_option_info">
        <div class="rstuning__option-opname"><?=$options['HTML_VALUE']?></div>
</div>
        <?php
    }

    public function onload(array $options = []): void
    {
        // \Bitrix\Main\UI\Extension::load('redsign.tuning.options.html');
    }
}
