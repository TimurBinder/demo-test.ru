<?php

namespace Redsign\Tuning;

use Bitrix\Main\Config\Option;

class OptionManagerBitrix extends OptionManager
{
    public function __construct(array $options)
    {
        parent::__construct($options);
    }

    /**
     * @param string $optionName
     * @param string|array $default
     * @return string|array
     */
    public function getOption(string $optionName, $default)
    {
        /** @var string $value */
        $value = Option::get('redsign.tuning', $optionName, '', SITE_ID);
        $value = $value ?: $default;

        if (is_string($value)) {
            $formatValue = unserialize($value, ['allowed_classes' => false]);
            if ($formatValue)
                $value = $formatValue;
        }

        return $value;
    }

    /**
     * @param string $optionName
     * @param string|array $value
     * @return self
     */
    public function saveOption(string $optionName, $value): self
    {
        if (is_array($value))
            $value = serialize($value);

        Option::set('redsign.tuning', $optionName, $value, SITE_ID);

        return $this;
    }
}
