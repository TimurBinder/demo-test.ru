<?php

namespace Redsign\Master;

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Application;
use \Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

class MyTemplate
{
    private static $instance;

    protected $sHeadType;
    protected $sFootType;
    protected $sView;
    protected $footType;
    protected $arViews;

    public function __construct()
    {
        $this->arViews = array('color', 'dark', 'light');

        $sHeadType = Option::get('redsign.master', 'head_type', "", SITE_ID);
        $sFootType = Option::get('redsign.master', 'foot_type', "", SITE_ID);

        $this->setHeadType($sHeadType);
        $this->setFootType($sFootType);
        $this->setView('colors');
    }

    public function getHeadType()
    {
        return $this->sHeadType;
    }

    public function setHeadType($sHeadType)
    {
        $this->sHeadType = $sHeadType;

        if ($sHeadType == 'type4') {
            $this->sView = 'dark';
        }

        $this->setFootType(Option::get('redsign.master', 'foot_type', "", SITE_ID));
    }

    public function getFootType()
    {
        return $this->sFootType;
    }

    public function setFootType($sFootType)
    {
        if ($sFootType == 'auto') {
            $sHeadType = $this->sHeadType;
            $arWideHeadType = array('type1', 'type4', 'type5', 'type6');

            $this->sFootType = in_array($sHeadType, $arWideHeadType) ? 'type1' : 'type2';
        } else {
            $this->sFootType = $sFootType;
        }
    }

    public function getView()
    {
        return $this->sView;
    }

    public function setView($sView)
    {
        $this->sView = in_array($sView, $this->arViews) ? $sView : 'colors';
    }

    public static function getInstance()
    {
        self::$instance = !empty(self::$instance) && self::$instance instanceof MyTemplate ? self::$instance : new MyTemplate();

        return  self::$instance;
    }

    public static function rsTuningOnBeforeGetReadyMacros(\Bitrix\Main\Event $event) {

        if (!Loader::includeModule('redsign.devfunc'))
            return;

        $arParams = $event->getParameters();
        $macrosManager = $arParams['ENTITY'];

        $macrosList = $macrosManager->getList();
        
        $color11 = $macrosList['COLOR_1_1'];
        if (strlen($color11) == 6) {
            $rsColor11 = new \RSColor($color11);
            $macrosManager->set('COLOR_1_1_DARKEN_10_PERSENT', $rsColor11->darken(10)->getHex());
        } else {
            $macrosManager->set('COLOR_1_1_DARKEN_10_PERSENT', $color11);
        }

        $color12 = $macrosList['COLOR_1_2'];
        if (strlen($color12) == 6) {
            $rsColor12 = new \RSColor($color12);
            $macrosManager->set('COLOR_1_2_DARKEN_10_PERSENT', $rsColor12->darken(10)->getHex());
        } else {
            $macrosManager->set('COLOR_1_2_DARKEN_10_PERSENT', $color12);
        }
    }
    
    public static function isSale() {
        return (ModuleManager::isModuleInstalled('catalog') && ModuleManager::isModuleInstalled('sale'));
    }

    public static function getTemplatePart($sPath) {
        if (empty($sPath)) {
            return;
        }
        
        $paths = array($sPath);
        
        $sFileExt = end(explode('.', $sPath));
        array_unshift($paths, str_replace($sFileExt, '', $sPath).'custom.'.$sFileExt);

        foreach ($paths as $path) {
            $filePath = Application::getDocumentRoot().$path;
            
            if (file_exists($filePath)) {
/*
                ob_start();
                include($filePath);
                return ob_get_flush();
*/
                return $filePath;
            }
        }

        return;
    }
}
