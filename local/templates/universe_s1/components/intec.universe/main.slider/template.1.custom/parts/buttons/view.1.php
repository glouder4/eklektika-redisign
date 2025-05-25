<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

?>
<?php return function ($sUrl, $bUrlBlank, $sText) { ?>
	<a class="widget-item-button intec-cl-background intec-cl-background-light-hover" href="<?= $sUrl ?>" <?= $bUrlBlank ? 'target="_blank"' : null ?>>
		<div class="intec-grid intec-grid-a-v-center intec-grid-a-h-center intec-grid-i-h-5">
			<div class="intec-grid-item-auto">
				<?= $sText ?>
			</div>
			<div class="intec-grid-item-auto" style="font-size: 0; line-height: 0;">
				<svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M18.2265 8.0828C18.6893 8.54553 18.6893 9.29577 18.2265 9.75851L10.6858 17.2992C10.2231 17.7619 9.47285 17.7619 9.01012 17.2992C8.54738 16.8365 8.54738 16.0862 9.01012 15.6235L15.713 8.92065L9.01012 2.21782C8.54738 1.75508 8.54738 1.00484 9.01012 0.542106C9.47285 0.0793715 10.2231 0.0793715 10.6858 0.542106L18.2265 8.0828ZM0.799988 7.73575L17.3887 7.73575V10.1056L0.799988 10.1056L0.799988 7.73575Z" fill="white"/>
				</svg>
			</div>
		</div>
	</a>
<?php } ?>
