<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

// echo "<pre>" . print_r($arResult['ITEMS'], true) . "</pre>";
?>

<div class="row mt-5">
    <!-- Дом -->
    <div data-select="" id="home-wrap" class="col-xxl-1 col-md-2 col-6 position-relative">
        <figure id="home-block">
            <figcaption>Дом</figcaption>
            <div class="select mt-3">
                <span class="value">1</span>
                <span class="arrow ms-3">
                    
                </span>
                <select name="home" id="home"></select>
                <p></p>
                <div class="select-values position-absolute">
<?foreach($arResult['ITEMS'] as $homeNumber => $home):?>
    <? $homeNumber = explode('№', $homeNumber)[1]; ?>
                    <option value="1"><?=$homeNumber?></option>
<?endforeach;?>
                </div>
        </div></figure>
    </div>
</div>

<input type="hidden" name="selectHome" value="1">
<input type="hidden" name="selectEntrance" value="1">
<input type="hidden" name="selectAppartment" value="1">


<?foreach($arResult['ITEMS'] as $homeNumber => $home):?>
    <? $homeNumber = trim(explode('№', $homeNumber)[1]); ?>
        <!-- Дом -->
        <?if ($homeNumber == 1):?>
            <div class="home selected row" id="home-<?=$homeNumber?>">
        <?else:?>
            <div class="home row" id="home-<?=$homeNumber?>">
        <?endif;?>

    <?foreach($home as $podezdNumber => $podezd):?>
        <??>
            <!-- Подъезд -->
            <div class="home-plan col-lg-4 col-12">
                <figure class="row mt-5">
                    <h4>
                        Подъезд <?=$podezdNumber?>
                    </h4>
                    <figcaption class="mt-3">
                        Этаж
                    </figcaption>
                </figure>

                <div class="row mt-3">
                    <div class="col-12">

        <?foreach($podezd as $floorNumber => $floor):?>
                        <!-- Этаж -->
                        <div class="row d-flex align-items-center">
                            <div class="floor-number col-1 me-2"><?=$floorNumber?></div>
                            <div class="col-10">
                                <div class="row d-flex flex-row-reverse">

            <?foreach($floor as $appartmentNumber => $appartment):?>
                                    <!-- Квартира -->
                                    <div class="col-2 p-0">
                            <?if ($appartment['PROPS']['Status']['VALUE'] == "Свободна"):?>
                                        <div href="#appartment-info" class="appartment onsold me-1 d-flex flex-column align-items-center justify-content-center">
                            <?elseif ($appartment['PROPS']['Status']['VALUE'] == "Забронирована"):?>
                                        <div href="#appartment-info" class="appartment booking me-1 d-flex flex-column align-items-center justify-content-center">
                            <?else:?>
                                        <div href="#appartment-info" class="appartment me-1 d-flex flex-column align-items-center justify-content-center">
                            <?endif;?>
                                            <input type="radio" name="appartment" value="96">
                                            <p class="number"><?=$appartmentNumber?></p>
                                            <p class="square"><?=$appartment['PROPS']['square']['VALUE']?> м2</p>
                                            <div class="meta">
                                                <div class="rooms-count"><?=$appartment['PROPS']['ROOM_NUMBER']['VALUE']?></div>
                                                <div class="rooms-class"><?=$appartment['PROPS']['TYPE']['VALUE']?></div>
                                                <div class="light-side"><?=$appartment['PROPS']['Side_of_the_world']['VALUE']?></div>
                                                <div class="hypothec">от 12 000 руб/мес</div>
                                                <div class="price"><?=$appartment['PROPS']['PRICE']['VALUE']?> руб</div>
                                                <img src="/local/templates/roseTown/src/media/img/roomPlan.png" class="plan" data-meta-src="/local/templates/roseTown/src/media/img/roomPlan.png">
                                                <div class="images">
                <?foreach($appartment as $imageId):?>
                                                    <img data-meta-src="<?=CFile::GetPath($imageId)?>">
                <?endforeach;?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            <?endforeach;?>
                                </div>
                            </div>

                        </div>
        <?endforeach;?>
                    </div>
                </div>
            </div>
    <?endforeach;?>

            </div>
<?endforeach;?>