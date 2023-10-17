<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

?>
<?php if (is_array($arResult['YEARS']) && count($arResult['YEARS']) > 0): ?>

<div class="b-news-archive">
	<?php if ($arParams['SHOW_TITLE']): ?>
		<span class=""><?=Loc::getMessage('RS.NA_ARCHIVE')?></span>
	<?php endif; ?>

	<ul class="b-news-archive__list list-inline">
		<li class="b-news-archive__item">
        	<a class="btn<?php if (!$arResult['HAS_SELECTED']) { echo ' btn-primary'; } else { echo ' btn-default'; }?>" href="<?=$arParams['SEF_FOLDER']?>"><?=Loc::getMessage('RS.NA_ALL')?></a>
    	</li>
    	<?php foreach ($arResult['YEARS'] as $iYear => $arYear): ?>
    		<?php $sYearId = $this->getEditAreaId($iYear); ?>
    		<li class="b-news-archive__item">
    			<?php if ($arParams['SHOW_YEARS']): ?>
            		<a class="btn<?php if ($arYear['SELECTED']) { echo ' btn-primary'; } else { echo ' btn-default'; }?>" href="<?=$arYear['ARCHIVE_URL']?>"><?=$arYear['NAME']?> (<?=$arYear['COUNT']?>)</a>
        		<?php endif; ?>

        		<?php
                if (
                    $arParams['SHOW_MONTHS'] &&
                    is_array($arYear['MONTHS']) && count($arYear['MONTHS']) > 0
                ):
                ?>
        			<ul>
        				<?php foreach ($arYear['MONTHS'] as $arMonth): ?>
        					<li class="b-news-archive__item">
    							<a class="btn btn-default" href="<?=$arMonth['ARCHIVE_URL']?>"><?=$arMonth['NAME']?> (<?=$arMonth['COUNT']?>)</a>
    						</li>
        				<?php endforeach; ?>
        			</ul>
        		<?php endif; ?>

        	</li>
    	<?php endforeach; ?>
	</ul>
</div>

<?php endif; ?>
