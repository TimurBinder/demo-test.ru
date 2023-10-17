<?php
$arUrlRewrite=array (
  0 => 
  array (
    'CONDITION' => '#^/news/archive/([0-9]+)?/?([0-9]+)?/?#',
    'RULE' => 'YEAR=$1&MONTH=$2',
    'ID' => '',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/company/partners/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/partners/index.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/catalog/compare/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog.compare.list',
    'PATH' => '/catalog/compare.php',
    'SORT' => 100,
  ),
  25 => 
  array (
    'CONDITION' => '#^/sale-promotions/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/sale-promotions/index.php',
    'SORT' => 100,
  ),
  24 => 
  array (
    'CONDITION' => '#^([^/]+?)/\\??(.*)#',
    'RULE' => 'SECTION_CODE=$1&$2',
    'ID' => 'bitrix:catalog.section',
    'PATH' => '/wishlist/index.php',
    'SORT' => 100,
  ),
  4 => 
  array (
    'CONDITION' => '#^/company/staff/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/company/staff/index.php',
    'SORT' => 100,
  ),
  6 => 
  array (
    'CONDITION' => '#^/services/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/services/teplovizionnoe-obsledovanie/index.php',
    'SORT' => 100,
  ),
  7 => 
  array (
    'CONDITION' => '#^/services/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/services/index.php',
    'SORT' => 100,
  ),
  8 => 
  array (
    'CONDITION' => '#^/articles/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/articles/index.php',
    'SORT' => 100,
  ),
  9 => 
  array (
    'CONDITION' => '#^/projects/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/projects/index.php',
    'SORT' => 100,
  ),
  23 => 
  array (
    'CONDITION' => '#^/catalog/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalog/index.php',
    'SORT' => 100,
  ),
  11 => 
  array (
    'CONDITION' => '#^/staff/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/staff/index.php',
    'SORT' => 100,
  ),
  12 => 
  array (
    'CONDITION' => '#^/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/news/index.php',
    'SORT' => 100,
  ),
);
