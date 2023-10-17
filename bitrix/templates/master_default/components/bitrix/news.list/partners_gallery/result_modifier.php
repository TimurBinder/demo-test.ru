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
	  'navText' => array(
            '<svg class="icon-svg"><use xlink:href="#svg-arrow-thin-left"></use></svg>',
            '<svg class="icon-svg"><use xlink:href="#svg-arrow-thin-right"></use></svg>'
        ),
				'responsive' => array(
						'0' => array('items' => '1'),
						'480' => array('items' => $arParams['OWL_PHONE']),
						'769' => array('items' => $arParams['OWL_TABLET']),
						'996' => array('items' => $arParams['OWL_PC']),
				),
    );

    if ($arParams['ADD_CONTAINER'] != 'Y') {
      $arParams['OWL_PARAMS']['responsive']['960'] = array(
        'items' => '5'
      );
      $arParams['OWL_PARAMS']['responsive']['1200'] = array(
        'items' => '5'
      );
    }
}
