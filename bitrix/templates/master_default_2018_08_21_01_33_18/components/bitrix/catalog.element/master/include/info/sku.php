<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use \Bitrix\Main\Localization\Loc;

?><?
if ($haveOffers && !empty($arResult['OFFERS_PROP']))
{
    ?>
    <div id="<?=$itemIds['TREE_ID']?>">
        <?
        foreach ($arResult['SKU_PROPS'] as $skuProperty)
        {
            if (!isset($arResult['OFFERS_PROP'][$skuProperty['CODE']]))
                continue;

            $propertyId = $skuProperty['ID'];
            $skuProps[] = array(
                'ID' => $propertyId,
                'SHOW_MODE' => $skuProperty['SHOW_MODE'],
                'VALUES' => $skuProperty['VALUES'],
                'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
            );
            ?>
            <div class="product-item-detail-info-container" data-entity="sku-line-block">
                <div class="product-item-detail-info-container-title"><?=htmlspecialcharsEx($skuProperty['NAME'])?>: <span class="product-item-scu-item-current" data-entity="sku-current-value"></span></div>
                <div class="product-item-scu-container">
                    <div class="product-item-scu-block">
                        <div class="product-item-scu-list">
                        <?php
                        if (in_array($skuProperty['CODE'], $arParams['OFFER_TREE_DROPDOWN_PROPS'])):
                            $dropdownId = $this->getEditAreaId('dd');
                        ?>
                            <div class="dropdown">
                                <button class="btn btn-lg btn-gray dropdown-toggle" type="button" id="<?=$dropdownId?>" data-toggle="dropdown" aria-expanded="true">
                                    <span data-entity="sku-current-value"></span>
                                    <svg class="dropdown__icon icon-svg"><use xlink:href="#svg-chevron-down"></use></svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-lg" role="menu" aria-labelledby="<?=$dropdownId?>">
                                    <?
                                    foreach ($skuProperty['VALUES'] as &$value)
                                    {
                                        $value['NAME'] = htmlspecialcharsbx($value['NAME']);
/*
                                        if ($skuProperty['SHOW_MODE'] === 'PICT')
                                        {
                                            ?>
                                            <li class="product-item-scu-item-color-container" title="<?=$value['NAME']?>"
                                                <?php if ($value['ID'] == 0): ?> style="display:none"<?php endif; ?>
                                                data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
                                                data-onevalue="<?=$value['ID']?>">
                                                <span class="product-item-scu-item-color-block">
                                                    <span class="product-item-scu-item-color" title="<?=$value['NAME']?>"
                                                        style="background-image: url(<?=$value['PICT']['SRC']?>);">
                                                    </span>
                                                </span>
                                            </li>
                                            <?
                                        }
                                        else
                                        {
*/
                                            ?>
                                            <li title="<?=$value['NAME']?>"
                                                <?php if ($value['ID'] == 0): ?> style="display:none"<?php endif; ?>
                                                data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
                                                data-onevalue="<?=$value['ID']?>">
                                                <label><?=$value['NAME']?></label>
                                            </li>
                                            <?
/*
                                        }
*/
                                    }
                                    ?>
                                </ul>
                            </div>
                        <?php else: ?>
                            <ul class="product-item-scu-item-list">
                                <?
                                foreach ($skuProperty['VALUES'] as &$value)
                                {
                                    $value['NAME'] = htmlspecialcharsbx($value['NAME']);

                                    if ($skuProperty['SHOW_MODE'] === 'PICT')
                                    {
                                        ?>
                                        <li class="product-item-scu-item-color-container" title="<?=$value['NAME']?>"
                                            <?php if ($value['ID'] == 0): ?> style="display:none"<?php endif; ?>
                                            data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
                                            data-onevalue="<?=$value['ID']?>">
                                            <span class="product-item-scu-item-color-block">
                                                <span class="product-item-scu-item-color" title="<?=$value['NAME']?>"
                                                    style="background-image: url(<?=$value['PICT']['SRC']?>);">
                                                </span>
                                            </span>
                                        </li>
                                        <?
                                    }
                                    else
                                    {
                                        ?>
                                        <li class="product-item-scu-item-btn-container btn btn-gray btn-lg" title="<?=$value['NAME']?>"
                                            <?php if ($value['ID'] == 0): ?> style="display:none"<?php endif; ?>
                                            data-treevalue="<?=$propertyId?>_<?=$value['ID']?>"
                                            data-onevalue="<?=$value['ID']?>">
                                            <span class="product-item-scu-item-btn-block">
                                                <span class="product-item-scu-item-btn"><?=$value['NAME']?></span>
                                            </span>
                                        </li>
                                        <?
                                    }
                                }
                                ?>
                            </ul>
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?
        }
        ?>
    </div>
    <?
}