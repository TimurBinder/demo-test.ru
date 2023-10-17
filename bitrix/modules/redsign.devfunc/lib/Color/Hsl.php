<?php

namespace Redsign\DevFunc\Color;

class Hsl
{
    private float $hue = 0;
    private float $saturation = 0;
    private float $lightness = 0;

    public function __construct(float $hue, float $saturation, float $lightness)
    {
        $this->hue = $hue;
        $this->saturation = $saturation;
        $this->lightness = $lightness;
    }

    public function getHue(): float
    {
        return $this->hue;
    }

    public function setHue(float $hue): self
    {
        $this->hue = $hue;

        return $this;
    }

    public function getSaturation(): float
    {
        return $this->saturation;
    }

    public function setSaturation(float $saturation): self
    {
        $this->saturation = $saturation;

        return $this;
    }

    public function getLightness(): float
    {
        return $this->lightness;
    }

    public function setLightness(float $lightness): self
    {
        $this->lightness = $lightness;

        return $this;
    }
}
