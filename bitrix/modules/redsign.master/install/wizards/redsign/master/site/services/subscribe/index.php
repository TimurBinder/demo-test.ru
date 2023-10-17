<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if($arSite = $dbSite -> Fetch())
	$lang = $arSite["LANGUAGE_ID"];


if(CModule::IncludeModule('subscribe'))
{
	$templates_dir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/php_interface/subscribe/templates";
	$template = $templates_dir."/store_news_".WIZARD_SITE_ID;
	//Copy template from module if where was no template
	if(!file_exists($template))
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/subscribe/install/php_interface/subscribe/templates/news", $template, false, true);
		$fname = $template."/template.php";
		if(file_exists($fname) && is_file($fname) && ($fh = fopen($fname, "rb")))
		{
			$php_source = fread($fh, filesize($fname));
			$php_source = preg_replace("#([\"'])(SITE_ID)(\\1)(\\s*=>\s*)([\"'])(.*?)(\\5)#", "\\1\\2\\3\\4\\5".WIZARD_SITE_ID."\\7", $php_source);
			$php_source = str_replace("Windows-1251", $arSite["CHARSET"], $php_source);
			$php_source = str_replace("Hello!", GetMessage("SUBSCR_NAME_1"), $php_source);
			$php_source = str_replace("<P>Best Regards!</P>", "", $php_source);
			fclose($fh);
			$fh = fopen($fname, "wb");
			if($fh)
			{
				fwrite($fh, $php_source);
				fclose($fh);
			}
		}
	}

	$rsRubric = CRubric::GetList(array(), array(
		"LID" => WIZARD_SITE_ID,
	));
	if(!$rsRubric->Fetch())
	{
		//Database actions
        $arRubrics = array(
            array(
                "ACTIVE"	=> "Y",
                "NAME"		=> GetMessage("SUBSCR_NAME_1"),
                "SORT"		=> 100,
                "DESCRIPTION"	=> GetMessage("SUBSCR_DESC_1"),
                "LID"		=> WIZARD_SITE_ID,
                "AUTO"		=> "Y",
                "DAYS_OF_MONTH"	=> "",
                "DAYS_OF_WEEK"	=> "1,2,3,4,5,6,7",  
                "TIMES_OF_DAY"	=> "05:00",
                "TEMPLATE"	=> substr($template, strlen($_SERVER["DOCUMENT_ROOT"]."/")),
                "VISIBLE"	=> "Y",
                "FROM_FIELD"	=> COption::GetOptionString("main", "email_from", "info@ourtestsite.com"),
                "LAST_EXECUTED"	=> ConvertTimeStamp(false, "FULL"),
            ),
            array(
                "ACTIVE"	=> "Y",
                "NAME"		=> GetMessage("SUBSCR_NAME_2"),
                "SORT"		=> 100,
                "DESCRIPTION"	=> GetMessage("SUBSCR_DESC_2"),
                "LID"		=> WIZARD_SITE_ID,
                "AUTO"		=> "Y",
                "DAYS_OF_MONTH"	=> "",
                "DAYS_OF_WEEK"	=> "1,2,3,4,5,6,7",  
                "TIMES_OF_DAY"	=> "05:00",
                "TEMPLATE"	=> substr($template, strlen($_SERVER["DOCUMENT_ROOT"]."/")),
                "VISIBLE"	=> "Y",
                "FROM_FIELD"	=> COption::GetOptionString("main", "email_from", "info@ourtestsite.com"),
                "LAST_EXECUTED"	=> ConvertTimeStamp(false, "FULL"),
            ),
        );
        
		$obRubric = new CRubric;
        foreach($arRubrics as $arFields){
            $ID = $obRubric->Add($arFields);
        }

	}
	COption::SetOptionString('subscribe', 'subscribe_section', '#SITE_DIR#personal/subscribe/');
}