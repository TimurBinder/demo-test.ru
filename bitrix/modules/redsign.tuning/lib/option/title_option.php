<?php

namespace Redsign\Tuning;

class TitleOption extends TuningOption
{
    protected string $name = 'title';
    protected string $description = 'Title block';

    public function showOption(array $options = []): void
    {
        ?>
<div class="rstuning__option rstuning-col-12 <?=$options['CSS_CLASS']?>">
        <div class="rstuning__option__title"><?=$options['NAME']?></div>
</div>
        <?php
    }

    public function onload(array $options = []): void
    {
        \Bitrix\Main\UI\Extension::load('redsign.tuning.options.title');
    }
}
