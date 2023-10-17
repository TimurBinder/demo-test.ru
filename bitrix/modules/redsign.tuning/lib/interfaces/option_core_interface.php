<?php

namespace Redsign\Tuning\Interfaces;

interface OptionCoreInterface
{
    public function showOption(array $options = []): void;
    public function onload(array $options = []): void;
    public function getPainting(): string;
}
