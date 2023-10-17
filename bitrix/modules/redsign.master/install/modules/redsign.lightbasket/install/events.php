<?php

$em = new CEventMessage();
$arSites = array();

$langs = CLanguage::GetList(($b=""), ($o=""));
while ($lang = $langs->Fetch()) {
    IncludeModuleLangFile(__FILE__, $lang["LID"]);

    $sites = CLang::GetList($by, $order, Array("LANGUAGE_ID"=>$lang["LID"]));
    while ($site = $sites->Fetch()) {
  		  $arSites[] = $site["LID"];
  	}

    $fres = CEventType::GetList(array("EVENT_NAME" => "RS_NEW_ORDER", "LID" => $lang["LID"]));
    if (!$fres->Fetch()) {
        $et = new CEventType;
        $et->Add(array(
            'LID' => $lang['LID'],
            'EVENT_NAME' => 'RS_NEW_ORDER',
            'NAME' => GetMessage('RS_LIGHTBASKET_EVENT_NEW_ORDER_NAME'),
            'DESCRIPTION' => GetMessage('RS_LIGHTBASKET_EVENT_NEW_ORDER_DESC'),
        ));

        // \Bitrix\Main\Diag\Debug::dump(GetMessage('RS_LIGHTBASKET_EVENT_NEW_ORDER_DESC')); die();

        if (is_array($arSites) && count($arSites) > 0) {
            $em->Add(array(
                'ACTIVE' => 'Y',
                'EVENT_NAME' => 'RS_NEW_ORDER',
                'LID' => $arSites,
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL#',
                'SUBJECT' => GetMessage('RS_LIGHTBASKET_EVENT_NEW_ORDER_THEME'),
                'MESSAGE' => GetMessage('RS_LIGHTBASKET_EVENT_NEW_ORDER_TEXT'),
                'BODY_TYPE' => 'text'
            ));
        }
    }

}
