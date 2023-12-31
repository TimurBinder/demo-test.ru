<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arShowFieldsList = array(
    "NONE" => GetMessage("NONE"),
    "RS_AUTHOR_NAME" => GetMessage("RS_AUTHOR_NAME"),
    "RS_COMPANY_NAME" => GetMessage("RS_COMPANY_NAME"),
    "RS_AUTHOR_EMAIL" => GetMessage("RS_AUTHOR_EMAIL"),
    "RS_AUTHOR_PHONE" => GetMessage("RS_AUTHOR_PHONE"),
    "RS_AUTHOR_COMMENT" => GetMessage("RS_AUTHOR_COMMENT"),
);

$arRequiresFieldsList = array(
    "NONE" => GetMessage("NONE"),
    "RS_AUTHOR_NAME" => GetMessage("RS_AUTHOR_NAME"),
    "RS_COMPANY_NAME" => GetMessage("RS_COMPANY_NAME"),
    "RS_AUTHOR_EMAIL" => GetMessage("RS_AUTHOR_EMAIL"),
    "RS_AUTHOR_PHONE" => GetMessage("RS_AUTHOR_PHONE"),
    "RS_AUTHOR_COMMENT" => GetMessage("RS_AUTHOR_COMMENT"),
);

$arComponentParameters = array(
    "PARAMETERS" => array(
        "ALFA_EMAIL_TO" => array(
            "NAME" => GetMessage("ALFA_MSG_EMAIL_TO"),
            "TYPE" => "STRING",
            "PARENT" => "BASE",
            "DEFAULT" => COption::GetOptionString("main", "email_from", ""),
        ),
        "SHOW_FIELDS" => array(
            "NAME" => GetMessage("ALFA_MSG_SHOW_FIELDS"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arShowFieldsList,
            "PARENT" => "BASE",
        ),
        "REQUIRED_FIELDS" => array(
            "NAME" => GetMessage("ALFA_MSG_REQUIRED_FIELDS"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arShowFieldsList,
            "PARENT" => "BASE",
        ),
        "ALFA_USE_CAPTCHA" => array(
            "NAME" => GetMessage("ALFA_MSG_USE_CAPTCHA"),
            "TYPE" => "CHECKBOX",
            "PARENT" => "BASE",
            "VALUE" => "Y",
        ),
        "ALFA_MESSAGE_AGREE" => array(
            "NAME" => GetMessage("ALFA_MSG_MESSAGE_AGREE"),
            "TYPE" => "STRING",
            "PARENT" => "BASE",
            "DEFAULT" => GetMessage("ALFA_MSG_MESSAGE_AGREE_DEFAULT"),
        ),
        "CACHE_TIME" => array(
            "PARENT" => "CACHE_SETTINGS",
            "DEFAULT" => 3600
        ),
        "AJAX_MODE" => array(),
    )
);
