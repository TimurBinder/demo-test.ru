<?
/**
 * Company developer: ALTASIB
 * Developer: Konstantin Volodin
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2016 ALTASIB
 */


global $DBType;
IncludeModuleLangFile(__FILE__);

if(method_exists(CModule, "AddAutoloadClasses"))
{
	CModule::AddAutoloadClasses(
		"altasib.docslist",
		$arClassesList
	);
}
else
{
	foreach ($arClassesList as $sClassName => $sClassFile)
	{
		require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/altasib.docslist/".$sClassFile);
	}
}
?>