<?php

namespace Redsign\Tuning;

use Bitrix\Main\Event;
use Redsign\Tuning;

class TuningCore
{
    /** @var ?self $instance */
    private static $instance = null;

    public bool $failInitialize = false;
    private OptionManager $instanceOptionManager;
    private TabCore $instanceTab;
    private TuningOption $instanceOption;
    private CssFileManager $instanceCssFileManager;
    private MacrosManager $instanceMacrosManager;

    public const EVENT_ON_AFTER_SAVE_OPTIONS = 'onAfterSaveOptions';

    public function __construct(array $params)
    {
        if (empty($params)) {
            $this->failInitialize();
        } else {
            $this->instanceOptionManager = $params['options'];
            $this->instanceTab = TabCore::getInstance($params['tabs']);
            $this->instanceOption = TuningOption::getInstance();
            $this->instanceCssFileManager = CssFileManager::getInstance();
            $this->instanceMacrosManager = MacrosManager::getInstance($this);
        }

        self::$instance = $this;
    }

    /**
     * @param string $optionName
     * @return string|array|null
     */
    public function getOptionValue(string $optionName)
    {
        if ($this->isFailInitialize())
            return null;

        if (!$optionType = $this->getInstanceOptionMananger()->getOptionTypeById($optionName))
            return null;

        if (empty($optionList = $this->getInstanceOptionMananger()->getOptionsByIds([$optionName])))
            return null;

        $arOption = $optionList[$optionName];
        $optionObj = $this->getInstanceOption()->getOptionObjectByName($optionType);

        if ($optionObj != null) {
            $value = $this->getInstanceOptionMananger()->get($optionName);

            $optionObj->prepareValueAfterGet([
                'OPTION' => &$arOption,
                'VALUE' => $value,
            ]);

            return $arOption['VALUE'];
        }

        return null;
    }

    public function getInstanceOptionMananger(): OptionManager
    {
        return $this->instanceOptionManager;
    }

    public function getInstanceTab(): TabCore
    {
        return $this->instanceTab;
    }

    public function getInstanceOption(): TuningOption
    {
        return $this->instanceOption;
    }

    public function getInstanceCssFileManager(): CssFileManager
    {
        return $this->instanceCssFileManager;
    }

    public function getInstanceMacrosManager(): MacrosManager
    {
        return $this->instanceMacrosManager;
    }

    /**
     * @param $optionName
     * @param array|string $value
     * @return void
     */
    public function setOptionValue(string $optionName, $value): void
    {
        $this->instanceOptionManager->set($optionName, $value);
    }

    public function getOptions(): array
    {
        return $this->instanceOptionManager->getOptions();
    }

    public function restoreDefaultOptions(): bool
    {
        $instanceOption = Tuning\TuningOption::getInstance();
        $instanceCssFileManager = $this->getInstanceCssFileManager();

        $optionList = $this->getOptions();

        if (!is_array($optionList) || empty($optionList))
            return false;

        foreach ($optionList as $id => $arOption) {
            $optionObj = $instanceOption->getOptionObjectByName($arOption['TYPE']);

            if ($optionObj != null) {
                if ($value = $optionObj->prepareValueBeforeRestoreDefault(['OPTION' => &$arOption])) {
                    $this->setOptionValue($id, $value);
                }
            }
        }

        $instanceCssFileManager->removeCss();

        $event = new Event('redsign.tuning', self::EVENT_ON_AFTER_SAVE_OPTIONS);
        $event->send();

        return true;
    }

    public function saveOptions(): void
    {
        $instanceOption = Tuning\TuningOption::getInstance();

        $optionList = $this->getOptions();

        /** @var \Bitrix\Main\HttpRequest */
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        foreach ($optionList as $id => $arOption) {
            $optionObj = $instanceOption->getOptionObjectByName($arOption['TYPE']);

            if ($optionObj != null) {
                $value = $optionObj->prepareValueBeforeSave([
                    'OPTION' => &$arOption,
                    'VALUE' => $request->getPost($arOption['CONTROL_NAME']),
                ]);

                if ($value) {
                    $this->setOptionValue($id, $value);
                }
            }
        }

        $event = new Event('redsign.tuning', self::EVENT_ON_AFTER_SAVE_OPTIONS);
        $event->send();
    }

    public static function getInstance(array $params = []): ?self
    {
        $instance = null;

        if (!empty(self::$instance) && self::$instance instanceof self) {
            $instance = self::$instance;

            if ($instance->isFailInitialize())
                return null;
        } else {
            $instance = new self($params);
        }

        return $instance;
    }

    public function isFailInitialize(): bool
    {
        return $this->failInitialize === true;
    }

    public function failInitialize(): void
    {
        $this->failInitialize = true;

        // throw new SystemException('Fail initialize tuning.core');
    }
}
