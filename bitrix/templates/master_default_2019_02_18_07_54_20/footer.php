<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

\Bitrix\Main\Loader::includeModule('redsign.master');

use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\ModuleManager;
use \Redsign\Master\SVGIconsManager;
use \Redsign\Master\MyTemplate;

Loc::loadMessages(__FILE__);

$request = Application::getInstance()->getContext()->getRequest();

if ($request->isAjaxRequest() || $request->get('rs_ajax__page') == 'Y')
{
	CMain::FinalActions();
	die();
}
		
$isHideSidebar = $APPLICATION->GetProperty('hide_sidebar') == 'Y';
if(!$isHideSidebar) {
    $sPageClasses = ' has-sidebar';
    $sPageClasses .= ($APPLICATION->GetProperty('sidebar_position') == 'right' ? ' is-right' : ' is-left');

    $APPLICATION->AddViewContent('has-sidebar', $sPageClasses);
} elseif ($APPLICATION->GetProperty('wide_page') != 'Y') {
    $APPLICATION->AddViewContent('has-sidebar', ' has-container');
}

$APPLICATION->AddViewContent('svg-icons', SVGIconsManager::releaseSVG());
?>
            </div><!--l-page__content-->
            <?php if (!$isHideSidebar): ?>
                <aside class="l-page__sidebar">

                    <?php
                    $sSidebarPath = $APPLICATION->GetProperty('sidebar-path');
                    ?>

                    <?php if (strlen($sSidebarPath) > 0 && file_exists(Application::getDocumentRoot().$sSidebarPath)):?>

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

                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "filter", Array(
	"COMPONENT_TEMPLATE" => ".default",
		"AREA_FILE_SHOW" => "sect",	// Показывать включаемую область
		"AREA_FILE_SUFFIX" => "sidebar",	// Суффикс имени файла включаемой области
		"AREA_FILE_RECURSIVE" => "Y",	// Рекурсивное подключение включаемых областей разделов
		"EDIT_TEMPLATE" => "",	// Шаблон области по умолчанию
	),
	false
);?>

                    <?php endif; ?>

                </aside>
            <?php endif; ?>
        </div><!--l-page-->
        <?php include 'include/footers/'.(MyTemplate::getInstance()->getFootType()).'.php'; ?>
    </div><!--wrapper-->

    <script>
        var template_path = '<?=SITE_TEMPLATE_PATH?>';
        $('#svg-icons').setHtmlByUrl({url:template_path+'/assets/images/icons.svg?1421'});
    </script>

	<?$APPLICATION->IncludeFile(
		SITE_DIR."include/footer/cart.php",
		Array(),
		Array("MODE"=>"html")
	);?>

  	<?$APPLICATION->IncludeFile(
  		SITE_DIR."include/tuning/component.php",
  		Array(),
  		Array("MODE"=>"html")
  	);?>
    
    <?$APPLICATION->IncludeFile(SITE_DIR."include/template/body_end.php", array(), array("MODE" => "html"))?>

	<div class="set_quantity_color_shaddow"></div>
	<div class="set_quantity_color">
	</div>
	
	
	<style>
		.set_quantity_color_shaddow{
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: #000;
			opacity: 0.6;
			z-index: 1000;
		}
		.set_quantity_color{
			display: none;
			position: fixed;
			top: 100px;
			left: 30%;
			width: 350px;
			max-width: 100%;
			background: #fff;
			z-index: 1001;
			padding: 20px;
		}
		.set_quantity_color .btn-close{
			position: absolute;
			top: -10px;
			right: -10px;
			color: red;
			text-decoration: none;
			display: block;
			border: 2px solid #000;
			border-radius: 50%;
			width: 30px;
			height: 30px;
			padding: 2px;
			background: #fff;
			text-align: center;
			font-weight: bold;
		}
	</style>
	
	<script>
		function set_quantity_color(id){
			$.ajax({
				type: "POST",
				url: "<?=SITE_TEMPLATE_PATH?>/set_quantity_color.php",
				data: "id="+id,
				success: function (html) {
					$('.set_quantity_color').html(html); 
					$('.set_quantity_color').css("top", (($(window).height() - $('.set_quantity_color').outerHeight()) / 2)  + "px");
					$('.set_quantity_color').css("left", (($(window).width() - $('.set_quantity_color').outerWidth()) / 2) + "px");
					$('.set_quantity_color_shaddow').show();
					$('.set_quantity_color').show();
				}
			});
	

		};
	</script>
 <!-- GETSALE CODE START -->
  <script type="text/javascript">
    (function(d, w, c) {
      w[c] = {
        projectId: 7283
      };

      var n = d.getElementsByTagName("script")[0],
      s = d.createElement("script"),
      f = function () { n.parentNode.insertBefore(s, n); };
      s.type = "text/javascript";
      s.async = true;
      s.src = "//rt.getsale.io/loader.js";

      if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
      } else { f(); }

    })(document, window, "getSaleInit");
  </script>
  <!-- GETSALE CODE END -->
</body>
</html>
<!--pre>
<?var_export($_SESSION)?>
</pre-->