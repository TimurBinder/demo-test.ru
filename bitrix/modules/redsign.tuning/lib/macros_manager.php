<?php

namespace Redsign\Tuning;

use Bitrix\Main\Event;

class MacrosManager
{
    /** @var ?self $instance */
    private static $instance = null;

    private array $macrosList = [];
    private TuningCore $instanceTuning;

    public function __construct(TuningCore $tuning)
    {
        $this->instanceTuning = $tuning;
    }

    public function set(string $macrosName, string $value): self
    {
        $this->macrosList[$macrosName] = $value;

        return $this;
    }

    public function get(string $macrosName): ?string
    {
        return $this->macrosList[$macrosName];
    }

    public function getList(): array
    {
        return $this->macrosList;
    }

    public function getReadyMacros(): array
    {
        $event = new Event(
            'redsign.tuning',
            'onBeforeGetReadyMacros',
            [
                'ENTITY' => self::$instance,
            ]
        );
        $event->send();

        return $this->getList();
    }

    public function initMacrosList(): void
    {
        $instanceOptionManager = $this->instanceTuning->getInstanceOptionMananger();

        $optionList = $instanceOptionManager->getOptions();

        if (!empty($optionList)) {
            foreach ($optionList as $id => $arOption) {
                if ($arOption['MULTIPLE'] == 'Y') {
                    /** @var array $arValues */
                    $arValues = $instanceOptionManager->get($id) ?: [];

                    foreach ($arOption['VALUES'] as $id2 => $arMultipleOption) {
                        // save macros values
                        $macrosName = $arMultipleOption['MACROS'] ?: '';

                        if (empty($arMultipleOption['CONTROL_NAME']) || $macrosName == '')
                            continue;

                        /** @var string $tmpValue */
                        $tmpValue = $arValues[$id2] ?: '';
                        if ($macrosName && $tmpValue) {
                            $this->set($macrosName, $tmpValue);

                            // tuning repaiting
                            if (array_key_exists('TUNING_COLOR', $arMultipleOption)) {
                                $this->set(\Redsign\Tuning\WidgetPainting::MACROS_NAME, $tmpValue);
                            }
                        }
                    }
                } else {
                    $value = $instanceOptionManager->get($id) ?: '';
                    $macrosName = $arOption['MACROS'] ?: '';

                    /** @var string $tmpValue */
                    $tmpValue = $value;
                    if ($macrosName && $tmpValue) {
                        $this->set($macrosName, $tmpValue);

                        // tuning repaiting
                        if (array_key_exists('TUNING_COLOR', $arOption)) {
                            $this->set(\Redsign\Tuning\WidgetPainting::MACROS_NAME, $tmpValue);
                        }
                    }
                }
            }
        }
    }

    public static function getInstance(TuningCore $tuning): self
    {
        if (!self::$instance)
            self::$instance = new MacrosManager($tuning);

        return self::$instance;
    }
}
