<?php

require_once(dirname(__FILE__)."/include.php"); 

use Bitrix\Main\Localization\Loc;
Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/options.php');
Loc::loadMessages(__FILE__);
use Bitrix\Main\Config\Option;

if (!$USER->IsAdmin()) {
    return;
}

if ($REQUEST_METHOD == 'POST' && strlen($Update.$RestoreDefaults)>0 && check_bitrix_sessid())
{
	if (strlen($RestoreDefaults) > 0)
	{
		$arOptionDefaults = array(
			DEACTIVATE_ELEMENT_OPT=>"N",
			TRANSLITERATE_CODE_OPT=>"N",
			SAVE_SOURCE_ID_OPT=>"N",
			IBLOCKS_SAVED_OPT=>serialize(array()),
		);
		foreach ($arOptionDefaults as $paramName=>$paramVal)
		{
			Option::set(MODULE_ID, $paramName, $paramVal);
		}

		/*$arOptionDefaults = Option::getDefaults(MODULE_ID);
		foreach ($arOptionDefaults as $paramName=>$paramVal)
		{
			Option::set(MODULE_ID, $paramName, $paramVal);
		}*/
	}
	else
	{
		$DEACTIVATE_ELEMENT_OPT_VAR = DEACTIVATE_ELEMENT_OPT;
		$$DEACTIVATE_ELEMENT_OPT_VAR = (isset($$DEACTIVATE_ELEMENT_OPT_VAR) && $$DEACTIVATE_ELEMENT_OPT_VAR === 'Y') ? 'Y' : 'N';
		Option::set(MODULE_ID, DEACTIVATE_ELEMENT_OPT, $$DEACTIVATE_ELEMENT_OPT_VAR);

		$TRANSLITERATE_CODE_OPT_VAR = TRANSLITERATE_CODE_OPT;
		$$TRANSLITERATE_CODE_OPT_VAR = (isset($$TRANSLITERATE_CODE_OPT_VAR) && $$TRANSLITERATE_CODE_OPT_VAR === 'Y') ? 'Y' : 'N';
		Option::set(MODULE_ID, TRANSLITERATE_CODE_OPT, $$TRANSLITERATE_CODE_OPT_VAR);

		$SAVE_SOURCE_ID_OPT_VAR = SAVE_SOURCE_ID_OPT;
		$$SAVE_SOURCE_ID_OPT_VAR = (isset($$SAVE_SOURCE_ID_OPT_VAR) && $$SAVE_SOURCE_ID_OPT_VAR === 'Y') ? 'Y' : 'N';
		Option::set(MODULE_ID, SAVE_SOURCE_ID_OPT, $$SAVE_SOURCE_ID_OPT_VAR);

		$IBLOCKS_SAVED_OPT_ARR = IBLOCKS_SAVED_OPT;
		$$IBLOCKS_SAVED_OPT_ARR = is_array($$IBLOCKS_SAVED_OPT_ARR) ? $$IBLOCKS_SAVED_OPT_ARR : array();

		// checks & adds SAVED_SOURCE_ID_PROP in iblocks (begin)

		// gets existing props
		CModule::IncludeModule('iblock');
		$arIblocks = array();
		$propertyIterator = \Bitrix\Iblock\PropertyTable::getList(array(
			'select' => array('ID', 'IBLOCK_ID'),
			'filter' => array('=CODE' => SAVED_SOURCE_ID_PROP, '=ACTIVE' => 'Y'),
			));
		while ($property = $propertyIterator->fetch())
		{
			$arIblocks[$property['ID']] = $property['IBLOCK_ID'];
		}
		unset($property, $propertyIterator);

		// diffs iblocks with ALREADY created SAVED_SOURCE_ID_PROP & SELECTED in module settings
		$arIblocksWithoutProp = array_diff($$IBLOCKS_SAVED_OPT_ARR, $arIblocks);

		// if some iblocks need to have SAVED_SOURCE_ID_PROP
		if (count($arIblocksWithoutProp)>0)
		{
			// creates SAVED_SOURCE_ID_PROP for iblocks

			foreach($arIblocksWithoutProp as $arIblockCur)
			{
				$addResult = \Bitrix\Iblock\PropertyTable::Add(array(
					'IBLOCK_ID' => $arIblockCur,
					'NAME' => SAVED_SOURCE_ID_PROP,
					'SORT' => SORT_FIELD_DEFAULT_VALUE,
					'CODE' => SAVED_SOURCE_ID_PROP,
					'PROPERTY_TYPE' => 'E',
					'IS_REQUIRED' => 'N',
					));
				if ($addResult->getId()<=0)
				{
					CAdminMessage::ShowMessage(Loc::getMessage("IBLOCK_SELECT_IMPOSSIBLE").$arIblockCur);
					$key_tmp = array_search($arIblockCur, $$IBLOCKS_SAVED_OPT_ARR);
					if (isset($$IBLOCKS_SAVED_OPT_ARR[$key_tmp]) && $$IBLOCKS_SAVED_OPT_ARR[$key_tmp] !== false) unset($$IBLOCKS_SAVED_OPT_ARR[$key_tmp]);
				}
			}			
		}

		// checks & adds for SAVED_SOURCE_ID_PROP (end)

		Option::set(MODULE_ID, IBLOCKS_SAVED_OPT, serialize($$IBLOCKS_SAVED_OPT_ARR));
	}
}

$tabControl = new CAdminTabControl('tabControl', array(
	array(
	'DIV' => 'edit1', 
	'TAB' => Loc::getMessage('IBLOCKS_TAB_SET'), 
	'ICON' => 'ib_settings', 
	'TITLE' => Loc::getMessage('IBLOCKS_TAB_TITLE_SET')
	),
	array(
	'DIV' => 'edit2', 
	'TAB' => Loc::getMessage('ETC_TAB_SET'), 
	'ICON' => 'ib_settings', 
	'TITLE' => Loc::getMessage('ETC_TAB_TITLE_SET')
	),
));

$tabControl->Begin();

?>

<script language="JavaScript">
function Toggle(src, dest) {
	var checkboxes = document.getElementsByName(dest);
	for (var i=0;i<checkboxes.length;i++) {
		if (!checkboxes[i].disabled) checkboxes[i].checked = src.checked;
	}
}
function Toggle2(src, control, dest) {
	var checkboxes = document.getElementsByName(dest);
	if (src.checked)
	{
		document.getElementById(control).disabled = false;
		for (var i=0;i<checkboxes.length;i++) {
			if (checkboxes[i].disabled) checkboxes[i].disabled = false;
		}
	}
	else
	{
		document.getElementById(control).disabled = true;
		document.getElementById(control).checked = false;
		for (var i=0;i<checkboxes.length;i++) {
			checkboxes[i].disabled = true;
			checkboxes[i].checked = false;
		}
	}
}
function ToggleCheck(src, dest) {
	if (document.getElementById(src).checked)
	{		
		var result = false;
		var checkboxes = document.getElementsByName(dest);
		for (var i=0;i<checkboxes.length;i++) {
			if (checkboxes[i].checked)
			{
				result = true;
				break;
			}
		}
		if (!result)
		{
			alert('<?=Loc::getMessage('SELECT_IBLOCK_PLEASE')?>');
			return false;
		}
	}
	return true;
}
function RestoreCheck(src)
{
	var result = false;
	result = confirm('<?=AddSlashes(Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>');
	if (result)
	{
		document.getElementById(src).checked = false;
	}
	return result; 
}
</script>

<form method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode(MODULE_ID)?>&amp;lang=<?=LANGUAGE_ID?>" onSubmit="return ToggleCheck('<?=SAVE_SOURCE_ID_OPT?>', '<?=IBLOCKS_SAVED_OPT?>[]')">
	<?=bitrix_sessid_post()?>

	<?
	$tabControl->BeginNextTab();
	?>

	<tr>
		<td width="40%"><?=Loc::getMessage(SAVE_SOURCE_ID_OPT)?>:</td>
		<td width="60%">
			<input type="checkbox" name="<?=SAVE_SOURCE_ID_OPT?>" id="<?=SAVE_SOURCE_ID_OPT?>" value="Y"<?=("Y" === Option::get(MODULE_ID, SAVE_SOURCE_ID_OPT, 'N') ? ' checked="checked"': '')?> onClick="Toggle2(this, 'SELECT_ALL_IBLOCKS', '<?=IBLOCKS_SAVED_OPT?>[]')">
		</td>
	</tr>

	<tr>
		<td></td>
		<td>				
			<div class="adm-info-message-wrap" align="left">
				<div class="adm-info-message">
					<?=Loc::getMessage('SAVE_SOURCE_ID_DESCR')?>
				</div>
			</div>
		</td>
	</tr>

	<tr class="heading">
		<td colspan="2"><b><?=Loc::getMessage('IBLOCKS_LIST')?></b></td>
	</tr>
	<?
	$IBLOCKS_SAVED_SELECTED = unserialize(Option::get(MODULE_ID, IBLOCKS_SAVED_OPT, ""));
	if(!is_array($IBLOCKS_SAVED_SELECTED)) $IBLOCKS_SAVED_SELECTED = array();
	CModule::IncludeModule('iblock');
	$propertyIterator = \Bitrix\Iblock\IblockTable::getList(array(
		'select' => array('ID', 'NAME'),
		'filter' => array('=ACTIVE' => 'Y'),
		'order'  => array('ID' => 'ASC'),
		));

	// returns attr checked for SELECT_ALL_IBLOCKS if selected all iblocks
	$SELECT_ALL_IBLOCKS_CHECKED_OR_NOT = ($propertyIterator->getSelectedRowsCount() == count($IBLOCKS_SAVED_SELECTED)) ? ' checked="checked"' : '';
	?>
	<tr>
		<td width="40%"><?=Loc::getMessage('SELECT_ALL_IBLOCKS')?>:</td>
		<td width="60%">
			<input type="checkbox" name="SELECT_ALL_IBLOCKS" id="SELECT_ALL_IBLOCKS" value="Y"<?=$SELECT_ALL_IBLOCKS_CHECKED_OR_NOT?> onClick="Toggle(this, '<?=IBLOCKS_SAVED_OPT?>[]')"<?=("Y" === Option::get(MODULE_ID, SAVE_SOURCE_ID_OPT, 'N') ? '' : ' disabled')?>>
		</td>
	</tr>
	<?
	while ($property = $propertyIterator->fetch())
	{
	?>
	<tr>
		<td width="40%"><?=$property['NAME'].' ['.$property['ID'].']'?>:</td>
		<td width="60%">
			<input type="checkbox" name="<?=IBLOCKS_SAVED_OPT?>[]" value="<?=$property['ID']?>"<?=( in_array($property['ID'], $IBLOCKS_SAVED_SELECTED) ? ' checked="checked"': '')?><?=("Y" === Option::get(MODULE_ID, SAVE_SOURCE_ID_OPT, 'N') ? '' : ' disabled')?>>
		</td>
	</tr>
	<?
	}
	unset($property, $propertyIterator);
	?>

	<?$tabControl->BeginNextTab()?>

	<tr>
		<td width="40%"><?=Loc::getMessage(DEACTIVATE_ELEMENT_OPT)?>:</td>
		<td width="60%">
			<input type="checkbox" name="<?=DEACTIVATE_ELEMENT_OPT?>" value="Y"<?=("Y" === Option::get(MODULE_ID, DEACTIVATE_ELEMENT_OPT, 'N') ? ' checked="checked"': '')?>>
		</td>
	</tr>

	<tr>
		<td width="40%"><?=Loc::getMessage(TRANSLITERATE_CODE_OPT)?>:</td>
		<td width="60%">
			<input type="checkbox" name="<?=TRANSLITERATE_CODE_OPT?>" value="Y"<?=("Y" === Option::get(MODULE_ID, TRANSLITERATE_CODE_OPT, 'N') ? ' checked="checked"': '')?>>
		</td>
	</tr>

	<?$tabControl->Buttons()?>

	<input type="submit" name="Update" id="Update" value="<?=Loc::getMessage("MAIN_SAVE")?>" title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
	<?if(strlen($_REQUEST["back_url_settings"])>0):?>
		<input type="button" name="Cancel" value="<?=Loc::getMessage("MAIN_OPT_CANCEL")?>" title="<?=Loc::getMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?=htmlspecialcharsbx(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST["back_url_settings"])?>">
	<?endif?>
	<input type="submit" name="RestoreDefaults" title="<?=Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="RestoreCheck('<?=SAVE_SOURCE_ID_OPT?>')" value="<?=Loc::getMessage("MAIN_RESTORE_DEFAULTS")?>">

	<?$tabControl->End()?>
</form>
