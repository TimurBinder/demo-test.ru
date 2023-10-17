<?php

namespace Mst\Copyeller;

require_once(dirname(__FILE__)."/../include.php");

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class EventHandler
{
	public static function OnBeforeIBlockElementAddHandler(&$arFields)
	{
		$context = \Bitrix\Main\Application::getInstance()->getContext();
		$request = $context->getRequest();

		// if the element is copied by module 'asd.iblock'
		if (isset($request["action"]) && $request["action"] == 'asd_copy')
		{
			// if set in options, marks any element as inactive
			if (\Bitrix\Main\Config\Option::get(MODULE_ID, DEACTIVATE_ELEMENT_OPT, 'N') === "Y")
			{
				$arFields['ACTIVE'] = 'N';
			}

			// if set in options, transliterates NAME and adds it in CODE field
			// adds mt_rand() at the end
			if (\Bitrix\Main\Config\Option::get(MODULE_ID, TRANSLITERATE_CODE_OPT, 'N') === "Y")
			{
				$arFields['CODE'] = \Cutil::translit($arFields['NAME'], SITE_ID).'_'.mt_rand(100000, 1000000);
			}
			
			$parameters = array(
				'filter' => array(
					'XML_ID' => $arFields['XML_ID'],
				)
			);

			// refills filter by IBLOCK_ID
			if (isset($request['IBLOCK_ID']) && $request['IBLOCK_ID']>0) $parameters['filter']['IBLOCK_ID'] = $request['IBLOCK_ID'];

			// creates array to keep and modify $request['ID'] (cause $request['ID'] values are protected)
			$requestID = array();

			// leaves in array only IDs of elements (not sections!!!)
			foreach ($request['ID'] as $rq_key=>$rq_val)
			{
				if (intval($rq_val)>0 || substr($rq_val, 0, 1) == 'E')
				{
					$requestID[$rq_key] = str_replace('E', '', $rq_val);
				}
			}

			// refills filter by IDs
			if (isset($requestID) && is_array($requestID)  && count($requestID)>0)
			{
				if (count($requestID) == 1)
				{
					$parameters['filter']['=ID'] = current($requestID);
				}
				else
				{
					$strArgs = "'".substr(str_repeat("?i,", count($requestID)), 0, -1)."'".", '".implode("', '", $requestID)."'";
					eval("\$parameters['filter']['@ID'] = new \\Bitrix\\Main\\DB\\SqlExpression(".$strArgs.");");
				}
			}

			// gets fields of src element
			\Bitrix\Main\Loader::IncludeModule('iblock');
			$row = \Bitrix\Iblock\ElementTable::getRow($parameters);

			// if the element with the given XML_ID, IBLOCK_ID and one of IDs exists
			if (!is_null($row))
			{
				// gets equal CODEs' names (in both iblocks)
				$arFileProps = array();
				$propertyIterator = \Bitrix\Iblock\PropertyTable::getList(array(
					'select' => array('CODE'),
					'filter' => array('@IBLOCK_ID' => new \Bitrix\Main\DB\SqlExpression('?i,?i', $arFields['IBLOCK_ID'], $row['IBLOCK_ID']), '=PROPERTY_TYPE' => \Bitrix\Iblock\PropertyTable::TYPE_LIST, '=ACTIVE' => 'Y', '=CNT' => '2'),
					'group'  => array('CODE'),
					'runtime' => array(new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(ID)')),
				));
				while ($property = $propertyIterator->fetch())
					$arFileProps[$property['CODE']] = $property['CODE'];
				unset($property, $propertyIterator);

				if (count($arFileProps)>0)
				{
					// gets props with equal CODEs' names
					$arFileProps2 = array();
					$arFileProps3 = array();
					$propertyIterator = \Bitrix\Iblock\PropertyTable::getList(array(
						'select' => array('ID', 'IBLOCK_ID', 'CODE'),
						'filter' => array('@IBLOCK_ID' => new \Bitrix\Main\DB\SqlExpression('?i,?i', $arFields['IBLOCK_ID'], $row['IBLOCK_ID']), '=PROPERTY_TYPE' => \Bitrix\Iblock\PropertyTable::TYPE_LIST, '=ACTIVE' => 'Y', 'CODE' => array_values($arFileProps)),
					));
					while ($property = $propertyIterator->fetch())
					{
						$arFileProps2[$property['IBLOCK_ID']][] = $property['ID'];
						$arFileProps3[$property['IBLOCK_ID']][$property['CODE']] = $property['ID'];
					}
					unset($property, $propertyIterator);

					// adds relations and values of props with type \Bitrix\Iblock\PropertyTable::TYPE_LIST in dst iblock
					// (!!!) need to rewrite (old core)					
					if (\CModule::IncludeModule("iblock"))
					{
						// gets text values of props in src iblock
						$srcIblockProps = array();
						foreach($arFileProps2[$row['IBLOCK_ID']] as $propId)
						{
							$db_props = \CIBlockElement::GetProperty($row['IBLOCK_ID'], $row['ID'], array("sort" => "asc"), array("ID"=>$propId));
							while ($ob = $db_props->GetNext())
								$srcIblockProps[$propId][] = $ob['VALUE_ENUM'];
							//unset($ob, $db_props);
						}

						// adds values to dst iblock
						foreach ($arFileProps as $fileProp)
						{
							foreach ($srcIblockProps[$arFileProps3[$row['IBLOCK_ID']][$fileProp]] as $filePropVal)
							{
								$getRowResult = \Bitrix\Iblock\PropertyEnumerationTable::getRow(array('filter'=>array('PROPERTY_ID'=>$arFileProps3[$arFields['IBLOCK_ID']][$fileProp], 'VALUE'=>$filePropVal)));

								if (is_null($getRowResult))
								{
									$addResult = \Bitrix\Iblock\PropertyEnumerationTable::Add(array(
										'PROPERTY_ID' => $arFileProps3[$arFields['IBLOCK_ID']][$fileProp],
										'VALUE' => $filePropVal,
										'XML_ID'=>md5(uniqid(""))
										));
									if ($addResult->getId()>0) $arFields['PROPERTY_VALUES'][$fileProp][] = $addResult->getId();
								}
								else
								{
									$arFields['PROPERTY_VALUES'][$fileProp][] = $getRowResult['ID'];
								}
							}
						}
					}
				}

				// adds ID of src element in $arFields['PROPERTY_VALUES'][SAVED_SOURCE_ID_PROP] (begin)
				if (\Bitrix\Main\Config\Option::get(MODULE_ID, SAVE_SOURCE_ID_OPT, 'N') === "Y")
				{
					$IBLOCKS_SAVED_SELECTED = unserialize(\Bitrix\Main\Config\Option::get(MODULE_ID, IBLOCKS_SAVED_OPT, array()));

					if (in_array($arFields['IBLOCK_ID'], $IBLOCKS_SAVED_SELECTED))
					{
						$arFields['PROPERTY_VALUES'][SAVED_SOURCE_ID_PROP] = $row['ID'];
					}
				}
				// adds ID of src element in $arFields['PROPERTY_VALUES'][SAVED_SOURCE_ID_PROP] (end)

			}
		}
	}

	public static function OnBeforeIBlockPropertyAddHandler(&$arFields)
	{
		if($arFields["CODE"] == SAVED_SOURCE_ID_PROP && $arFields["PROPERTY_TYPE"] != "E")
        {
			global $APPLICATION;
            $APPLICATION->throwException(Loc::getMessage('MST_IBLOCK_SAVED_SOURCE_ID_PROP_MUST_BE_E'));
            return false;
        }
	}
}
