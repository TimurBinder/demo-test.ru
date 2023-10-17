<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

use \Bitrix\Main\Loader;

if (!Loader::includeModule('redsign.devfunc')) {
    return;
}

$params = array(
    'RESIZE' => array(
        'small' => array(
            'MAX_WIDTH' => 150,
            'MAX_HEIGHT' => 100
        ),
        'big' => array(
            'MAX_WIDTH' => 1024,
            'MAX_HEIGHT' => 512,
        ),
    ),
    'DETAIL_PICTURE' => true,
    'ADDITIONAL_PICT_PROP' => array($arParams['IBLOCK_ID'] => $arParams['ADD_PICT_PROP'])
);

RSDevFunc::getElementPictures($arResult, $params);

$arResult['RELATE_PROPS'] = array();
if (count($arResult['DISPLAY_PROPERTIES'] > 0) && Loader::includeModule('iblock')) {
    $uploadDir = \Bitrix\Main\Config\Option::get('main', 'upload_dir', 'upload');
    foreach ($arResult['DISPLAY_PROPERTIES'] as &$arProp) {
        if ($arProp['PROPERTY_TYPE'] == 'F' && count($arProp['VALUE']) > 0) {
            if ($arProp['CODE'] == $arParams['ADD_PICT_PROP']) {
                continue;
            }

            $filesIterator = \Bitrix\Main\FileTable::getList(array(
                'filter' => array(
                    'ID' => $arProp['VALUE']
                )
            ));

            $arProp['DISPLAY_VALUE'] = $filesIterator->fetchAll();

            if ($arProp['DISPLAY_VALUE'] > 0) {
                foreach ($arProp['DISPLAY_VALUE'] as &$arFile) {

                    $arFileName = explode('.', $arFile['FILE_NAME']);
                    $extenstion = end($arFileName);

                    $arFile['FILE_SRC'] = '/'.$uploadDir.'/'.$arFile['SUBDIR'].'/'.$arFile['FILE_NAME'];
                    $arFile['FILE_EXTENSION'] = strtoupper($extenstion);
                    $arFile['FILE_SIZE'] = CFile::FormatSize($arFile['FILE_SIZE'], 1);
                }
                unset($arFile);
            }
        }

        if ($arProp['PROPERTY_TYPE'] == 'E' && count($arProp['VALUE']) > 0) {
            $arResult['RELATE_PROPS'][] = $arProp;
        }
    }
    unset($arProp);

    $component = $this->GetComponent();
    $component->SetResultCacheKeys(array('RELATE_PROPS'));
}
