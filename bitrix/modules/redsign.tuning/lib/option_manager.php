<?php

namespace Redsign\Tuning;

use Bitrix\Main\SystemException;
use Redsign\Tuning;

defined('ADMIN_MODULE_NAME') or define('ADMIN_MODULE_NAME', 'redsign.tuning');

abstract class OptionManager implements Interfaces\OptionManagerInterface
{
    /** @var ?self $instance */
    private static $instance = null;

    public array $options = [];
    public array $optionValuesDefault = [];
    public array $optionValues = [];
    public array $childrenOptions = [];

    public function __construct(array $options)
    {
        $this->options = $options;

        $this->initOptions();
    }

    /**
     * @param string $optionName
     * @param string|array $default
     * @return string|array
     */
    abstract public function getOption(string $optionName, $default);

    /**
     * @param string $optionName
     * @param string|array $value
     * @return self
     */
    abstract public function saveOption(string $optionName, $value): self;

    /**
     * @param string $optionName
     * @param string|array $value
     * @return void
     */
    public function set(string $optionName, $value): void
    {
        if (array_key_exists($optionName, $this->optionValues)) {
            $this->optionValues[$optionName] = $value;

            // $valueTmp = is_array($value) ? serialize($value): $value;

            $this->saveOption($optionName, $value);
        }
    }

    /**
     * @param string $optionName
     * @return array|string|null
     */
    public function get(string $optionName)
    {
        return $this->optionValues[$optionName];
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOptionsByIds(array $arIds): array
    {
        $arReturnOptions = [];

        foreach ($this->options as $id => $arOption) {
            if (in_array($id, $arIds))
                $arReturnOptions[$id] = &$this->options[$id];
        }

        return $arReturnOptions;
    }

    public function saveOptionsByArray(array $arValues): void
    {
        foreach ($arValues as $optionName => $value) {
            $this->set($optionName, $value);
        }
    }

    public function initOptions(): void
    {
        if (!is_array($this->options) || empty($this->options))
            return;

        $this->initOptionsByArray($this->options);
    }

    public function initOptionsByArray(array &$arOptions): void
    {
        foreach ($arOptions as $id => $arOption) {
            $arOptions[$id]['ID'] = $id;
            $arOptions[$id]['CSS_CLASS'] = $arOption['CSS_CLASS'] ?? '';
            $arOptions[$id]['RELOAD'] = isset($arOption['RELOAD']) && $arOption['RELOAD'] == 'Y' ? 'Y' : 'N';
            $arOptions[$id]['MULTIPLE'] = isset($arOption['MULTIPLE']) && $arOption['MULTIPLE'] == 'Y' ? 'Y' : 'N';

            $this->initOption($id, $arOptions[$id]);

            if (!empty($arOptions[$id]['CHILDREN'])) {
                $this->addChildrenOptionsByArray($arOptions[$id]['CHILDREN']);
                $this->initOptionsByArray($arOptions[$id]['CHILDREN']);
                $arOptions[$id]['CHILDREN'] = array_keys($arOptions[$id]['CHILDREN']);
            }
        }
    }

    public function initOption(string $optionName, array $arOption): void
    {
        $defaultOption = [];
        $optionValues = [];

        if ('Y' == $arOption['MULTIPLE']) {
            if (!empty($arOption['VALUES'])) {
                $defaultOption[$optionName] = [];
                $optionValues[$optionName] = [];

                foreach ($arOption['VALUES'] as $id => $arMultipleOption) {
                    if (!empty($arMultipleOption['DEFAULT'])) {
                        $defaultOption[$optionName][$id] = $arMultipleOption['DEFAULT'];
                    }
                }

                $optionValues[$optionName] = $this->getOption($optionName, $defaultOption[$optionName]);
            }
        } else {
            if (!empty($arOption['DEFAULT'])) {
                $defaultOption[$optionName] = $arOption['DEFAULT'];
                $optionValues[$optionName] = $this->getOption($optionName, $defaultOption[$optionName]);
            }
        }

        $this->optionValuesDefault = array_merge($this->optionValuesDefault, $defaultOption);
        $this->optionValues = array_merge($this->optionValues, $optionValues);
    }

    public function addChildrenOptionsByArray(array $arOptions): void
    {
        if (empty($arOptions) || !is_array($arOptions))
            return;

        $arIds = array_keys($arOptions);

        if (empty($this->options)) {
            $this->options = $arOptions;
            return;
        }

        foreach ($arOptions as $id => $arOption) {
            if (!array_key_exists($id, $this->options)) {
                $this->options[$id] = $arOption;
            }
        }

        $this->childrenOptions = array_merge($this->childrenOptions, $arIds);
    }

    public function addChildrenOptionsById(string $id): self
    {
        $this->childrenOptions[] = $id;

        return $this;
    }

    public function isChildrenById(string $id): bool
    {
        return in_array($id, $this->childrenOptions);
    }

    public function getOptionTypeById(string $id): ?string
    {
        if (array_key_exists($id, $this->options))
            return $this->options[$id]['TYPE'];
        else return null;
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            $tuning = Tuning\TuningCore::getInstance();

            if (!$tuning)
                throw new SystemException('Tuning core error');

            self::$instance = $tuning->getInstanceOptionMananger();
        }

        return self::$instance;
    }
}
