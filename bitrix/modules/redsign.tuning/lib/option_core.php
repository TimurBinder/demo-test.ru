<?php

namespace Redsign\Tuning;

use Bitrix\Main\SystemException;

abstract class OptionCore implements Interfaces\OptionCoreInterface
{
    public const UCWORD_DELIMETER = ' ';

    protected array $optionPaths = [];
    public static array $optionsList = [];

    abstract public function showOption(array $options = []): void;
    abstract public function onload(array $options = []): void;
    abstract public function getPainting(): string;

    public function __construct()
    {
    }

    public function defaultInit(): void
    {
        $arDefaultPathes = [
            __DIR__ . '/option'
        ];
        $this->addOptionsPathByArray($arDefaultPathes);
        $this->readOptionClasses();
    }

    public function readOptionClasses(): array
    {
        $options = [];

        try {
            foreach ($this->optionPaths as $path) {
                // Get all "*_option.php" files
                $arFiles = new \RegexIterator(
                    new \RecursiveIteratorIterator(
                        new \RecursiveDirectoryIterator($path)
                    ),
                    '/^.+_option.php$/'
                );

                foreach ($arFiles as $file) {
                    // Prepare filename
                    $option = $this->sanitizeName(substr($file->getFilename(), 0, -11));
                    $optionName = strtolower($option);

                    if (array_key_exists($optionName, $options))
                        continue;

                    require_once $file->getPathname();

                    $optionObject = $this->getOptionObjectByName($option);

                    if (is_object($optionObject))
                        $options[$optionName] = $optionObject;
                }
            }
        } catch (\Exception $ex) {
            throw new SystemException('Error getting options from path: ' . $path);
        }

        $this->setOptionsList($options);

        return $this->getOptionsList();
    }

    public function getOptionObjectByName(string $option): ?TuningOption
    {
        $optionNamespace = __NAMESPACE__ . "\\" . $option . 'option';
        $object = null;

        if (class_exists($optionNamespace))
            $object = new $optionNamespace();

        if (!$object instanceof TuningOption)
            return null;

        return $object;
    }

    public function getOptionObjectByOptionName(string $option): ?TuningOption
    {
        $tmp = $this->getOptionsList();
        if (isset($tmp[$option]) && $tmp[$option] instanceof TuningOption)
            return $tmp[$option];
        else return null;
    }

    public function addOptionPath(string $path, bool $before = false): self
    {
        if (!file_exists($path) || !is_dir($path))
            throw new SystemException('Option path "' . $path . '" does not exist!');

        if (!in_array($path, $this->optionPaths)) {
            if ($before)
                array_unshift($this->optionPaths, $path);
            else array_push($this->optionPaths, $path);
        }

        return $this;
    }

    public function addOptionsPathByArray(array $arPathes = []): void
    {
        foreach ($arPathes as $value) {
            $this->addOptionPath($value);
        }
    }

    public function setOptionsList(array $arOptions = []): self
    {
        $this->optionsList = $arOptions;

        return $this;
    }

    public function getOptionsList(): array
    {
        return $this->optionsList;
    }

    public function sanitizeName(string $option): string
    {
        return str_replace(self::UCWORD_DELIMETER, '', $this->ucwordsUnicode(str_replace('_', self::UCWORD_DELIMETER, $option)));
    }

    protected function ucwordsUnicode(string $str, string $encoding = 'UTF-8'): string
    {
        if (extension_loaded('mbstring'))
            return mb_convert_case($str, MB_CASE_TITLE, $encoding);

        return ucwords($str, self::UCWORD_DELIMETER);
    }
}
