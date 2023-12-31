<?php
use Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class CASDiblockAction {

	private static $gridAction = '';
	private static $gridId = '';
	private static $action = '';

	private static $iblockIncluded = null;
	private static $catalogIncluded = null;

	public static function OnBeforePrologHandler() {

		global $USER_FIELD_MANAGER;

		self::initAction();
		$action = self::getAction();
		$gridAction = self::getGridAction();
		if ($action == '' && $gridAction == '') {
			return;
		}
		if (!check_bitrix_sessid()) {
			return;
		}
		if (self::$iblockIncluded === null) {
			self::$iblockIncluded = Loader::includeModule('iblock');
		}
		if (!self::$iblockIncluded) {
			return;
		}
		if (self::$catalogIncluded === null) {
			self::$catalogIncluded = Loader::includeModule('catalog');
		}

		$BID = (isset($_REQUEST['ID']) ? (int)$_REQUEST['ID'] : 0);

		if ($action=='asd_prop_export' && $BID>0 &&
			CASDIblockRights::IsIBlockEdit($BID)
		) {
			$strPath = $_SERVER['DOCUMENT_ROOT'].'/bitrix/tmp/asd.iblock/';
			$strName = 'asd_props_export_'.$BID.'_'.md5(LICENSE_KEY).'.xml';
			CheckDirPath($strPath);
			if ($hdlOutput = fopen($strPath.$strName, 'wb')) {
				fwrite($hdlOutput, '<?xml version="1.0" encoding="'.SITE_CHARSET.'"?>'."\n");
				fwrite($hdlOutput, '<asd_iblock_props>'."\n");
				fwrite($hdlOutput, CASDiblockTools::ExportPropsToXML($BID, $_REQUEST['p']));
				if ($_REQUEST['forms'] == 'Y') {
					fwrite($hdlOutput, CASDiblockTools::ExportSettingsToXML($BID, array('forms')));
				}
				fwrite($hdlOutput, '</asd_iblock_props>'."\n");
				fclose($hdlOutput);
			}
			?><script type="text/javascript">
				top.BX.closeWait(); top.BX.WindowManager.Get().AllowClose(); top.BX.WindowManager.Get().Close();
				window.location.href = '/bitrix/tools/asd.iblock/props_export.php?ID=<? echo $BID; ?>&v=' + Math.random();
			</script><?
			die();
		}

		if ($action=='asd_prop_import' && $BID>0 && !$_FILES['xml_file']['error'] &&
			CASDIblockRights::IsIBlockEdit($BID)
		) {
			$arOldNewID = array();
			CASDiblockTools::ImportPropsFromXML($BID, $_FILES['xml_file']['tmp_name'], $arOldNewID);
			CASDiblockTools::ImportFormsFromXML($BID, $_FILES['xml_file']['tmp_name'], $arOldNewID);
			LocalRedirect('/bitrix/admin/iblock_edit.php?type='.$_REQUEST['type'].'&tabControl_active_tab=edit2&lang='.LANGUAGE_ID.'&ID='.$BID.'&admin=Y');
		}

		$IBLOCK_ID = 0;
		if (isset($_REQUEST['IBLOCK_ID'])) {
			$IBLOCK_ID = (int)$_REQUEST['IBLOCK_ID'];
			if ($IBLOCK_ID < 0) {
				$IBLOCK_ID = 0;
			}
		}

		if ($action=='asd_reverse' && $IBLOCK_ID>0 &&
			CASDIblockRights::IsIBlockEdit($IBLOCK_ID)
		) {
			$LIST_MODE = CIBlock::GetArrayByID($IBLOCK_ID, 'LIST_MODE');
			if ((string)$LIST_MODE === '') {
				$LIST_MODE = COption::GetOptionString('iblock', 'combined_list_mode', 'N')=='Y' ? 'C' : 'S';
			}
			$LIST_MODE = $LIST_MODE=='C' ? 'S' : 'C';
			$ib = new CIBlock();
			$ib->Update($IBLOCK_ID, array('LIST_MODE' => $LIST_MODE));
			$elementListPage = '';
			if (!defined('CATALOG_PRODUCT')) {
				$elementListPage = $LIST_MODE == 'S' ? 'iblock_element_admin' : 'iblock_list_admin';
			} else {
				$elementListPage = $LIST_MODE == 'S' ? 'cat_product_admin' : 'cat_product_list';
			}
			LocalRedirect('/bitrix/admin/'.$elementListPage.'.php?IBLOCK_ID='.$IBLOCK_ID.
																'&type='.htmlspecialcharsbx($_REQUEST['type']).
																'&find_section_section='.intval($_REQUEST['find_section_section']).
																'&lang='.LANGUAGE_ID);
		}

		$strCurPage = $GLOBALS['APPLICATION']->GetCurPage();
		$bElemPage = ($strCurPage=='/bitrix/admin/iblock_element_admin.php' ||
						$strCurPage=='/bitrix/admin/cat_product_admin.php'
					);
		$bSectPage = ($strCurPage=='/bitrix/admin/iblock_section_admin.php' ||
						$strCurPage=='/bitrix/admin/cat_section_admin.php'
					);
		$bMixPage = ($strCurPage=='/bitrix/admin/iblock_list_admin.php');
		$bRightPage = ($bElemPage || $bSectPage || $bMixPage);
		$successRedirect = false;

		if ($bRightPage && $gridAction=='asd_copy_in_list' && isset($_REQUEST['ID']) && (string)$_REQUEST['ID'] !== '') {
			$bDoAction = true;
			//$gridAction = 'asd_copy';
			$_REQUEST['asd_ib_dest'] = $IBLOCK_ID;
			$_REQUEST['ID'] = array($_REQUEST['ID']);
		} else {
			$bDoAction = false;
		}

		if ($bRightPage && !empty($_REQUEST['ID']) &&
			($_SERVER['REQUEST_METHOD']=='POST' || $bDoAction) &&
			($gridAction=='asd_copy' || $gridAction=='asd_copy_in_list' || $gridAction=='asd_move') &&
			isset($_REQUEST['asd_ib_dest']) && (int)$_REQUEST['asd_ib_dest'] > 0 &&
			CASDIblockRights::IsIBlockDisplay($_REQUEST['asd_ib_dest'])
		) {
			$intSrcIBlockID = $IBLOCK_ID;
			$intDestIBlockID = (int)$_REQUEST['asd_ib_dest'];

			$enableMultiSelect = !$bSectPage && (string)\Bitrix\Main\Config\Option::get('asd.iblock', 'enable_section_multiselect') === 'Y';

			$keepOldSections = (string)\Bitrix\Main\Config\Option::get('asd.iblock', 'keep_old_sections_for_copy') === 'Y';

			$multipleCopy = (string)\Bitrix\Main\Config\Option::get('asd.iblock', 'multiple_copy') === 'Y';

			$intSetSectID = 0;
			$sections = array();
			$emptySections = false;
			if (isset($_REQUEST['asd_sect_dest']) && is_string($_REQUEST['asd_sect_dest'])) {
				if ($enableMultiSelect) {
					$rawSections = str_replace(' ', '', trim($_REQUEST['asd_sect_dest']));
					if ($rawSections !== '') {
						$rawSections = explode(',', $rawSections);
						if (!empty($rawSections) && is_array($rawSections)) {
							foreach ($rawSections as $value) {
								$value = (int)$value;
								if ($value > 0) {
									$sections[] = $value;
								}
							}
						}
					}
				} else {
					$value = (int)$_REQUEST['asd_sect_dest'];
					if ($value > 0) {
						$sections = array($value);
					}
				}
			}

			$allowedSectionOperation = count($sections) < 2;
			if ($allowedSectionOperation && !empty($sections)) {
				$intSetSectID = reset($sections);
			}
			if (empty($sections)) {
				$sections = array(0);
				$emptySections = true;
			}

			$boolCreateElement = false;
			$boolCreateSection = false;

			if ($bElemPage || $bMixPage) {
				foreach (array_keys($sections) as $index) {
					if (!CASDIblockRights::IsSectionElementCreate($intDestIBlockID, $sections[$index])) {
						unset($sections[$index]);
					}
				}
				$boolCreateElement = !empty($sections);
			}
			if ($bSectPage || $bMixPage) {
				$boolCreateSection = CASDIblockRights::IsSectionSectionCreate($intDestIBlockID, $intSetSectID);
			}
			if (!empty($sections)) {
				if (count($sections) == 1 && reset($sections) == 0)
				$emptySections = true;
			}

			if ($emptySections) {
				$elementSections = array(0 => array());
			} else {
				if ($multipleCopy) {
					foreach ($sections as $sectionId) {
						$elementSections[] = array($sectionId);
					}
				} else {
					$elementSections = array(0 => $sections);
				}
			}

			if ($boolCreateElement || $boolCreateSection) {
				$arPropListCache = array();
				$arOldPropListCache = array();
				$arNamePropListCache = array();
				$arOldNamePropListCache = array();

				$boolUFListCache = false;
				$arUFListCache = array();
				$arOldUFListCache = array();
				$arUFEnumCache = array();
				$arOldUFEnumCache = array();
				$arUFNameEnumCache = array();
				$arOldUFNameEnumCache = array();

				$arDestIBlock = CIBlock::GetArrayByID($intDestIBlockID);
				$arDestIBFields = $arDestIBlock['FIELDS'];
				$boolCodeUnique = false;
				if ($arDestIBFields['CODE']['DEFAULT_VALUE']['UNIQUE'] == 'Y') {
					$boolCodeUnique = ($intSrcIBlockID == $intDestIBlockID);
				}
				$boolSectCodeUnique = false;
				if ($arDestIBFields['SECTION_CODE']['DEFAULT_VALUE']['UNIQUE'] == 'Y') {
					$boolSectCodeUnique = ($intSrcIBlockID == $intDestIBlockID);
				}

				$boolCopyCatalog = false;
				$boolNewCatalog = false;
				if (self::$catalogIncluded) {
					$boolCopyCatalog = (is_array(CCatalog::GetByID($intDestIBlockID)));
					$boolNewCatalog = $boolCopyCatalog;
					if ($boolCopyCatalog) {
						$boolCopyCatalog = (is_array(CCatalog::GetByID($intSrcIBlockID)));
					}
				}

				$sectionsErr = false;

				$el = new CIBlockElement();
				$sc = new CIBlockSection();
				$obEnum = new CUserFieldEnum();
				foreach ($_REQUEST['ID'] as $eID) {
					$boolCopyElem = false;
					$boolCopySect = false;
					if ($bMixPage) {
						if (strncmp($eID, 'E', 1) == 0) {
							$boolCopyElem = true;
						} else {
							$boolCopySect = true;
						}
						$ID = (int)substr($eID, 1);
					} else {
						$boolCopyElem = $bElemPage;
						$boolCopySect = $bSectPage;
						$ID = (int)$eID;
					}
					if ($boolCreateElement && $boolCopyElem) {
						if ($obSrc = CIBlockElement::GetByID($ID)->GetNextElement()) {
							$arSrc = $obSrc->GetFields();
							$arSrcPr = $obSrc->GetProperties(false, array('EMPTY' => 'N'));
							$arSrc['PREVIEW_PICTURE'] = (int)$arSrc['PREVIEW_PICTURE'];
							if ($arSrc['PREVIEW_PICTURE'] > 0) {
								$arSrc['PREVIEW_PICTURE'] = CFile::MakeFileArray($arSrc['PREVIEW_PICTURE']);
								if (empty($arSrc['PREVIEW_PICTURE'])) {
									$arSrc['PREVIEW_PICTURE'] = false;
								} else {
									$arSrc['PREVIEW_PICTURE']['COPY_FILE'] = 'Y';
								}
							} else {
								$arSrc['PREVIEW_PICTURE'] = false;
							}
							$arSrc['DETAIL_PICTURE'] = (int)$arSrc['DETAIL_PICTURE'];
							if ($arSrc['DETAIL_PICTURE'] > 0) {
								$arSrc['DETAIL_PICTURE'] = CFile::MakeFileArray($arSrc['DETAIL_PICTURE']);
								if (empty($arSrc['DETAIL_PICTURE'])) {
									$arSrc['DETAIL_PICTURE'] = false;
								}
								else {
									$arSrc['DETAIL_PICTURE']['COPY_FILE'] = 'Y';
								}
							}
							else {
								$arSrc['DETAIL_PICTURE'] = false;
							}
							$rawSource = $arSrc;
							$arSrc = array(
								'IBLOCK_ID' => $intDestIBlockID,
								'ACTIVE' => $arSrc['ACTIVE'],
								'ACTIVE_FROM' => $arSrc['ACTIVE_FROM'],
								'ACTIVE_TO' => $arSrc['ACTIVE_TO'],
								'SORT' => $arSrc['SORT'],
								'NAME' => $arSrc['~NAME'],
								'PREVIEW_PICTURE' => $arSrc['PREVIEW_PICTURE'],
								'PREVIEW_TEXT' => $arSrc['~PREVIEW_TEXT'],
								'PREVIEW_TEXT_TYPE' => $arSrc['PREVIEW_TEXT_TYPE'],
								'DETAIL_TEXT' => $arSrc['~DETAIL_TEXT'],
								'DETAIL_TEXT_TYPE' => $arSrc['DETAIL_TEXT_TYPE'],
								'DETAIL_PICTURE' => $arSrc['DETAIL_PICTURE'],
								'WF_STATUS_ID' => $arSrc['WF_STATUS_ID'],
								'IBLOCK_SECTION_ID' => $arSrc['IBLOCK_SECTION_ID'],
								'CODE' => $arSrc['~CODE'],
								'TAGS' => $arSrc['~TAGS'],
								'PROPERTY_VALUES' => array(),
							);
							if ($gridAction == 'asd_move' && $intDestIBlockID != $intSrcIBlockID) {
								$arSrc['CREATED_BY'] = $rawSource['CREATED_BY'];
								$arSrc['SHOW_COUNTER'] = $rawSource['SHOW_COUNTER'];
							}
							if (
								($gridAction == 'asd_move' || $gridAction == 'asd_copy')
								&& $intDestIBlockID != $intSrcIBlockID
							) {
								$arSrc['XML_ID'] = $rawSource['~XML_ID'];
							}
							if ($arDestIBFields['CODE']['IS_REQUIRED'] == 'Y') {
								if ((string)$arSrc['CODE'] === '') {
									$arSrc['CODE'] = mt_rand(100000, 1000000);
								}
							}
							if ($arDestIBFields['CODE']['DEFAULT_VALUE']['UNIQUE'] == 'Y') {
								$boolElCodeUnique = $boolCodeUnique;
								if (!$boolCodeUnique) {
									$rsCheckItems  = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $intDestIBlockID, '=CODE' => $arSrc['CODE'], 'CHECK_PERMISSIONS' => 'N'),
																	false, array('nTopCount' => 1), array('ID', 'IBLOCK_ID'));
									if ($arCheck = $rsCheckItems->Fetch()) {
										$boolElCodeUnique = true;
									}
								}
								if ($boolElCodeUnique) {
									$arSrc['CODE'] .= mt_rand(100, 10000);
								}
							}
							$otherIblock = $intSrcIBlockID != $intDestIBlockID;
							if ($otherIblock) {
								if (empty($arPropListCache)) {
									$rsProps = CIBlockProperty::GetList(
										array(),
										array('IBLOCK_ID' => $intDestIBlockID, 'PROPERTY_TYPE' => 'L', 'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'N')
									);
									while ($arProp = $rsProps->Fetch()) {
										$arValueList = array();
										$arNameList = array();
										$rsValues = CIBlockProperty::GetPropertyEnum($arProp['ID']);
										while ($arValue = $rsValues->Fetch()) {
											$arValueList[$arValue['XML_ID']] = $arValue['ID'];
											$arNameList[$arValue['ID']] = trim($arValue['VALUE']);
										}
										if (!empty($arValueList)) {
											$arPropListCache[$arProp['CODE']] = $arValueList;
										}
										if (!empty($arNameList)) {
											$arNamePropListCache[$arProp['CODE']] = $arNameList;
										}
									}
								}
								if (empty($arOldPropListCache)) {
									$rsProps = CIBlockProperty::GetList(
										array(),
										array('IBLOCK_ID' => $intSrcIBlockID, 'PROPERTY_TYPE' => 'L', 'ACTIVE' => 'Y', 'CHECK_PERMISSIONS' => 'N')
									);
									while ($arProp = $rsProps->Fetch()) {
										$arValueList = array();
										$arNameList = array();
										$rsValues = CIBlockProperty::GetPropertyEnum($arProp['ID']);
										while ($arValue = $rsValues->Fetch()) {
											$arValueList[$arValue['ID']] = $arValue['XML_ID'];
											$arNameList[$arValue['ID']] = trim($arValue['VALUE']);
										}
										if (!empty($arValueList)) {
											$arOldPropListCache[$arProp['CODE']] = $arValueList;
										}
										if (!empty($arNameList)) {
											$arOldNamePropListCache[$arProp['CODE']] = $arNameList;
										}
									}
								}
							}
							foreach ($arSrcPr as &$arProp) {
								$propertyIndex = (string)$arProp['CODE'];
								if ($propertyIndex === '' && !$otherIblock) {
									$propertyIndex = $arProp['ID'];
								}
								if ($propertyIndex === '') {
									continue;
								}
								if ($arProp['USER_TYPE'] == 'HTML') {
									if (is_array($arProp['~VALUE'])) {
										if ($arProp['MULTIPLE'] == 'N') {
											$arSrc['PROPERTY_VALUES'][$propertyIndex] = array('VALUE' => array('TEXT' => $arProp['~VALUE']['TEXT'], 'TYPE' => $arProp['~VALUE']['TYPE']));
											if ($arProp['WITH_DESCRIPTION'] == 'Y') {
												$arSrc['PROPERTY_VALUES'][$propertyIndex]['DESCRIPTION'] = $arProp['~DESCRIPTION'];
											}
										} else {
											if (!empty($arProp['~VALUE'])) {
												$arSrc['PROPERTY_VALUES'][$propertyIndex] = array();
												foreach ($arProp['~VALUE'] as $propValueKey => $propValue) {
													$oneNewValue = array('VALUE' => array('TEXT' => $propValue['TEXT'], 'TYPE' => $propValue['TYPE']));
													if ($arProp['WITH_DESCRIPTION'] == 'Y') {
														$oneNewValue['DESCRIPTION'] = $arProp['~DESCRIPTION'][$propValueKey];
													}
													$arSrc['PROPERTY_VALUES'][$propertyIndex][] = $oneNewValue;
													unset($oneNewValue);
												}
												unset($propValue, $propValueKey);
											}
										}
									}
								} elseif ($arProp['USER_TYPE'] == 'video') {
									if (!empty($arProp['~VALUE'])) {
										if ($arProp['MULTIPLE'] == 'N') {
											if (!is_array($arProp['~VALUE'])) {
												$arProp['~VALUE'] = unserialize($arProp['~VALUE'], ['allowed_classes' => false]);
											}
											if (is_array($arProp['~VALUE'])) {
												$arSrc['PROPERTY_VALUES'][$propertyIndex] = ['VALUE' => $arProp['~VALUE']];
												if ($arProp['WITH_DESCRIPTION'] == 'Y') {
													$arSrc['PROPERTY_VALUES'][$propertyIndex]['DESCRIPTION'] = $arProp['~DESCRIPTION'];
												}
											}
										} else {
											$arSrc['PROPERTY_VALUES'][$propertyIndex] = array();
											foreach ($arProp['~VALUE'] as $propValueKey => $propValue) {
												if (!is_array($propValue)) {
													$propValue = unserialize($propValue, ['allowed_classes' => false]);
												}
												if (is_array($propValue)) {
													$oneNewValue = array('VALUE' => $propValue);
													if ($arProp['WITH_DESCRIPTION'] == 'Y')
													{
														$oneNewValue['DESCRIPTION'] = $arProp['~DESCRIPTION'][$propValueKey];
													}
													$arSrc['PROPERTY_VALUES'][$propertyIndex][] = $oneNewValue;
													unset($oneNewValue);
												}
											}
											unset($propValue, $propValueKey);
										}
									}
								} elseif ($arProp['PROPERTY_TYPE'] == 'F') {
									if (is_array($arProp['VALUE'])) {
										$arSrc['PROPERTY_VALUES'][$propertyIndex] = array();
										foreach ($arProp['VALUE'] as $propValueKey => $file) {
											if ($file > 0) {
												$tmpValue = CFile::MakeFileArray($file);
												if (!is_array($tmpValue))
													continue;
												if ($arProp['WITH_DESCRIPTION'] == 'Y') {
													$tmpValue = array(
														'VALUE' => $tmpValue,
														'DESCRIPTION' => $arProp['~DESCRIPTION'][$propValueKey]
													);
												}
												$arSrc['PROPERTY_VALUES'][$propertyIndex][] = $tmpValue;
											}
										}
									} elseif ($arProp['VALUE'] > 0) {
										$tmpValue = CFile::MakeFileArray($arProp['VALUE']);
										if (is_array($tmpValue)) {
											if ($arProp['WITH_DESCRIPTION'] == 'Y') {
												$tmpValue = array(
													'VALUE' => $tmpValue,
													'DESCRIPTION' => $arProp['~DESCRIPTION']
												);
											}
											$arSrc['PROPERTY_VALUES'][$propertyIndex] = $tmpValue;
										}
									}
								} elseif ($arProp['PROPERTY_TYPE'] == 'L') {
									if (!empty($arProp['VALUE_ENUM_ID'])) {
										if ($intSrcIBlockID == $arSrc['IBLOCK_ID']) {
											$arSrc['PROPERTY_VALUES'][$propertyIndex] = $arProp['VALUE_ENUM_ID'];
										} else {
											if (isset($arPropListCache[$arProp['CODE']]) && isset($arOldPropListCache[$arProp['CODE']])) {
												if (is_array($arProp['VALUE_ENUM_ID'])) {
													$arSrc['PROPERTY_VALUES'][$arProp['CODE']] = array();
													foreach ($arProp['VALUE_ENUM_ID'] as &$intValueID) {
														$strValueXmlID = $arOldPropListCache[$arProp['CODE']][$intValueID];
														if (isset($arPropListCache[$arProp['CODE']][$strValueXmlID])) {
															$arSrc['PROPERTY_VALUES'][$arProp['CODE']][] = $arPropListCache[$arProp['CODE']][$strValueXmlID];
														} else {
															$strValueName = $arOldNamePropListCache[$arProp['CODE']][$intValueID];
															$intValueKey = array_search($strValueName, $arNamePropListCache[$arProp['CODE']]);
															if ($intValueKey !== false) {
																$arSrc['PROPERTY_VALUES'][$arProp['CODE']][] = $intValueKey;
															}
														}
													}
													if (isset($intValueID)) {
														unset($intValueID);
													}
													if (empty($arSrc['PROPERTY_VALUES'][$arProp['CODE']])) {
														unset($arSrc['PROPERTY_VALUES'][$arProp['CODE']]);
													}
												} else {
													$strValueXmlID = $arOldPropListCache[$arProp['CODE']][$arProp['VALUE_ENUM_ID']];
													if (isset($arPropListCache[$arProp['CODE']][$strValueXmlID])) {
														$arSrc['PROPERTY_VALUES'][$arProp['CODE']] = $arPropListCache[$arProp['CODE']][$strValueXmlID];
													} else {
														$strValueName = $arOldNamePropListCache[$arProp['CODE']][$arProp['VALUE_ENUM_ID']];
														$intValueKey = array_search($strValueName, $arNamePropListCache[$arProp['CODE']]);
														if ($intValueKey !== false) {
															$arSrc['PROPERTY_VALUES'][$arProp['CODE']] = $intValueKey;
														}
													}
												}
											}
										}
									}
								} elseif ($arProp['PROPERTY_TYPE'] == 'S' || $arProp['PROPERTY_TYPE'] == 'N') {
									if ($arProp['MULTIPLE'] == 'Y') {
										if (is_array($arProp['~VALUE'])) {
											if ($arProp['WITH_DESCRIPTION'] == 'Y') {
												$arSrc['PROPERTY_VALUES'][$propertyIndex] = array();
												foreach ($arProp['~VALUE'] as $propValueKey => $propValue) {
													$arSrc['PROPERTY_VALUES'][$propertyIndex][] = array(
														'VALUE' => $propValue,
														'DESCRIPTION' => $arProp['~DESCRIPTION'][$propValueKey]
													);
												}
												unset($propValue, $propValueKey);
											} else {
												$arSrc['PROPERTY_VALUES'][$propertyIndex] = $arProp['~VALUE'];
											}
										}
									} else {
										$arSrc['PROPERTY_VALUES'][$propertyIndex] = (
											$arProp['WITH_DESCRIPTION'] == 'Y'
											? array('VALUE' => $arProp['~VALUE'], 'DESCRIPTION' => $arProp['~DESCRIPTION'])
											: $arProp['~VALUE']
										);
									}
								} else {
									$arSrc['PROPERTY_VALUES'][$propertyIndex] = $arProp['~VALUE'];
								}
							}
							if (isset($arProp)) {
								unset($arProp);
							}

							$seoTemplates = CASDIblockElementTools::getSeoFieldTemplates($intSrcIBlockID, $ID);
							if (!empty($seoTemplates)) {
								$arSrc['IPROPERTY_TEMPLATES'] = $seoTemplates;
							}
							unset($seoTemplates);

							$oldSections = array();
							if ($intSrcIBlockID == $intDestIBlockID) {
								if (
									$gridAction == 'asd_copy_in_list'
									|| ($gridAction == 'asd_copy' && $keepOldSections)
								) {
									$rsSections = CIBlockElement::GetElementGroups($ID, true);
									while ($arSection = $rsSections->Fetch()) {
										if (CASDIblockRights::IsSectionElementCreate($intDestIBlockID, $arSection['ID'])) {
											$oldSections[] = $arSection['ID'];
										}
									}
									unset($arSection, $rsSections);
								}
							}

							$elementError = false;
							foreach ($elementSections as $newSections) {
								if (array_key_exists('IBLOCK_SECTION', $arSrc)) {
									unset($arSrc['IBLOCK_SECTION']);
								}
								$iblockSections = array_merge($oldSections, $newSections);
								if (!empty($iblockSections)) {
									$iblockSections = array_unique($iblockSections);
									$arSrc['IBLOCK_SECTION'] = $iblockSections;
								}
								unset($iblockSections);

								$intNewID = $el->Add($arSrc, true, true, true);
								if ($intNewID) {
									if (self::$catalogIncluded && $boolCopyCatalog) {
										$priceRes = CPrice::GetListEx(
											array(),
											array('PRODUCT_ID' => $ID),
											false,
											false,
											array('PRODUCT_ID', 'EXTRA_ID', 'CATALOG_GROUP_ID', 'PRICE', 'CURRENCY', 'QUANTITY_FROM', 'QUANTITY_TO')
										);
										while ($arPrice = $priceRes->Fetch()) {
											$arPrice['PRODUCT_ID'] = $intNewID;
											CPrice::Add($arPrice);
										}
									}
									if (self::$catalogIncluded && $boolNewCatalog) {
										$arProduct = array(
											'ID' => $intNewID
										);
										if ($boolCopyCatalog) {
											$productRes = CCatalogProduct::GetList(
												array(),
												array('ID' => $ID),
												false,
												false,
												array(
													'QUANTITY_TRACE_ORIG',
													'CAN_BUY_ZERO_ORIG',
													'NEGATIVE_AMOUNT_TRACE_ORIG',
													'SUBSCRIBE_ORIG',
													'WEIGHT',
													'PRICE_TYPE',
													'RECUR_SCHEME_TYPE',
													'RECUR_SCHEME_LENGTH',
													'TRIAL_PRICE_ID',
													'WITHOUT_ORDER',
													'SELECT_BEST_PRICE',
													'VAT_ID',
													'VAT_INCLUDED',
													'WIDTH',
													'LENGTH',
													'HEIGHT',
													'PURCHASING_PRICE',
													'PURCHASING_CURRENCY',
													'MEASURE'
												)
											);
											if ($arCurProduct = $productRes->Fetch()) {
												$arProduct = $arCurProduct;
												$arProduct['ID'] = $intNewID;
												$arProduct['QUANTITY'] = 0;
												$arProduct['QUANTITY_TRACE'] = $arProduct['QUANTITY_TRACE_ORIG'];
												$arProduct['CAN_BUY_ZERO'] = $arProduct['CAN_BUY_ZERO_ORIG'];
												$arProduct['NEGATIVE_AMOUNT_TRACE'] = $arProduct['NEGATIVE_AMOUNT_TRACE_ORIG'];
												if (isset($arProduct['SUBSCRIBE_ORIG'])) {
													$arProduct['SUBSCRIBE'] = $arProduct['SUBSCRIBE_ORIG'];
												}
												foreach ($arProduct as $productKey => $productValue)  {
													if ($productValue === null)
														unset($arProduct[$productKey]);
												}
											}
										}
										CCatalogProduct::Add($arProduct, false);
									}
								}
								else
								{
									CASDiblock::$error .= '['.$ID.'] '.$el->LAST_ERROR."\n";
									$elementError = true;
								}
							}
							if (!$elementError) {
								if ($gridAction == 'asd_move') {
									if (CASDIblockRights::IsElementDelete($intSrcIBlockID, $ID))
									{
										$el->Delete($ID);
									}
									else
									{
										CASDiblock::$error .= '['.$ID.'] '.Loc::getMessage('ASD_ACTION_ERR_DELETE_ELEMENT_RIGHTS')."\n";
									}
								}
							}
						}
					}

					if ($boolCreateSection && $boolCopySect) {
						if ($gridAction == 'asd_move') {
							continue;
						}
						$rsSections = CIBlockSection::GetList(
							array(),
							array('ID' => $ID, 'IBLOCK_ID' => $intSrcIBlockID),
							false,
							array('ID', 'NAME', 'XML_ID', 'CODE', 'IBLOCK_SECTION_ID', 'IBLOCK_ID',
								'ACTIVE', 'SORT', 'PICTURE', 'DESCRIPTION', 'DESCRIPTION_TYPE',
								'DETAIL_PICTURE', 'SOCNET_GROUP_ID',
								'UF_*'
							)
						);
						if ($arSrcSect = $rsSections->Fetch())
						{
							if (!$allowedSectionOperation) {
								$sectionsErr = true;
								continue;
							}

							$arDestSect = $arSrcSect;
							unset($arDestSect['ID']);
							$arDestSect['IBLOCK_ID'] = $intDestIBlockID;
							if ($arDestIBFields['SECTION_CODE']['IS_REQUIRED'] == 'Y') {
								if ((string)$arDestSect['CODE'] === '') {
									$arDestSect['CODE'] = mt_rand(100000, 1000000);
								}
							}
							if ($arDestIBFields['SECTION_CODE']['DEFAULT_VALUE']['UNIQUE'] == 'Y') {
								$boolScCodeUnique = $boolSectCodeUnique;
								if (!$boolSectCodeUnique) {
									$rsCheckItems  = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $intDestIBlockID, '=CODE' => $arSrc['CODE'], 'CHECK_PERMISSIONS' => 'N'),
																	false, array('nTopCount' => 1), array('ID', 'IBLOCK_ID'));
									if ($arCheck = $rsCheckItems->Fetch()) {
										$boolScCodeUnique = true;
									}
								}
								if ($boolScCodeUnique) {
									$arDestSect['CODE'] .= mt_rand(100, 10000);
								}
							}

							if ($intSetSectID > 0) {
								$arDestSect['IBLOCK_SECTION_ID'] = $intSetSectID;
							} elseif ($intSrcIBlockID != $intDestIBlockID) {
								$arDestSect['IBLOCK_SECTION_ID'] = 0;
							}

							$arDestSect['PICTURE'] = (int)$arDestSect['PICTURE'];
							if ($arDestSect['PICTURE'] > 0) {
								$arDestSect['PICTURE'] = CFile::MakeFileArray($arDestSect['PICTURE']);
								if (empty($arDestSect['PICTURE'])) {
									$arDestSect['PICTURE'] = false;
								} else {
									$arDestSect['PICTURE']['COPY_FILE'] = 'Y';
								}
							} else {
								$arDestSect['PICTURE'] = false;
							}
							$arDestSect['DETAIL_PICTURE'] = (int)$arDestSect['DETAIL_PICTURE'];
							if ($arDestSect['DETAIL_PICTURE'] > 0) {
								$arDestSect['DETAIL_PICTURE'] = CFile::MakeFileArray($arDestSect['DETAIL_PICTURE']);
								if (empty($arDestSect['DETAIL_PICTURE'])) {
									$arDestSect['DETAIL_PICTURE'] = false;
								} else {
									$arDestSect['DETAIL_PICTURE']['COPY_FILE'] = 'Y';
								}
							} else {
								$arDestSect['DETAIL_PICTURE'] = false;
							}

							if (!$boolUFListCache) {
								$boolUFListCache = true;
								$arUFListCache = $USER_FIELD_MANAGER->GetUserFields('IBLOCK_'.$intDestIBlockID.'_SECTION');
								if (!empty($arUFListCache)) {
									if ($intSrcIBlockID != $intDestIBlockID) {
										$arOldUFListCache = $USER_FIELD_MANAGER->GetUserFields('IBLOCK_'.$intSrcIBlockID.'_SECTION');
										if (empty($arOldUFListCache)) {
											$arUFListCache = array();
										}
									} else {
										$arOldUFListCache = $arUFListCache;
									}
								}
								if (!empty($arUFListCache)) {
									if ($intSrcIBlockID != $intDestIBlockID) {
										foreach ($arUFListCache as &$arOneUserField) {
											if ('enum' == $arOneUserField['USER_TYPE']['BASE_TYPE']) {
												$arUFEnumCache[$arOneUserField['FIELD_NAME']] = array();
												$arUFNameEnumCache[$arOneUserField['FIELD_NAME']] = array();
												$rsEnum = $obEnum->GetList(array(), array('USER_FIELD_ID'=>$arOneUserField['ID']));
												while ($arEnum = $rsEnum->Fetch()) {
													$arUFEnumCache[$arOneUserField['FIELD_NAME']][$arEnum['XML_ID']] = $arEnum['ID'];
													$arUFNameEnumCache[$arOneUserField['FIELD_NAME']][$arEnum['ID']] = trim($arEnum['VALUE']);
												}
											}
										}
										if (isset($arOneUserField)) {
											unset($arOneUserField);
										}
										foreach ($arOldUFListCache as &$arOneUserField) {
											if ($arOneUserField['USER_TYPE']['BASE_TYPE'] == 'enum') {
												$arOldUFEnumCache[$arOneUserField['FIELD_NAME']] = array();
												$arOldUFNameEnumCache[$arOneUserField['FIELD_NAME']] = array();
												$rsEnum = $obEnum->GetList(array(), array('USER_FIELD_ID'=>$arOneUserField['ID']));
												while ($arEnum = $rsEnum->Fetch()) {
													$arOldUFEnumCache[$arOneUserField['FIELD_NAME']][$arEnum['ID']] = $arEnum['XML_ID'];
													$arOldUFNameEnumCache[$arOneUserField['FIELD_NAME']][$arEnum['ID']] = trim($arEnum['VALUE']);
												}
											}
										}
										if (isset($arOneUserField)) {
											unset($arOneUserField);
										}
									}
								}
							}

							if (!empty($arUFListCache)) {
								foreach ($arUFListCache as &$arOneUserField) {
									if (!isset($arDestSect[$arOneUserField['FIELD_NAME']])) {
										continue;
									}
									if ($arOneUserField['USER_TYPE']['BASE_TYPE'] == 'file') {
										if (!empty($arDestSect[$arOneUserField['FIELD_NAME']])) {
											if (is_array($arDestSect[$arOneUserField['FIELD_NAME']])) {
												$arNewFileList = array();
												foreach ($arDestSect[$arOneUserField['FIELD_NAME']] as &$intFileID) {
													$arNewFile = false;
													$intFileID = (int)$intFileID;
													if ($intFileID > 0) {
														$arNewFile = CFile::MakeFileArray($intFileID);
													}
													if (!empty($arNewFile)) {
														$arNewFileList[] = $arNewFile;
													}
												}
												if (isset($intFileID)) {
													unset($intFileID);
												}
												$arDestSect[$arOneUserField['FIELD_NAME']] = (!empty($arNewFileList) ? $arNewFileList : false);
											} else {
												$arNewFile = false;
												$intFileID = (int)$arDestSect[$arOneUserField['FIELD_NAME']];
												if ($intFileID > 0) {
													$arNewFile = CFile::MakeFileArray($intFileID);
												}
												$arDestSect[$arOneUserField['FIELD_NAME']] = (!empty($arNewFile) ? $arNewFile : false);
											}
										} else {
											$arDestSect[$arOneUserField['FIELD_NAME']] = false;
										}
									} elseif ($arOneUserField['USER_TYPE']['BASE_TYPE'] == 'enum') {
										if (!empty($arDestSect[$arOneUserField['FIELD_NAME']])) {
											if ($intSrcIBlockID != $intDestIBlockID) {
												if (array_key_exists($arOneUserField['FIELD_NAME'], $arUFEnumCache) && array_key_exists($arOneUserField['FIELD_NAME'], $arOldUFEnumCache)) {
													if (is_array($arDestSect[$arOneUserField['FIELD_NAME']])) {
														$arNewEnumList = array();
														foreach ($arDestSect[$arOneUserField['FIELD_NAME']] as &$intValueID) {
															$strValueXmlID = $arOldUFEnumCache[$arOneUserField['FIELD_NAME']][$intValueID];
															if (array_key_exists($strValueXmlID, $arUFEnumCache[$arOneUserField['FIELD_NAME']])) {
																$arNewEnumList[] = $arUFEnumCache[$arOneUserField['FIELD_NAME']][$strValueXmlID];
															} else {
																$strValueName = $arOldUFNameEnumCache[$arOneUserField['FIELD_NAME']][$intValueID];
																$intValueKey = array_search($strValueName, $arUFNameEnumCache[$arOneUserField['FIELD_NAME']]);
																if ($intValueKey !== false) {
																	$arNewEnumList[] = $intValueKey;
																}
															}
														}
														if (isset($intValueID)) {
															unset($intValueID);
														}
														if (!empty($arNewEnumList)) {
															$arDestSect[$arOneUserField['FIELD_NAME']] = $arNewEnumList;
														}
													} else {
														$strValueXmlID = $arOldUFEnumCache[$arOneUserField['FIELD_NAME']][$arDestSect[$arOneUserField['FIELD_NAME']]];
														if (array_key_exists($strValueXmlID, $arUFEnumCache[$arOneUserField['FIELD_NAME']])) {
															$arDestSect[$arOneUserField['FIELD_NAME']] = $arUFEnumCache[$arOneUserField['FIELD_NAME']][$strValueXmlID];
														} else {
															$strValueName = $arOldUFNameEnumCache[$arOneUserField['FIELD_NAME']][$arDestSect[$arOneUserField['FIELD_NAME']]];
															$intValueKey = array_search($strValueName, $arUFNameEnumCache[$arOneUserField['FIELD_NAME']]);
															if ($intValueKey !== false) {
																$arDestSect[$arOneUserField['FIELD_NAME']] = $intValueKey;
															}
														}
													}
												}
											}
										} else {
											$arDestSect[$arOneUserField['FIELD_NAME']] = false;
										}
									}
								}
								if (isset($arOneUserField)) {
									unset($arOneUserField);
								}
							}

							$intNewID = $sc->Add($arDestSect);
							if (!$intNewID) {
								CASDiblock::$error .= '['.$ID.'] '.$sc->LAST_ERROR."\n";
							}
						}
					}
				}
				if ($sectionsErr) {
					CASDiblock::$error .= Loc::getMessage('ASD_ACTION_ERR_MULTI_SECTION_TO_SECTION');
				}
				$successRedirect = empty(CASDiblock::$error);
			}
			if ($successRedirect) {
				self::clearRequest();
				LocalRedirect(self::getRedirectUrl(array('asd_ib_dest', 'asd_sect_dest', 'ID')));
			}
		}

		if ($gridAction=='asd_remove' && $IBLOCK_ID > 0 && isset($_REQUEST['find_section_section']) &&
			!empty($_REQUEST['ID']) && CASDIblockRights::IsIBlockDisplay($IBLOCK_ID)
		) {
			$intSectionID = (int)$_REQUEST['find_section_section'];
			if ($intSectionID > 0) {
				$elementObj = new CIBlockElement();
				$workflowMode = (CIBlock::GetArrayByID($IBLOCK_ID, 'WORKFLOW') != 'N' && Loader::includeModule('workflow'));
				$strCurPage = $GLOBALS['APPLICATION']->GetCurPage();
				$bElemPage = ($strCurPage=='/bitrix/admin/iblock_element_admin.php' ||
							$strCurPage=='/bitrix/admin/cat_product_admin.php'
				);
				$bMixPage = ($strCurPage=='/bitrix/admin/iblock_list_admin.php');
				if ($bElemPage || $bMixPage) {
					foreach ($_REQUEST['ID'] as $eID) {
						if ($bMixPage) {
							if (substr($eID, 0, 1) != 'E') {
								continue;
							}
							$ID = (int)substr($eID, 1);
						} else {
							$ID = (int)$eID;
						}
						if ($ID <= 0)
							continue;
						$iterator = CIBlockElement::GetList(
							array(),
							array('ID' => $ID, 'IBLOCK_ID' => $IBLOCK_ID),
							false,
							false,
							array('ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID')
						);
						$currentElement = $iterator->Fetch();
						if (empty($currentElement))
							continue;
						if (CASDIblockRights::IsElementEdit($IBLOCK_ID, $ID)) {
							$currentElement['IBLOCK_SECTION_ID'] = (int)$currentElement['IBLOCK_SECTION_ID'];
							$checkMainSection = ($currentElement['IBLOCK_SECTION_ID'] == $intSectionID);
							$minSectionId = null;
							$arSectionList = array();
							$rsSections = CIBlockElement::GetElementGroups($ID, true);
							while ($arSection = $rsSections->Fetch()) {
								$arSection['ID'] = (int)$arSection['ID'];
								if ($arSection['ID'] != $intSectionID) {
									$arSectionList[] = $arSection['ID'];
									if ($minSectionId === null || $minSectionId > $arSection['ID']) {
										$minSectionId = $arSection['ID'];
									}
								}
							}

							$fields = array(
								'IBLOCK_SECTION' => $arSectionList
							);
							if (CASDiblockVersion::checkMinVersion('15.5.11') && $checkMainSection) {
								$fields['IBLOCK_SECTION_ID'] = $minSectionId;
							}
							$result = $elementObj->Update($ID, $fields, $workflowMode);
							if (!$result)
								CASDiblock::$error .= '['.$ID.'] '.$elementObj->LAST_ERROR."\n";
							$successRedirect = empty(CASDiblock::$error);
						}
					}
				}
				unset($elementObj);
			}
			if ($successRedirect) {
				self::clearRequest();
				LocalRedirect(self::getRedirectUrl());
			}
		}
	}

	public static function OnAfterIBlockUpdateHandler($arFields) {
		if ($arFields['RESULT'] && CASDIblockRights::IsIBlockEdit($arFields['ID'])) {
			global $USER_FIELD_MANAGER;
			$PROPERTY_ID = CASDiblock::$UF_IBLOCK;
			$USER_FIELD_MANAGER->EditFormAddFields($PROPERTY_ID, $arFields);
			$USER_FIELD_MANAGER->Update($PROPERTY_ID, $arFields['ID'], $arFields);
		}
	}

	private static function initAction() {
		self::$gridAction = '';
		self::$action = '';
		if (CASDiblockVersion::isIblockNewGridv18()) {
			if (isset($_REQUEST['grid_id'])) {
				self::$gridId = $_REQUEST['grid_id'];
				if (isset($_REQUEST['action_button_'.self::$gridId])) {
					self::$gridAction = $_REQUEST['action_button_'.self::$gridId];
				} else {
					if (isset($_REQUEST['action']) && is_array($_REQUEST['action'])) {
						if (isset($_REQUEST['action']['action_button_'.self::$gridId])) {
							self::$gridAction = $_REQUEST['action']['action_button_'.self::$gridId];
						}
						if (self::$gridAction !== '') {
							foreach ($_REQUEST['action'] as $key => $value) {
								if ($key == 'action_button_'.self::$gridId) {
									continue;
								}
								if (!isset($_REQUEST[$key])) {
									$_REQUEST[$key] = $value;
								}
							}
							unset($key, $value);
						}
					}
				}
			}
		} else {
			if (isset($_REQUEST['action_button']) && !isset($_REQUEST['action'])) {
				self::$gridAction = $_REQUEST['action_button'];
			} elseif (isset($_REQUEST['action'])) {
				self::$gridAction = $_REQUEST['action'];
			}
		}
		if (isset($_REQUEST['action_button']) && is_string($_REQUEST['action_button']) && !isset($_REQUEST['action'])) {
			self::$action = $_REQUEST['action_button'];
		} elseif (isset($_REQUEST['action']) && is_string($_REQUEST['action'])) {
			self::$action = $_REQUEST['action'];
		}
	}

	private static function getGridAction()
	{
		return self::$gridAction;
	}

	private static function getAction()
	{
		return self::$action;
	}

	private static function clearRequest() {
		unset($_REQUEST['action']);
		if (CASDiblockVersion::isIblockNewGridv18()) {
			if (isset($_REQUEST['action_button_'.self::$gridId])) {
				unset($_REQUEST['action_button_'.self::$gridId]);
			}
		} else {
			if (isset($_REQUEST['action_button'])) {
				unset($_REQUEST['action_button']);
			}
		}
	}

	private static function getRedirectUrl(array $keys = array())
	{
		$keys[] = 'action';
		if (CASDiblockVersion::isIblockNewGridv18()) {
			$keys[] = 'action_button_'.self::$gridId;
		} else {
			$keys[] = 'action_button';
		}
		return $GLOBALS['APPLICATION']->GetCurPageParam('', $keys);
	}
}