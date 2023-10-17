<?php

namespace Redsign\DevFunc\Color;

class Rgb
{
    private int $red = 0;
    private int $green = 0;
    private int $blue = 0;
    private int $opacity = 100;

    public function __construct(int $red = 0, int $green = 0, int $blue = 0, int $opacity = 100)
    {
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->opacity = $opacity;
    }

    public function getRed(): int
    {
        return $this->red;
    }

    public function setRed(int $red): self
    {
        $this->red = $red;

        return $this;
    }

    public function getGreen(): int
    {
        return $this->green;
    }

    public function setGreen(int $green): self
    {
        $this->green = $green;

        return $this;
    }

    public function getBlue(): int
    {
        return $this->blue;
    }

    public function setBlue(int $blue): self
    {
        $this->blue = $blue;

        return $this;
    }

    public function getOpacity(): int
    {
        return $this->opacity;
    }

    public function setOpacity(int $opacity): self
    {
        $this->opacity = $opacity;

        return $this;
    }
}
