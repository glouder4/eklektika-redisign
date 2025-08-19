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
                <a href="/personal/profile/" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                            <polyline points="10,17 15,12 10,7"/>
                            <line x1="15" y1="12" x2="3" y2="12"/>
                        </svg>
                    </div>
                    <div class="data">
                        <span>Вход</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/catalog/" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="8.5" cy="7" r="4"/>
                            <line x1="20" y1="8" x2="20" y2="14"/>
                            <line x1="23" y1="11" x2="17" y2="11"/>
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
                <a href="/personal/profile/" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <div class="data">
                        <span>Личный кабинет</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/orders/?filter_status=N" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 12l2 2 4-4"/>
                            <path d="M21 12c-1 0-2-1-2-2s1-2 2-2 2 1 2 2-1 2-2 2z"/>
                            <path d="M3 12c1 0 2-1 2-2s-1-2-2-2-2 1-2 2 1 2 2 2z"/>
                            <path d="M12 3c0 1-1 2-2 2s-2 1-2 2 1 2 2 2 2 1 2 2 1-2 2-2 2-1 2-2-1-2-2-2-2-1-2-2z"/>
                        </svg>
                    </div>
                    <div class="data">
                        <span>Заказы</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/orders/?filter_date_from=&filter_status[]=R&filter_status[]=RO&filter_status[]=RС" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </div>
                    <div class="data">
                        <span>Резервы</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/orders/?filter_date_from=&filter_date_to=&filter_status=OB" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                        </svg>
                    </div>
                    <div class="data">
                        <span>Образцы</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/kp/" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                            <line x1="1" y1="10" x2="23" y2="10"/>
                        </svg>
                    </div>
                    <div class="data">
                        <span>Коммерческие предложения</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="/personal/profile/" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"/>
                            <line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </div>
                    <div class="data">
                        <span>Написать менеджеру</span>
                    </div>
                </a>
            </div>
            <div class="widget">
                <a href="<?=$arResult['LOGOUT_URL'];?>" rel="nofollow" class="widget-item">
                    <div class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                            <polyline points="16,17 21,12 16,7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
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
            <a href="/personal/profile/" rel="nofollow" class="top_header-btn auth">Войти</a>
            <a href="/personal/profile/registration.php" rel="nofollow" class="top_header-btn reg">Стать дилером</a>
        <?php }
        else{ ?>
            <a href="<?=$arResult['LOGOUT_URL'];?>" rel="nofollow" class="top_header-btn auth">Выйти</a>
        <?php }
    ?>
</div>