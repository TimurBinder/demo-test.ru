<?
/**
 * Company developer: ALTASIB
 * Developer: Konstantin Volodin
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2016 ALTASIB
 */

global $MESS;
$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen("/index.php"));
IncludeModuleLangFile(__FILE__);

Class altasib_docslist extends CModule
{
	var $MODULE_ID = "altasib.docslist";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	function altasib_docslist()
	{
		$arModuleVersion = array();
		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}
		else
		{
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}

		$this->MODULE_NAME = GetMessage("ALTASIB_DOCSLIST_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("ALTASIB_DOCSLIST_MODULE_DESCRIPTION");
		$this->PARTNER_NAME = "ALTASIB";
		$this->PARTNER_URI = "http://www.altasib.ru/";
	}


	function DoInstall()
	{
		global $APPLICATION, $step;
		$GLOBALS["errors"] = $this->errors;
		$RIGHT = $APPLICATION->GetGroupRight("altasib.docslist");
		if ($RIGHT >= "W")
		{
			$step = IntVal($step);
			if($step < 2)
			{
				$APPLICATION->IncludeAdminFile(GetMessage('ALTASIB_DOCSLIST_INSTALL_TITLE'),$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/install/step1.php");
			}
			elseif($step==2)
			{
				$this->InstallFiles();
				$this->InstallDB();
				if($_REQUEST["INSTALL_IB"] == "Y")
				{
					$this->InstallDemo();
				}
				$GLOBALS["errors"] = $this->errors;
				$APPLICATION->IncludeAdminFile(GetMessage('ALTASIB_DOCSLIST_INSTALL_TITLE'),$_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/install/step2.php");
			}
		}
	}

	function DoUninstall()
	{
		global $APPLICATION, $step, $DB;
		$step = IntVal($step);
		if($step<2)
		{
			$APPLICATION->IncludeAdminFile(GetMessage("ALTASIB_DOCSLIST_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/install/unstep1.php");
		}
		elseif($step==2)
		{
			if (intval($_GET["ALTASIB_DOCSLIST_DEL_BD"]) == 1)
			{
				$this->UnInstallDB();
				$this->UnInstallFiles();
				if(!CModule::IncludeModule("iblock"))die();
				$DB->StartTransaction();
				if(!CIBlockType::Delete('altasib_docs'))
				{
					$DB->Rollback();
					echo 'Delete error!';
				}
				$DB->Commit();
			}
			$this->UnInstallFiles();
			UnRegisterModule("altasib.docslist");
			$APPLICATION->IncludeAdminFile(GetMessage("ALTASIB_DOCSLIST_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/install/unstep2.php");
		}
	}

	function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFilesEx("/bitrix/components/altasib/docslist");
		return true;
	}

	function UnInstallDB($arParams = array())
	{
		global $DB, $DBType, $APPLICATION;
		UnRegisterModule("altasib.docslist");
		$this->errors = false;
		return true;
	}

	function InstallDB()
	{
		global $APPLICATION;
		$this->errors = FALSE;
		RegisterModule("altasib.docslist");
	}

	function InstallDemo()
	{
		global $DB, $DBType, $APPLICATION;
		$this->errors = false;
		CModule::IncludeModule("iblock");
		//add type iblock
		$arLang = array();
		$l = CLanguage::GetList($lby="sort", $lorder="asc");
		while($ar = $l->ExtractFields("l_"))
			$arIBTLang[]=$ar;

		for($i=0; $i<count($arIBTLang); $i++)
		{
			if($arIBTLang[$i]["LID"]=="ru")
				$NAME = GetMessage("ALTASIB_IB_DOCS");
			else
				$NAME = GetMessage("ALTASIB_IB_DOCS_EN");
			$arLang[$arIBTLang[$i]["LID"]] = array("NAME" => $NAME);
		}
		$arFields = array(
			"ID"		=> "altasib_docs",
			"LANG"		=> $arLang,
			"SECTIONS"	=> "Y"
		);

		$obBlocktype = new CIBlockType;
		if($arTypeIB = CIBlockType::GetList(Array(),Array("ID"=>GetMessage("ALTASIB_IB_DOCS_TYPE_EN")))->Fetch())
			$IBLOCK_TYPE_ID = $arTypeIB["ID"];
		else
			$IBLOCK_TYPE_ID = $obBlocktype->Add($arFields);



		if(!$IBLOCK_TYPE_ID)
			echo $obBlocktype->LAST_ERROR;

		$arSites = Array();
		$obSites = CSite::GetList($by1="sort", $order1="desc");
		while($arSite = $obSites->Fetch())
		{
			$arSites[] = $arSite["ID"];
		}

		$arIB = CIBlock::GetList(false,Array("CODE"=>"altasib_docslist"))->Fetch();

		if(!$arIB)
		{
			$ib = new CIBlock;
			$arFields = Array(
				"NAME"				=> GetMessage("ALTASIB_IB_DOCSLIST"),
				"CODE"				=> "altasib_docslist",
				"LIST_PAGE_URL"		=>"",
				"DETAIL_PAGE_URL"	=>"",
				"SITE_ID"			=> $arSites,
				"IBLOCK_TYPE_ID"	=> $IBLOCK_TYPE_ID,
				"WORKFLOW"			=> "N"
			);
			$IBLOCK_ID = $ib->Add($arFields);

			if(!$IBLOCK_ID)
			{
				ShowError (GetMessage("ALTASIB_DOCSLIST_ERR_IB_CREATE").$ib->LAST_ERROR);
				die();
			}
		}

		//add props
		$res = CIBlock::GetList(Array(),Array("CODE"=>"altasib_docslist"),true);
		$ar_res = $res->Fetch();

		$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$ar_res["ID"]));
		while ($arr=$rsProp->Fetch())
			$arPropsCode[] = $arr["CODE"];

		if(empty($arPropsCode) || !is_array($arPropsCode))
			$arPropsCode = array();

		if(!in_array("DOCUMENT_FILE", $arPropsCode))
		{
			$arFields = Array(
			"NAME" => GetMessage("DOCUMENT_FILE"),
			"ACTIVE" => "Y",
			"SORT" => "500",
			"CODE" => "DOC_FILE",
			"PROPERTY_TYPE" => "F",
			"FILE_TYPE" => "doc,docx,txt,rtf,pdf,xls,xlsx,7z,rar,zip",
			"IBLOCK_ID" => $ar_res["ID"],
			"IS_REQUIRED" => "Y",
			"WITH_DESCRIPTION" => "N",
			);

			$ibp = new CIBlockProperty;
			$PropID_answer = $ibp->Add($arFields);
		}

		//Set view mode
		global $DB;
		$formElementID = "form_element_".(string) $ar_res["ID"];
		//Create SQL query
		$arQuery = ":\"edit1--#--".		GetMessage("ALTASIB_DOCSLIST_TAB_ELEMENT")			."--,".
					"--NAME--#--*".		 GetMessage("ALTASIB_DOCSLIST_ELM_NAME")			."--,".
					"--ACTIVE--#--".	GetMessage("ALTASIB_DOCSLIST_ACTIVE_FROM")			."--,".
					"--ACTIVE_FROM--#--".	GetMessage("ALTASIB_DOCSLIST_ACTIVE_FROM")		."--,".
					"--PROPERTY_".(string) $PropID_answer."--#--*".GetMessage("DOCUMENT_FILE") ."--,".
					"--PREVIEW_TEXT--#--".GetMessage("ALTASIB_DOCSLIST_ELM_PREVIEW_TEXT")	."--;".
					"--edit2--#--".			GetMessage("ALTASIB_DOCSLIST_ELM_SECTION")		."--,".
					"--SECTIONS--#--".		GetMessage("ALTASIB_DOCSLIST_ELM_SECTION")		."--;".
					"--edit3--#--".			GetMessage("ALTASIB_DOCSLIST_RIGHTS")			."--,".
					"--RIGHTS--#--".		GetMessage("ALTASIB_DOCSLIST_ELM_RIGHTS")		."--;--\";}','Y')";
		$myQueryLen = (string) mb_strlen($arQuery,"CP1251")-11;
		$myQuery = "insert into b_user_option (category, name, value, common) values ('form', '".$formElementID. //IBlock ID
			"','a:1:{s:4:\"tabs\";s:".$myQueryLen.$arQuery;
		$DB->Query($myQuery);
		CIBlock::SetPermission($ar_res['ID'], Array("1"=>"X", "2"=>"R"));

		if ($_REQUEST["INSTALL_DEMO"])
		{
			//sect
			$sect = new CIBlockSection;
			$arFields = Array(
				"ACTIVE"	=> "Y",
				"IBLOCK_ID"	=> $ar_res["ID"],
				"NAME"		=> GetMessage("ALTASIB_DOCSLIST_TEST_SECTION_1")
			);
			$sectID = $sect->Add($arFields);
			//----

			//demo1
			$el = new CIBlockElement;
			$arLoadProductArray = Array(
				"IBLOCK_SECTION_ID" => $sectID,
				"IBLOCK_ID"			=> $ar_res["ID"],
				"NAME"				=> GetMessage("ALTASIB_DOCSLIST_NAME_ELEMENT_0"),
				"ACTIVE"			=> "Y",
				"ACTIVE_FROM"		=> date('d.m.Y H:i:s', time()),
				"PREVIEW_TEXT"		=> GetMessage("ALTASIB_DOCSLIST_TEXT_ELEMENT_0"),
			);
			$propID = $el->Add($arLoadProductArray);
			$el = new CIBlockElement;
			$arFile[0] = array(
				"VALUE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/install/components/altasib/docslist/files/doc.pdf"),
				"DESCRIPTION" => ""
			);
			$el->SetPropertyValuesEx($propID, $ar_res["ID"], array("DOC_FILE" => $arFile));


			//demo2
			$el = new CIBlockElement;
			$arLoadProductArray = Array(
				"IBLOCK_SECTION_ID"	=> $sectID,
				"IBLOCK_ID"			=> $ar_res["ID"],
				"NAME"				=> GetMessage("ALTASIB_DOCSLIST_NAME_ELEMENT_1"),
				"ACTIVE"			=> "Y",
				"ACTIVE_FROM"		=> date('d.m.Y H:i:s', time()),
				"PREVIEW_TEXT"		=> GetMessage("ALTASIB_DOCSLIST_TEXT_ELEMENT_1"),
			);
			$propID = $el->Add($arLoadProductArray);

			$el = new CIBlockElement;
			$arFile[0] = array(
				"VALUE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/install/components/altasib/docslist/files/doc.docx"),
				"DESCRIPTION" => ""
			);
			$el->SetPropertyValuesEx($propID, $ar_res["ID"], array("DOC_FILE" => $arFile));

			//demo3
			$el = new CIBlockElement;
			$arLoadProductArray = Array(
				"IBLOCK_SECTION_ID" => $sectID,
				"IBLOCK_ID"			=> $ar_res["ID"],
				"NAME"				=> GetMessage("ALTASIB_DOCSLIST_NAME_ELEMENT_2"),
				"ACTIVE"			=> "Y",
				"ACTIVE_FROM"		=> date('d.m.Y H:i:s', time()),
				"PREVIEW_TEXT"		=> GetMessage("ALTASIB_DOCSLIST_TEXT_ELEMENT_2"),
			);
			$propID = $el->Add($arLoadProductArray);

			$el = new CIBlockElement;
			$arFile[0] = array(
				"VALUE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/install/components/altasib/docslist/files/doc.txt"),
				"DESCRIPTION" => ""
			);
			$el->SetPropertyValuesEx($propID, $ar_res["ID"], array("DOC_FILE" => $arFile));

			//sect
			$sect = new CIBlockSection;
			$arFields = Array(
				"ACTIVE"	=> "Y",
				"IBLOCK_ID" => $ar_res["ID"],
				"NAME"		=> GetMessage("ALTASIB_DOCSLIST_TEST_SECTION_2")
			);
			$sectID = $sect->Add($arFields);

			//demo4
			$el = new CIBlockElement;
			$arLoadProductArray = Array(
				"IBLOCK_SECTION_ID" => $sectID,
				"IBLOCK_ID"		 => $ar_res["ID"],
				"NAME"				=> GetMessage("ALTASIB_DOCSLIST_NAME_ELEMENT_3"),
				"ACTIVE"			=> "Y",
				"ACTIVE_FROM"		=> date('d.m.Y H:i:s', time()),
				"PREVIEW_TEXT"		=> GetMessage("ALTASIB_DOCSLIST_TEXT_ELEMENT_3"),
			);
			$propID = $el->Add($arLoadProductArray);

			$el = new CIBlockElement;
			$arFile[0] = array(
				"VALUE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/install/components/altasib/docslist/files/doc.docx"),
				"DESCRIPTION" => ""
			);
			$el->SetPropertyValuesEx($propID, $ar_res["ID"], array("DOC_FILE" => $arFile));

			//demo5
			$el = new CIBlockElement;
			$arLoadProductArray = Array(
				"IBLOCK_SECTION_ID" => $sectID,
				"IBLOCK_ID"		 => $ar_res["ID"],
				"NAME"				=> GetMessage("ALTASIB_DOCSLIST_NAME_ELEMENT_4"),
				"ACTIVE"			=> "Y",
				"ACTIVE_FROM"		=> date('d.m.Y H:i:s', time()),
				"PREVIEW_TEXT"		=> GetMessage("ALTASIB_DOCSLIST_TEXT_ELEMENT_4"),
			);
			$propID = $el->Add($arLoadProductArray);

			$el = new CIBlockElement;
			$arFile[0] = array(
				"VALUE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/install/components/altasib/docslist/files/doc.pdf"),
				"DESCRIPTION" => ""
			);
			$el->SetPropertyValuesEx($propID, $ar_res["ID"], array("DOC_FILE" => $arFile));

			//demo6
			$el = new CIBlockElement;
			$arLoadProductArray = Array(
				"IBLOCK_SECTION_ID" => $sectID,
				"IBLOCK_ID"		 => $ar_res["ID"],
				"NAME"				=> GetMessage("ALTASIB_DOCSLIST_NAME_ELEMENT_5"),
				"ACTIVE"			=> "Y",
				"ACTIVE_FROM"		=> date('d.m.Y H:i:s', time()),
				"PREVIEW_TEXT"		=> GetMessage("ALTASIB_DOCSLIST_TEXT_ELEMENT_5"),
			);
			$propID = $el->Add($arLoadProductArray);

			$el = new CIBlockElement;
			$arFile[0] = array(
				"VALUE" => CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/install/components/altasib/docslist/files/doc.txt"),
				"DESCRIPTION" => ""
			);
			$el->SetPropertyValuesEx($propID, $ar_res["ID"], array("DOC_FILE" => $arFile));
		}
	}
}
?>
