<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @global CMain $APPLICATION
 */

if (!CModule::IncludeModule('intec.core'))
    return;

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$sFormType = $arResult['FORM_TYPE'];

$oFrame = $this->createFrame();

?>
<div class="widget-authorization-panel" id="<?= $sTemplateId ?>" style="display: flex;">
    <?php $oFrame->begin() ?>
        <?php if ($sFormType == 'login') { ?>
            <div class="widget-panel-button" data-action="login">
                <div class="widget-panel-button-wrapper">
                    <div class="widget-panel-button-icon-svg">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M18.364 5.63604C21.8787 9.15076 21.8787 14.8492 18.364 18.3639C14.8493 21.8787 9.1508 21.8787 5.6361 18.3639C2.12138 14.8492 2.12138 9.15074 5.6361 5.63604C9.15082 2.12132 14.8493 2.12132 18.364 5.63604" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
				<path d="M17.3074 19.257C16.9234 17.417 14.7054 16 12.0004 16C9.29542 16 7.07742 17.417 6.69342 19.257" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
				<path d="M14.1213 7.87868C15.2929 9.05025 15.2929 10.9497 14.1213 12.1213C12.9497 13.2929 11.0502 13.2929 9.87868 12.1213C8.70711 10.9497 8.70711 9.05025 9.87868 7.87868C11.0502 6.70711 12.9497 6.70711 14.1213 7.87868Z" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		    </div>
                    <div class="widget-panel-button-text">
                        <?= Loc::getMessage('W_HEADER_S_A_F_LOGIN') ?>
                    </div>
                </div>
            </div>
            <?php include(__DIR__.'/parts/script.php') ?>
        <?php } else { ?>
		<a rel="nofollow" href="<?= $arResult['PROFILE_URL'] ?>" class="widget-panel-button">
	                <div class="widget-panel-button-wrapper">
				<div class="widget-panel-button-icon-svg">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M18.364 5.63604C21.8787 9.15076 21.8787 14.8492 18.364 18.3639C14.8493 21.8787 9.1508 21.8787 5.6361 18.3639C2.12138 14.8492 2.12138 9.15074 5.6361 5.63604C9.15082 2.12132 14.8493 2.12132 18.364 5.63604" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M17.3074 19.257C16.9234 17.417 14.7054 16 12.0004 16C9.29542 16 7.07742 17.417 6.69342 19.257" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M14.1213 7.87868C15.2929 9.05025 15.2929 10.9497 14.1213 12.1213C12.9497 13.2929 11.0502 13.2929 9.87868 12.1213C8.70711 10.9497 8.70711 9.05025 9.87868 7.87868C11.0502 6.70711 12.9497 6.70711 14.1213 7.87868Z" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</div>
	                    <div class="widget-panel-button-text" style="max-width: 60px;text-overflow: ellipsis;overflow: hidden;padding-right: 15px;">
	                        <?= $arResult['USER_LOGIN'] ?>
	                    </div>
	                </div>
            	</a>
             <a rel="nofollow" href="<?= $arResult['LOGOUT_URL'] ?>" class="widget-panel-button">
                <div class="widget-panel-button-wrapper ">
                    <div class="widget-panel-button-icon-svg">
			<svg width="24" height="24" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M2.25016 8.99927L11.5352 8.99927" stroke="#000" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
			<path d="M4.78516 11.5305L2.25391 8.99927L4.78516 6.46877" stroke="#000" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
			<path d="M2.25 12.375V14.0625C2.25 14.9948 3.00525 15.75 3.9375 15.75H14.0625C14.9948 15.75 15.75 14.9948 15.75 14.0625V3.9375C15.75 3.00525 14.9948 2.25 14.0625 2.25H3.9375C3.00525 2.25 2.25 3.00525 2.25 3.9375V5.625" stroke="#000" stroke-width="1.1" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>

			</div>
                    <div class="widget-panel-button-text ">
                        <?= Loc::getMessage('W_HEADER_S_A_F_LOGOUT') ?>
                    </div>
                </div>
            </a>
        <?php } ?>
    <?php $oFrame->beginStub() ?>
        <div class="widget-panel-button" data-action="login">
			<div class="widget-panel-button-wrapper">
				<div class="widget-panel-button-icon-svg">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M18.364 5.63604C21.8787 9.15076 21.8787 14.8492 18.364 18.3639C14.8493 21.8787 9.1508 21.8787 5.6361 18.3639C2.12138 14.8492 2.12138 9.15074 5.6361 5.63604C9.15082 2.12132 14.8493 2.12132 18.364 5.63604" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M17.3074 19.257C16.9234 17.417 14.7054 16 12.0004 16C9.29542 16 7.07742 17.417 6.69342 19.257" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M14.1213 7.87868C15.2929 9.05025 15.2929 10.9497 14.1213 12.1213C12.9497 13.2929 11.0502 13.2929 9.87868 12.1213C8.70711 10.9497 8.70711 9.05025 9.87868 7.87868C11.0502 6.70711 12.9497 6.70711 14.1213 7.87868Z" fill="none" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
				</div>
				<div class="widget-panel-button-text">
					<?= Loc::getMessage('W_HEADER_S_A_F_LOGIN') ?>
				</div>
			</div>
		</div>
    <?php $oFrame->end() ?>
</div>