<?php

namespace Redsign\Master;

use Bitrix\Main\IO;
use Bitrix\Main\Application;

class SVGIconsManager
{
    protected static $svgIconsPaths = array();
    protected static $icons = array();
    protected static $isChanged = false;
    protected static $xml = '';

    protected static $svgAttributes = array(
        'version' => 1.1,
        'xmlns' => 'http://www.w3.org/2000/svg',
        'xmlns:xlink' => 'http://www.w3.org/1999/xlink',
    );

    const EXT = '.svg';
    const PREFIX_ID = 'svg-icon-';

    public static function addPath($path)
    {
        if (!in_array($path, self::$svgIconsPaths)) {
            self::$svgIconsPaths[] = $path;
            self::$isChanged = true;
        }
    }

    public static function pushIcon($key)
    {
        if (!self::isExistIcon($key)) {
            self::$icons[] = $key;
            self::$isChanged = true;
        }
    }

    public static function removeIcon($key)
    {
        if (!self::isExistIcon($key)) {
            self::$icons[] = $key;
            self::$isChanged = true;
        }
    }

    public static function isExistIcon($key)
    {
        return array_key_exists($key, self::$icons);
    }

    public static function releaseSVG()
    {
        if (!self::$isChanged) {
            return self::$xml;
        }

        $xml = self::createSVG();
        $xmlTree = $xml->GetTree();

        foreach (self::$icons as $iconKey) {
            $icon = self::getIcon($iconKey);

            if (!$icon) {
                continue;
            }

            $symbol = self::getSymbol($icon, $iconKey);
            $xmlTree->children[] = $symbol;
        }

        self::$xml = $xml->GetString();
        self::$isChanged = false;

        return self::$xml;
    }

    protected static function createSVG()
    {
        $xml = new \CDataXML();
        $xml->loadString('<svg>');

        $tree = $xml->GetTree();

        foreach (self::$svgAttributes as $attrName => $attrValue) {
            $attribute = new \CDataXMLNode();
            $attribute->name = $attrName;
            $attribute->content = $attrValue;
            $tree->attributes[] = $attribute;
        }

        return $xml;
    }

    public static function getIcon($key)
    {
        foreach (self::$svgIconsPaths as $iconPath) {
            $file = new IO\File(Application::getDocumentRoot().$iconPath.DIRECTORY_SEPARATOR.$key.self::EXT);
            if ($file->isExists()) {
                return $file->getContents();
            }
        }

        return false;
    }

    protected static function getSymbol($svg, $key)
    {
        $iconXML = new \CDataXML();
        $iconXML->loadString($svg);
        $iconXMLTree = $iconXML->GetTree();
        $iconXMLTreeChildren = $iconXMLTree->children[0];

        $idAttribute = new \CDataXMLNode();
        $idAttribute->name = 'id';
        $idAttribute->content = self::PREFIX_ID.$key;

        $viewboxAttribute = new \CDataXMLNode();
        $viewboxAttribute->name = 'viewBox';
        $viewboxAttribute->content = $iconXMLTreeChildren->getAttribute('viewBox');

        $symbol = new \CDataXMLNode();
        $symbol->name = 'symbol';
        $symbol->attributes = array($idAttribute, $viewboxAttribute);
        $symbol->children = $iconXMLTreeChildren->children();

        return $symbol;
    }
}
