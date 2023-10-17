<?php

/**
 * @var CMain $APPLICATION
 */

global $APPLICATION;

if (!function_exists('REDSIGNDevComGetProfiSize')) {
    function REDSIGNDevComGetProfiSize(int $nowW, int $nowH, int $maxW, int $maxH): array
    {
        if ($nowW <= 0 && $nowH <= 0 && $maxW <= 0 && $maxH <= 0)
            return [];

        if ($nowW > $maxW || $nowH > $maxH) {
            $factorW = $nowW / $maxW;
            $factorH = $nowH / $maxH;
            if ($factorW > $factorH) {
                $trueW = $maxW;
                $trueH = floor($nowH / $factorW);
            } elseif ($factorW < $factorH) {
                $trueW = floor($nowW / $factorH);
                $trueH = $maxH;
            } else {
                $trueW = $maxW;
                $trueH = $maxH;
            }
        } else {
            $trueW = $nowW;
            $trueH = $nowH;
        }
        return array( $trueW, $trueH );
    }
}
