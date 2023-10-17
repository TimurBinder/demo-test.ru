<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
$arComponentDescription = array(
    'NAME' => 'Каталог лендинга Rose Town',
    'DESCRIPTION' => 'Выводит Каталог лендинга Rose Town',
    'PATH' => array(
        'ID' => 'roseTown',
        'NAME' => 'Rose-Town Landing',
        'CHILD' => array(
            'ID' => 'roseTown_catalog',
            'NAME' => 'Каталог'
        )
    ),
    'ICON' => '/images/icon.gif'
);