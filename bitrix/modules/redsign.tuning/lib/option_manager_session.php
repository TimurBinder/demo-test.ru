<?php

namespace Redsign\Tuning;

class OptionManagerSession extends OptionManager
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
        $session = \Bitrix\Main\Application::getInstance()->getSession();
        $data = $session->get('redsign.tuning');

        return $data[$optionName] ?: $default;
    }

    /**
     * @param string $optionName
     * @param string|array $value
     * @return self
     */
    public function saveOption(string $optionName, $value): self
    {
        $session = \Bitrix\Main\Application::getInstance()->getSession();
        $data = $session->get('redsign.tuning') ?: [];

        $data[$optionName] = $value;

        $session->set('redsign.tuning', $data);

        return $this;
    }
}
