<?php

namespace Redsign\Tuning\Interfaces;

interface OptionManagerInterface
{
    public function __construct(array $defaultOptions);

    /**
     * @param string $optionName
     * @param string|array $default
     * @return string|array
     */
    public function getOption(string $optionName, $default);

    /**
     * @param string $optionName
     * @param string|array $value
     * @return self
     */
    public function saveOption(string $optionName, $value): self;
}
