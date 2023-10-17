<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_SITE_ID") || !defined("WIZARD_SITE_DIR"))
	return;

use \Bitrix\Main\Loader;

function ___writeToAreasFile($path, $text)
{
	//if(file_exists($fn) && !is_writable($abs_path) && defined("BX_FILE_PERMISSIONS"))
	//	@chmod($abs_path, BX_FILE_PERMISSIONS);

	$fd = @fopen($path, "wb");
	if(!$fd)
		return false;

	if(false === fwrite($fd, $text))
	{
		fclose($fd);
		return false;
	}

	fclose($fd);

	if(defined("BX_FILE_PERMISSIONS"))
		@chmod($path, BX_FILE_PERMISSIONS);
}

if (COption::GetOptionString("main", "upload_dir") == "")
	COption::SetOptionString("main", "upload_dir", "upload");

if(COption::GetOptionString("redsign.master", "wizard_installed", "N", WIZARD_SITE_ID) == "N" || WIZARD_INSTALL_DEMO_DATA)
{
	if(file_exists(WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/"))
	{
		CopyDirFiles(
			WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/",
			WIZARD_SITE_PATH,
			$rewrite = true,
			$recursive = true,
			$delete_after_copy = false
		);
	}
	COption::SetOptionString("redsign.master", "template_converted", "Y", "", WIZARD_SITE_ID);

    $wizrdHomepageId = $wizard->GetVar(WIZARD_TEMPLATE_ID."_pageID");

    $arHomePages = WizardServices::GetThemes($wizard->GetPath()."/site/services/main/ru/public");
    if (is_array($arHomePages) && count($arHomePages) > 0) {
        $arHomePages = array_keys($arHomePages);
    } else {
        $arHomePages = array();
    }

    if (in_array($wizrdHomepageId, $arHomePages)) {
        $indexPath = WIZARD_ABSOLUTE_PATH.'/site/services/main/ru/public/'.$wizrdHomepageId.'/copy/_index.php'; // TODO Lang path

        if (file_exists($indexPath)) {
            CopyDirFiles(
                $indexPath,
                WIZARD_SITE_PATH."/_index.php",
                $rewrite = true,
                $recursive = true,
                $delete_after_copy = true
            );
        }
    }
	
	if (Loader::includeModule('catalog')) {
        $catalogPath = WIZARD_ABSOLUTE_PATH.'/site/services/main/ru/public_catalog/sale'; // TODO Lang path
	} else {
		$catalogPath = WIZARD_ABSOLUTE_PATH.'/site/services/main/ru/public_catalog/corp'; // TODO Lang path
	}
	
	if (file_exists($catalogPath)) {
		CopyDirFiles(
			$catalogPath,
			WIZARD_SITE_PATH,
			$rewrite = true,
			$recursive = true,
			$delete_after_copy = true
		);
	}
}
elseif (COption::GetOptionString("redsign.master", "template_converted", "N", WIZARD_SITE_ID) == "N")
{
	CopyDirFiles(
		WIZARD_ABSOLUTE_PATH."/site/services/main/".LANGUAGE_ID."/public_convert/",
		WIZARD_SITE_PATH,
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = false
	);
	CopyDirFiles(
		WIZARD_SITE_PATH."/include/company_logo.php",
		WIZARD_SITE_PATH."/include/company_logo_old.php",
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = true
	);

	COption::SetOptionString("redsign.master", "template_converted", "Y", "", WIZARD_SITE_ID);
}

$wizard =& $this->GetWizard();
___writeToAreasFile(WIZARD_SITE_PATH."include/copyright.php", $wizard->GetVar("siteCopy"));
___writeToAreasFile(WIZARD_SITE_PATH."include/footer/allrights.php", $wizard->GetVar("siteCopy"));
___writeToAreasFile(WIZARD_SITE_PATH."include/telephone.php", $wizard->GetVar("siteTelephone"));
___writeToAreasFile(WIZARD_SITE_PATH."include/telephone2.php", $wizard->GetVar("siteTelephone2"));

/*
if ($wizard->GetVar("templateID") != "master")
{
	$arSocNets = array("shopFacebook" => "facebook", "shopTwitter" => "twitter", "shopVk" => "vk", "shopGooglePlus" => "google");
	foreach($arSocNets as $socNet=>$includeFile)
	{
		$curSocnet = $wizard->GetVar($socNet);
		if ($curSocnet)
		{
			$text = '<a href="'.$curSocnet.'"></a>';
			___writeToAreasFile(WIZARD_SITE_PATH."include/socnet_".$includeFile.".php", $text);
		}
	}
}
*/
if(COption::GetOptionString("redsign.master", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
	return;

WizardServices::PatchHtaccess(WIZARD_SITE_PATH);

// #SITE_DIR#
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".bottom_additional.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".bottom_catalog.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".bottom_services.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".main.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH.".personal.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."articles/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."auth/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."brands/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."cart/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."catalog/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."company/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."contacts/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."help/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."helpinfo/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."include/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."news/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."personal/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."projects/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."sale-promotions/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."search/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."services/", Array("SITE_DIR" => WIZARD_SITE_DIR));


// #SITE_TEMPLATE_PATH#
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."include/", Array("SITE_TEMPLATE_PATH" => BX_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".WIZARD_THEME_ID));

// #SHOP_EMAIL#
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/ask/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/ask_staff/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/feedback/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/job/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/order_service/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/product_ask/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/recall/index.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/footer/contacts.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/index/map_contacts.php", Array("SHOP_EMAIL" => $wizard->GetVar("shopEmail")));

// #SALE_PHONE#
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/footer/phones.php", Array("SALE_PHONE" => $wizard->GetVar("siteTelephone")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/header/phone.php", Array("SALE_PHONE" => $wizard->GetVar("siteTelephone")));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/index/map_contacts.php", Array("SALE_PHONE" => $wizard->GetVar("siteTelephone")));

// #SALE_PHONE_URL#
$sPhoneUrl = preg_replace('/\D/', '', $wizard->GetVar("siteTelephone"));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/footer/phones.php", Array("SALE_PHONE_URL" => $sPhoneUrl));

// #SALE_PHONE2#
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/footer/phones.php", Array("SALE_PHONE2" => $wizard->GetVar("siteTelephone2")));

// #SALE_PHONE_URL2#
$sPhoneUrl2 = preg_replace('/\D/', '', $wizard->GetVar("siteTelephone2"));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/footer/phones.php", Array("SALE_PHONE_URL2" => $sPhoneUrl2));

// #SITE_SCHEDULE#
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/index/map_contacts.php", Array("SITE_SCHEDULE" => $wizard->GetVar("siteSchedule")));

// #SITE_SMALL_ADDRESS#
//$smallAdress = $wizard->GetVar("shopLocation").', '.$wizard->GetVar("shopAdr");
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/index/map_contacts.php", Array("SITE_SMALL_ADDRESS" => $wizard->GetVar("siteAddress")));

// SITE META
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/_index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_DESCRIPTION" => htmlspecialcharsbx($wizard->GetVar("siteMetaDescription"))));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.section.php", array("SITE_KEYWORDS" => htmlspecialcharsbx($wizard->GetVar("siteMetaKeywords"))));

// #REDSIGN_COPYRIGHT#
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".WIZARD_THEME_ID."/include/footers/type1.php", array('REDSIGN_COPYRIGHT' => GetMessage('REDSIGN_COPYRIGHT')));
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".WIZARD_THEME_ID."/include/footers/type2.php", array('REDSIGN_COPYRIGHT' => GetMessage('REDSIGN_COPYRIGHT')));

// #CART_PATH#
$sCartPath = 'cart/';
if (CModule::includeModule('catalog')) {
	$sCartPath = 'personal/cart/';
} else {
	$sCartPath = 'cart/';
}

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."brands/index.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."catalog/index.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/header/catalog_menu.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/template/catalog/element_blocks.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/template/catalog/landing_section.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/template/news/catalog_items.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/template/news/services_items.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/template/order/popular.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."services/index.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."wishlist/index.php", Array("CART_PATH" => $sCartPath));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."_index.php", Array("CART_PATH" => $sCartPath));

// if (CModule::IncludeModule("sale"))
// {
	$addResult = \Bitrix\Main\UserConsent\Internals\AgreementTable::add(array(
		"ACTIVE" => \Bitrix\Main\UserConsent\Agreement::ACTIVE,
		"CODE" => "sale_default",
		"NAME" => GetMessage("WIZ_DEFAULT_USER_CONSENT_NAME"),
		"TYPE" => \Bitrix\Main\UserConsent\Agreement::TYPE_CUSTOM,
		"LANGUAGE_ID" => LANGUAGE_ID,
		"AGREEMENT_TEXT" => GetMessage("REDSIGN.AGREEMENT_TEXT"),
		"LABEL_TEXT" => GetMessage("REDSIGN.AGREEMENT_LABEL_TEXT", array("#URL#" => WIZARD_SITE_DIR."company/license_work/")),
		// "DATA_PROVIDER" => \Bitrix\Sale\UserConsent::DATA_PROVIDER_CODE,
	));
	if ($addResult->isSuccess())
	{
        $iAgreementId = $addResult->getId();

        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."catalog/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."services/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/ask/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/ask_staff/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/cheaper/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/feedback/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/job/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/order_service/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/product_ask/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."include/forms/recall/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."cart/order/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
        CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."help/faq/index.php", Array("USER_CONSENT_ID" => $iAgreementId));
	}
// }

$arUrlRewrite = array();
if (file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php"))
{
	include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");
}

$arNewUrlRewrite = array(
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."news/archive/([0-9]+)?/?([0-9]+)?/?#",
		"RULE" => "YEAR=\$1&MONTH=\$2",
		"ID" => "",
		"PATH" => WIZARD_SITE_DIR."news/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."news/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."company/partners/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."company/partners/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."sale-promotions/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."sale-promotions/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."company/staff/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."company/staff/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."projects/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."projects/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."articles/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."articles/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."services/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => WIZARD_SITE_DIR."services/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."catalog/#",
		"RULE" => "",
		"ID" => "bitrix:catalog",
		"PATH" => WIZARD_SITE_DIR."catalog/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."staff/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."staff/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."news/index.php",
	),
	array(
		"CONDITION" => "#^".WIZARD_SITE_DIR."brands/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => WIZARD_SITE_DIR."brands/index.php",
    ),
    array (
        "CONDITION" => "#^".WIZARD_SITE_DIR."personal/#",
        "RULE" => "",
        "ID" => 'bitrix:sale.personal.section',
        "PATH" => WIZARD_SITE_DIR."personal/index.php",
        "SORT" => 100,
    )
);

foreach ($arNewUrlRewrite as $arUrl)
{
	if (!in_array($arUrl, $arUrlRewrite))
	{
		CUrlRewriter::Add($arUrl);
	}
}
?>