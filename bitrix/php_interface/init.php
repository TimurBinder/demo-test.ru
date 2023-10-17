<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); 
//$GLOBALS['send_mail_to'] = "get@denius.biz";

//Функция отладки
function d($a)
{
    echo "Debug: <br />";
    echo "<pre>";
    print_r($a);
    echo "</pre>";
}


//Отправка email о новом заказе
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("PostMessageNewOrder", "OnAfterIBlockElementAddHandler"));

class PostMessageNewOrder
{
    // создаем обработчик события "OnAfterIBlockElementAdd"
    public static function OnAfterIBlockElementAddHandler(&$arFields)
    {
        //Форма обратной связи
        if ($arFields["IBLOCK_ID"] == 28)
        {
            $arSend['EMAIL'] = $arFields['PROPERTY_VALUES']['EMAIL'];
            $arSend['NAME'] = $arFields['PROPERTY_VALUES']['NAME'];
			$arSend['PHONE'] = $arFields['PROPERTY_VALUES']['PHONE_NUMBER'];
			$arSend['COMPANY_NAME'] = $arFields['PROPERTY_VALUES']['COMPANY_NAME'];
			$arSend['COMMENT'] = $arFields['PROPERTY_VALUES']['COMMENT'];
			$arSend['ORDER_ID'] = $arFields['ID'];
            $arSend['ORDER_LIST'] = $arFields['PREVIEW_TEXT'];
            CEvent::Send('NEW_ORDER', SITE_ID, $arSend, 'Y', 21);
        }
    }
}


AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("MyClass", "OnAfterIBlockElementUpdateHandler"));

class MyClass
{
    // создаем обработчик события "OnAfterIBlockElementUpdate"
    public static function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        
        CModule::IncludeModule( 'iblock' );
        $file = false;
        
        $arSelect1 = Array("ID", "NAME", "PROPERTY_XLSX");
        $arFilter1 = Array("IBLOCK_ID"=>3, "ID"=>$arFields['ID']);
        $res1 = CIBlockElement::GetList(Array(), $arFilter1, false, Array("nPageSize"=>5000), $arSelect1);
        while($ob1 = $res1->GetNextElement())
        {
            $arFields1 = $ob1->GetFields();
            // строка, которую будем записывать
            if($arFields1["PROPERTY_XLSX_VALUE"]){
                $file = CFile::getPath($arFields1["PROPERTY_XLSX_VALUE"]);
            }
        }
        

        if($file){
            $file = $_SERVER["DOCUMENT_ROOT"].$file;
            require_once $_SERVER["DOCUMENT_ROOT"]."/spreadsheet-reader/php-excel-reader/excel_reader2.php";
            require_once $_SERVER["DOCUMENT_ROOT"]."/spreadsheet-reader/SpreadsheetReader.php"; 
            
            $ar1 = array();
            $ar2 = array();
            
            $Spreadsheet = new SpreadsheetReader($file);
            
            $Sheets = $Spreadsheet -> Sheets();
            
            foreach ($Sheets as $Index => $Name)
            {
            
            	$Spreadsheet -> ChangeSheet($Index);
            	foreach ($Spreadsheet as $Key => $Row)
            	{
                    if($Key==0)continue;
            	    $ar1[] = $Row[0];
                    $ar2[] = $Row[1];
            	}
            }
            //if(sizeof($ar1) && sizeof($ar2))
            CIBlockElement::SetPropertyValueCode($arFields['ID'], "KIND", $ar1);
            CIBlockElement::SetPropertyValueCode($arFields['ID'], "PRICE_KIND", $ar2);
        }
    }
}
?>