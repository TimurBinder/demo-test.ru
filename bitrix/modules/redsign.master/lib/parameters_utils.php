<?php

namespace Redsign\Master;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class ParametersUtils {

	public static function getSettingsScript($scriptName) {
		return '/bitrix/js/redsign.master/component_settings/'.$scriptName.'.js';
	}

	public static function getComponentTemplateList($componentName = '')
	{
		$arReturn = array();
		$arTemplateInfo = \CComponentUtil::GetTemplatesList($componentName);
		if (!empty($arTemplateInfo))
		{
			sortByColumn($arTemplateInfo, array('TEMPLATE' => SORT_ASC, 'NAME' => SORT_ASC));
			$arTemplateList = array();
			$arSiteTemplateList = array(
				'.default' => Loc::getMessage('RS.TEMPLATE_SITE_DEFAULT'),
			);
			$arTemplateID = array();
			foreach ($arTemplateInfo as &$template)
			{
				if ('' != $template["TEMPLATE"] && '.default' != $template["TEMPLATE"])
				{
					$arTemplateID[] = $template["TEMPLATE"];
				}
				if (!isset($template['TITLE']))
				{
					$template['TITLE'] = $template['NAME'];
				}
			}
			unset($template);

			if (!empty($arTemplateID))
			{
				$rsSiteTemplates = \CSiteTemplate::GetList(
					array(),
					array("ID" => $arTemplateID),
					array()
				);
				while ($arSitetemplate = $rsSiteTemplates->Fetch())
				{
					$arSiteTemplateList[$arSitetemplate['ID']] = $arSitetemplate['NAME'];
				}
			}

			foreach ($arTemplateInfo as &$template)
			{
				if (isset($arHiddenTemplates[$template['NAME']]))
				{
					continue;
				}
				$strDescr = $template["TITLE"].' ('.('' != $template["TEMPLATE"] && '' != $arSiteTemplateList[$template["TEMPLATE"]] ? $arSiteTemplateList[$template["TEMPLATE"]] : Loc::getMessage('RS.TEMPLATE_SITE_DEFAULT')).')';
				$arTemplateList[$template['NAME']] = $strDescr;
			}
			unset($template);
			$arReturn = $arTemplateList;
		}

		return $arReturn;
	}

	public static function addCommonParameters (&$arTemplateParameters, $arCurrentValues = array(), $arrParams = array())
	{
		$defaultValues = array('-' => Loc::getMessage('RS.MASTER.UNDEFINED'));

		$bIBlock = Loader::includeModule('iblock');

		if (is_array($arTemplateParameters))
		{

			if (!isset($arTemplateParameters['ADD_CONTAINER']))
			{
				$arTemplateParameters['ADD_CONTAINER'] = array(
					'NAME' => Loc::getMessage('RS.MASTER.ADD_CONTAINER'),
					'TYPE' => 'CHECKBOX',
					'VALUE' => 'Y',
					'DEFAULT' => 'Y',
					'REFRESH' => 'Y',
				);
			}

			if (is_array($arrParams) && count($arrParams) > 0)
			{

				$bUserFieldsNeed = false;

				foreach ($arrParams as $sParamsName)
				{
					switch ($sParamsName)
					{
						case 'sectionsView':
							$bUserFieldsNeed = true;
						break;
						case 'propertyPrice':
							$bIblockPropertyNeed = true;
						break;
					}
				}


				if ($bUserFieldsNeed)
				{
					global $USER_FIELD_MANAGER;

					$arProperty_UF = array();
					$arUserFields = $USER_FIELD_MANAGER->GetUserFields(array());
					foreach ($arUserFields as $FIELD_NAME=>$arUserField)
					{
						$arProperty_UF[$FIELD_NAME] = $arUserField['LIST_COLUMN_LABEL'] ? $arUserField['LIST_COLUMN_LABEL']: $FIELD_NAME;
					}
				}

				// if ($bIblockPropertyNeed && $bIBlock) {
					// if (isset($arCurrentValues['IBLOCK_ID']) && intval($arCurrentValues['IBLOCK_ID']) > 0) {
						// $arAllPropList = array();
						// $arFilePropList = $defaultValues;
						// $arListPropList = array();

						// $rsProps = CIBlockProperty::GetList(
							// array('SORT' => 'ASC', 'ID' => 'ASC'),
							// array('IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], 'ACTIVE' => 'Y')
						// );
						// while ($arProp = $rsProps->Fetch())
						// {
							// $strPropName = '['.$arProp['ID'].']'.('' != $arProp['CODE'] ? '['.$arProp['CODE'].']' : '').' '.$arProp['NAME'];

							// if ($arProp['CODE'] == '')
							// {
								// $arProp['CODE'] = $arProp['ID'];
							// }

							// $arAllPropList[$arProp['CODE']] = $strPropName;

							// if ($arProp['PROPERTY_TYPE'] === 'F')
							// {
								// $arFilePropList[$arProp['CODE']] = $strPropName;
							// }

							// if ($arProp['PROPERTY_TYPE'] === 'L')
							// {
								// $arListPropList[$arProp['CODE']] = $strPropName;
							// }
						// }
					// }
				// }

				foreach ($arrParams as $sParamsName)
				{

					switch ($sParamsName)
					{

						case 'blockName':
							$arTemplateParameters['SHOW_PARENT_NAME'] = array(
								'NAME' => Loc::getMessage('RS.MASTER.SHOW_PARENT_NAME'),
								'TYPE' => 'CHECKBOX',
								'VALUE' => 'Y',
								'DEFAULT' => 'N',
								'REFRESH' => 'Y',
							);

							if ($arCurrentValues['SHOW_PARENT_NAME'] == 'Y')
							{

								$arTemplateParameters['PARENT_NAME'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.PARENT_NAME'),
									'TYPE' => 'STRING',
									'VALUE' => '',
									'DEFAULT' => '',
								);

								$arTemplateParameters['BLOCK_NAME_IS_LINK'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.PARENT_NAME_IS_LINK'),
									'TYPE' => 'CHECKBOX',
									'VALUE' => 'Y',
									'DEFAULT' => 'N',
								);
							}
							break;

						case 'owlSupport':
							$arTemplateParameters['USE_OWL'] = array(
								'NAME' => Loc::getMessage('RS.MASTER.USE_OWL'),
								'TYPE' => 'CHECKBOX',
								'VALUE' => 'Y',
								'DEFAULT' => 'N',
								'REFRESH' => 'Y',
							);

							if ($arCurrentValues['USE_OWL'] == 'Y')
							{
								$arTemplateParameters['OWL_CHANGE_SPEED'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.OWL_CHANGE_SPEED'),
									'TYPE' => 'STRING',
									'DEFAULT' => '2000',
								);

								$arTemplateParameters['OWL_CHANGE_DELAY'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.OWL_CHANGE_DELAY'),
									'TYPE' => 'STRING',
									'DEFAULT' => '8000',
								);

								$arTemplateParameters['OWL_PHONE'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.OWL_PHONE'),
									'TYPE' => 'STRING',
									'DEFAULT' => '1',
								);

								$arTemplateParameters['OWL_TABLET'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.OWL_TABLET'),
									'TYPE' => 'STRING',
									'DEFAULT' => '2',
								);

								$arTemplateParameters['OWL_PC'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.OWL_PC'),
									'TYPE' => 'STRING',
									'DEFAULT' => '3',
								);
							}
							break;

						case 'bootstrapCols':
							$arValues = array(
								'12' => '1',
								'6' => '2',
								'4' => '3',
								'3' => '4',
								'1-5' => '5',
								'2' => '6',
							);
							$arTemplateParameters['COLS_IN_ROW'] = array(
								'NAME' => Loc::getMessage('RS.MASTER.COLS_IN_ROW'),
								'TYPE' => 'LIST',
								'VALUES' => $arValues,
								'DEFAULT' => '4',
							);
							$arTemplateParameters['COL_XS'] = array(
								'NAME' => Loc::getMessage('RS.MASTER.COL_XS'),
								'TYPE' => 'LIST',
								'VALUES' => $arValues,
								'DEFAULT' => '12',
							);
							$arTemplateParameters['COL_SM'] = array(
								'NAME' => Loc::getMessage('RS.MASTER.COL_SM'),
								'TYPE' => 'LIST',
								'VALUES' => $arValues,
								'DEFAULT' => '6',
							);
							$arTemplateParameters['COL_MD'] = array(
								'NAME' => Loc::getMessage('RS.MASTER.COL_MD'),
								'TYPE' => 'LIST',
								'VALUES' => $arValues,
								'DEFAULT' => '6',
							);
							$arTemplateParameters['COL_LG'] = array(
								'NAME' => Loc::getMessage('RS.MASTER.COL_LG'),
								'TYPE' => 'LIST',
								'VALUES' => $arValues,
								'DEFAULT' => '6',
							);
							break;

						case 'sectionsView':

							$arViewModeList = array(
								//'LIST' => Loc::getMessage('RS.MASTER.SECTIONS_VIEW_MODE.LIST'),
								'LINE' => Loc::getMessage('RS.MASTER.SECTIONS_VIEW_MODE.LINE'),
								//'TEXT' => Loc::getMessage('RS.MASTER.SECTIONS_VIEW_MODE.TEXT'),
								'TILE' => Loc::getMessage('RS.MASTER.SECTIONS_VIEW_MODE.TILE'),
								'THUMB' => Loc::getMessage('RS.MASTER.SECTIONS_VIEW_MODE.THUMB'),
                                'BANNER' => Loc::getMessage('RS.MASTER.SECTIONS_VIEW_MODE.BANNER'),
                                'CARDS' => Loc::getMessage('RS.MASTER.SECTIONS_VIEW_MODE.CARDS')
							);

							$arTemplateParameters['SECTIONS_VIEW_MODE'] = array(
								'PARENT' => 'SECTIONS_SETTINGS',
								'NAME' => Loc::getMessage('RS.MASTER.SECTIONS_VIEW_MODE'),
								'TYPE' => 'LIST',
								'VALUES' => $arViewModeList,
								'MULTIPLE' => 'N',
								'DEFAULT' => 'LINE',
								'REFRESH' => 'Y'
							);

							if (isset($arCurrentValues['SECTIONS_VIEW_MODE']))
							{

								if ('TILE' == $arCurrentValues['SECTIONS_VIEW_MODE'])
								{

									$arTemplateParameters['LINE_ELEMENT_COUNT'] = array(
										'PARENT' => 'SECTIONS_SETTINGS',
										'NAME' => Loc::getMessage('RS.MASTER.LINE_ELEMENT_COUNT'),
										'TYPE' => 'LIST',
										'VALUES' => array(
											'2' => '2',
											'3' => '3',
											'4' => '4',
											'6' => '6',
											'12' => '12',
										),
										'DEFAULT' => '3'
									);
								}
								elseif (in_array($arCurrentValues['SECTIONS_VIEW_MODE'], array('BANNER', 'CARDS', 'THUMB')))
								{
									$arTemplateParameters['BANNER_TYPE'] = array(
										'PARENT' => 'SECTIONS_SETTINGS',
										'NAME' => Loc::getMessage('RS.MASTER.BANNER_TYPE'),
										'TYPE' => 'LIST',
										'VALUES' => array_merge($defaultValues, $arProperty_UF),
										'REFRESH' => 'Y'
									);

                                    if ($arCurrentValues['SECTIONS_VIEW_MODE'] == 'CARDS')
                                    {
                                        $arTemplateParameters['CARD_ICON'] = array(
                                            'PARENT' => 'SECTIONS_SETTINGS',
                                            'NAME' => Loc::getMessage('RS.MASTER.CARD_ICON'),
                                            'TYPE' => 'LIST',
                                            'DEFAULT' => 'UF_CARD_ICON',
                                            'VALUES' => array_merge($defaultValues, $arProperty_UF),
                                            'REFRESH' => 'Y'
                                        );
                                    }
								}
							}
							break;

						case 'propertyPrice':
							$arTemplateParameters['PRICE_PROP'] = array(
								'PARENT' => 'VISUAL',
								'NAME' => Loc::getMessage('RS.MASTER.PRICE_PROP'),
								'TYPE' => 'LIST',
								'VALUES' => $defaultValues + $arAllPropList,
							);
							$arTemplateParameters['DISCOUNT_PROP'] = array(
								'PARENT' => 'VISUAL',
								'NAME' => Loc::getMessage('RS.MASTER.DISCOUNT_PROP'),
								'TYPE' => 'LIST',
								'VALUES' => $defaultValues + $arAllPropList,
							);

							$arTemplateParameters['CURRENCY_PROP'] = array(
								'PARENT' => 'VISUAL',
								'NAME' => Loc::getMessage('RS.MASTER.CURRENCY_PROP'),
								'TYPE' => 'LIST',
								'VALUES' => $defaultValues + $arAllPropList,
							);

							$arTemplateParameters['PRICE_DECIMALS'] = array(
								'PARENT' => 'VISUAL',
								'NAME' => Loc::getMessage('RS.MASTER.PRICE_DECIMALS_PROP'),
								'TYPE' => 'LIST',
								'VALUES' => array(
									'0' => '0',
									'1' => '1',
									'2' => '2',
								),
								'DEFAULT' => '0',
							);

							$arTemplateParameters['SHOW_OLD_PRICE'] = array(
								'PARENT' => 'VISUAL',
								'NAME' => Loc::getMessage('RS.MASTER.SHOW_OLD_PRICE'),
								'TYPE' => 'CHECKBOX',
								'DEFAULT' => 'N'
							);
							break;

						case 'share':

							$arTemplateParameters['USE_SHARE'] = array(
								'NAME' => Loc::getMessage('RS.MASTER.USE_SHARE'),
								'TYPE' => 'CHECKBOX',
								'MULTIPLE' => 'N',
								'VALUE' => 'Y',
								'DEFAULT' =>'N',
								'REFRESH'=> 'Y',
							);

							if ($arCurrentValues['USE_SHARE'] == 'Y')
							{
								$arSocialServices = array(
									'blogger' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.BLOGGER'),
									'delicious' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.DELICIOUS'),
									'digg' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.DIGG'),
									'evernote' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.EVERNOTE'),
									'facebook' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.FACEBOOK'),
									'gplus' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.GPLUS'),
									'linkedin' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.LINKEDIN'),
									'lj' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.LJ'),
									'moimir' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.MOIMIR'),
									'odnoklassniki' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.ODNOKLASSNIKI'),
									'pinterest' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.PINTEREST'),
									'pocket' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.POCKET'),
									'qzone' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.QZONE'),
									'reddit' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.REDDIT'),
									'renren' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.RENREN'),
									'sinaWeibo ' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.SINA_WEIBO'),
									'surfingbird' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.SURFINGBIRD'),
									'telegram' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.TELEGRAM'),
									'tencentWeibo' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.TENCENT_WEIBO'),
									'tumblr' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.TUMBLR'),
									'twitter' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.TWITTER'),
									'viber' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.VIBER'),
									'vkontakte' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.VKONTAKTE'),
									'whatsapp' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES.WHATSAPP'),
								);

								$arSocialCopy = array(
									'first' => Loc::getMessage('RS.MASTER.SOCIAL_COPY.FIRST'),
									'last' => Loc::getMessage('RS.MASTER.SOCIAL_COPY.LAST'),
									'hidden' => Loc::getMessage('RS.MASTER.SOCIAL_COPY.HIDDEN'),
								);
								$arSocialSize = array(
									'm' => Loc::getMessage('RS.MASTER.SOCIAL_SIZE.M'),
									's' => Loc::getMessage('RS.MASTER.SOCIAL_SIZE.S'),
								);
								$arTemplateParameters['SOCIAL_SERVICES'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.SOCIAL_SERVICES'),
									'TYPE' => 'LIST',
									'VALUES' => $defaultValues + $arSocialServices,
									'MULTIPLE' => 'Y',
									'DEFAULT' => '',
									'ADDITIONAL_VALUES' => 'Y',
								);
								$arTemplateParameters['SOCIAL_COUNTER'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.SOCIAL_COUNTER'),
									'TYPE' => 'CHECKBOX',
									'DEFAULT' => 'N',
								);
								$arTemplateParameters['SOCIAL_COPY'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.SOCIAL_COPY'),
									'TYPE' => 'LIST',
									'VALUES' => $arSocialCopy
								);
								$arTemplateParameters['SOCIAL_LIMIT'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.SOCIAL_LIMIT'),
									'TYPE' => 'STRING',
									'DEFAULT' => '',
								);
								$arTemplateParameters['SOCIAL_SIZE'] = array(
									'NAME' => Loc::getMessage('RS.MASTER.SOCIAL_SIZE'),
									'TYPE' => 'LIST',
									'VALUES' => $arSocialSize
								);
							}

							break;
					}

				}
			}
		}
	}

	public static function addB24CRMFormsParameters($name, $code, &$arTemplateParameters, &$arCurrentValues) {
		$arTemplateParameters['RS_'.$code.'_B24_CRM_FORM_USE'] = array(
			'NAME' => Loc::getMessage('RS_B24_CRM_FORM_USE', array('#NAME#' => $name)),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
			'REFRESH' => 'Y'
		);

		if ($arCurrentValues['RS_'.$code.'_B24_CRM_FORM_USE'] == 'Y')
		{
			$arTemplateParameters['RS_'.$code.'_B24_CRM_FORM_ID'] = array(
				'NAME' => Loc::getMessage('RS_B24_CRM_FORM_ID'),
				'TYPE' => 'STRING'
			);

			$arTemplateParameters['RS_'.$code.'_B24_CRM_FORM_SEC'] = array(
				'NAME' => Loc::getMessage('RS_B24_CRM_FORM_SEC'),
				'TYPE' => 'STRING'
			);
		}
	}

}
