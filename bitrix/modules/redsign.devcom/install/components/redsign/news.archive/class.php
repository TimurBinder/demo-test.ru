<?php

namespace Redsign\Components;

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Loader;
use Bitrix\Main\ORM;
use Bitrix\Main\Web\Uri;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


class NewsArchive extends \CBitrixComponent
{
    protected CurrentUser $user;
    protected array $months = [];
    protected bool $bUserHaveAccess = false;
    protected bool $isIblock = true;
    protected array $urlTemplates = [];

    /**
     * Errors list.
     * @var string[]
     */
    protected array $errors = [];

    /**
     * Warnings list.
     * @var string[]
     */
    protected array $warnings = [];


    /**
     * @param \CBitrixComponent|null $component
     */
    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->user = CurrentUser::get();
    }

    public function onIncludeComponentLang()
    {
        $this->includeComponentLang(basename(__FILE__));
        Loc::loadMessages(__FILE__);
    }

    /**
     * Check Required Modules
     * @throws Main\SystemException
     */
    protected function checkModules(): void
    {
        if (!Main\Loader::includeModule('iblock'))
            throw new Main\SystemException(
                Loc::getMessage('RS_DEVCOM.NEWS_ARCHIVE.IBLOCK_MODULE_NOT_INSTALLED') ?: ''
            );

        $this->isIblock = true;
    }

    /**
     * @param array $params
     * @return array
     */
    public function onPrepareComponentParams($params): array
    {
        $params = parent::onPrepareComponentParams($params);

        if (!isset($params['CACHE_TIME']))
            $params['CACHE_TIME'] = 36000000;

        $params['IBLOCK_TYPE'] = trim($params['IBLOCK_TYPE']);
        if (strlen($params['IBLOCK_TYPE']) <= 0)
            $params['IBLOCK_TYPE'] = 'news';
        $params['IBLOCK_ID'] = trim($params['IBLOCK_ID']);

        $params['PARENT_SECTION'] = (int) $params['PARENT_SECTION'];
        if (Loader::includeModule('iblock')) {
            $params['PARENT_SECTION'] = \CIBlockFindTools::GetSectionID(
                $params['PARENT_SECTION'],
                $params['PARENT_SECTION_CODE'],
                array(
                    'GLOBAL_ACTIVE' => 'Y',
                    'IBLOCK_ID' => $params['IBLOCK_ID'],
                )
            );
        }

        $params['ARCHIVE_URL'] = trim($params['ARCHIVE_URL']);


        if (strlen($params["FILTER_NAME"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $params["FILTER_NAME"])) {
            $arrFilter = array();
        } else {
            $arrFilter = $GLOBALS[$params["FILTER_NAME"]];
            if (!is_array($arrFilter))
                $arrFilter = array();
        }

        $params['CACHE_FILTER'] = $params['CACHE_FILTER'] == 'Y';
        if (!$params['CACHE_FILTER'] && count($arrFilter) > 0)
            $params['CACHE_TIME'] = 0;

        $params['ACTIVE_DATE_FORMAT'] = trim($params['ACTIVE_DATE_FORMAT']);
        if (strlen($params['ACTIVE_DATE_FORMAT']) <= 0) {
            global $DB;
            $params['ACTIVE_DATE_FORMAT'] = $DB->DateFormatToPHP(\CSite::GetDateFormat('SHORT'));
        }

        $params['CHECK_PERMISSIONS'] = $params['CHECK_PERMISSIONS'] != 'N';

        $params['USE_PERMISSIONS'] = $params['USE_PERMISSIONS'] == 'Y';
        if (!is_array($params['GROUP_PERMISSIONS']))
            $params['GROUP_PERMISSIONS'] = array(1);

        $this->bUserHaveAccess = !$params['USE_PERMISSIONS'];
        if ($params['USE_PERMISSIONS']) {
            $arUserGroupArray = $this->user->getUserGroups();
            foreach ($params['GROUP_PERMISSIONS'] as $PERM) {
                if (in_array($PERM, $arUserGroupArray)) {
                    $this->bUserHaveAccess = true;
                    break;
                }
            }
        }

        $params["SHOW_TITLE"] = $params["SHOW_TITLE"] == "Y";
        $params["SHOW_YEARS"] = $params["SHOW_YEARS"] == "Y";
        $params["SHOW_MONTHS"] = $params["SHOW_MONTHS"] == "Y";

        return $params;
    }

    protected function getMonths(): array
    {

        if ($this->arParams['PARENT_SECTION'] > 0) {
            $arFilter['=SECTION_ID'] = $this->arParams['PARENT_SECTION'];
            if ($this->arParams['INCLUDE_SUBSECTIONS']) {
                $arFilter['=INCLUDE_SUBSECTIONS'] = 'Y';
            }
        }

        $arSelect = [
            'COUNT',
            'MONTH',
            'YEAR',
        ];

        $arFilter = [
            'ACTIVE' => 'Y',
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID']
        ];

        $arGroup = [
            'YEAR',
            'MONTH',
        ];

        $arOrder = [
            'YEAR' => 'DESC',
            'MONTH' => 'DESC',
        ];

        $arRuntimeFields = [
            new ORM\Fields\ExpressionField('COUNT', 'COUNT(*)'),
            new ORM\Fields\ExpressionField('MONTH', 'MONTH(ACTIVE_FROM)'),
            new ORM\Fields\ExpressionField('YEAR', 'YEAR(ACTIVE_FROM)'),
        ];

        $monthsIterator = \Bitrix\Iblock\ElementTable::getlist([
            'select' => $arSelect,
            'filter' => $arFilter,
            'group' => $arGroup,
            'order' => $arOrder,
            'runtime' => $arRuntimeFields,
        ]);

        $months = [];

        while ($month = $monthsIterator->fetch()) {
            $months[] = $month;
            unset($month);
        }

        return $months;
    }

    protected function prepareData(): void
    {
        /** @var \Bitrix\Main\HttpRequest */
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        $this->months = $this->getMonths();

        $year = intval($request->get('YEAR'));
        $month = intval($request->get('MONTH'));

        if ($year > 0) {
            if ($this->arParams['FILTER_NAME'] == '' || !preg_match('/^[A-Za-z_][A-Za-z01-9_]*$/', $this->arParams['FILTER_NAME']))
                $arrFilter = [];
            else $arrFilter = $GLOBALS[$this->arParams['FILTER_NAME']];

            if (!is_array($arrFilter))
                $arrFilter = [];

            if ($month == 0) {
                $start = new \Bitrix\Main\Type\Date('01.01.' . $year, 'j.n.Y');
                $end = new \Bitrix\Main\Type\Date('31.12.' . $year, 'j.n.Y');
            } else {
                $start = new \Bitrix\Main\Type\Date('01.' . $month . '.' . $year, 'j.n.Y');
                $end = new \Bitrix\Main\Type\Date('31.' . $month . '.' . $year, 'j.n.Y');
            }

            $arrFilter['><DATE_ACTIVE_FROM'] = [
                $start->toString(),
                $end->toString(),
            ];

            $GLOBALS[$this->arParams['FILTER_NAME']] = $arrFilter;
        }
    }

    protected function formatResult(): void
    {
        /** @var \Bitrix\Main\HttpRequest */
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        $sCurrentUri = $request->getRequestUri();

        $this->arResult['HAS_SELECTED'] = false;
        $this->arResult['YEARS'] = array();

        foreach ($this->months as $month) {
            if ($month['YEAR'] <= 0)
                continue;

            $date = new \Bitrix\Main\Type\Date('01.' . $month['MONTH'] . '.' . $month['YEAR'], 'j.n.Y');

            if (!isset($this->arResult['YEARS'][$month['YEAR']])) {
                $this->arResult['YEARS'][$month['YEAR']] = [
                    'NAME' => $month['YEAR'],
                    'SELECTED' => false,
                ];

                if ($this->arParams['SEF_MODE'] == 'Y') {
                    $archiveParts = [];

                    if ($this->arParams['ARCHIVE_URL']) {
                        $TEMPLATE = $this->arParams['ARCHIVE_URL'];
                    } else {
                        $TEMPLATE = '';
                    }

                    if ($TEMPLATE) {
                        //$TEMPLATE = str_replace("#YEAR#", $month['YEAR'], $TEMPLATE);
                        //$TEMPLATE = str_replace("#MONTH#", '', $TEMPLATE);
                        $archiveParts[] = $month['YEAR'];
                        $TEMPLATE = str_replace('#ARCHIVE_PATH#', implode('/', $archiveParts), $TEMPLATE);

                        $this->arResult['YEARS'][$month['YEAR']]['ARCHIVE_URL'] = preg_replace("'(?<!:)/+'s", "/", $TEMPLATE);
                    }
                } else {
                    $uri = new Uri($sCurrentUri);
                    $uri->deleteParams([
                        'YEAR',
                        'MONTH',
                    ]);
                    $uri->addParams([
                        'YEAR' => $month['YEAR'],
                    ]);
                    $this->arResult['YEARS'][$month['YEAR']]['ARCHIVE_URL'] = $uri->getUri();
                }
            }

            if ($request->get('YEAR') == $month['YEAR']) {
                $this->arResult['YEARS'][$month['YEAR']]['SELECTED'] = true;
                $this->arResult['HAS_SELECTED'] = true;
            }

            $this->arResult['YEARS'][$month['YEAR']]['COUNT'] += intval($month['COUNT']);

            $month['NAME'] = FormatDate('f', $date->getTimestamp());


            if ($this->arParams['SEF_MODE'] == 'Y') {
                $archiveParts = array();

                if ($this->arParams['ARCHIVE_URL']) {
                    $TEMPLATE = $this->arParams['ARCHIVE_URL'];
                } else {
                    $TEMPLATE = '';
                }

                if ($TEMPLATE) {
                    $archiveParts[] = $month['YEAR'];
                    $archiveParts[] = $month['MONTH'];

                    $TEMPLATE = str_replace('#ARCHIVE_PATH#', implode('/', $archiveParts), $TEMPLATE);

                    $month['ARCHIVE_URL'] = preg_replace("'(?<!:)/+'s", "/", $TEMPLATE);
                }
            } else {
                $uri = new Uri($sCurrentUri);
                $uri->deleteParams([
                    'YEAR',
                    'MONTH',
                ]);

                $uri->addParams([
                    'MONTH' => $month['MONTH'],
                ]);

                $month['ARCHIVE_URL'] = $uri->getUri();
            }

            //$month['ARCHIVE_URL'] = $this->arParams['ARCHIVE_URL'].'?year='.$month['YEAR'].'&month='.$month['MONTH'];


            if ($request->get('YEAR') == $month['YEAR'] && $request->get('MONTH') == $month['MONTH']) {
                $month['SELECTED'] = true;
                $this->arResult['HAS_SELECTED'] = true;
            }

            $this->arResult['YEARS'][$month['YEAR']]['MONTHS'][$month['MONTH']] = $month;
            unset($month);
        }

        $this->arResult['ARCHIVE_URL'] = preg_replace(
            "'(?<!:)/+'s",
            "/",
            str_replace('#ARCHIVE_PATH#', '', $this->arParams['ARCHIVE_URL'])
        );

        $this->arResult['ERRORS'] = $this->errors;
        $this->arResult['WARNINGS'] = $this->warnings;
    }

    /**
     * Extract data from cache. No action by default.
     * @return bool
     */
    protected function extractDataFromCache(): bool
    {
        return false;
    }

    protected function putDataToCache(): void
    {
    }

    protected function abortDataCache(): void
    {
    }

    /**
     * Start Component
     */
    public function executeComponent(): void
    {
        try {
            $this->checkModules();
            if (!$this->extractDataFromCache()) {
                $this->prepareData();
                $this->formatResult();

                $this->setResultCacheKeys(array());
                $this->includeComponentTemplate();
                $this->putDataToCache();
            }
        } catch (Main\SystemException $e) {
            $this->abortDataCache();

            ShowError($e->getMessage());
        }
    }
}
