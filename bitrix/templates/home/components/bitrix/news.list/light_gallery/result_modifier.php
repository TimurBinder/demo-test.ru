<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

if($arParams['USE_OWL'] == 'Y') {
    $arParams['OWL_PARAMS'] = array(
      'items' => 1,
      'margin' => 0,
      'dots' => false,
      'nav' => true,
      'responsive' => array(
          '0' => array('items' => '1'),
          '480' => array('items' => '2'),
          '769' => array('items' => '3'),
          '1200' => array('items' => '4')
      ),
      'autoplay' => true,
      'loop' => true,
      //'autoplayTimeout' => 1000,
      //autoplayHoverPause' => true,
      'navText' => array('<span style="float:left;margin-left:5px;color:#fff;font-size:35px;font-weight:bold;cursor:pointer;background-color:#555555;" title="Предыдущий"><</span>',
	  					 '<span style="float:right;margin-right:5px;color:#fff;font-size:35px;font-weight:bold;cursor:pointer;background-color:#555555;" title="Следующий">></span>'),
    );

    if ($arParams['ADD_CONTAINER'] != 'Y') {
      $arParams['OWL_PARAMS']['responsive']['960'] = array(
        'items' => '4'
      );
      $arParams['OWL_PARAMS']['responsive']['1200'] = array(
        'items' => '5'
      );
    }
}
