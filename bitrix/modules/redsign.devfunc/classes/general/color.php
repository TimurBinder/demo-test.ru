<?php

use Bitrix\Main\Localization\Loc;
use Redsign\DevFunc\Color\Hex;
use Redsign\DevFunc\Color\Hsl;
use Redsign\DevFunc\Color\Rgb;

Loc::loadMessages(__FILE__);

class RSColor
{
    private Hex $hex;
    private Hsl $hsl;
    private Rgb $rgb;
    private array $options = [
        'MIN_CONTRAST_RATIO' => self::MIN_CONTRAST_RATIO,
        'COLOR_CONTRAST_LIGHT' => self::COLOR_CONTRAST_LIGHT,
        'COLOR_CONTRAST_DARK' => self::COLOR_CONTRAST_DARK,
        'WHITE' => '#FFFFFF',
        'BLACK' => '#000000',
    ];

    public const DEFAULT_ADJUST = 10;
    public const YIQ_CONTRASTED_THRESHOLD = 150;
    public const YIQ_TEXT_LIGHT = '#FFFFFF';
    public const YIQ_TEXT_DARK = '#212529';

    public const MIN_CONTRAST_RATIO = 3.5;
    public const COLOR_CONTRAST_LIGHT = '#FFFFFF';
    public const COLOR_CONTRAST_DARK = '#000000';

    public function __construct(string $hex, array $options = [])
    {
        $this->options = array_merge($this->options, $options);

        $this->setHex(new Hex($hex));
    }

    public function __clone()
    {
        $this->hex = clone $this->hex;
        $this->hsl = clone $this->hsl;
        $this->rgb = clone $this->rgb;
    }

    public static function hex2Rgb(Hex $color): Rgb
    {
        $hex = $color->getValue();

        return new Rgb(
            (int) hexdec($hex[0] . $hex[1]),
            (int) hexdec($hex[2] . $hex[3]),
            (int) hexdec($hex[4] . $hex[5])
        );
    }

    public static function hex2Hsl(Hex $color): Hsl
    {
        $rgb = self::hex2Rgb($color);

        return self::rgb2Hsl($rgb);
    }

    public static function rgb2Hsl(Rgb $rgb): Hsl
    {
        $var_R = ($rgb->getRed() / 255);
        $var_G = ($rgb->getGreen() / 255);
        $var_B = ($rgb->getBlue() / 255);

        $var_Min = min($var_R, $var_G, $var_B);
        $var_Max = max($var_R, $var_G, $var_B);
        $del_Max = $var_Max - $var_Min;

        $hue = 0;
        $saturation = 0;
        $lightness = ($var_Max + $var_Min) / 2;

        if ($del_Max == 0) {
            $hue = 0;
            $saturation = 0;
        } else {
            if ($lightness < 0.5)
                $saturation = $del_Max / ($var_Max + $var_Min);
            else $saturation = $del_Max / (2 - $var_Max - $var_Min);

            $del_R = ((($var_Max - $var_R) / 6) + ($del_Max / 2)) / $del_Max;
            $del_G = ((($var_Max - $var_G) / 6) + ($del_Max / 2)) / $del_Max;
            $del_B = ((($var_Max - $var_B) / 6) + ($del_Max / 2)) / $del_Max;

            if ($var_R == $var_Max)
                $hue = $del_B - $del_G;
            elseif ($var_G == $var_Max)
                $hue = (1 / 3) + $del_R - $del_B;
            elseif ($var_B == $var_Max)
                $hue = (2 / 3) + $del_G - $del_R;

            if ($hue < 0)
                $hue++;

            if ($hue > 1)
                $hue--;
        }

        $hue = ($hue * 360);

        return new Hsl($hue, $saturation, $lightness);
    }

    public static function hsl2Rgb(Hsl $hsl): Rgb
    {
        $hue = $hsl->getHue() / 360;
        $saturation = $hsl->getSaturation();
        $lightness = $hsl->getLightness();

        if ($saturation == 0) {
            $r = $lightness * 255;
            $g = $lightness * 255;
            $b = $lightness * 255;
        } else {
            if ($lightness < 0.5)
                $var_2 = $lightness * (1 + $saturation);
            else $var_2 = ($lightness + $saturation) - ($saturation * $lightness);

            $var_1 = 2 * $lightness - $var_2;

            $r = round(255 * self::hue2Rgb($var_1, $var_2, $hue + (1 / 3)));
            $g = round(255 * self::hue2Rgb($var_1, $var_2, $hue));
            $b = round(255 * self::hue2Rgb($var_1, $var_2, $hue - (1 / 3)));
        }

        return new Rgb((int) $r, (int) $g, (int) $b);
    }

    public static function hsl2Hex(Hsl $hsl): Hex
    {
        $rgb = self::hsl2Rgb($hsl);

        return self::rgb2Hex($rgb);
    }

    public static function rgb2Hex(Rgb $rgb): Hex
    {
        return new Hex(sprintf('%02X%02X%02X', $rgb->getRed(), $rgb->getGreen(), $rgb->getBlue()));
    }

    public function darken(float $amount = self::DEFAULT_ADJUST): self
    {
        $hsl = $this->getHsl();
        $lightness = $hsl->getLightness();

        if ($amount) {
            $lightness = ($lightness * 100) - $amount;
            $lightness = ($lightness < 0) ? 0 : $lightness / 100;
        } else {
            $lightness = $lightness / 2;
        }

        $this->setHsl($hsl->setLightness($lightness));

        return $this;
    }

    public function lighten(float $amount = self::DEFAULT_ADJUST): self
    {
        $hsl = $this->getHsl();
        $lightness = $hsl->getLightness();

        if ($amount) {
            $lightness = ($lightness * 100) + $amount;
            $lightness = ($lightness > 100) ? 1 : $lightness / 100;
        } else {
            $lightness += (1 - $lightness) / 2;
        }

        $this->setHsl($hsl->setLightness($lightness));

        return $this;
    }

    public function adjustHue(int $deg = self::DEFAULT_ADJUST): self
    {
        $hsl = $this->getHsl();

        $this->setHsl($hsl->setHue($hsl->getHue() + $deg));

        return $this;
    }

    public function saturate(float $amount = self::DEFAULT_ADJUST): self
    {
        $hsl = $this->getHsl();
        $saturation = $hsl->getSaturation();

        if ($amount) {
            $saturation = ($saturation * 100) + $amount;
            $saturation = ($saturation > 100) ? 1 : $saturation / 100;
        } else {
            $saturation += (1 - $saturation) / 2;
        }

        $this->setHsl($hsl->setSaturation($saturation));

        return $this;
    }

    public function desaturate(float $amount = self::DEFAULT_ADJUST): self
    {
        $hsl = $this->getHsl();
        $saturation = $hsl->getSaturation();

        if ($amount) {
            $saturation = ($saturation * 100) - $amount;
            $saturation = ($saturation < 0) ? 0 : $saturation / 100;
        } else {
            $saturation = $saturation / 2 ;
        }

        $this->setHsl($hsl->setSaturation($saturation));

        return $this;
    }

    public function invert(): self
    {
        $rgb = $this->getRgb();

        $rgb->setRed(255 - $rgb->getRed());
        $rgb->setGreen(255 - $rgb->getGreen());
        $rgb->setBlue(255 - $rgb->getBlue());

        $this->setRgb($rgb);

        return $this;
    }

    public function getHsl(): Hsl
    {
        return $this->hsl;
    }

    public function setHsl(Hsl $hsl): self
    {
        $this->hsl = $hsl;

        $this->hex = self::hsl2Hex($this->hsl);
        $this->rgb = self::hex2Rgb($this->hex);

        return $this;
    }

    public function getHex(): Hex
    {
        return $this->hex;
    }

    public function setHex(Hex $hex): self
    {
        $this->hex = $hex;

        $this->rgb = self::hex2Rgb($this->hex);
        $this->hsl = self::hex2Hsl($this->hex);

        return $this;
    }

    public function getRgb(): Rgb
    {
        return $this->rgb;
    }

    public function setRgb(Rgb $rgb): self
    {
        $this->rgb = $rgb;

        $this->hex = self::rgb2Hex($this->rgb);
        $this->hsl = self::hex2Hsl($this->hex);

        return $this;
    }

    public function formatRgb(): string
    {
        $rgb = $this->getRgb();

        return $rgb->getRed() . ', ' . $rgb->getGreen() . ', ' . $rgb->getBlue();
    }

    public function formatRgba(float $opacity = 1): string
    {
        $rgb = $this->getRgb();

        return $rgb->getRed() . ', ' . $rgb->getGreen() . ', ' . $rgb->getBlue() . ', ' . $opacity;
    }

    private static function hue2Rgb(float $v1, float $v2, float $vH): float
    {
        if ($vH < 0) {
            $vH += 1;
        }
        if ($vH > 1) {
            $vH -= 1;
        }
        if ((6 * $vH) < 1) {
            return ($v1 + ($v2 - $v1) * 6 * $vH);
        }
        if ((2 * $vH) < 1) {
            return $v2;
        }
        if ((3 * $vH) < 2) {
            return ($v1 + ($v2 - $v1) * ((2 / 3) - $vH) * 6);
        }
        return $v1;
    }

    public function yiq(): bool
    {
        $rgb = $this->getRgb();

        $yiq = (($rgb->getRed() * 299) + ($rgb->getGreen() * 587) + ($rgb->getBlue() * 114)) / 1000;

        return $yiq >= self::YIQ_CONTRASTED_THRESHOLD;
    }

    public function getYiq(): self
    {
        if ($this->yiq()) {
            return new self(self::YIQ_TEXT_DARK);
        } else {
            return new self(self::YIQ_TEXT_LIGHT);
        }
    }

    public function colorLevel(float $level = 0): self
    {
        if ($level > 0) {
            $colorBase = new self('#000000');
        } else {
            $colorBase = new self('#ffffff');
        }

        $level = abs($level);

        return self::mix($colorBase, $level);
    }

    public function shade(float $level = 50): self
    {
        return (new self('#000000'))->mix($this, $level);
    }

    public function tint(float $level = 50): self
    {
        return (new self('#ffffff'))->mix($this, $level);
    }

    public function mix(self $colorBase, float $level = 50): self
    {
        if ($level) {
            $p = $level / 100;
            $w = 2 * $p - 1;
            // double a = color1->a() - color2->a();
            $a = 0;

            // $w1 = ((($w * $a == -1) ? $w : ($w + $a) / (1 + $w * $a)) + 1) / 2;
            $w1 = ((($w + $a) / (1 + $w * $a)) + 1) / 2;
            $w2 = 1 - $w1;

            $rgb = $this->getRgb();
            $baseRgb = $colorBase->getRgb();

            $rgb->setRed((int) floor($rgb->getRed() * $w1 + $baseRgb->getRed() * $w2));
            $rgb->setGreen((int) floor($rgb->getGreen() * $w1 + $baseRgb->getGreen() * $w2));
            $rgb->setBlue((int) floor($rgb->getBlue() * $w1 + $baseRgb->getBlue() * $w2));

            $this->setRgb($rgb);
        }

        return $this;
    }

    public function opaque(self $foreground): self
    {
        $rgb = $foreground->getRgb();
        $opacity = $rgb->getOpacity();

        return $foreground->setRgb($rgb->setOpacity(100))->mix($this, $opacity);
    }

    public function contrast(
        string $colorContrastDark = self::COLOR_CONTRAST_DARK,
        string $colorContrastLight = self::COLOR_CONTRAST_LIGHT,
        float $minContrastRatio = self::MIN_CONTRAST_RATIO
    ): self {
        $foregrounds = [
            $colorContrastLight ?: $this->options['COLOR_CONTRAST_LIGHT'],
            $colorContrastDark ?: $this->options['COLOR_CONTRAST_DARK'],
            $this->options['WHITE'],
            $this->options['BLACK'],
        ];
        $minContrastRatio = $minContrastRatio ?: $this->options['MIN_CONTRAST_RATIO'];
        $maxRatio = 0;
        $maxRatioColor = reset($foregrounds);

        foreach ($foregrounds as $color) {
            $color = new self($color);
            $contrastRatio = self::contrastRatio($this, $color);

            if ($contrastRatio > $minContrastRatio) {
                return $color;
            } elseif ($contrastRatio > $maxRatio) {
                $maxRatio = $contrastRatio;
                $maxRatioColor = $color;
            }
        }

        // @warn "Found no color leading to #{$min-contrast-ratio}:1 contrast ratio against #{$background}...";

        return $maxRatioColor;
    }

    public static function contrastRatio(self $background, self $foreground): float
    {
        $l1 = self::luminance($background);
        $l2 = self::luminance($background->opaque($foreground));

        return $l1 > $l2 ? ($l1 + 0.05) / ($l2 + 0.05) : ($l2 + 0.05) / ($l1 + 0.05);
    }

    private static function luminance(self $color): float
    {
        $luminanceList = [0.0008, 0.001, 0.0011, 0.0013, 0.0015, 0.0017, 0.002, 0.0022, 0.0025, 0.0027, 0.003, 0.0033, 0.0037, 0.004, 0.0044, 0.0048, 0.0052, 0.0056, 0.006, 0.0065, 0.007, 0.0075, 0.008, 0.0086, 0.0091, 0.0097, 0.0103, 0.011, 0.0116, 0.0123, 0.013, 0.0137, 0.0144, 0.0152, 0.016, 0.0168, 0.0176, 0.0185, 0.0194, 0.0203, 0.0212, 0.0222, 0.0232, 0.0242, 0.0252, 0.0262, 0.0273, 0.0284, 0.0296, 0.0307, 0.0319, 0.0331, 0.0343, 0.0356, 0.0369, 0.0382, 0.0395, 0.0409, 0.0423, 0.0437, 0.0452, 0.0467, 0.0482, 0.0497, 0.0513, 0.0529, 0.0545, 0.0561, 0.0578, 0.0595, 0.0612, 0.063, 0.0648, 0.0666, 0.0685, 0.0704, 0.0723, 0.0742, 0.0762, 0.0782, 0.0802, 0.0823, 0.0844, 0.0865, 0.0887, 0.0908, 0.0931, 0.0953, 0.0976, 0.0999, 0.1022, 0.1046, 0.107, 0.1095, 0.1119, 0.1144, 0.117, 0.1195, 0.1221, 0.1248, 0.1274, 0.1301, 0.1329, 0.1356, 0.1384, 0.1413, 0.1441, 0.147, 0.15, 0.1529, 0.1559, 0.159, 0.162, 0.1651, 0.1683, 0.1714, 0.1746, 0.1779, 0.1812, 0.1845, 0.1878, 0.1912, 0.1946, 0.1981, 0.2016, 0.2051, 0.2086, 0.2122, 0.2159, 0.2195, 0.2232, 0.227, 0.2307, 0.2346, 0.2384, 0.2423, 0.2462, 0.2502, 0.2542, 0.2582, 0.2623, 0.2664, 0.2705, 0.2747, 0.2789, 0.2831, 0.2874, 0.2918, 0.2961, 0.3005, 0.305, 0.3095, 0.314, 0.3185, 0.3231, 0.3278, 0.3325, 0.3372, 0.3419, 0.3467, 0.3515, 0.3564, 0.3613, 0.3663, 0.3712, 0.3763, 0.3813, 0.3864, 0.3916, 0.3968, 0.402, 0.4072, 0.4125, 0.4179, 0.4233, 0.4287, 0.4342, 0.4397, 0.4452, 0.4508, 0.4564, 0.4621, 0.4678, 0.4735, 0.4793, 0.4851, 0.491, 0.4969, 0.5029, 0.5089, 0.5149, 0.521, 0.5271, 0.5333, 0.5395, 0.5457, 0.552, 0.5583, 0.5647, 0.5711, 0.5776, 0.5841, 0.5906, 0.5972, 0.6038, 0.6105, 0.6172, 0.624, 0.6308, 0.6376, 0.6445, 0.6514, 0.6584, 0.6654, 0.6724, 0.6795, 0.6867, 0.6939, 0.7011, 0.7084, 0.7157, 0.7231, 0.7305, 0.7379, 0.7454, 0.7529, 0.7605, 0.7682, 0.7758, 0.7835, 0.7913, 0.7991, 0.807, 0.8148, 0.8228, 0.8308, 0.8388, 0.8469, 0.855, 0.8632, 0.8714, 0.8796, 0.8879, 0.8963, 0.9047, 0.9131, 0.9216, 0.9301, 0.9387, 0.9473, 0.956, 0.9647, 0.9734, 0.9823, 0.9911, 1];

        $rgb = [
          'r' => $color->getRgb()->getRed(),
          'g' => $color->getRgb()->getGreen(),
          'b' => $color->getRgb()->getBlue(),
        ];

        foreach ($rgb as $name => $value) {
            $rgb[$name] = ($value / 255 < 0.03928)
                ? $value / 255 / 12.92
                : $luminanceList[$value];
        }

        return $rgb['r'] * 0.2126 + $rgb['g'] * 0.7152 + $rgb['b'] * 0.0722;
    }
}
