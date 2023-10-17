<?php

namespace Redsign\Tuning;

use Bitrix\Main\Config\Option;
use Redsign\Tuning;

class CssFileManager
{
    /** @var ?self $instance */
    private static $instance = null;

    private string $fileColorMacros;
    private string $fileColorCompiled;
    private static string $fileColorMacrosContent = '';
    private static string $fileColorCompiledContent = '';

    public function __construct()
    {
        $this->fileColorMacros = Option::get('redsign.tuning', 'fileColorMacros', '', SITE_ID);
        $this->fileColorCompiled = Option::get('redsign.tuning', 'fileColorCompiled', '', SITE_ID);
    }

    public function generateCss(array $arMacroses = []): void
    {
        $fileContents = $this->fileGetContents();
        $this->filePutContents($fileContents);

        if (!empty($arMacroses)) {
            require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/classes/general/wizard.php');

            \CWizardUtil::ReplaceMacros($this->getFileColorCompiled(true), $arMacroses);
        }
    }

    public function removeCss(): void
    {
        \Bitrix\Main\IO\File::deleteFile($this->getFileColorCompiled(true));
    }

    public function getFileColorMacrosContent(): string
    {
        if (!isset($this->fileColorMacrosContent))
            $this->fileColorMacrosContent = $this->fileGetContents();

        return $this->fileColorMacrosContent;
    }

    public function getFileColorCompiledContent(array $arMacroses = []): string
    {
        if (!isset($this->fileColorCompiledContent)) {
            $macrosContent = $this->getFileColorMacrosContent();

            if (!empty($arMacroses)) {
                foreach ($arMacroses as $macrosName => $value) {
                    $macrosContent = str_replace('#' . $macrosName . '#', $value, $macrosContent);
                }
            }

            $this->fileColorCompiledContent = $macrosContent;
        }

        return $this->fileColorCompiledContent;
    }

    public function getFileColorMacros(bool $addDocRoot = false): string
    {
        if ($addDocRoot === true)
            return \Bitrix\Main\Application::getDocumentRoot() . $this->fileColorMacros;
        else return $this->fileColorMacros;
    }

    public function getFileColorCompiled(bool $addDocRoot = false): string
    {
        if ($addDocRoot === true)
            return \Bitrix\Main\Application::getDocumentRoot() . $this->fileColorCompiled;
        else return $this->fileColorCompiled;
    }

    public function fileGetContents(): string
    {
        $contents = file_get_contents($this->getFileColorMacros(true));
        $contents .= Tuning\WidgetPainting::getInstance()->fileGetContents();

        return $contents;
    }

    public function filePutContents(string $fileContents = ''): void
    {
        file_put_contents($this->getFileColorCompiled(true), $fileContents);
    }

    public static function getInstance(): self
    {
        if (!self::$instance)
            self::$instance = new self();

        return self::$instance;
    }
}
