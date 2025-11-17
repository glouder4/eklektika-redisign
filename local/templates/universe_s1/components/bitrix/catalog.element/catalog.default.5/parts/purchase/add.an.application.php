<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var array $arVisual
 * @var array $arSvg
 */
?>

<div class="container-btn-add-an-applocation-and-btn-create-kp">
    <div class="container-btn-add-an-applocation">
        <div class="btn-add-an-applocation">
            Добавить нанесение 
            <span>
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="7" viewBox="0 0 12 7" fill="none">
                    <path d="M1 0.999822L6 5.18164L11 0.999823" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
        </div>
    </div>
    <div class="container-btn-create-kp">
        <div class="btn-create-kp">
            Создать КП
        </div>
    </div>
</div>
<?/*
global $USER;
if ($USER->IsAuthorized() && $USER->IsAdmin()): 
?>

<? endif; */?>