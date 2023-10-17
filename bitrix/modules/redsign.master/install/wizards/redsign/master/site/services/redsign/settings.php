<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Config\Option;
use Bitrix\Main\UserConsent\Agreement;

Loc::loadMessages(__FILE__);

Option::set('redsign.master', 'wizard_installed', 'Y', WIZARD_SITE_ID);

if (Loader::includeModule('redsign.tuning')) {

    $tuning = \Redsign\Tuning\TuningCore::getInstance();

    $wizrdHomepageId = $wizard->GetVar(WIZARD_TEMPLATE_ID."_pageID");

    switch ($wizrdHomepageId) {
        
        case 'type2':
            Option::set('redsign.master', 'head_type', 'type2', WIZARD_SITE_ID);
            $tuning->setOptionValue('MAIN_MENU_COLOR_SCHEME', 'colors');
            break;

        case 'type3':
            Option::set('redsign.master', 'head_type', 'type3', WIZARD_SITE_ID);
            $tuning->setOptionValue('MAIN_MENU_COLOR_SCHEME', 'colors');
            break;
            
        case 'type4':
            Option::set('redsign.master', 'head_type', 'type5', WIZARD_SITE_ID);
            $tuning->setOptionValue('MAIN_MENU_COLOR_SCHEME', 'dark');
            break;
            
        case 'type5':
            Option::set('redsign.master', 'head_type', 'type6', WIZARD_SITE_ID);
            $tuning->setOptionValue('MAIN_MENU_COLOR_SCHEME', 'colors');
            break;
            
        case 'type6':
            Option::set('redsign.master', 'head_type', 'type4', WIZARD_SITE_ID);
            $tuning->setOptionValue('MAIN_MENU_COLOR_SCHEME', 'dark');
            break;

        default:
            Option::set('redsign.master', 'head_type', 'type1', WIZARD_SITE_ID);
            $tuning->setOptionValue('MAIN_MENU_COLOR_SCHEME', 'colors');
            break;
    }
}

if (Loader::includeModule('catalog') && Loader::includeModule('sale')) {
	Option::set($updater->moduleID, 'head_settings_show_topline', 'Y', $arSite['ID']);
	Option::set($updater->moduleID, 'head_settings_topline_location', 'Y', $arSite['ID']);
	Option::set($updater->moduleID, 'head_settings_topline_shedule', 'Y', $arSite['ID']);
	Option::set($updater->moduleID, 'head_settings_topline_recall', 'Y', $arSite['ID']);
	Option::set($updater->moduleID, 'head_settings_topline_auth', 'Y', $arSite['ID']);
	Option::set($updater->moduleID, 'head_settings_topline_favorite', 'Y', $arSite['ID']);
	Option::set($updater->moduleID, 'head_settings_topline_compare', 'Y', $arSite['ID']);
	Option::set($updater->moduleID, 'head_settings_topline_cart', 'Y', $arSite['ID']);
}

if (Loader::includeModule('redsign.devfunc')) {
    $arData = array(
        'mp_code' => array('redsign.master'),
    );

    $ret = \Redsign\DevFunc\Module::registerInstallation($arData);
}