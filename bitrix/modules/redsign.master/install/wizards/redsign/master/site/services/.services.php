<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arServices = Array(
	"main" => Array(
		"NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
		"STAGES" => Array(
			"files.php", // Copy bitrix files
			"search.php", // Indexing files
			"template.php", // Install template
			"theme.php", // Install theme
			"menu.php", // Install menu
			"settings.php",
		),
	),
	"catalog" => Array(
		"NAME" => GetMessage("SERVICE_CATALOG_SETTINGS"),
		"STAGES" => Array(
			"index.php"
		),
	),
	"iblock" => Array(
		"NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
		"STAGES" => Array(
			"types.php",
			"banners_banners.php",
			"banners_side_banners.php",
			"company_awards.php",
			"company_history.php",
			"company_license.php",
			"company_partners.php",
			"company_projects.php",
			"company_reviews.php",
			"company_staff.php",
			"company_useful_documents.php",
			"company_vacancies.php",
			"forms_ask.php",
			"forms_feedback.php",
			"forms_form_apply_job.php",
			"forms_form_recall.php",
			"forms_forms_faq.php",
			"forms_order_service.php",
			"forms_product_ask.php",
			"forms_product_cheaper.php",
			"forms_staff_ask.php",
			"help_faq.php",
			"help_help_instructions.php",
			"help_help_pages.php",
			"helpinfo_articles.php",
            "helpinfo_deals.php",
			"helpinfo_news.php",
			"lightbasket_orders.php",
			"system_brands.php",
			"system_features.php",
			"system_gallery.php",
			"system_juristic_documents.php",
			"system_landings.php",
			"system_landings_enum.php",
			"system_quotes.php",
			"system_regions.php",
			"system_sidebar_information.php",
			"references.php",
			"references2.php",
		),
	),
	"sale" => Array(
		"NAME" => GetMessage("SERVICE_SALE_DEMO_DATA"),
		"STAGES" => Array(
			"locations.php",
			"step1.php",
			"step2.php",
			"step3.php"
		),
	),
	"forum" => Array(
		"NAME" => GetMessage("SERVICE_FORUM")
	),
	"advertising" => Array(
		"NAME" => GetMessage("SERVICE_ADVERTISING"),
	),
	"subscribe" => Array(
		"NAME" => GetMessage("SERVICE_SUBSCRIBE")
	),
	"redsign" => Array(
		"NAME" => GetMessage("SERVICE_REDSIGN"),
        "STAGES" => Array(
			"devcom.php",
			"devfunc.php",
			"favorite.php",
			"forms.php",
			"location.php",
			"lightbasket.php",
			"tuning.php",
			"settings.php",
		),
        "MODULE_ID" => "redsign.master"
	),
);

if (CModule::IncludeModule("catalog"))
{
    $arServices['iblock']['STAGES'] = array_merge(
        $arServices['iblock']['STAGES'],
        array(
            "catalog_catalog-sale.php",
            "catalog_catalog2-sale.php",
            "catalog_catalog3-sale.php",
            "catalog_catalog4-sale.php",
            "catalog_services-sale.php",
            "catalog_services3-sale.php",
            "catalog_services4-sale.php",
        )
    );
}
else
{
    $arServices['iblock']['STAGES'] = array_merge(
        $arServices['iblock']['STAGES'],
        array(
			"catalog_catalog.php",
			"catalog_services.php",
        )
    );
}
?>