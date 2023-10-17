<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Config\Option;
use Bitrix\Main\Application;

CModule::IncludeModule('iblock'); 

$options = Option::getForModule("progress365.realtyfeed");

$xmlProperties = [
    'xml_pr_rooms' => 'Количество комнат',
    'xml_pr_area' => 'Общая площадь',
    'xml_pr_floor_number' => 'Этаж',
    'xml_pr_price' => 'Цена',
    'xml_pr_house_number' => 'Номер дома',
    'xml_pr_status' => 'Статус',
    'xml_pr_photos' => 'Фото',
];

$cianFilePath = Application::getDocumentRoot().'/'.$options["cian_file_path"];

if (time() - filemtime($cianFilePath) > 24 * 60 * 60) {
    $useCommonDescription = $options["use_common_description"] == 'Y' ? true : false;
    $considerActivity = $options["consider_activity"] == 'Y' ? true : false;
    $commonDescription = $options["common_description"];
    $address = $options["address"];
    $phoneNumber = $options["phone"];
    $emailAddress = $options["email"];
    
    $cianPersonType = $options["cian_person_type"];
    $cianFirstName = $options["cian_first_name"];
    $cianSecondName = $options["cian_second_name"];
    $cianLastName = $options["cian_last_name"];
    $cianInn = $options["cian_inn"];
    
    $iblockId = $options["iblock_id"]; 
    
    $statusId = $options["xml_pr_status"];
    $statusesFull = array_filter($options, function ($key) {
        return strpos($key, "status_active_") === 0;
    }, ARRAY_FILTER_USE_KEY);
    
    $statusesIds = [];
    foreach ($statusesFull as $key => $value) {
        if ($value == 'Y') $statusesIds[] = preg_replace("/[^0-9]/", "", $key);
    }
    
    $select = ['ID', 'IBLOCK_ID', 'NAME'];
    
    $xmlPropertiesIds = [];
    
    foreach ($xmlProperties as $name => $text) {
        $id = $options[$name];
        $xmlPropertiesIds[$name] = $id;
        $select[] = 'PROPERTY_'.$id; 
    } 
    
    $arFilter = ["IBLOCK_ID" => IntVal($iblockId)];
    
    if ($considerActivity) {
        $arFilter["ACTIVE"] = "Y";
    }
    
    if ($statusId && $statusesIds) {
        $arFilter["PROPERTY_".$statusId] = $statusesIds;
    }
    
    $res = CIBlockElement::GetList(
        Array(), 
        $arFilter ,
        false,
        Array(),
        $select
    );
    
    $elements = [];
    
    $housesFloors = [];
    
    while($ob = $res->Fetch()){
        
        $el = [
            'id' => $ob['ID'],
            'description' => $useCommonDescription ? $commonDescription : $ob['NAME'],
        ];
    
        foreach ($xmlPropertiesIds as $name => $id) {
            if ($name == 'xml_pr_floor_number') {
                $value = preg_replace("/[^0-9]/", "", $ob["PROPERTY_".$id."_VALUE"]);
            } elseif ($name == 'xml_pr_area') {
                $value = str_replace(",", ".", $ob["PROPERTY_".$id."_VALUE"]);
            } elseif ($name == 'xml_pr_house_number') {
                $enumId = $ob["PROPERTY_".$id."_ENUM_ID"];
                if (isset($housesFloors[$enumId])) {
                    $el['house_floors'] = $housesFloors[$enumId];
                } else {
                    $houseFloors = $options["house_".$enumId."_floors"];
                    $el['house_floors'] =  $houseFloors;
                    $housesFloors[$enumId] = $houseFloors;
                }
                $value = $ob["PROPERTY_".$id."_VALUE"];
            } elseif ($name == 'xml_pr_photos') {
                $el['photos'] = [];
                $propRes = CIBlockElement::GetProperty(IntVal($iblockId), $ob['ID'], array("sort" => "asc"), array("ID" => $xmlPropertiesIds['xml_pr_photos']));
                while ($file = $propRes->GetNext())
                {
                    $el['photos'][] = 'http://' . $_SERVER['HTTP_HOST'] . CFile::GetPath($file['VALUE']);
                }
            } else {
                $value = $ob["PROPERTY_".$id."_VALUE"];
            }
            $el[$name] = $value;
        } 
    
        $elements[] = $el;
    }
    
    
    $dom = new DOMDocument('1.0', 'utf-8');
    
    $feed = $dom->createElement('feed');
    $dom->appendChild($feed);
    $feed->appendChild( $dom->createElement('feed_version', '2') );
    
    foreach ($elements as $element) {
        $object = $dom->createElement('object');
        $feed->appendChild($object);
    
        $object->appendChild($dom->createElement('ExternalId', $element['id']));
    
        $object->appendChild($dom->createElement('Description', $element['description']));
    
        $object->appendChild($dom->createElement('Address', $address.', '.$element['xml_pr_house_number']));
    
        $phones = $dom->createElement('Phones');
        $object->appendChild($phones);
    
        $phoneSchema = $dom->createElement('PhoneSchema');
        $phones->appendChild($phoneSchema);
    
        $phoneSchema->appendChild($dom->createElement('CountryCode', '+7'));
        $phoneSchema->appendChild($dom->createElement('Number', $phoneNumber));
    
        if ($emailAddress) {
            $subAgent = $dom->createElement('SubAgent');
            $object->appendChild($subAgent);
    
            $subAgent->appendChild($dom->createElement('Email', $emailAddress));
        }
    
        $object->appendChild($dom->createElement('Category', 'newBuildingFlatSale'));
    
        $object->appendChild($dom->createElement('FlatRoomsCount', $element['xml_pr_rooms']));
    
        $object->appendChild($dom->createElement('TotalArea', $element['xml_pr_area']));
    
        $object->appendChild($dom->createElement('FloorNumber', $element['xml_pr_floor_number']));
    
        $building = $dom->createElement('Building');
        $object->appendChild($building);
    
        $building->appendChild($dom->createElement('FloorsCount', $element['house_floors']));
    
        $clpModeration = $dom->createElement('CplModeration');
        $object->appendChild($clpModeration);
    
        $clpModeration->appendChild($dom->createElement('PersonType', $cianPersonType));
    
        if ($cianPersonType == 'legal') {
            $clpModeration->appendChild($dom->createElement('Inn', $cianInn));
        } elseif ($cianPersonType == 'natural') {
            $clpModeration->appendChild($dom->createElement('FirstName', $cianFirstName));
            $clpModeration->appendChild($dom->createElement('SecondName', $cianSecondName));
            $clpModeration->appendChild($dom->createElement('LastName', $cianLastName));
        }
    
        $bargainTerms = $dom->createElement('BargainTerms');
        $object->appendChild($bargainTerms);
    
        $bargainTerms->appendChild($dom->createElement('Price', trim($element['xml_pr_price'])));
        $bargainTerms->appendChild($dom->createElement('SaleType', 'free'));
    
        if ($element['photos']) {
            $photos = $dom->createElement('Photos');
            $object->appendChild($photos);
    
            foreach ($element['photos'] as $photoUrl) {
                $photoSchema = $dom->createElement('PhotoSchema');
                $photos->appendChild($photoSchema);
        
                $photoSchema->appendChild($dom->createElement('FullUrl', $photoUrl));
            }
    
        }
    }
    
    if (file_exists($cianFilePath)) {
        file_put_contents($cianFilePath, $dom->saveXML());
    } else {
        $dom->save($cianFilePath);
    }
}

// $this->IncludeComponentTemplate();
?>