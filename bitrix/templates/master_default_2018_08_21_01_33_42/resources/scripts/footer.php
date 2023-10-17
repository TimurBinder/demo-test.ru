<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

\Bitrix\Main\Loader::includeModule('redsign.master');

use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\SVGIconsManager;
use \Redsign\Master\MyTemplate;

Loc::loadMessages(__FILE__);

$isHideSidebar = $APPLICATION->GetProperty('hide_sidebar') == 'Y';
if(!$isHideSidebar) {
    $sPageClasses = ' has-sidebar';
    $sPageClasses .= ($APPLICATION->GetProperty('sidebar_position') == 'right' ? ' is-right' : ' is-left');

    $APPLICATION->AddViewContent('has-sidebar', $sPageClasses);
} elseif ($APPLICATION->GetPageProperty('wide_page') != 'Y') {
    $APPLICATION->AddViewContent('has-sidebar', ' has-container');
}


$APPLICATION->AddViewContent('svg-icons', SVGIconsManager::releaseSVG());
?>
            </div><!--l-page__content-->
            <?php if (!$isHideSidebar): ?>
                <aside class="l-page__sidebar">
                    <?php
                    $sSidebarPath = $APPLICATION->GetPageProperty('sidebar-path');
                    ?>
                    <?php if (strlen($sSidebarPath) > 0):?>

                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            ".default",
                            array(
                                "COMPONENT_TEMPLATE" => ".default",
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => $sSidebarPath,
                                "EDIT_TEMPLATE" => ""
                            ),
                            false
                        );?>

                    <?php else: ?>

                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            ".default",
                            array(
                                "COMPONENT_TEMPLATE" => ".default",
                                "AREA_FILE_SHOW" => "sect",
                                "AREA_FILE_SUFFIX" => "sidebar",
                                "AREA_FILE_RECURSIVE" => "Y",
                                "EDIT_TEMPLATE" => ""
                            ),
                            false
                        );?>

                    <?php endif; ?>

                    <?
                                  /*
                    $sSidebarMenuPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/sidebar/menu.php';
                    $sSidebarButtonsPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/sidebar/buttons.php';

                    if (file_exists($sSidebarMenuPath)) {
                        include($sSidebarMenuPath);
                    }
                    ?>

                    <?php $APPLICATION->ShowViewContent('site_sidebar'); ?>

                    <?
                    if (file_exists($sSidebarButtonsPath)) {
                        include($sSidebarButtonsPath);
                    }
                    ?>


                    <?$APPLICATION->IncludeFile('sidebar_blocks.php', array(), array("MODE" => "html"))*/?>
                </aside>
            <?php endif; ?>
        </div><!--l-page-->
        <?php include 'include/footers/'.(MyTemplate::getInstance()->getFootType()).'.php'; ?>
    </div><!--wrapper-->

    <?$APPLICATION->IncludeFile(
  		SITE_DIR."include/footer/cart.php",
  		Array(),
  		Array("MODE"=>"html")
  	);?>

    <script>
    var template_path = '<?=SITE_TEMPLATE_PATH?>';
    $('#svg-icons').setHtmlByUrl({url:template_path+'/assets/images/icons.svg?123312346'});
    </script>

	<?$APPLICATION->IncludeFile(
		SITE_DIR."include/tuning/component.php",
		Array(),
		Array("MODE"=>"html")
	);?>

    <?$APPLICATION->IncludeFile(SITE_DIR."include/template/body_end.php", array(), array("MODE" => "html"))?>

    <script>
    (function(w,d,u,b){w['Bitrix24FormObject']=b;w[b] = w[b] || function(){arguments[0].ref=u;
            (w[b].forms=w[b].forms||[]).push(arguments[0])};
            if(w[b]['forms']) return;
            s=d.createElement('script');r=1*new Date();s.async=1;s.src=u+'?'+r;
            h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
    //})(window,document,'<?=SITE_TEMPLATE_PATH?>/test.js','b24form');
    })(window,document,'https://b24-8vn23c.bitrix24.ru/bitrix/js/crm/form_loader.js','b24form');
    //console.log(document.getElementById('test'));
    b24form({"id":"7","lang":"ru","sec":"qkta8e","type":"link","click": [].slice.call(document.querySelectorAll('a[href^="/include/forms/ask/"]'))});
    </script>
</body>
</html>
