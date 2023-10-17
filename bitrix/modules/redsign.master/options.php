<?php
use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\ModuleManager;
use \Bitrix\Main\EventManager;
use \Redsign\Master\AdminUtils;

Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/options.php');
Loc::loadMessages(__FILE__);

\Bitrix\Main\Loader::includeModule('redsign.master');

$app = Application::getInstance();
$context = $app->getContext();
$request = $context->getRequest();

$moduleId = 'redsign.master';

$siteIterator = \Bitrix\Main\SiteTable::getList();
$arSites = $siteIterator->fetchAll();

$arHeaders = [
    'type1',
    'type2',
    'type3',
    'type4',
    'type5',
    'type6',
    'custom'
];

$arFooters = [
    'auto',
    'type1',
    'type2',
    'custom'
];

$isSale = (ModuleManager::isModuleInstalled('catalog') && ModuleManager::isModuleInstalled('sale'));

$arCommonOptions = [
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_CRM_FORMS'),
        'TYPE' => 'HEADER'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_CRM_FORMS_SCRIPT'),
        'TYPE' => 'INPUT',
        'ID' => 'b24_crm_form_script',
        'CODE' => 'B24_CRM_FORM_SCRIPT'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_LOCATION'),
        'TYPE' => 'HEADER',
        'IS_SHOW' => $isSale
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_LOCATION_DEFAULT'),
        'TYPE' => 'LOCATION',
        'ID' => 'default_location_code',
        'CODE' => 'DEFAULT_LOCATION_CODE',
        'IS_SHOW' => $isSale
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_LOCATION_SET'),
        'TYPE' => 'CHECKBOX',
        'ID' => 'set_location_sale_order_ajax',
        'CODE' => 'SET_LOCATION_SALE_ORDER_AJAX',
        'IS_SHOW' => $isSale
    ]
];

$arHeadOptions = [
    [
        'NAME' => Loc::getMessage('RS.MASTER_OPTIONS_COMMON'),
        'TYPE' => 'HEADER',
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_HEADER_TYPE'),
        'TYPE' => 'SELECT',
        'ID' => 'head_type',
        'CODE' => 'HEAD_TYPE',
        'VALUES' => $arHeaders
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_ASK'),
        'TYPE' => 'CHECKBOX',
        'ID' => 'head_settings_show_ask_button',
        'CODE' => 'HEAD_SETTINGS_SHOW_ASK_BUTTON'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_CONTACTS_PATH'),
        'TYPE' => 'INPUT',
        'ID' => 'head_settings_contacts_path',
        'CODE' => 'HEAD_SETTINGS_CONTACTS_PATH'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_LOGO'),
        'TYPE' => 'INCLUDE',
        'INCLUDE_FILE' => 'include/header/logo.php'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_SLOGAN'),
        'TYPE' => 'INCLUDE',
        'INCLUDE_FILE' => 'include/header/slogan.php'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_TOPLINE'),
        'TYPE' => 'HEADER'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_TOPLINE_SHOW'),
        'TYPE' => 'CHECKBOX',
        'ID' => 'head_settings_show_topline',
        'CODE' => 'HEAD_SETTINGS_SHOW_TOPLINE',
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_TOPLINE_DETECT_LOCATION'),
        'TYPE' => 'CHECKBOX',
        'ID' => 'head_settings_topline_location',
        'CODE' => 'HEAD_SETTINGS_TOPLINE_LOCATION',
        'IS_SHOW' => $isSale
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_TOPLINE_SHEDULE'),
        'TYPE' => 'CHECKBOX',
        'ID' => 'head_settings_topline_shedule',
        'CODE' => 'HEAD_SETTINGS_TOPLINE_SHEDULE',
        'INCLUDE_FILE' => 'include/header/topline_shedule.php'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_TOPLINE_RECALL'),
        'TYPE' => 'CHECKBOX',
        'ID' => 'head_settings_topline_recall',
        'CODE' => 'HEAD_SETTINGS_TOPLINE_RECALL'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_TOPLINE_AUTH'),
        'TYPE' => 'CHECKBOX',
        'ID' => 'head_settings_topline_auth',
        'CODE' => 'HEAD_SETTINGS_TOPLINE_AUTH'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_TOPLINE_FAVORITE'),
        'TYPE' => 'CHECKBOX',
        'ID' => 'head_settings_topline_favorite',
        'CODE' => 'HEAD_SETTINGS_TOPLINE_FAVORITE'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_TOPLINE_COMPARE'),
        'TYPE' => 'CHECKBOX',
        'ID' => 'head_settings_topline_compare',
        'CODE' => 'HEAD_SETTINGS_TOPLINE_COMPARE'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD_SETTINGS_TOPLINE_CART'),
        'TYPE' => 'CHECKBOX',
        'ID' => 'head_settings_topline_cart',
        'CODE' => 'HEAD_SETTINGS_TOPLINE_CART'
    ]
];

$arFootOptions = [
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_FOOTER_TYPE'),
        'TYPE' => 'SELECT',
        'ID' => 'foot_type',
        'CODE' => 'FOOT_TYPE',
        'VALUES' => $arFooters
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_FOOT_SETTINGS_PHONES'),
        'TYPE' => 'INCLUDE',
        'INCLUDE_FILE' => 'include/footer/phones.php'
    ],
    [
        'NAME' => Loc::getMessage('RS.MASTER.OPTIONS_FOOT_SETTINGS_CONTACTS'),
        'TYPE' => 'INCLUDE',
        'INCLUDE_FILE' => 'include/footer/contacts.php'
    ],
];
if ($request->isPost() && check_bitrix_sessid()) {
    foreach ($arSites as $arSite) {
        foreach (array_merge($arCommonOptions, $arHeadOptions, $arFootOptions) as $arOption) {
            if (!empty($arOption['CODE']) && !empty($arOption['ID'])) {
                $val = $request->getPost($arOption['CODE'].'_'.$arSite['LID']);


                Option::set($moduleId, $arOption['ID'], $request->getPost($arOption['CODE'].'_'.$arSite['LID']), $arSite['LID']);
            }

            if ($arOption['ID'] == 'set_location_sale_order_ajax') {

				if ($request->getPost($arOption['CODE'].'_'.$arSite['LID']) == 'Y') {
					EventManager::getInstance()->registerEventHandler(
						'sale',
						'OnSaleComponentOrderProperties',
						$moduleId,
						'Redsign\\Master\\Location',
						'OnSaleComponentOrderSetLocation'
					);
				} else {
					EventManager::getInstance()->unRegisterEventHandler(
						'sale',
						'OnSaleComponentOrderProperties',
						$moduleId,
						'Redsign\\Master\\Location',
						'OnSaleComponentOrderSetLocation'
					);
				}
            }
        }
    }
}

$arTabs = array();
$arTabs[] = array(
    'DIV' => 'redsign_master',
    'TAB' => Loc::getMessage('RS.MASTER.TAB_NAME_SETTINGS'),
    'ICON' => '',
    'TITLE' => Loc::getMessage('RS.MASTER.TAB_TITLE_SETTINGS')
);
$arTabs[] = array(
    'DIV' => 'redsign_master_head_settings',
    'TAB' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD'),
    'ICON' => '',
    'TITLE' => Loc::getMessage('RS.MASTER.OPTIONS_HEAD')
);
$arTabs[] = array(
    'DIV' => 'redsign_master_foot_settings',
    'TAB' => Loc::getMessage('RS.MASTER.OPTIONS_FOOT'),
    'ICON' => '',
    'TITLE' => Loc::getMessage('RS.MASTER.OPTIONS_FOOT'),
);
$tabControl = new CAdminTabControl('tabControl', $arTabs);

$tabControl->Begin();
?>
<form method="post" name="rsmaster_option" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
    <?=bitrix_sessid_post();?>
    <?php $tabControl->BeginNextTab(); ?>
    <tr>
        <td valign="top" colspan="2">
            <?php
            $tabs = AdminUtils::getSiteTabs($arSites);
            $tabSiteControl = new CAdminViewTabControl("subTabControl", $tabs);
            $tabSiteControl->Begin();
            foreach ($arSites as $arSite):
                $tabSiteControl->BeginNextTab();
            ?>
            <table width="75%" align="center">
                <?php AdminUtils::showOptions($arCommonOptions, $arSite); ?>
            </table>
            <?php
            endforeach;
            $tabSiteControl->End();
            ?>
        </td>
    </tr>

    <?php $tabControl->BeginNextTab();  ?>
    <tr>
        <td valign="top" colspan="2">
            <?php
            $tabs = AdminUtils::getSiteTabs($arSites);
            $tabSiteControl = new CAdminViewTabControl("subTabControl2", $tabs);
            $tabSiteControl->Begin();
            foreach ($arSites as $arSite):
                $tabSiteControl->BeginNextTab();
            ?>
            <table width="75%" align="center">
                <?php AdminUtils::showOptions($arHeadOptions, $arSite); ?>
            </table>
            <?php endforeach; ?>
            <?php $tabSiteControl->End(); ?>
        </td>
    </tr>

    <?php $tabControl->BeginNextTab();  ?>
    <tr>
        <td valign="top" colspan="2">
            <?php
            $tabs = AdminUtils::getSiteTabs($arSites);
            $tabSiteControl = new CAdminViewTabControl("subTabControl3", $tabs);
            $tabSiteControl->Begin();
            foreach ($arSites as $arSite):
                $tabSiteControl->BeginNextTab();
            ?>
            <table width="75%" align="center">
                <?php AdminUtils::showOptions($arFootOptions, $arSite); ?>
            </table>
            <?php endforeach; ?>
            <?php $tabSiteControl->End(); ?>
        </td>
    </tr>
<?php
$tabControl->Buttons(array());
$tabControl->End();
?>


</form>
