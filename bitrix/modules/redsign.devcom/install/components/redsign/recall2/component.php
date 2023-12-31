<?php

/**
 * @var CMain $APPLICATION
 * @var CBitrixComponent $this
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


global $APPLICATION;

$arParams['EVENT_TYPE'] = 'REDSIGN_RECALL2';
$arParams['REQUEST_PARAM_NAME'] = 'redsign_recall2';
$arResult['ACTION_URL'] = $APPLICATION->GetCurPage();
$arResult['PARAMS_HASH'] = md5(serialize($arParams) . $this->GetTemplateName());

if (!isset($arParams['SHOW_FIELDS']) || !is_array($arParams['SHOW_FIELDS'])) {
    $arParams['SHOW_FIELDS'] = array();
}

if (!isset($arParams['REQUIRED_FIELDS']) || !is_array($arParams['REQUIRED_FIELDS'])) {
    $arParams['REQUIRED_FIELDS'] = array();
}

$arFields = array(
    array(
        'CONTROL_NAME' => 'RS_AUTHOR_NAME',
        'CONTROL_ID' => 'RS_AUTHOR_NAME',
        'SHOW' => 'N',
        'EVENT_FIELD_NAME' => 'AUTHOR',
        'VALUE' => '',
        'HTML_VALUE' => '',
    ),
    array(
        'CONTROL_NAME' => 'RS_COMPANY_NAME',
        'CONTROL_ID' => 'RS_COMPANY_NAME',
        'SHOW' => 'N',
        'EVENT_FIELD_NAME' => 'COMPANY',
        'VALUE' => '',
        'HTML_VALUE' => '',
    ),
    array(
        'CONTROL_NAME' => 'RS_AUTHOR_EMAIL',
        'CONTROL_ID' => 'RS_AUTHOR_EMAIL',
        'SHOW' => 'N',
        'EVENT_FIELD_NAME' => 'AUTHOR_EMAIL',
        'VALUE' => '',
        'HTML_VALUE' => '',
    ),
    array(
        'CONTROL_NAME' => 'RS_AUTHOR_PHONE',
        'CONTROL_ID' => 'RS_AUTHOR_PHONE',
        'SHOW' => 'N',
        'EVENT_FIELD_NAME' => 'AUTHOR_PHONE',
        'VALUE' => '',
        'HTML_VALUE' => '',
    ),
    array(
        'CONTROL_NAME' => 'RS_AUTHOR_COMMENT',
        'CONTROL_ID' => 'RS_AUTHOR_COMMENT',
        'SHOW' => 'N',
        'EVENT_FIELD_NAME' => 'AUTHOR_COMMENT',
        'VALUE' => '',
        'HTML_VALUE' => '',
    ),
);

if (is_array($arParams['SHOW_FIELDS'])) {
    foreach ($arFields as $key => $arField) {
        $arFields[$key]['SHOW'] = in_array($arField['CONTROL_ID'], $arParams['SHOW_FIELDS']) ? 'Y' : 'N';
    }
}

$arResult['FIELDS'] = $arFields;

if (!function_exists('redsign_add_recall2_type')) {
    function redsign_add_recall2_type(string $EVENT_TYPE): bool
    {
        $return = false;
        $et = new \CEventType();
        $EventTypeID = $et->Add([
            'LID' => 'ru',
            'EVENT_NAME' => $EVENT_TYPE,
            'NAME' => GetMessage('INSTALL_EVENT_TYPE_NAME'),
            'DESCRIPTION' => GetMessage('INSTALL_EVENT_TYPE_DESCRIPTION')
        ]);
        if ($EventTypeID > 0) {
            $arSites = array();
            $rsSites = CSite::GetList($by = 'sort', $order = 'desc', array());
            while ($arSite = $rsSites->Fetch()) {
                $arSites[] = $arSite['LID'];
            }
            $arr['ACTIVE'] = 'Y';
            $arr['EVENT_NAME'] = $EVENT_TYPE;
            $arr['LID'] = $arSites;
            $arr['EMAIL_FROM'] = '#AUTHOR_EMAIL#';
            $arr['EMAIL_TO'] = '#EMAIL_TO#';
            $arr['BCC'] = '';
            $arr['SUBJECT'] = '#THEME#';
            $arr['BODY_TYPE'] = 'text';
            $arr['MESSAGE'] = GetMessage('INSTALL_EVENT_TEMPLATE_BODY');

            $emess = new \CEventMessage();
            $EventTemplateID = $emess->Add($arr);
            if ($EventTemplateID > 0) {
                $return = true;
            }
        } else {
            $return = false;
        }
        return $return;
    }
}

if ($USER->IsAuthorized()) $arParams['ALFA_USE_CAPTCHA'] = 'N';

if ($arParams['ALFA_USE_CAPTCHA'] == 'Y') {
    include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/captcha.php');
    $cpt = new CCaptcha();
    $captchaPass = COption::GetOptionString('main', 'captcha_password', '');
    if (strlen($captchaPass) <= 0) {
        $captchaPass = $this->randString(10);
        COption::SetOptionString('main', 'captcha_password', $captchaPass);
    }
    $cpt->SetCodeCrypt($captchaPass);
    $arResult['CATPCHA_CODE'] = htmlspecialchars($cpt->GetCodeCrypt());
}

if ($_REQUEST[$arParams['REQUEST_PARAM_NAME']] == 'Y') {
    $arFilter = array('TYPE_ID' => $arParams['EVENT_TYPE'], 'LID' => 'ru');
    $rsET = CEventType::GetList($arFilter);
    if (!$arET = $rsET->Fetch()) {
        redsign_add_recall2_type($arParams['EVENT_TYPE']);
    }
    $arResult['LAST_ERROR'] = '';
    $arResult['GOOD_SEND'] = '';

    if (check_bitrix_sessid() && (!isset($_REQUEST['PARAMS_HASH']) || $arResult['PARAMS_HASH'] === $_REQUEST['PARAMS_HASH'])) {
        if ($arParams['ALFA_USE_CAPTCHA'] == 'Y') {
            if (strlen($_POST['captcha_word']) < 1 && strlen($_POST['captcha_sid']) < 1) {
                $arResult['LAST_ERROR'] = GetMessage('ALFA_MSG_CAPTCHA_EMPRTY');
            } elseif (!$APPLICATION->CaptchaCheckCode($_POST['captcha_word'], $_POST['captcha_sid'])) {
                $arResult['LAST_ERROR'] = GetMessage('ALFA_MSG_CAPTCHA_WRONG');
            }
        }

        if ($arResult['LAST_ERROR'] == '') {
            $arEventFields = array();
            $arEventFields['THEME'] = GetMessage('ALFA_MSG_THEME');
            $arEventFields['EMAIL_TO'] = trim(( $arParams['ALFA_EMAIL_TO'] ));
            foreach ($arResult['FIELDS'] as $key => $arField) {
                $arEventFields[$arField['EVENT_FIELD_NAME']] = trim(( $_REQUEST[$arField['CONTROL_NAME']] ));
                if ((empty($arParams['REQUIRED_FIELDS']) || in_array($arField['CONTROL_NAME'], $arParams['REQUIRED_FIELDS'])) && strlen($_REQUEST[$arField['CONTROL_NAME']]) <= 1) {
                    $arResult['LAST_ERROR'] = GetMessage('ALFA_MSG_EMPTY_REQUIRED_FIELDS');
                }
            }
            if ($arResult['LAST_ERROR'] == '') {
                ;
                CEvent::Send($arParams['EVENT_TYPE'], SITE_ID, $arEventFields, 'N');
                $arResult['GOOD_SEND'] = 'Y';
            }
        }
        foreach ($arResult['FIELDS'] as $key => $arField) {
            // set request
            $arResult['FIELDS'][$key]['HTML_VALUE'] = $_REQUEST[$arField['CONTROL_NAME']];
        }
    } else {
        $arResult['LAST_ERROR'] = GetMessage('ALFA_MSG_OLD_SESS');
    }
}

$this->IncludeComponentTemplate();
