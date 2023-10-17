<?php if ( ! defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>

<!DOCTYPE html>
<html lang="ru">

<head>
<?php $APPLICATION->showHead(); ?>
<title><?php $APPLICATION->showTitle(); ?></title>
<?php
    use Bitrix\Main\Page\Asset;
    // подключаем стили
    Asset::getInstance()->addCss('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/src/static/css/style.css');
    Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . '/src/static/css/fonts.css');
    // подключаем скрипты
    Asset::getInstance()->addJs('https://api-maps.yandex.ru/2.1/?apikey=8c8d4c22-b1c5-469c-86f5-b139c525f4e6
    &lang=ru_RU');
    Asset::getInstance()->addJs('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/src/static/js/script.js');
    // подключаем строки
    Asset::getInstance()->addString('<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">');
?>
</head>

<body>
<?$APPLICATION->ShowPanel()?>
    <div class="first-screen">
        <div class="background w-100 z-5 position-absolute">
            <img src="<?=SITE_TEMPLATE_PATH?>/src/media/img/strict.png">

            <div class="target-block">
                <div id="target-1" class="target">
                    <p><span class="d-md-inline d-none">дом</span> №1</p>
                    <div class="line">
                        <div class=""></div>
                        <div class=""></div>
                    </div>
                </div>

                <div id="target-info-1" class="target-info">
                    <h3>Дом 1</h3>
                    <p class="mt-1">94 квартиры</p>
                    <div class="d-flex justify-content-between mt-4">
                        <div class="d-flex align-items-center">
                            <div class="circle green me-3"></div>
                            <p>В продаже</p>
                        </div>
                        <p>53</p>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <div class="circle pink me-3"></div>
                            <p>Продано</p>
                        </div>
                        <p>12</p>
                    </div>
                    <div class="mt-4"></div>
                    <a href="#choose-appartment" data-home-number="1" class="button ps-3 pe-3 pt-2 pb-2">Выбрать квартиру</a>
                </div>
            </div>

            <div class="target-block">
                <div id="target-2" class="target">
                    <p><span class="d-md-inline d-none">дом</span> №2</p>
                    <div class="line">
                        <div class=""></div>
                        <div class=""></div>
                    </div>
                </div>

                <div id="target-info-2" class="target-info">
                    <h3>Дом 2</h3>
                    <p class="mt-1">94 квартиры</p>
                    <div class="d-flex justify-content-between mt-4">
                        <div class="d-flex align-items-center">
                            <div class="circle green me-3"></div>
                            <p>В продаже</p>
                        </div>
                        <p>53</p>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <div class="circle pink me-3"></div>
                            <p>Продано</p>
                        </div>
                        <p>12</p>
                    </div>
                    <div class="mt-4"></div>
                    <a href="#choose-appartment" data-home-number="2" class="button ps-3 pe-3 pt-2 pb-2">Выбрать квартиру</a>
                </div>
            </div>

            <div class="target-block">
                <div id="target-3" class="target">
                    <p><span class="d-md-inline d-none">дом</span> №3</p>
                    <div class="line">
                        <div class=""></div>
                        <div class=""></div>
                    </div>
                </div>

                <div id="target-info-3" class="target-info">
                    <h3>Дом 3</h3>
                    <p class="mt-1">94 квартиры</p>
                    <div class="d-flex justify-content-between mt-4">
                        <div class="d-flex align-items-center">
                            <div class="circle green me-3"></div>
                            <p>В продаже</p>
                        </div>
                        <p>53</p>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <div class="circle pink me-3"></div>
                            <p>Продано</p>
                        </div>
                        <p>12</p>
                    </div>
                    <div class="mt-4"></div>
                    <a href="#choose-appartment" data-home-number="3" class="button ps-3 pe-3 pt-2 pb-2">Выбрать квартиру</a>
                </div>
            </div>

            <div class="target-block">
                <div id="target-4" class="target">
                    <p><span class="d-md-inline d-none">дом</span> №4</p>
                    <div class="line">
                        <div class=""></div>
                        <div class=""></div>
                    </div>
                </div>

                <div id="target-info-4" class="target-info">
                    <h3>Дом 4</h3>
                    <p class="mt-1">94 квартиры</p>
                    <div class="d-flex justify-content-between mt-4">
                        <div class="d-flex align-items-center">
                            <div class="circle green me-3"></div>
                            <p>В продаже</p>
                        </div>
                        <p>53</p>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <div class="circle pink me-3"></div>
                            <p>Продано</p>
                        </div>
                        <p>12</p>
                    </div>
                    <div class="mt-4"></div>
                    <a href="#choose-appartment" data-home-number="4" class="button ps-3 pe-3 pt-2 pb-2">Выбрать квартиру</a>
                </div>
            </div>

            <div class="target-block">
                <div id="target-5" class="target">
                    <p><span class="d-md-inline d-none">дом</span> №5</p>
                    <div class="line">
                        <div class=""></div>
                        <div class=""></div>
                    </div>
                </div>

                <div id="target-info-5" class="target-info">
                    <h3>Дом 5</h3>
                    <p class="mt-1">94 квартиры</p>
                    <div class="d-flex justify-content-between mt-4">
                        <div class="d-flex align-items-center">
                            <div class="circle green me-3"></div>
                            <p>В продаже</p>
                        </div>
                        <p>53</p>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <div class="circle pink me-3"></div>
                            <p>Продано</p>
                        </div>
                        <p>12</p>
                    </div>
                    <div class="mt-4"></div>
                    <a href="#choose-appartment" data-home-number="5" class="button ps-3 pe-3 pt-2 pb-2">Выбрать квартиру</a>
                </div>
            </div>

            <div class="target-block">
                <div id="target-6" class="target">
                    <p><span class="d-md-inline d-none">дом</span> №6</p>
                    <div class="line">
                        <div class=""></div>
                        <div class=""></div>
                    </div>
                </div>

                <div id="target-info-6" class="target-info">
                    <h3>Дом 6</h3>
                    <p class="mt-1">94 квартиры</p>
                    <div class="d-flex justify-content-between mt-4">
                        <div class="d-flex align-items-center">
                            <div class="circle green me-3"></div>
                            <p>В продаже</p>
                        </div>
                        <p>53</p>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <div class="circle pink me-3"></div>
                            <p>Продано</p>
                        </div>
                        <p>12</p>
                    </div>
                    <div class="mt-4"></div>
                    <a href="#choose-appartment" data-home-number="4" class="button ps-3 pe-3 pt-2 pb-2">Выбрать квартиру</a>
                </div>
            </div>

            <div class="target-block">
                <div id="target-7" class="target target-empty">
                    <p></p>
                    <div class="line">
                        <div class=""></div>
                        <div class=""></div>
                    </div>
                </div>

                <div id="target-info-7" class="target-info">
                    <h3>Дом 6</h3>
                    <p class="mt-1">94 квартиры</p>
                    <div class="d-flex justify-content-between mt-4">
                        <div class="d-flex align-items-center">
                            <div class="circle green me-3"></div>
                            <p>В продаже</p>
                        </div>
                        <p>53</p>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <div class="circle pink me-3"></div>
                            <p>Продано</p>
                        </div>
                        <p>12</p>
                    </div>
                    <div class="mt-4"></div>
                    <a href="#" class="button ps-3 pe-3 pt-2 pb-2">Выбрать квартиру</a>
                </div>
            </div>

            <div class="target-block">
                <div id="target-8" class="target target-empty">
                    <p></p>
                    <div class="line">
                        <div class=""></div>
                        <div class=""></div>
                    </div>
                </div>

                <div id="target-info-8" class="target-info">
                    <h3>Дом 6</h3>
                    <p class="mt-1">94 квартиры</p>
                    <div class="d-flex justify-content-between mt-4">
                        <div class="d-flex align-items-center">
                            <div class="circle green me-3"></div>
                            <p>В продаже</p>
                        </div>
                        <p>53</p>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <div class="circle pink me-3"></div>
                            <p>Продано</p>
                        </div>
                        <p>12</p>
                    </div>
                    <div class="mt-4"></div>
                    <a href="#" class="button ps-3 pe-3 pt-2 pb-2">Выбрать квартиру</a>
                </div>
            </div>

            <div class="target-block">
                <div id="target-9" class="target target-empty">
                    <p></p>
                    <div class="line">
                        <div class=""></div>
                        <div class=""></div>
                    </div>
                </div>

                <div id="target-info-9" class="target-info">
                    <h3>Дом 6</h3>
                    <p class="mt-1">94 квартиры</p>
                    <div class="d-flex justify-content-between mt-4">
                        <div class="d-flex align-items-center">
                            <div class="circle green me-3"></div>
                            <p>В продаже</p>
                        </div>
                        <p>53</p>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <div class="d-flex align-items-center">
                            <div class="circle pink me-3"></div>
                            <p>Продано</p>
                        </div>
                        <p>12</p>
                    </div>
                    <div class="mt-4"></div>
                    <a href="#" class="button ps-3 pe-3 pt-2 pb-2">Выбрать квартиру</a>
                </div>
            </div>
        </div>

        
        <div class="burger-menu p-5">
            <div class="bg">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    Array(
                        "AREA_FILE_SHOW" => "file",
                        "AREA_FILE_SUFFIX" => "inc",
                        "COMPOSITE_FRAME_MODE" => "A",
                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                        "EDIT_TEMPLATE" => "",
                        "PATH" => SITE_TEMPLATE_PATH . "/include_areas/burger-bacground.php"
                    )
                );?>
            </div>
            
            <div class="container">
                <div class="cross row">
                    <hr>
                    <hr>
                </div>

                <?php
                $APPLICATION->IncludeComponent(
                    "bitrix:menu", 
                    "burger", 
                    array(
                        "ALLOW_MULTI_SELECT" => "N",
                        "CHILD_MENU_TYPE" => "left",
                        "DELAY" => "N",
                        "MAX_LEVEL" => "1",
                        "MENU_CACHE_GET_VARS" => array(
                        ),
                        "MENU_CACHE_TIME" => "3600",
                        "MENU_CACHE_TYPE" => "N",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "ROOT_MENU_TYPE" => "rose_town",
                        "USE_EXT" => "N",
                        "COMPONENT_TEMPLATE" => "burger"
                    ),
                    false
                );
                ?>

                <div class="burger-logo row mt-4">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "COMPOSITE_FRAME_MODE" => "A",
                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => SITE_TEMPLATE_PATH . "/include_areas/burger-logo.php"
                        )
                    );?>
                    
                </div>

                <div class="phones mt-4">
                    <h4 class="row">
                        Отдел продаж:
                    </h4>

                    <div class="phone row mt-3">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "COMPOSITE_FRAME_MODE" => "A",
                                "COMPOSITE_FRAME_TYPE" => "AUTO",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => SITE_TEMPLATE_PATH . "/include_areas/burger-numbers/1.php"
                            )
                        );?>
                        
                    </div>

                    <div class="phone row mt-2">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.include",
                        "",
                        Array(
                            "AREA_FILE_SHOW" => "file",
                            "AREA_FILE_SUFFIX" => "inc",
                            "COMPOSITE_FRAME_MODE" => "A",
                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                            "EDIT_TEMPLATE" => "",
                            "PATH" => SITE_TEMPLATE_PATH . "/include_areas/burger-numbers/2.php"
                        )
                    );?>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="container position-relative z-1">
            <header>
                <div class="row align-items-start pt-md-5 pt-3">
                    <div class="col-md-1 col-4">
                        <div class="burger">
                            <hr>
                            <hr>
                            <hr>
                        </div>
                    </div>

                    <div class="phone col-md-2 col-4 order-md-1 order-2">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "COMPOSITE_FRAME_MODE" => "A",
                                "COMPOSITE_FRAME_TYPE" => "AUTO",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => SITE_TEMPLATE_PATH . "/include_areas/burger-numbers/1.php"
                            )
                        );?>
                    </div>

                    <div class="col-md-6 col-4 d-flex justify-content-center order-md-2 order-1">
                        <div class="logo row flex-column align-items-center">
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            Array(
                                "AREA_FILE_SHOW" => "file",
                                "AREA_FILE_SUFFIX" => "inc",
                                "COMPOSITE_FRAME_MODE" => "A",
                                "COMPOSITE_FRAME_TYPE" => "AUTO",
                                "EDIT_TEMPLATE" => "",
                                "PATH" => SITE_TEMPLATE_PATH . "/include_areas/logo-header.php"
                            )
                        );?>
                        </div>
                    </div>

                    <div class="col-md-3 d-md-inline d-none order-3">
                        <a href="#choose-appartment" class="button ps-4 pe-4 pt-2 pb-2">Выбрать квартиру</a>
                    </div>
                </div>

                <div class="row d-lg-flex d-none">
                <?php
                $APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"main", 
	array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "rose_town",
		"USE_EXT" => "N",
		"COMPONENT_TEMPLATE" => "main"
	),
	false
);
                ?>
                </div>
            </header>
        </div>
    </div>

    <main>