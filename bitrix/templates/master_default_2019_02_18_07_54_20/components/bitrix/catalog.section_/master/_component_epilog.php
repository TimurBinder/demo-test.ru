<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var array $arParams
 * @var array $templateData
 * @var string $templateFolder
 * @var CatalogSectionComponent $component
 */
 
use \Bitrix\Main\Page\Asset;


global $APPLICATION;

if (!empty($templateData['TEMPLATE_LIBRARY']))
{
	$loadCurrency = false;
	if (!empty($templateData['CURRENCIES']))
	{
		$loadCurrency = \Bitrix\Main\Loader::includeModule('currency');
	}

	CJSCore::Init($templateData['TEMPLATE_LIBRARY']);

	if ($loadCurrency)
	{
		?>
		<script>
			BX.Currency.setCurrencies(<?=$templateData['CURRENCIES']?>);
		</script>
		<?
	}
}

if ($arParams['USE_FAVORITE'] == 'Y' && \Bitrix\Main\Loader::includeModule('redsign.favorite'))
{
	CJSCore::Init('rs_favorite');
}

//	lazy load and big data json answers
$request = \Bitrix\Main\Context::getCurrent()->getRequest();

if ($request->isAjaxRequest() && ($request->get('action') === 'catalogRefresh') && $request->get('ajax_id') == $arParams['TEMPLATE_AJAXID'])
{
	$content = ob_get_contents();
	ob_end_clean();

	list(, $sectionContainer) = explode('<!-- section-container -->', $content);

	$component::sendJsonAnswer(array(
		'section' => $sectionContainer,
		'sorter' => $APPLICATION->GetViewContent($arParams['TEMPLATE_AJAXID'].'_sorter'),
	));
}

if (
	$request->isAjaxRequest()
	&& (
		$request->get('action') === 'showMore'
		|| $request->get('action') === 'deferredLoad'
	)
)
{
	$content = ob_get_contents();
	ob_end_clean();

	list(, $itemsContainer) = explode('<!-- items-container -->', $content);
	list(, $paginationContainer) = explode('<!-- pagination-container -->', $content);

	if ($arParams['AJAX_MODE'] === 'Y')
	{
		$component->prepareLinks($paginationContainer);
		// $component->prepareLinks($filterContainer);
	}

	$component::sendJsonAnswer(array(
		'items' => $itemsContainer,
		'pagination' => $paginationContainer,
		// 'filter' => $filterContainer,
		// 'sorter' => $APPLICATION->GetViewContent($arParams['TEMPLATE_AJAXID'].'_sorter'),
	));
}