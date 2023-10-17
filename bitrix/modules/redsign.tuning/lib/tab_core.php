<?php

namespace Redsign\Tuning;

class TabCore
{
    /** @var ?self $instance */
    private static $instance = null;

    public static array $tabList = [];

    public function __construct(array $tabs)
    {
        $this->tabList = $tabs;
    }

    public function getTabList(string $code = ''): array
    {
        if (!empty($code))
            return $this->tabList[$code];
        else return $this->tabList;
    }

    public static function getInstance(array $tabs = []): self
    {
        if (!self::$instance)
            self::$instance = new self($tabs);

        return self::$instance;
    }
}
