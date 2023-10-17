<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

if ($arResult['FILE'] != ''): ?>
<div class="b-note-text">
    <?php include($arResult["FILE"]); ?>
</div>

<?php endif; ?>
