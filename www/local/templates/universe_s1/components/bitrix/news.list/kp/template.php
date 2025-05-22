<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);
CModule::IncludeModule("iblock");
CModule::IncludeModule("intec.eklectika");
?>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<div class="intec-content intec-content-visible" id="app-kp">
	<div class="intec-content-wrapper">
		<?php include(__DIR__.'/parts/offers.php');?>
	</div>
	
	<?php include(__DIR__.'/parts/script.php');?>
</div>
