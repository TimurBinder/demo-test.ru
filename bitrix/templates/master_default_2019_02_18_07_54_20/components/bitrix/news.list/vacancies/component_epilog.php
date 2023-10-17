<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

\Bitrix\Main\Page\Asset::getInstance()->addString('<script src="https://yastatic.net/share2/share.js" async="async" charset="utf-8"></script>');
