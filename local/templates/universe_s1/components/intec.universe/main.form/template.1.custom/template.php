<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 */

$this->setFrameMode(true);

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<div class="widget c-form c-form-template-1" id="<?= $sTemplateId ?>">
	<div class="intec-content intec-content-primary">
		<div class="intec-content-wrapper" style="overflow:hidden">
			<?= Html::beginTag('div', [
				'class' => 'widget-form-body',
				'data-lazyload-use' => $arResult['BACKGROUND']['IMAGE']['USE'] && $arResult['LAZYLOAD']['USE'] ? 'true' : 'false',
				'data-original' => $arResult['BACKGROUND']['IMAGE']['USE'] && $arResult['LAZYLOAD']['USE'] ? $arResult['BACKGROUND']['IMAGE']['PATH'] : null,
				'data-theme' => $arResult['THEME'],
				'data-view' => $arResult['VIEW'],
				'data-align-horizontal' => $arResult['VIEW'] === 'vertical' ? $arResult['ALIGN']['HORIZONTAL'] : null,
				'data-parallax-ratio' => $arResult['BACKGROUND']['IMAGE']['USE'] && $arResult['BACKGROUND']['IMAGE']['PARALLAX']['USE'] ?
					$arResult['BACKGROUND']['IMAGE']['PARALLAX']['RATIO'] : null,
				'style' => [
					'background-color' => !empty($arResult['BACKGROUND']['COLOR']) ? $arResult['BACKGROUND']['COLOR'] : null,
					'background-image' => $arResult['BACKGROUND']['IMAGE']['USE'] ?
						(!$arResult['LAZYLOAD']['USE'] ? 'url(\''.$arResult['BACKGROUND']['IMAGE']['PATH'].'\')' : null) : null,
					'background-position' => $arResult['BACKGROUND']['IMAGE']['USE'] ?
						$arResult['BACKGROUND']['IMAGE']['HORIZONTAL'].' '.$arResult['BACKGROUND']['IMAGE']['VERTICAL'] : null,
					'background-size' => $arResult['BACKGROUND']['IMAGE']['USE'] ? $arResult['BACKGROUND']['IMAGE']['SIZE'] : null,
					'background-repeat' => $arResult['BACKGROUND']['IMAGE']['USE'] && $arResult['BACKGROUND']['IMAGE']['SIZE'] === 'contain' ?
						$arResult['BACKGROUND']['IMAGE']['REPEAT'] : null,
				]
			]) ?>
			
			
			
				<div class="bg-lines-horisontal"></div>
				<div class="bg-lines-vertical"></div>
				<div class="bg-letters"></div>
				<div class="widget-form-content intec-grid intec-grid-a-v-center intec-grid-wrap">
					<?= Html::beginTag('div', [
						'class' => Html::cssClassFromArray([
							'intec-grid-item' => [
								'' => true,
								'768-1' => true
							]
						], true)
					]) ?>
						<div class="widget-form-text">
							<?php if (!empty($arResult['TITLE'])) { ?>
								<div class="widget-form-name">
									<?= $arResult['TITLE'] ?>
								</div>
							<?php } ?>
							<?php if ($arResult['DESCRIPTION']['SHOW'] && !empty($arResult['DESCRIPTION']['TEXT'])) { ?>
								<div class="widget-form-description">
									<?= $arResult['DESCRIPTION']['TEXT'] ?>
								</div>
							<?php } ?>
						</div>
					<?= Html::endTag('div') ?>
					<?php if ($arResult['BUTTON']['SHOW']) { ?>
						<?= Html::beginTag('div', [
							'class' => Html::cssClassFromArray([
								'widget-form-buttons' => true,
								'intec-grid-item' => [
									'3' => true,
									'768-1' => true
								]
							], true)
						]) ?>
							<div class="widget-form-buttons-wrap">
								<?= Html::beginTag('div', [
									'class' => Html::cssClassFromArray([
										'widget-form-button' => true,
									], true),
									'data-role' => 'form.button'
								]) ?>
									<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M1 25.2871L25 1.28711M25 1.28711V25.2871M25 1.28711H1.54545" stroke="black" stroke-width="2"/>
									</svg>
								<?= Html::endTag('div') ?>
							</div>
						<?= Html::endTag('div') ?>
					<?php } ?>
				</div>
				
				
				
				
				
				
				
			<?= Html::endTag('div') ?>
		</div>
	</div>
    <?php if ($arResult['BUTTON']['SHOW'])
        include(__DIR__.'/parts/script.php');
    ?>
</div>
<script>
$('#<?= $sTemplateId ?> .widget-form-body').hover(
	function () {
		var elem = $(this);
		
		$(elem).find('.bg-lines-horisontal').addClass('active')
		$(elem).find('.bg-lines-vertical').addClass('hover')
		$(elem).find('.bg-letters').addClass('hover');
		
		$(elem).find('.bg-lines-horisontal').addClass('active');
		setTimeout(function () {
			if ($(elem).find('.bg-lines-vertical').hasClass('hover'))
				$(elem).find('.bg-lines-vertical').addClass('active');
		}, 300);
		setTimeout(function () {
			if ($(elem).find('.bg-letters').hasClass('hover'))
				$(elem).find('.bg-letters').addClass('active');
		}, 300);
	}, function () {
		var elem = $(this);
		
		$(elem).find('.bg-lines-horisontal').removeClass('active')
		$(elem).find('.bg-lines-vertical').removeClass('hover')
		$(elem).find('.bg-letters').removeClass('hover');
		
		$(elem).find('.bg-letters').removeClass('active');
		setTimeout(function () {
			$(elem).find('.bg-lines-vertical').removeClass('active');
		}, 300);
		setTimeout(function () {
			$(elem).find('.bg-lines-horisontal').removeClass('active');
		}, 200);
	}
); 
</script>