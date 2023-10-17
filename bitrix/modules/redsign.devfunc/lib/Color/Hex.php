<?php

namespace Redsign\DevFunc\Color;

use Bitrix\Main\ArgumentException;

class Hex
{
    private string $hex;

    public function __construct(string $hex)
    {
        $this->hex = self::checkValue($hex);
    }

    public function getValue(): string
    {
        return $this->hex;
    }

    public function setValue(string $hex): self
    {
        $this->hex = self::checkValue($hex);

        return $this;
    }

    private static function checkValue(string $hex): string
    {
        $hex = str_replace('#', '', trim($hex));
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        } elseif (strlen($hex) != 6) {
            throw new ArgumentException('HEX color needs to be 6 or 3 digits long');
        }

        return $hex;
    }
}
