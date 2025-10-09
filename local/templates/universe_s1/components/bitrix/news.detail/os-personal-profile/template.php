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

// Получаем данные компании
$companyName = $arResult["NAME"];
$companyEmail = $arResult["PROPERTIES"]["OS_COMPANY_EMAIL"]["VALUE"] ?? '';
$companyPhone = $arResult["PROPERTIES"]["OS_COMPANY_PHONE"]["VALUE"] ?? '';
$companyInn = $arResult["PROPERTIES"]["OS_COMPANY_INN"]["VALUE"] ?? '';
$companyBossIds = $arResult["PROPERTIES"]["OS_COMPANY_BOSS"]["VALUE"] ?? [];
$companyUserIds = $arResult["PROPERTIES"]["OS_COMPANY_USERS"]["VALUE"] ?? [];

// Преобразуем в массив если пришло одно значение
if (!is_array($companyBossIds)) {
    $companyBossIds = $companyBossIds ? [$companyBossIds] : [];
}
if (!is_array($companyUserIds)) {
    $companyUserIds = $companyUserIds ? [$companyUserIds] : [];
}

// Получаем информацию о руководителях
$bosses = [];
if (!empty($companyBossIds)) {
    foreach ($companyBossIds as $bossId) {
        if ($bossId) {
            $rsUser = CUser::GetByID($bossId);
            if ($boss = $rsUser->Fetch()) {
                $bosses[] = $boss;
            }
        }
    }
}

// Получаем информацию о сотрудниках (исключая руководителей)
$employees = [];
if (!empty($companyUserIds)) {
    foreach ($companyUserIds as $userId) {
        if (!in_array($userId, $companyBossIds)) {
            $rsUser = CUser::GetByID($userId);
            if ($user = $rsUser->Fetch()) {
                $employees[] = $user;
            }
        }
    }
}

// Проверяем, является ли текущий пользователь руководителем компании
global $USER;
$currentUserId = $USER->GetID();
$isCompanyBoss = in_array($currentUserId, $companyBossIds);
?>

<div class="company-profile">
    <!-- Левая колонка: Информация о компании + Руководство -->
    <div class="company-profile__left">
        <!-- Блок 1: Информация о компании -->
        <div class="company-profile__block company-info">
            <h2 class="company-profile__title">Информация о компании</h2>
            <div class="company-info__content">
                <div class="company-info__name">
                    <strong><?=$companyName?></strong>
                </div>
                
                <?if($companyInn):?>
                <div class="company-info__item">
                    <span class="company-info__label">ИНН:</span>
                    <span class="company-info__value"><?=$companyInn?></span>
                </div>
                <?endif;?>
                
                <?if($companyPhone):?>
                <div class="company-info__item">
                    <span class="company-info__label">Телефон:</span>
                    <span class="company-info__value">
                        <a href="tel:<?=$companyPhone?>"><?=$companyPhone?></a>
                    </span>
                </div>
                <?endif;?>
                
                <?if($companyEmail):?>
                <div class="company-info__item">
                    <span class="company-info__label">Email:</span>
                    <span class="company-info__value">
                        <a href="mailto:<?=$companyEmail?>"><?=$companyEmail?></a>
                    </span>
                </div>
                <?endif;?>
            </div>
        </div>

        <!-- Блок 2: Информация о руководстве -->
        <div class="company-profile__block company-management">
            <h2 class="company-profile__title">Руководство</h2>
            <div class="company-management__content">
                <?if(!empty($bosses)):?>
                <?foreach($bosses as $boss):?>
                <div class="management-card">
                    <div class="management-card__avatar">
                        <?if($boss['PERSONAL_PHOTO']):?>
                            <?$photoSrc = CFile::GetPath($boss['PERSONAL_PHOTO']);?>
                            <img src="<?=$photoSrc?>" alt="<?=$boss['NAME']?> <?=$boss['LAST_NAME']?>">
                        <?else:?>
                            <div class="management-card__avatar-placeholder">
                                <?=mb_substr($boss['NAME'], 0, 1)?>
                            </div>
                        <?endif;?>
                    </div>
                    <div class="management-card__info">
                        <div class="management-card__name">
                            <?=$boss['NAME']?> <?=$boss['LAST_NAME']?>
                            <?if($boss['WORK_POSITION']):?>
                                <span class="management-card__position"> - <?=$boss['WORK_POSITION']?></span>
                            <?endif;?>
                        </div>
                        <div class="management-card__contacts">
                            <?if($boss['PERSONAL_PHONE']):?>
                            <div class="management-card__contact">
                                <a href="tel:<?=$boss['PERSONAL_PHONE']?>">
                                    <?=$boss['PERSONAL_PHONE']?>
                                </a>
                            </div>
                            <?endif;?>
                            <?if($boss['EMAIL']):?>
                            <div class="management-card__contact">
                                <a href="mailto:<?=$boss['EMAIL']?>">
                                    <?=$boss['EMAIL']?>
                                </a>
                            </div>
                            <?endif;?>
                        </div>
                    </div>
                </div>
                <?endforeach;?>
                <?else:?>
                <div class="company-management__empty">
                    Руководитель не назначен
                </div>
                <?endif;?>
            </div>
        </div>
    </div>

    <!-- Правая колонка: Список сотрудников -->
    <div class="company-profile__right">
        <!-- Блок 3: Список сотрудников -->
        <div class="company-profile__block company-employees">
            <div class="company-profile__title-wrapper">
                <h2 class="company-profile__title">Сотрудники</h2>
                <?if($isCompanyBoss):?>
                <button class="company-employees__add-btn" onclick="alert('Добавление сотрудника')">
                    + Добавить
                </button>
                <?endif;?>
            </div>
            <div class="company-employees__content">
                <?if(!empty($employees)):?>
                <div class="employees-list">
                    <?foreach($employees as $employee):?>
                    <div class="employee-card">
                        <div class="employee-card__avatar">
                            <?if($employee['PERSONAL_PHOTO']):?>
                                <?$photoSrc = CFile::GetPath($employee['PERSONAL_PHOTO']);?>
                                <img src="<?=$photoSrc?>" alt="<?=$employee['NAME']?> <?=$employee['LAST_NAME']?>">
                            <?else:?>
                                <div class="employee-card__avatar-placeholder">
                                    <?=mb_substr($employee['NAME'], 0, 1)?>
                                </div>
                            <?endif;?>
                        </div>
                        <div class="employee-card__info">
                            <div class="employee-card__name">
                                <?=$employee['NAME']?> <?=$employee['LAST_NAME']?>
                                <?if($employee['WORK_POSITION']):?>
                                    <span class="employee-card__position"> - <?=$employee['WORK_POSITION']?></span>
                                <?endif;?>
                            </div>
                            <?if($employee['PERSONAL_PHONE'] || $employee['EMAIL']):?>
                            <div class="employee-card__contacts">
                                <?if($employee['PERSONAL_PHONE']):?>
                                <a href="tel:<?=$employee['PERSONAL_PHONE']?>" class="employee-card__contact">
                                    <?=$employee['PERSONAL_PHONE']?>
                                </a>
                                <?endif;?>
                                <?if($employee['EMAIL']):?>
                                <a href="mailto:<?=$employee['EMAIL']?>" class="employee-card__contact">
                                    <?=$employee['EMAIL']?>
                                </a>
                                <?endif;?>
                            </div>
                            <?endif;?>
                        </div>
                    </div>
                    <?endforeach;?>
                </div>
                <?else:?>
                <div class="company-employees__empty">
                    Сотрудники не добавлены
                </div>
                <?endif;?>
            </div>
        </div>
    </div>
</div>