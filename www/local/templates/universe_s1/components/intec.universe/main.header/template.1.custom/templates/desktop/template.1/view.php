<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$sTemplateId = $arData['id'];
$sTemplateType = $arData['type'];
$bPanelShow = false;
$bContainerShow = false;

$arMenuMain = $arResult['MENU']['MAIN'];
$arMenuInfo = $arResult['MENU']['INFO'];

foreach (['AUTHORIZATION', 'ADDRESS', 'EMAIL'] as $sBlock)
    $bPanelShow = $bPanelShow || $arResult[$sBlock]['SHOW']['DESKTOP'];

if ($arMenuInfo['SHOW'])
    $bPanelShow = true;

$bContactsShow =
    $arResult['ADDRESS']['SHOW']['DESKTOP'] ||
    $arResult['REGIONALITY']['USE'] ||
    $arResult['EMAIL']['SHOW']['DESKTOP'];

if ($bContactsShow)
    $bPanelShow = true;

foreach (['LOGOTYPE', 'TAGLINE', 'CONTACTS', 'BASKET', 'DELAY', 'COMPARE'] as $sBlock)
    $bContainerShow = $bContainerShow || $arResult[$sBlock]['SHOW']['DESKTOP'];

$bBasketShow =
    $arResult['BASKET']['SHOW']['DESKTOP'] ||
    $arResult['DELAY']['SHOW']['DESKTOP'] ||
    $arResult['COMPARE']['SHOW']['DESKTOP'];

$sMenuPosition = false;
$sSearchPosition = false;
$sPhonesPosition = false;
$sSocialPosition = false;

if ($arMenuMain['SHOW']['DESKTOP'])
    $sMenuPosition = $arMenuMain['POSITION'];

if ($sMenuPosition === 'top')
    $bContainerShow = true;

if ($arResult['SEARCH']['SHOW']['DESKTOP']) {
    $sSearchPosition = 'bottom';

    if ($sMenuPosition == 'top')
        $sSearchPosition = 'top';
}

if ($sSearchPosition === 'top')
    $bPanelShow = true;

if ($sSearchPosition === 'bottom')
    $bContainerShow = true;

if ($arResult['CONTACTS']['SHOW']['DESKTOP'])
    $sPhonesPosition = $arResult['CONTACTS']['POSITION'];

if ($sPhonesPosition === 'top')
    $bPanelShow = true;

if ($arResult['SOCIAL']['SHOW']['DESKTOP'])
    $sSocialPosition = $arResult['SOCIAL']['POSITION'];

if ($sSocialPosition !== false)
    $bPanelShow = true;

$arContacts = [];
$arContact = null;

if ($arResult['CONTACTS']['SHOW']) {
    $arContacts = $arResult['CONTACTS']['VALUES'];
    $arContact = $arResult['CONTACTS']['SELECTED'];
}

?>
<header class="header">
    <div style="background: #7B4FA3;">
        <div class="container">
            <div class="header__top">
                <a href="/" rel="nofollow" class="header__logo--link">
                    <img src="/local/templates/universe_s1/onlineservice_addons/assets/logo.svg" alt="yoliba logo" class="header__logo">
                </a>
                <div class="header__brand-description">
                    <span>Подарки для всех</span>
                    <span>Легко найти, приятно подарить</span>
                </div>
                <div class="header__social-links">
                    <div class="header__social-links-phones">
                        <div class="widget-container-contacts-wrap intec-grid-item-auto">
                            <div class="widget-container-item widget-container-contacts intec-grid intec-grid-i-h-7" data-block="phone" data-multiple="<?= !empty($arContacts) ? 'true' : 'false' ?>" data-expanded="false">
                                <div class="intec-grid-item-auto">
                                    <div class="widget-container-phone">
                                        <div class="widget-container-phone-content">
                                            <?php if ($arResult['CONTACTS']['ADVANCED']) { ?>
                                                <?php foreach ($arContact as $arContactItem) { ?>
                                                    <a href="tel:<?= $arContactItem['PHONE']['VALUE'] ?>" class="tel header__social-links-phone" data-block-action="popup.open">
                                                        <span class="value"><?= $arContactItem['PHONE']['DISPLAY'] ?></span>
                                                    </a>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php foreach ($arContact as $arContactItem) { ?>
                                                    <a href="tel:<?= $arContactItem['VALUE'] ?>" class="tel header__social-links-phone" data-block-action="popup.open">
                                                        <span class="value"><?= $arContactItem['DISPLAY'] ?></span>
                                                    </a>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if (!empty($arContacts)) { ?>
                                                <div class="widget-container-phone-popup" data-block-element="popup">
                                                    <div class="widget-container-phone-popup-wrapper scrollbar-inner">
                                                        <?php if ($arResult['CONTACTS']['ADVANCED']) {
                                                            $sScheduleString = '';
                                                            ?>
                                                            <?php foreach ($arContacts as $arContact) { ?>
                                                                <div class="widget-container-phone-popup-contacts">
                                                                    <?php if (!empty($arContact['PHONE'])) { ?>
                                                                        <a href="tel:<?= $arContact['PHONE']['VALUE'] ?>" class="tel widget-container-phone-popup-contact phone intec-cl-text-hover">
                                                                            <span class="value"><?= $arContact['PHONE']['DISPLAY'] ?></span>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php if (!empty($arContact['ADDRESS'])) { ?>
                                                                        <div class="widget-container-phone-popup-contact address adr">
                                                                            <?php if (Type::isArray($arContact['ADDRESS'])) { ?>
                                                                                <?php foreach ($arContact['ADDRESS'] as $sValue) { ?>
                                                                                    <div class="locality"><?= $sValue ?></div>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <span class="locality"><?= $arContact['ADDRESS'] ?></span>
                                                                            <?php } ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <?php if (!empty($arContact['SCHEDULE'])) { ?>
                                                                        <div  class="widget-container-phone-popup-contact schedule">
                                                                            <?php if (Type::isArray($arContact['SCHEDULE'])) { ?>
                                                                                <?php foreach ($arContact['SCHEDULE'] as $sValue) { ?>
                                                                                    <?= $sValue ?>
                                                                                    <?php $sScheduleString .= $sValue.', '; ?>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <?= $arContact['SCHEDULE'] ?>
                                                                                <?php $sScheduleString .= $arContact['SCHEDULE'].', '; ?>
                                                                            <?php } ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <?php if (!empty($arContact['EMAIL'])) { ?>
                                                                        <a href="mailto:<?= $arContact['EMAIL'] ?>" class="widget-container-phone-popup-contact email intec-cl-text-hover">
                                                                            <span class="value"><?= $arContact['EMAIL'] ?></span>
                                                                        </a>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php }
                                                            $sScheduleString = substr($sScheduleString, 0, (strlen($sScheduleString) - 2));
                                                            ?>
                                                            <span class="workhours">
																		<span class="value-title" title="<?=$sScheduleString?>"></span>
																	</span>
                                                            <?php
                                                            unset($sScheduleString);
                                                        } else { ?>
                                                            <?php foreach ($arContacts as $arContact) { ?>
                                                                <a href="tel:<?= $arContact['VALUE'] ?>" class="tel widget-container-phone-popup-item intec-cl-text-hover">
                                                                    <?= $arContact['DISPLAY'] ?>
                                                                </a>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if (!empty($arContacts)) { ?>
                                            <div class="widget-container-phone-arrow far fa-chevron-down" data-block-action="popup.open"></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="header__social-links-icons">
                        <a rel="nofollow" href="#" class="header__social-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                                <path d="M11 0C17.0753 0 22 4.9247 22 11C22 17.0753 17.0753 22 11 22C9.19604 22.0031 7.42152 21.5603 5.83335 20.7138C5.59655 20.5876 5.32119 20.5493 5.06233 20.6199L1.69186 21.5396C0.945572 21.7432 0.260591 21.0587 0.463725 20.3122L1.38154 16.9397C1.45195 16.6809 1.4135 16.4058 1.28732 16.1691C0.440073 14.5803 -0.00314471 12.8049 1.67955e-05 11C1.67955e-05 4.9247 4.92471 0 11 0ZM7.25121 5.83L7.03121 5.8388C6.88897 5.8486 6.74999 5.88596 6.62201 5.9488C6.50274 6.01646 6.39383 6.10092 6.29861 6.1996C6.16661 6.3239 6.09181 6.43169 6.01151 6.5362C5.60465 7.06519 5.38558 7.71464 5.38891 8.38199C5.39111 8.92099 5.53191 9.44569 5.75191 9.93629C6.20181 10.9285 6.94211 11.979 7.91891 12.9525C8.15431 13.1868 8.38531 13.4222 8.63391 13.6411C9.84768 14.7096 11.294 15.4803 12.8579 15.8917L13.4827 15.9874C13.6862 15.9984 13.8897 15.983 14.0943 15.9731C14.4146 15.9562 14.7273 15.8695 15.0106 15.719C15.1501 15.6468 15.2865 15.5687 15.4194 15.4849C15.4277 15.4796 15.436 15.4742 15.4441 15.4686C15.4634 15.4552 15.5054 15.4255 15.5694 15.378C15.7179 15.268 15.8092 15.1899 15.9324 15.0612C16.0248 14.9659 16.1018 14.8551 16.1634 14.729C16.2492 14.5497 16.335 14.2076 16.3702 13.9227C16.3966 13.7049 16.3889 13.5861 16.3856 13.5124C16.3812 13.3947 16.2833 13.2726 16.1766 13.2209L15.5413 12.936C15.538 12.9345 15.5353 12.9333 15.532 12.9319C15.4613 12.9011 14.5563 12.5064 13.9942 12.2507C13.9329 12.224 13.8673 12.2087 13.8006 12.2056C13.7253 12.1977 13.6493 12.2061 13.5775 12.2302C13.4467 12.2742 13.3216 12.4056 13.231 12.5098C13.1099 12.6491 12.8942 12.9043 12.5103 13.3694C12.4647 13.4307 12.4018 13.4771 12.3297 13.5026C12.2576 13.528 12.1796 13.5314 12.1055 13.5124C12.0338 13.4933 11.9636 13.469 11.8954 13.4398C11.759 13.3826 11.7117 13.3606 11.6182 13.321C10.9867 13.0459 10.4021 12.6736 9.88571 12.2177C9.74711 12.0967 9.61841 11.9647 9.48641 11.8371C9.05368 11.4226 8.67654 10.9538 8.36441 10.4423L8.29951 10.3378C8.2536 10.2672 8.21595 10.1915 8.18731 10.1123C8.15448 9.98528 8.21463 9.87794 8.24194 9.83774C8.24984 9.82611 8.25901 9.81576 8.26846 9.80536C8.3251 9.74299 8.53877 9.50645 8.64601 9.36979C8.76701 9.21579 8.86931 9.06619 8.93531 8.95949C9.06511 8.75049 9.10581 8.53599 9.03761 8.36989C8.72961 7.61749 8.41134 6.86913 8.08281 6.1248C8.01791 5.9774 7.82541 5.8718 7.65051 5.8509C7.59111 5.84356 7.53171 5.8377 7.47231 5.8333C7.32461 5.82482 7.17651 5.82629 7.02901 5.8377L7.25121 5.83Z" fill="white"/>
                            </svg>
                        </a>
                        <a rel="nofollow" href="#" class="header__social-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="19" viewBox="0 0 21 19" fill="none">
                                <path d="M20.9329 2.2972L17.7514 17.905C17.5141 19.0042 16.9051 19.2518 16.0252 18.7556L11.253 15.0673L8.91674 17.4077C8.68049 17.6564 8.44319 17.905 7.9014 17.905L8.27415 12.7611L17.176 4.28435C17.5477 3.89374 17.0742 3.7518 16.6006 4.07199L5.5326 11.378L0.759313 9.85299C-0.289635 9.4987 -0.289635 8.75269 0.996612 8.25755L19.5448 0.701758C20.4583 0.417879 21.2374 0.915217 20.9329 2.2972Z" fill="white"/>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="header__icons">
                    <a href="#" class="header__icon header__icon--dice">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                            <path d="M1 14.8673V2.41082C1 2.04374 1.14582 1.6917 1.40538 1.43214C1.66494 1.17258 2.01698 1.02676 2.38406 1.02676H14.8406C15.2077 1.02676 15.5597 1.17258 15.8193 1.43214C16.0788 1.6917 16.2246 2.04374 16.2246 2.41082V14.8673C16.2246 15.2344 16.0788 15.5865 15.8193 15.846C15.5597 16.1056 15.2077 16.2514 14.8406 16.2514H2.38406C2.01698 16.2514 1.66494 16.1056 1.40538 15.846C1.14582 15.5865 1 15.2344 1 14.8673Z" stroke="white" stroke-width="0.958194"/>
                            <path d="M10.6901 5.17896H13.4582M10.6901 11.0612H13.4582M10.6901 13.1373H13.4582M3.76978 5.17896H5.15383M5.15383 5.17896H6.53789M5.15383 5.17896V3.7949M5.15383 5.17896V6.56301M4.1753 13.0778L5.15383 12.0992M5.15383 12.0992L6.13305 11.1207M5.15383 12.0992L4.1753 11.1207M5.15383 12.0992L6.13305 13.0778" stroke="white" stroke-width="0.958194" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="header__icon-title">Рассчет</span>
                    </a>
                    <?php include(__DIR__.'/../../../parts/onlineservice-basket.php') ?>

                    <!-- Авторизация -->
                    <?php if (
                        $arResult['AUTHORIZATION']['SHOW']['DESKTOP'] ||
                        $sSearchPosition === 'top'
                    ) { ?>
                        <div class="widget-panel-buttons-wrap intec-grid-item-auto">
                            <?php if ($arResult['AUTHORIZATION']['SHOW']['DESKTOP']) { ?>
                                <?php include(__DIR__.'/../../../parts/auth/onlineservice-panel.1.php') ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <div class="header__icon header__icon--menu" id="menuIcon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="header__search-row">
            <?php

            $arSearchParams = !empty($arSearchParams) ? $arSearchParams : [];

            $sPrefix = 'SEARCH_';
            $arParameters = [];

            foreach ($arParams as $sKey => $sValue)
                if (StringHelper::startsWith($sKey, $sPrefix)) {
                    $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefix));
                    $arParameters[$sKey] = $sValue;
                }

            $arParameters = ArrayHelper::merge($arParameters, $arSearchParams);
            $arParameters['PAGE'] = $arResult['SEARCH']['MODE'] === 'site' ? $arResult['URL']['SEARCH'] : $arResult['URL']['CATALOG'];
            $arParameters['INPUT_ID'] = $arParameters['INPUT_ID'].'-input-1';

            $arMenu = $arResult['MENU']['MAIN'];
            $arMenuParams = !empty($arMenuParams) ? $arMenuParams : [];

            $sPrefixCatalog = 'MENU_MAIN_';
            $arParametersCatalog = [];

            foreach ($arParams as $sKey => $sValue)
                if (StringHelper::startsWith($sKey, $sPrefixCatalog)) {
                    $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefixCatalog));
                    $arParametersCatalog[$sKey] = $sValue;
                }

            $arParametersCatalog['TRANSPARENT'] = $arResult['VISUAL']['TRANSPARENCY'] ? 'Y' : 'N';
            $arParametersCatalog = ArrayHelper::merge($arParametersCatalog, $arMenuParams, [
                'ROOT_MENU_TYPE' => $arMenu['ROOT'],
                'CHILD_MENU_TYPE' => $arMenu['CHILD'],
                'MAX_LEVEL' => $arMenu['LEVEL'],
                'MENU_CACHE_TYPE' => 'N',
                'USE_EXT' => 'Y',
                'DELAY' => 'N',
                'ALLOW_MULTI_SELECT' => 'N'
            ]);

            ?>
            <?php $APPLICATION->IncludeComponent(
                'bitrix:menu',
                'onlineservice.horizontal.1.custom',
                $arParametersCatalog,
                $this->getComponent()
            ); ?>
            <?php $APPLICATION->IncludeComponent(
                "bitrix:search.title",
                "onlineservice.input.1",
                $arParameters,
                $this->getComponent()
            ) ?>
            <div class="header__navigation">
                <ul class="header__navigation-list">
                    <li class="header__navigation-list--item">
                        <a class="header__navigation-list--item-link" href="/company/">О компании</a>
                    </li>
                    <li class="header__navigation-list--item">
                        <a class="header__navigation-list--item-link" href="/services/">Услуги</a>
                    </li>
                    <li class="header__navigation-list--item">
                        <a class="header__navigation-list--item-link" href="#">Купить</a>
                    </li>
                    <li class="header__navigation-list--item">
                        <a class="header__navigation-list--item-link" href="/informacziya-dlya-dilerov/">Стать дилером</a>
                    </li>
                    <li class="header__navigation-list--item">
                        <a class="header__navigation-list--item-link" href="/contacts/">Контакты</a>
                    </li>
                </ul>
            </div>
            <button href="#" class="header__callback-btn" data-action="forms.call.open">
                Заказать звонок
            </button>
            <?php include(__DIR__.'/../../../parts/forms/call.php') ?>
        </div>
    </div>
</header>
<nav class="mobile-menu" id="mobileMenu">
    <div class="container">
        <div class="mobile-menu__content">
            <ul class="mobile-menu__main">
                <li><a href="/company/">О компании</a></li>
                <li>Услуги
                    <span class="mobile-menu__arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="7" height="12" viewBox="0 0 7 12" fill="none">
                            <path d="M1 1L6 6L1 11" stroke="#FBB040" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </li>
                <li><a href="#">Купить</a></li>
                <li><a href="/informacziya-dlya-dilerov/">Стать дилером</a></li>
                <li><a href="/contacts/">Контакты</a></li>
            </ul>
            <ul class="mobile-menu__sub">
                <li>Виды нанесения
                    <span class="mobile-menu__arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" width="7" height="12" viewBox="0 0 7 12" fill="none">
                            <path d="M1 1L6 6L1 11" stroke="#FBB040" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </li>
                <li><a href="#">Программа привилегий и бонусов для дилеров</a></li>
                <li><a href="#">Гибкая система оплаты</a></li>
                <li><a href="#">Разработка дизайна сувенирной продукции</a></li>
                <li><a href="#">Товар на складе в Москве</a></li>
                <li><a href="/dostavka/">Доставка</a></li>
                <li><a href="/company/news/">Новости</a></li>
                <li><a href="/help/brands/">Бренды</a></li>
                <li><a href="#">Новинки</a></li>
                <li><a href="/shares/">Акции и Скидки</a></li>
                <li><a href="#">Как проехать</a></li>
            </ul>
            <button class="mobile-menu__call-btn" data-action="forms.call.open">Заказать звонок</button>
            <div class="mobile-menu__footer">
                <div class="mobile-menu__support">
                    Служба поддержки<br>
                    <a href="tel:+78007075211">+7 (800) 707-52-11</a>
                </div>
                <div class="mobile-menu__socials">
                    <span class="icon-wa">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22" fill="none">
                            <path d="M11 0C17.0753 0 22 4.9247 22 11C22 17.0753 17.0753 22 11 22C9.19604 22.0031 7.42152 21.5603 5.83335 20.7138C5.59655 20.5876 5.32119 20.5493 5.06233 20.6199L1.69186 21.5396C0.945572 21.7432 0.260591 21.0587 0.463725 20.3122L1.38154 16.9397C1.45195 16.6809 1.4135 16.4058 1.28732 16.1691C0.440073 14.5803 -0.00314471 12.8049 1.67955e-05 11C1.67955e-05 4.9247 4.92471 0 11 0ZM7.25121 5.83L7.03121 5.8388C6.88897 5.8486 6.74999 5.88596 6.62201 5.9488C6.50274 6.01646 6.39383 6.10092 6.29861 6.1996C6.16661 6.3239 6.09181 6.43169 6.01151 6.5362C5.60465 7.06519 5.38558 7.71464 5.38891 8.38199C5.39111 8.92099 5.53191 9.44569 5.75191 9.93629C6.20181 10.9285 6.94211 11.979 7.91891 12.9525C8.15431 13.1868 8.38531 13.4222 8.63391 13.6411C9.84768 14.7096 11.294 15.4803 12.8579 15.8917L13.4827 15.9874C13.6862 15.9984 13.8897 15.983 14.0943 15.9731C14.4146 15.9562 14.7273 15.8695 15.0106 15.719C15.1501 15.6468 15.2865 15.5687 15.4194 15.4849C15.4277 15.4796 15.436 15.4742 15.4441 15.4686C15.4634 15.4552 15.5054 15.4255 15.5694 15.378C15.7179 15.268 15.8092 15.1899 15.9324 15.0612C16.0248 14.9659 16.1018 14.8551 16.1634 14.729C16.2492 14.5497 16.335 14.2076 16.3702 13.9227C16.3966 13.7049 16.3889 13.5861 16.3856 13.5124C16.3812 13.3947 16.2833 13.2726 16.1766 13.2209L15.5413 12.936C15.538 12.9345 15.5353 12.9333 15.532 12.9319C15.4613 12.9011 14.5563 12.5064 13.9942 12.2507C13.9329 12.224 13.8673 12.2087 13.8006 12.2056C13.7253 12.1977 13.6493 12.2061 13.5775 12.2302C13.4467 12.2742 13.3216 12.4056 13.231 12.5098C13.1099 12.6491 12.8942 12.9043 12.5103 13.3694C12.4647 13.4307 12.4018 13.4771 12.3297 13.5026C12.2576 13.528 12.1796 13.5314 12.1055 13.5124C12.0338 13.4933 11.9636 13.469 11.8954 13.4398C11.759 13.3826 11.7117 13.3606 11.6182 13.321C10.9867 13.0459 10.4021 12.6736 9.88571 12.2177C9.74711 12.0967 9.61841 11.9647 9.48641 11.8371C9.05368 11.4226 8.67654 10.9538 8.36441 10.4423L8.29951 10.3378C8.2536 10.2672 8.21595 10.1915 8.18731 10.1123C8.15448 9.98528 8.21463 9.87794 8.24194 9.83774C8.24984 9.82611 8.25901 9.81576 8.26846 9.80536C8.3251 9.74299 8.53877 9.50645 8.64601 9.36979C8.76701 9.21579 8.86931 9.06619 8.93531 8.95949C9.06511 8.75049 9.10581 8.53599 9.03761 8.36989C8.72961 7.61749 8.41134 6.86913 8.08281 6.1248C8.01791 5.9774 7.82541 5.8718 7.65051 5.8509C7.59111 5.84356 7.53171 5.8377 7.47231 5.8333C7.32461 5.82482 7.17651 5.82629 7.02901 5.8377L7.25121 5.83Z" fill="white"/>
                        </svg>
                    </span>
                    <span class="icon-tg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="19" viewBox="0 0 21 19" fill="none">
                            <path d="M20.9329 2.2972L17.7514 17.905C17.5141 19.0042 16.9051 19.2518 16.0252 18.7556L11.253 15.0673L8.91674 17.4077C8.68049 17.6564 8.44319 17.905 7.9014 17.905L8.27415 12.7611L17.176 4.28435C17.5477 3.89374 17.0742 3.7518 16.6006 4.07199L5.5326 11.378L0.759313 9.85299C-0.289635 9.4987 -0.289635 8.75269 0.996612 8.25755L19.5448 0.701758C20.4583 0.417879 21.2374 0.915217 20.9329 2.2972Z" fill="white"/>
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </div>
</nav>


<?php if ($sPhonesPosition !== false && !empty($arContacts) && !defined('EDITOR')) { ?>
    <script type="text/javascript">
        template.load(function (data) {
            var $ = this.getLibrary('$');
            var root = data.nodes;
            var block = $('[data-block="phone"]', root);
            var popup = $('[data-block-element="popup"]', block);
            var scrollContacts = $('.scrollbar-inner', popup);

            popup.open = $('[data-block-action="popup.open"]', block);
            popup.open.on('mouseenter', function () {
                block.attr('data-expanded', 'true');
            });

            block.on('mouseleave', function () {
                block.attr('data-expanded', 'false');
            });

            scrollContacts.scrollbar();
        }, {
            'name': '[Component] intec.universe:main.header (template.1) > desktop (template.1) > phone.expand',
            'nodes': <?= JavaScript::toObject('#'.$sTemplateId) ?>,
            'loader': {
                'name': 'lazy'
            }
        });
    </script>
<?php } ?>
