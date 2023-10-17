<?
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();

$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

Loader::includeModule($module_id);
Loader::includeModule("iblock");

$arIblocks = CIBlock::GetList();

$arIblockValues = [];
while ($el = $arIblocks->Fetch()) {
    $arIblockValues[$el['ID']] = $el['NAME'];
}

$xmlProperties = [
    'xml_pr_rooms' => 'Количество комнат',
    'xml_pr_area' => 'Общая площадь',
    'xml_pr_floor_number' => 'Этаж',
    'xml_pr_price' => 'Цена',
    'xml_pr_house_number' => 'Номер дома',
    'xml_pr_status' => 'Статус',
    'xml_pr_photos' => 'Фото',
];

// AJAX
if ($request->isPost() && check_bitrix_sessid() && $request->getPost('iblock_id') && $request->getPost('action') == 'change_iblock_id') {
    $iblockId = $request->getPost('iblock_id');

    $arIblockProperties = $iblockId ? getIBlockProperties($iblockId) : [];

    $options = '';

    foreach ($arIblockProperties as $id => $title) {
        $options .= "<option value='$id'>$title</option>";
    }

    die($options);
}

function getIBlockProperties($iblockId) {
    $arIblockProperties = [];
    $rsProperties = CIBlockProperty::GetList([], [
        'IBLOCK_ID' => $iblockId,
        'ACTIVE' => 'Y'
    ]);
    while ($arProperty = $rsProperties->Fetch()) {
        $arIblockProperties[$arProperty['ID']] = $arProperty['NAME'];
    }
    return $arIblockProperties;
}

$iblockId = Option::get($module_id, 'iblock_id');

$arIblockProperties = $iblockId ? getIBlockProperties($iblockId) : [];

$commonOptions = [
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_IBLOCK_TITLE"),
    [
        "iblock_id",
        Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_IBLOCK_ID_TITLE"),
        null,
        [
            "selectbox",
            $arIblockValues,
        ]
    ]
];

$commonOptions[] = Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_IBLOCK_PROPERTIES_TITLE");

foreach ($xmlProperties as $propertyName => $propertyTitle) {
    $commonOptions[] = [
        $propertyName,
        $propertyTitle,
        null,
        [
            "selectbox",
            $arIblockProperties,
        ]
    ];
}

$houseNumberPropID = Option::get($module_id, 'xml_pr_house_number');

$houses = [];
if ($iblockId && $houseNumberPropID) {
    $rsPropertyEnums = CIBlockPropertyEnum::GetList(
        array("SORT"=>"ASC"), 
        array("IBLOCK_ID" => $iblockId, "PROPERTY_ID" => $houseNumberPropID)
    );
    while ($arPropertyEnum = $rsPropertyEnums->GetNext()) {
        $houses[$arPropertyEnum["ID"]] = $arPropertyEnum["VALUE"];
    }
}

$commonOptions[] = Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_HOUSE_FLOORS_TITLE");

foreach ($houses as $id => $value) {
    $commonOptions[] = [
        "house_".$id."_floors",
        Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_HOUSE_FLOORS_LABEL").' '.$value,
        "",
        [
            "text"
        ]
    ];
}

$statusPropID = Option::get($module_id, 'xml_pr_status');

$statuses = [];
if ($iblockId && $statusPropID) {
    $rsPropertyEnums = CIBlockPropertyEnum::GetList(
        array("SORT"=>"ASC"), 
        array("IBLOCK_ID" => $iblockId, "PROPERTY_ID" => $statusPropID)
    );
    while ($arPropertyEnum = $rsPropertyEnums->GetNext()) {
        $statuses[$arPropertyEnum["ID"]] = $arPropertyEnum["VALUE"];
    }
}

$commonOptions[] = Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_STATUSES_TITLE");

foreach ($statuses as $id => $value) {
    $commonOptions[] = [
        "status_active_".$id,
        $value,
        "Y",
        [
            "selectbox",
            [
                'Y' => 'Выгружать с этим статусом',
                'N' => 'Не выгружать с этим статусом'
            ],
        ]
    ];
}

$commonOptions[] = Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_DESCRIPTION_TITLE");

$commonOptions[] = [
    "use_common_description",
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_USE_COMMON_DESCRIPTION_DESCRIPTION_TITLE"),
    "Y",
    [
        "checkbox"
    ]
];

$commonOptions[] = [
    "common_description",
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_COMMON_DESCRIPTION_DESCRIPTION_TITLE"),
    "",
    [
        "textarea"
    ]
];

$commonOptions[] = Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_ADDITIONAL_INFO_TITLE");

$commonOptions[] = [
    "address",
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_ADDRESS_LABEL"),
    "",
    [
        "text"
    ]
];
$commonOptions[] = [
    "phone",
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_PHONE_LABEL"),
    "",
    [
        "text"
    ]
];
$commonOptions[] = [
    "email",
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_EMAIL_LABEL"),
    "",
    [
        "text"
    ]
];


$commonOptions[] = Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_CONDITION_TITLE");

$commonOptions[] = [
    "consider_activity",
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_CONSIDER_ACTIVITY_TITLE"),
    "Y",
    [
        "checkbox"
    ]
];

$cianOptions = [
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_FILE_PATH_TITLE"),
    [
        "cian_file_path",
        Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_FILE_PATH_LABEL"),
        '',
        [
            "text"
        ]
    ]
];

$cianOptions[] = Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_CIAN_CLP_MODERATION_TITLE");

$cianOptions[] = array('note' => Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_CIAN_CLP_MODERATION_NOTE"));

$cianOptions[] = [
    "cian_person_type",
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_PERSON_TYPE_LABEL"),
    'legal',
    [
        "selectbox",
        [
            'legal' => Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_PERSON_TYPE_LEGAL"),
            'natural' => Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_PERSON_TYPE_NATURAL")
        ],
    ]
];

$cianOptions[] = [
    "cian_first_name",
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_FIRST_NAME_LABEL"),
    '',
    [
        "text"
    ]
];

$cianOptions[] = [
    "cian_second_name",
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_SECOND_NAME_LABEL"),
    '',
    [
        "text"
    ]
];

$cianOptions[] = [
    "cian_last_name",
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_LAST_NAME_LABEL"),
    '',
    [
        "text"
    ]
];

$cianOptions[] = [
    "cian_inn",
    Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_INN_LABEL"),
    '',
    [
        "text"
    ]
];


$aTabs = array(
    [
        "DIV" => "common",
        "TAB" => Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_COMMON_TAB_NAME"),
        "TITLE" => Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_COMMON_TAB_NAME"),
        "OPTIONS" => $commonOptions,
    ],
    [
        "DIV" => "cian",
        "TAB" => Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_CIAN_TAB_NAME"),
        "TITLE" => Loc::getMessage("PROGRESS365_REALTYFEED_OPTIONS_CIAN_TAB_NAME"),
        "OPTIONS" => $cianOptions,
    ]
);

// SAVE

if ($request->isPost() && check_bitrix_sessid() && $request->getPost('apply') != '') {
    foreach ($aTabs as $aTab) {
        // __AdmSettingsSaveOptions($moduleId,	$aTab['OPTIONS']);
        foreach ($aTab["OPTIONS"] as $arOption) {
            if (!is_array($arOption) || $arOption["note"]) {
                continue;
            }

            if ($request["apply"]) {
                $optionValue = $request->getPost($arOption[0]);
                if ($arOption[0] == "switch_on") {
                    if ($optionValue == "") {
                        $optionValue = "N";
                    }
                }
                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
            } elseif ($request["default"]) {
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . $module_id . "&lang=" . LANG);
}

Asset::getInstance()->addJs("/bitrix/js/main/core/core.js");

$tabControl = new CAdminTabControl("tabControl",$aTabs);

$tabControl->Begin();
?>

<form action="<? echo ($APPLICATION->GetCurPage()); ?>?mid=<? echo ($module_id); ?>&lang=<? echo (LANG); ?>" method="post" id="">

    <?
    foreach ($aTabs as $aTab) {

        if ($aTab["OPTIONS"]) {

            $tabControl->BeginNextTab();

            __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
        }
    }

    $tabControl->Buttons();
    ?>

    <input type="submit" name="apply" value="<? echo (Loc::GetMessage("PROGRESS365_REALTYFEED_OPTIONS_INPUT_APPLY")); ?>"
        class="adm-btn-save" />
    <input type="submit" name="default" value="<? echo (Loc::GetMessage("PROGRESS365_REALTYFEED_OPTIONS_INPUT_DEFAULT")); ?>" />

    <?=bitrix_sessid_post()?>

</form>


<?
$tabControl->End();
?>
<script type="text/javascript">
    BX.ready(function() {
        const selectElements = document.querySelectorAll('select[name^="xml_pr_"]');

        BX.bind(document.getElementsByName("iblock_id")[0], 'change', function() {
            console.log(this.value)

            BX.ajax.post(
                '<?= $APPLICATION->GetCurPageParam(); ?>',
                {
                    'iblock_id': this.value, // Передаем выбранный ID инфоблока
                    'action': 'change_iblock_id',
                    'sessid': BX.bitrix_sessid(), // Передаем идентификатор сессии
                },
                function(result) {
                    console.log(result);
                    selectElements.forEach(function(selectElement) {
                        selectElement.innerHTML = result;
                    });
                    // BX('common_edit_table').innerHTML = result;
                }
            );
        });
    });
</script>