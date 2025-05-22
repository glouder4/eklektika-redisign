<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var array $arTickets
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 */

?>
<div class="sale-personal-section-claims">
    <div class="sale-personal-section-claims-header">
        <div class="sale-personal-section-claims-title">
            Информация о сроках поставки
        </div>
    </div>
    <div class="sale-personal-section-claims-wrap">
        <div class="sale-personal-section-claims-items">
			<?$APPLICATION->IncludeComponent("bitrix:main.include","",Array(
				"AREA_FILE_SHOW" => "file", 
				"AREA_FILE_SUFFIX" => "inc", 
				"AREA_FILE_RECURSIVE" => "Y", 
				"PATH" => "/include/info.php" 
			), 
			$component, 
			["HIDE_ICONS" => "Y"]
		);?>
        </div>
    </div>
</div>