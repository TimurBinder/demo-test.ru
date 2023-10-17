<?php

namespace Redsign\Components;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


class Forms extends \CBitrixComponent
{
    protected array $messages = [
        'ERRORS' => [],
        'SUCCESS' => [],
    ];

    protected array $fields = [];

    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }

    /**
     * @param array $params
     * @return array
     */
    public function onPrepareComponentParams($params): array
    {
        $params['IBLOCK_ID'] = isset($params['IBLOCK_ID']) ? (int)$params['IBLOCK_ID'] : 0;
        $params['USE_CAPTCHA'] = isset($params['USE_CAPTCHA']) && $params['USE_CAPTCHA'] == 'Y' ? 'Y' : 'N';

        return $params;
    }

    public function getFields(): void
    {
        if ($this->arParams['IBLOCK_ID'] == 0)
            return;

        $propertyIterator = Iblock\PropertyTable::getList([
            'filter' => [
                '=ACTIVE' => 'Y',
                '=IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
            ],
            'order' => ['SORT' => 'ASC'],
        ]);

        while ($arProperty = $propertyIterator->fetch()) {
            if ($arProperty['PROPERTY_TYPE'] != 'S' && $arProperty['PROPERTY_TYPE'] != 'L')
                continue;

            if ($arProperty['PROPERTY_TYPE'] == 'L') {
                $propertyEnumIterator = Iblock\PropertyEnumerationTable::getList([
                    'filter' => [
                        '=PROPERTY_ID' => $arProperty['ID'],
                    ],
                ]);
                $arProperty['VALUES'] = $propertyEnumIterator->fetchAll();
            }

            $arProperty['CURRENT_VALUE'] = '';

            $this->fields[] = $arProperty;
        }
    }

    protected function getCaptchaCode(): string
    {
        $cpt = new \CCaptcha();
        $captchaPass = Option::get('main', 'captcha_password', '');
        if ($captchaPass == '') {
            $captchaPass = $this->randString(10);
            Option::set('main', 'captcha_password', $captchaPass);
        }
        $cpt->SetCodeCrypt($captchaPass);

        return htmlspecialcharsbx($cpt->GetCodeCrypt());
    }

    protected function getResult(): void
    {
        /** @var \Bitrix\Main\HttpRequest */
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        $this->arResult['REQUEST_URI'] = $request->getRequestUri();

        $this->arResult['FORM_NAME'] = 'form_' . (\Bitrix\Main\Security\Random::getString(6));

        $this->arResult['FIELDS'] = $this->fields;

        foreach ($this->fields as $field) {
            $this->arResult['USER_CONSENT_PROPERTY_DATA'][] = $field['NAME'];
        }

        if ($this->arParams['USE_CAPTCHA'] == 'Y') {
            $this->arResult['CAPTCHA_CODE'] = $this->getCaptchaCode();
            $this->arResult['USE_CAPTCHA'] = 'Y';
        } else {
            $this->arResult['USE_CAPTCHA'] = 'N';
        }

        $this->arResult['MESSAGES'] = $this->messages;
    }

    protected function checkCaptcha(string $code, string $word): bool
    {
        $cpt = new \CCaptcha();
        $pass = Option::get('main', 'captcha_password', '');

        if ($code != '' && $cpt->CheckCodeCrypt($word, $code, $pass))
            return true;

        return false;
    }

    protected function save(): bool
    {
        /** @var \Bitrix\Main\HttpRequest */
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        if ($request->isPost() && check_bitrix_sessid()) {
            $arProps = [];

            foreach ($this->fields as &$arField) {
                if (!empty($arField['USER_TYPE']) && $arField['USER_TYPE'] == 'HTML') {
                    $arProps[$arField['CODE']] = [
                        'VALUE' => [
                            'TYPE' => 'TEXT',
                            'TEXT' => $request->getPost('FIELD_' . $arField['CODE'])
                        ]
                    ];

                    $arField['CURRENT_VALUE'] = $request->getPost('FIELD_' . $arField['CODE']);
                } else {
                    $arProps[$arField['CODE']] = $arField['CURRENT_VALUE'] = $request->getPost('FIELD_' . $arField['CODE']);
                }
            }
            unset($arField);

            if ($this->arParams['USE_CAPTCHA'] == 'Y') {
                /** @var string $captchaCode */
                $captchaCode = $request->getPost('captcha_sid') ?: '';

                /** @var string $captchaWord */
                $captchaWord = $request->getPost('captcha_word') ?: '';

                if (!$this->checkCaptcha($captchaCode, $captchaWord)) {
                    $this->messages['ERRORS'][] = Loc::getMessage('RS_FORMS_CAPTCHA_ERROR');

                    return false;
                }
            }

            $name = $request->getPost('FORM_NAME') ?: '';
            if ($name == '')
                $name = ConvertTimeStamp(false, 'FULL');

            $el = new \CIBlockElement();
            $elId = $el->add([
                'IBLOCK_SECTION_ID' => false,
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'CODE' => md5($name),
                'NAME' => $name,
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => $arProps,
            ]);

            if ($elId) {
                $this->messages['SUCCESS'][] = isset($this->arParams['SUCCESS_MESSAGE']) ? $this->arParams['SUCCESS_MESSAGE'] : Loc::getMessage('RS_FORMS_SUCCESS_MSG');

                return true;
            } else {
                $this->messages['ERRORS'] += explode('<br>', $el->LAST_ERROR);
                unset($this->messages[count($this->messages) - 1]);

                return false;
            }
        }

        return false;
    }

    protected function send(): bool
    {
        if (empty($this->arParams['EVENT_TYPE']) || empty($this->arParams['EMAIL_TO']))
            return false;

        $arEventFields = [];

        foreach ($this->fields as $field) {
            $arEventFields[$field['CODE']] = $field['CURRENT_VALUE'];
        }

        $arEventFields['EMAIL_TO'] = $this->arParams['EMAIL_TO'];

        \CEvent::Send($this->arParams['EVENT_TYPE'], SITE_ID, $arEventFields, 'N');

        return true;
    }

    public function executeComponent(): void
    {
        if (!Loader::includeModule('iblock'))
            return;

        if ($this->arParams['USE_CAPTCHA'] == 'Y') {
            include_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/captcha.php';
        }

        $this->setFramemode(false);

        $this->getFields();
        if ($this->save()) {
            $this->send();
        }
        $this->getResult();

        $this->includeComponentTemplate();
    }
}
