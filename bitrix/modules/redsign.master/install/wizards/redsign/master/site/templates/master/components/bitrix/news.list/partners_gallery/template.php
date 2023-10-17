<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$this->setFrameMode(true);
use \Bitrix\Main\Localization\Loc;
if(count($arResult['ITEMS']) > 0):
	?>

	<section class="l-section l-section--padding b-light-gallery-partners">

		<?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
			<div class="container">
			<?php endif; ?>
			<?php if (!empty($arParams['PARENT_NAME'])): ?>
				<div class="l-section__head">
					<div class="section-head">
						<div class="section-head__title">
							<h2 class="l-page--title-center"><?=$arParams['PARENT_NAME']?></h2>
						</div>
					</div>
				</div>

			<?php endif; ?>
			<div class="l-section__main">
			<?php if($arParams['USE_OWL'] == 'Y'): ?>
				<div class="b-light-gallery__items owl owl-carousel owl-master owl-carousel-partners owl-theme" data-slider="true" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arParams['OWL_PARAMS'])?>'>
			<?php else: ?>
				<div class="b-light-gallery__items is-grid">
			<?php endif; ?>

				<?php foreach ($arResult['ITEMS'] as $arItem): ?>
					<?php
					$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
					?>
					<div class="b-light-gallery__item"  id="<?=$this->GetEditAreaId($arItem['ID']);?>">
						<a href="<?=$arItem['DETAIL_PAGE_URL']?>"  data-code="<?=$arItem['CODE']?>">
							<img class="b-light-gallery__item__partner-img" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
						</a>
					</div>
				<?php endforeach; ?>
				</div>
			</div>

			<div class="l-section__footer">
				<div class="section-footer">
					<?php if($arParams['RS_NOT_SHOW_BTN_SUMMARY_PAGE'] != 'Y'): ?>
						<div class="section-footer__more-button text-center">
							<div class="partners__all">
								<a class="btn btn-gray" href="<?=$arResult["ITEMS"]["0"]["LIST_PAGE_URL"]?>">
									<?=(strlen($arParams['RS_BTN_TEXT_SUMMARY_PAGE']) > 0) ? $arParams['RS_BTN_TEXT_SUMMARY_PAGE'] : Loc::getMessage('RS.PARTNERS'); ?>
									<span class="partners__all-svg"><svg class='icon-svg partners'><use xlink:href='#svg-arrow-thin-right'></use></svg></span>
								</a>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
			</div>
			<?php endif; ?>


	</section>
<?php endif; ?>
