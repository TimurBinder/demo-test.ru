<pre>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

	if(CModule::IncludeModule("iblock")) 
	{ 
        $arPrice = array();
		$arSelect1 = Array("ID", "NAME", "PROPERTY_PRICE_KIND");
		$arFilter1 = Array("IBLOCK_ID"=>3, "ACTIVE"=>"Y", "ID"=>383);
        $res1 = CIBlockElement::GetList(Array(), $arFilter1, false, Array(), $arSelect1);
        while($ob1 = $res1->GetNextElement())
        {
            $arFields1 = $ob1->GetFields();
            $arPrice[] = $arFields1["PROPERTY_PRICE_KIND_VALUE"];
        }
 }
 
d($arPrice);
?>
</pre>