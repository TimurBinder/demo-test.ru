<? $APPLICATION->IncludeComponent("bitrix:search.form", "type1", array(
	"USE_SUGGEST" => "Y",
		"PAGE" => "/search/index.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "N"
	)
); ?>