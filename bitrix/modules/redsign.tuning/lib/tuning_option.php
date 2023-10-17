<?php

namespace Redsign\Tuning;

use Redsign\Tuning;

class TuningOption extends OptionCore
{
    /** @var ?self $instance */
    private static $instance = null;

    protected string $name = '';
    protected string $description = '';
    protected bool $isSortable = false;

    public function showOption(array $options = []): void
    {
    }

    public function onload(array $options = []): void
    {
    }

    public function getPainting(): string
    {
        return '';
    }

    public function prepareValueBeforeRestoreDefault(array $params): ?string
    {
        if (empty($params['OPTION']['CONTROL_NAME']))
            return null;

        $tuning = TuningCore::getInstance();
        if ($tuning)
            $instanceMacrosManager = Tuning\MacrosManager::getInstance($tuning);

        if ('Y' == $params['OPTION']['MULTIPLE']) {
            if (empty($params['OPTION']['VALUES']))
                return null;

            $value = [];

            foreach ($params['OPTION']['VALUES'] as $id2 => $arMultipleOption) {
                if (empty($params['OPTION']['DEFAULT']))
                    continue;

                $value[$id2] = $params['OPTION']['DEFAULT'];

                // save macros values
                $macrosName = $arMultipleOption['MACROS'] ?: '';

                /** @var string $tmpValue */
                $tmpValue = $value[$id2];

                if ($macrosName && isset($instanceMacrosManager))
                    $instanceMacrosManager->set($macrosName, $tmpValue);
            }

            return serialize($value);
        } else {
            $value = $params['OPTION']['DEFAULT'] ?: '';
            $macrosName = $params['OPTION']['MACROS'] ?: '';

            /** @var string $tmpValue */
            $tmpValue = $value;

            if ($macrosName && isset($instanceMacrosManager))
                $instanceMacrosManager->set($macrosName, $tmpValue);

            return $value;
        }
    }

    /**
     * @param $params
     * @return array|string|null
     */
    public function prepareValueBeforeSave(array $params)
    {
        if (empty($params['OPTION']['CONTROL_NAME']))
            return null;

        $tuning = TuningCore::getInstance();
        if ($tuning)
            $instanceMacrosManager = Tuning\MacrosManager::getInstance($tuning);

        if ('Y' == $params['OPTION']['MULTIPLE']) {
            if (empty($params['OPTION']['VALUES']))
                return null;

            $value = [];
            $arValue = $params['VALUE'] ?: [];
            $tmpValue = [];

            foreach ($params['OPTION']['VALUES'] as $id2 => $arMultipleOption) {
                if (empty($arValue[$arMultipleOption['CONTROL_NAME']]))
                    continue;

                $value[$id2] = $arValue[$arMultipleOption['CONTROL_NAME']];

                // save macros values
                $macrosName = $arMultipleOption['MACROS'] ?: '';

                /** @var string $tmpValue */
                $tmpValue = $value[$id2];
                if ($macrosName && isset($instanceMacrosManager))
                    $instanceMacrosManager->set($macrosName, $tmpValue);
            }

            return $value;
        } else {
            $value = $params['VALUE'] ?: '';
            $macrosName = $params['OPTION']['MACROS'] ?: '';

            /** @var string $tmpValue */
            $tmpValue = $value;

            if ($macrosName && isset($instanceMacrosManager))
                $instanceMacrosManager->set($macrosName, $tmpValue);

            return $value;
        }
    }

    public function prepareValueAfterGet(array $params): void
    {
        if ('Y' == $params['OPTION']['MULTIPLE']) {
            $arValues = $params['VALUE'];

            if (!empty($params['OPTION']['VALUES'])) {
                foreach ($params['OPTION']['VALUES'] as $id2 => $arValue) {
                    if (array_key_exists($id2, $arValues)) {
                        $params['OPTION']['VALUES'][$id2]['VALUE'] = $arValues[$id2];
                    }
                }
            }
        }

        $params['OPTION']['VALUE'] = $params['VALUE'];
    }

    public function getOptionName(): string
    {
        return $this->name;
    }

    public function isSortable(): bool
    {
        return ($this->isSortable ? $this->isSortable : false);
    }

    public function getOptionDescription(): string
    {
        return $this->description;
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
