<?
/**
 * Company developer: ALTASIB
 * Developer: Konstantin Volodin
 * Site: http://www.altasib.ru
 * E-mail: dev@altasib.ru
 * @copyright (c) 2006-2016 ALTASIB
 */


IncludeModuleLangFile(__FILE__);
global $errors;
?>

<form action="<?=$APPLICATION->GetCurPage()?>" name="form1">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?echo LANG?>">
	<input type="hidden" name="id" value="altasib.docslist">
	<input type="hidden" name="install" value="Y">
	<input type="hidden" name="step" value="2">

	<input type="checkbox" name="INSTALL_IB" id="INSTALL_IB" value="Y" checked onclick="if(!this.checked)document.getElementById('INSTALL_DEMO').checked = false" />
	<label for="INSTALL_IB"><?=GetMessage('INSTALL_DOCSLIST_IB')?></label><br/>

	<input type="checkbox" name="INSTALL_DEMO" id="INSTALL_DEMO" value="Y" checked onclick="if(this.checked)document.getElementById('INSTALL_IB').checked = true" />
		<label for="INSTALL_DEMO"><?=GetMessage('INSTALL_DOCSLIST_DEMO')?></label><br/>
	<br /><br />
	<input type="submit" name="inst" value="<?=GetMessage('INSTALL_DOCSLIST_SUBMIT')?>" />
</form>