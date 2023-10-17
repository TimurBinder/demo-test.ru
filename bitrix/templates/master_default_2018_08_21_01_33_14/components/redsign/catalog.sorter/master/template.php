<?if(!defined('B_PROLOG_INCLUDED')||B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
?>
<nav class="sorter js-catalog_refresh" id="<?=$arParams['TEMPLATE_AJAXID']?>_sorter" data-ajax-id="<?=$arParams['TEMPLATE_AJAXID']?>">
    <?php
    $this->SetViewTarget($arParams['TEMPLATE_AJAXID'].'_sorter');

    $frame = $this->createFrame($arParams['TEMPLATE_AJAXID'].'_sorter', false)->begin();
    $frame->setBrowserStorage(true);
    ?>

	<?php
    if (
        $arParams['ALFA_CHOSE_TEMPLATES_SHOW'] == 'Y'
        && is_array($arResult['CTEMPLATE']) && count($arResult['CTEMPLATE']) > 1
    ):
    ?>
        <div class="sorter__item sorter__item-views">
            <div class="btn-group">
                <?php foreach ($arResult['CTEMPLATE'] as $key => $template): ?>
                    <a class="btn btn-lg btn-default <?=($template['USING'] == 'Y' ? ' active' : '')?> js-sorterajax-switcher" href="<?=$template['URL']?>" title="<?=($template['NAME_LANG'] !=  '' ? $template['NAME_LANG'] : $template['VALUE'])?>" rel="nofollow">
                        <svg class="icon icon-svg"><use xlink:href="#svg-<?=$template['VALUE']?>"></use></svg>
                    </a>
                <?php endforeach; ?>
            </div>
<?/*
            <div class="hidden pull-right templateDrop dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuTemplate" data-toggle="dropdown" aria-expanded="true">
                    <i class="fa <?=$template['VALUE']?>"></i>
                </button>
                <ul class="dropdown-menu list-unstyled" role="menu" aria-labelledby="dropdownMenuTemplate">
                    <?foreach ($arResult['CTEMPLATE'] as $template):?>
                        <li>
                            <a href="<?=$template['URL']?>" rel="nofollow">
                                <i class="fa <?=$template['VALUE']?>"></i>
                            </a>
                        </li>
                    <?endforeach;?>
                </ul>
            </div>
*/?>
        </div>
    <?php endif; ?>

	<?php
    if (
        $arParams['ALFA_OUTPUT_OF_SHOW'] == 'Y'
        && is_array($arResult['COUTPUT']) && count($arResult['COUTPUT']) > 1
    ):
    ?>
        <div class="sorter__item hidden-xs">
			<span class="sorter__name"><?=Loc::getMessage('RS.FLYAWAY.OUTPUT_TITLE')?></span>
			<div class="dropdown js-sorter">
				<button class="btn btn-lg btn-default dropdown-toggle" type="button" id="dropdownMenuOutput" data-toggle="dropdown" aria-expanded="true">
					<span class="js-sorter-btn"><?=$arResult['USING']['COUTPUT']['ARRAY']['VALUE']?></span>
                    <svg class="dropdown__icon icon-svg"><use xlink:href="#svg-chevron-down"></use></svg>
				</button>
				<ul class="dropdown-menu dropdown-menu-lg" role="menu" aria-labelledby="dropdownMenuOutput">
					<?foreach ($arResult['COUTPUT'] as $output):?>
						<li class="js-sorter-item">
							<a class="js-sorter-switcher" href="<?=$output['URL']?>" rel="nofollow">
								<span class="js-sorter-text"><?=$output['VALUE']?></span>
							</a>
						</li>
					<?endforeach;?>
				</ul>
			</div>
		</div>
    <?php endif; ?>

	<?php
    if (
        $arParams['ALFA_SORT_BY_SHOW'] == 'Y'
        && is_array($arResult['CSORTING']) && count($arResult['CSORTING']) > 1
    ):
    ?>
        <?php
        $usingSortName = '';
        if (strpos(strtolower($arResult['USING']['CSORTING']['ARRAY']['VALUE']), 'price') === false) {
            $usingSortName = strtolower($arResult['USING']['CSORTING']['ARRAY']['VALUE']);
        } elseif ($arResult['USING']['CSORTING']['ARRAY']['DIRECTION'] == 'asc') {
            $usingSortName = 'property_price_false_asc';
        } else {
            $usingSortName = 'property_price_false_desc';
        }
    ?>
        <div class="sorter__item">
			<div class="dropdown js-sorter">
				<button class="btn btn-lg btn-default dropdown-toggle" type="button" id="dropdownMenuSortBy" data-toggle="dropdown" aria-expanded="true">
					<span class="visible-xs"><?=Loc::getMessage('RS.FLYAWAY.SORT')?></span>
					<span class="hidden-xs">
						<span class="js-sorter-btn"><?=Loc::getMessage('RS.FLYAWAY.SORT_VARIABLE.'.$usingSortName)?></span>
                        <svg class="dropdown__icon icon-svg"><use xlink:href="#svg-chevron-down"></use></svg>
					</span>
					<span class="hidden">
						<?=Loc::getMessage('RS.FLYAWAY.SORT_VARIABLE_ICON.'.$usingSortName)?>
					</span>
				</button>
				<ul class="dropdown-menu dropdown-menu-lg" role="menu" aria-labelledby="dropdownMenuSortBy">

					<?foreach ($arResult['CSORTING'] as $key => $sort):
                        if(strtolower($sort['VALUE']) == 'sort_desc') continue;
                        $sortName = '';
                        if (strpos(strtolower($sort['VALUE']), 'price') === false) {
                            $sortName = strtolower($sort['VALUE']);
                        } elseif ($sort['DIRECTION'] == 'asc') {
                            $sortName = 'property_price_false_asc';
                        } else {
                            $sortName = 'property_price_false_desc';
                        }
                    ?>
					<li class="views-item js-sorter-item<?=($arResult['USING']['CSORTING']['KEY'] == $key ? ' views-item_current' : '')?>">
						<a class="js-sorter-switcher" href="<?=$sort['URL']?>" rel="nofollow">
							<span class="js-sorter-text"><?=Loc::getMessage('RS.FLYAWAY.SORT_VARIABLE.'.$sortName)?></span>
							<span class="hidden"><?=Loc::getMessage('RS.FLYAWAY.SORT_VARIABLE_ICON.'.$sortName)?></span>
						</a>
					</li>
					<?endforeach;?>
				</ul>
			</div>
		</div>
	<?endif;?>
    
	<?php
    $frame->end();
    $this->EndViewTarget();
    
    echo $APPLICATION->GetViewContent($arParams['TEMPLATE_AJAXID'].'_sorter');
    ?>
</nav>

