<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
    'NAME' => 'Каталог Планировки лендинга Rose Town',
    'DESCRIPTION' => 'Выводит Планировки лендинга Rose Town',
    'PATH' => array(
        'ID' => 'roseTown',
        'NAME' => 'Rose-Town Landing',
        'CHILD' => array(
            'ID' => 'roseTown_plans',
            'NAME' => 'Планировки'
        )
    ),
    'ICON' => '/images/icon.gif'
);