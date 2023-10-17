<?php

namespace Redsign\Master;

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\ModuleManager;

class AdminUtils {

    const MODULE_ID = "redsign.master";

    public static function getEditPublicFileLink($file, $siteId) {
        return "javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?site=".$siteId."&bxpublic=Y&from=includefile&path=".$file."&lang=".LANGUAGE_ID."','width':'1200','height':'500'}).Show();";
    }

    public static function showOptions($arOptions, $arSite) {
        foreach ($arOptions as $arOption) {
            self::showOptionRow($arOption, $arSite);
        }
    }
    
    public static function getSiteTabs($arSites) {
        $arTabs = array();

        $rand = \Bitrix\Main\Security\Random::getString(3);

        foreach ($arSites as $arSite) {
            $arTabs[] = array(
                'DIV' => 'redsign_media_sub_'.$arSite['LID'].'_'.$rand,
                'TAB' => '('.$arSite['LID'].') '.$arSite["NAME"],
                'TITLE' => ''
            );
        }

        return $arTabs;
    }

    public static function showOptionRow($arOption, $arSite) {
        if (isset($arOption['SHOW']) && !$arOption['IS_SHOW']) {
            return;
        }

        switch ($arOption['TYPE']) {
            case 'HEADER':
                ?><tr class="heading">
                    <td colspan="2" style="background: transparent;"><?=$arOption['NAME']?></td>
                </tr><?
                break;

            case 'SELECT':
                $currentVal = (isset($arOption['ID']) && strlen($arOption['ID']) ? Option::get(self::MODULE_ID, $arOption['ID'], '', $arSite['LID']) : '');
                ?><tr><td><?=$arOption['NAME']?></td>
                <td><select name="<?=$arOption['CODE']?>_<?=$arSite['LID']?>">
                    <?php
                    if (isset($arOption['VALUES']) && is_array($arOption['VALUES'])):
                        foreach ($arOption['VALUES'] as $value):
                    ?>
                    <option value="<?=$value?>" <?php if ($value == $currentVal) echo 'selected'; ?>><?=$value?></option>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </select></td></tr><?php
                break;

            case 'CHECKBOX':
                $currentVal = (isset($arOption['ID']) && strlen($arOption['ID']) ? Option::get(self::MODULE_ID, $arOption['ID'], '', $arSite['LID']) : 'N');
                ?><tr>
                    <td><label for="<?=$arOption['CODE']?>_<?=$arSite['LID']?>"><?=$arOption['NAME']?></label></td>
                    <td>
                        <input type="checkbox" value="Y" name="<?=$arOption['CODE']?>_<?=$arSite['LID']?>" id="<?=$arOption['CODE']?>_<?=$arSite['LID']?>" <?php if ($currentVal == "Y") echo "checked";?>>
                        <?php if (isset($arOption['INCLUDE_FILE'])): ?>
                        &nbsp;
                        <a class="adm-btn" href="<?=self::getEditPublicFileLink($arSite['DIR'].$arOption['INCLUDE_FILE'], $siteId)?>" title="<?=Loc::getMessage('RS.EDIT_FILE')?>">Редактировать</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php
                break;

            case 'INPUT':
                $currentVal = (isset($arOption['ID']) && strlen($arOption['ID']) ? Option::get(self::MODULE_ID, $arOption['ID'], '', $arSite['LID']) : '');
                ?><tr>
                    <td align="right" width="50%"><?=$arOption['NAME']?></td>
                    <td width="50%"><input type="text" size="40" value="<?=$currentVal?>" name="<?=$arOption['CODE']?>_<?= $arSite["LID"] ?>" id="<?=$arOption['CODE']?>_<?=$arSite['LID']?>"></td>
                </tr>
                <?php
                break;

            case 'LOCATION':
                if (ModuleManager::isModuleInstalled('sale')) {
                    $currentVal = (isset($arOption['ID']) && strlen($arOption['ID']) ? Option::get(self::MODULE_ID, $arOption['ID'], '', $arSite['LID']) : '');
                    global $APPLICATION;
                    ?><tr>
                      <td align="right" width="50%"><?=$arOption['NAME']?></td>
                      <td width="50%">
                          <?php $APPLICATION->IncludeComponent(
                              "bitrix:sale.location.selector.search",
                              "",
                              Array(
                                  "COMPONENT_TEMPLATE" => ".default",
                                  "ID" => '',
                                  "CODE" => $currentVal,
                                  "INPUT_NAME" => $arOption['CODE']."_".$arSite['LID'],
                                  "PROVIDE_LINK_BY" => "code",
                                  "JS_CONTROL_GLOBAL_ID" => "",
                                  "FILTER_BY_SITE" => "Y",
                                  "SHOW_DEFAULT_LOCATIONS" => "Y",
                                  "CACHE_TYPE" => "A",
                                  "CACHE_TIME" => "36000000",
                                  "FILTER_SITE_ID" => $arSite['LID'],
                                  "INITIALIZE_BY_GLOBAL_EVENT" => "",
                                  "SUPPRESS_ERRORS" => "N"
                              )
                          ); ?>
                      </td>
                    </tr>
                    <?php
                }
                break;

            case 'INCLUDE':
                ?><tr>
                    <td><?=$arOption['NAME']?></td>
                    <td><a class="adm-btn" href="<?=self::getEditPublicFileLink($arSite['DIR'].$arOption['INCLUDE_FILE'], $siteId)?>" title="<?=Loc::getMessage('RS.EDIT_FILE')?>"><?=Loc::getMessage('RS.EDIT_FILE')?></a></td>
                </tr><?
                break;

            default:
                break;
        }
    }

}
