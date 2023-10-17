<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

$arPhones = $arParams['PHONES'];
?>
<?php if ($arPhones > 0): ?>
	<?php foreach ($arPhones as $phone): if (empty($phone)) continue; ?>
		<?php
		if (preg_match('/^\#(.+)\#$/', $phone, $matches)) {
			$sPhoneUrl = str_replace($matches[1], $matches[1].'_URL', $phone);
		} else {
			$sPhoneUrl = preg_replace('/[^0-9\+]/', '', $phone);
		}
		?>
		<?php if (strlen($sPhoneUrl) > 0): ?>
			<a href="tel:<?=$sPhoneUrl?>" class="b-head-phone">
				<span class="b-head-icon hidden-lg hidden-md">
					<svg class="icon-svg icon-svg-phone"><use xlink:href="#svg-phone"></use></svg>
				</span>
				<span class="b-head-phone__text"><?=$phone?></span>
			</a>
		<?php else: ?>
			<span class="b-head-phone">
				<span class="b-head-icon hidden-lg hidden-md">
					<svg class="icon-svg icon-svg-phone"><use xlink:href="#svg-phone"></use></svg>
				</span>
				<span class="b-head-phone__text"><?=$phone?></span>
			</span>
		<?php endif; ?>
	<?php endforeach;?>
<?php endif; ?>
