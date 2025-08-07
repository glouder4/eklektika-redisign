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
<a rel="nofollow" href="/personal/profile/" class="header__icon header__icon--user">
    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="17" viewBox="0 0 19 17" fill="none">
        <path d="M9.62908 8.2992C11.4609 8.2992 12.9459 6.81421 12.9459 4.98238C12.9459 3.15055 11.4609 1.66555 9.62908 1.66555C7.79725 1.66555 6.31226 3.15055 6.31226 4.98238C6.31226 6.81421 7.79725 8.2992 9.62908 8.2992Z" stroke="white" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M17.2424 16.307C16.7252 14.6942 15.7092 13.2873 14.3409 12.2891C12.9726 11.2909 11.3226 10.7531 9.62891 10.7531C7.93521 10.7531 6.28525 11.2909 4.91692 12.2891C3.54859 13.2873 2.53259 14.6942 2.01538 16.307H17.2424Z" stroke="white" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <span class="header__icon-title">Профиль</span>
</a>

<div id="profile_fields--wrapper">
    <div class="widgets">
        <?php
        if( !$USER->IsAuthorized() ){ ?>
            <div class="widget">
                <a href="<?=$arResult['PROFILE_URL'];?>" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="17" viewBox="0 0 19 17" fill="none">
                            <path d="M9.62908 8.2992C11.4609 8.2992 12.9459 6.81421 12.9459 4.98238C12.9459 3.15055 11.4609 1.66555 9.62908 1.66555C7.79725 1.66555 6.31226 3.15055 6.31226 4.98238C6.31226 6.81421 7.79725 8.2992 9.62908 8.2992Z" stroke="black" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.2424 16.307C16.7252 14.6942 15.7092 13.2873 14.3409 12.2891C12.9726 11.2909 11.3226 10.7531 9.62891 10.7531C7.93521 10.7531 6.28525 11.2909 4.91692 12.2891C3.54859 13.2873 2.53259 14.6942 2.01538 16.307H17.2424Z" stroke="black" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="data">
                        <span>Вход</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/registration.php" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="17" viewBox="0 0 19 17" fill="none">
                            <path d="M9.62908 8.2992C11.4609 8.2992 12.9459 6.81421 12.9459 4.98238C12.9459 3.15055 11.4609 1.66555 9.62908 1.66555C7.79725 1.66555 6.31226 3.15055 6.31226 4.98238C6.31226 6.81421 7.79725 8.2992 9.62908 8.2992Z" stroke="black" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.2424 16.307C16.7252 14.6942 15.7092 13.2873 14.3409 12.2891C12.9726 11.2909 11.3226 10.7531 9.62891 10.7531C7.93521 10.7531 6.28525 11.2909 4.91692 12.2891C3.54859 13.2873 2.53259 14.6942 2.01538 16.307H17.2424Z" stroke="black" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="data">
                        <span>Регистрация</span>
                    </div>
                </a>
            </div>
        <?php }
        else { ?>
            <div class="widget">
                <a href="<?=$arResult['PROFILE_URL'];?>" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="17" viewBox="0 0 19 17" fill="none">
                            <path d="M9.62908 8.2992C11.4609 8.2992 12.9459 6.81421 12.9459 4.98238C12.9459 3.15055 11.4609 1.66555 9.62908 1.66555C7.79725 1.66555 6.31226 3.15055 6.31226 4.98238C6.31226 6.81421 7.79725 8.2992 9.62908 8.2992Z" stroke="black" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.2424 16.307C16.7252 14.6942 15.7092 13.2873 14.3409 12.2891C12.9726 11.2909 11.3226 10.7531 9.62891 10.7531C7.93521 10.7531 6.28525 11.2909 4.91692 12.2891C3.54859 13.2873 2.53259 14.6942 2.01538 16.307H17.2424Z" stroke="black" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="data">
                        <span>Личный кабинет</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="<?=$arResult['LOGOUT_URL'];?>" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="17" viewBox="0 0 19 17" fill="none">
                            <path d="M9.62908 8.2992C11.4609 8.2992 12.9459 6.81421 12.9459 4.98238C12.9459 3.15055 11.4609 1.66555 9.62908 1.66555C7.79725 1.66555 6.31226 3.15055 6.31226 4.98238C6.31226 6.81421 7.79725 8.2992 9.62908 8.2992Z" stroke="black" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.2424 16.307C16.7252 14.6942 15.7092 13.2873 14.3409 12.2891C12.9726 11.2909 11.3226 10.7531 9.62891 10.7531C7.93521 10.7531 6.28525 11.2909 4.91692 12.2891C3.54859 13.2873 2.53259 14.6942 2.01538 16.307H17.2424Z" stroke="black" stroke-width="1.08595" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="data">
                        <span>Выход</span>
                    </div>
                </a>
            </div>

        <?php } ?>
    </div>
</div>

<div id="top_header-auth_reg-btns--wrapper">
    <?php
        if( !$USER->IsAuthorized() ){ ?>
            <a href="<?=$arResult['PROFILE_URL'];?>" rel="nofollow" class="top_header-btn auth">Войти</a>
            <a href="/personal/profile/registration.php" rel="nofollow" class="top_header-btn reg">Стать дилером</a>
        <?php }
        else{ ?>
            <a href="<?=$arResult['LOGOUT_URL'];?>" rel="nofollow" class="top_header-btn auth">Выйти</a>
        <?php }
    ?>
</div>