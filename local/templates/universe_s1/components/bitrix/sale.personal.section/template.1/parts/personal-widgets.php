<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use intec\core\helpers\Html;

/**
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

global $USER;

$defaultWidgets = [
    'plan' => ['amount' => 459500, 'percent' => 92],
    'credit' => ['amount' => 459500, 'percent' => 92, 'days' => 30],
    'debt' => ['amount' => 459500, 'percent' => 92],
    'turnover' => [
        'left' => 'ЗНАЧЕНИЕ 1',
        'right' => 'ЗНАЧЕНИЕ 2',
    ],
];

$arWidgets = isset($arResult['PERSONAL_WIDGETS']) && is_array($arResult['PERSONAL_WIDGETS'])
    ? array_replace_recursive($defaultWidgets, $arResult['PERSONAL_WIDGETS'])
    : $defaultWidgets;

// Процент только из групп скидки компании (см. Company::$companyDiscountPercentByAssignedGroupId).
$discountPct = 0.0;
if ($USER->IsAuthorized()) {
    $companyClass = '\\OnlineService\\Site\\Company';

    // Fail-safe для переходного периода рефакторинга: не роняем рендер ЛК при отсутствии модуля/класса.
    if (!class_exists($companyClass)) {
        \Bitrix\Main\Loader::includeModule('eklektika.company');
    }

    if (class_exists($companyClass)) {
        $discountPct = (float)$companyClass::getMaxCompanyDiscountPercentForUserGroups(
            CUser::GetUserGroup($USER->GetID())
        );
    }
}

$discountLabel = '-' . (int)round($discountPct);

$ringR = 36;
$ringC = 2 * M_PI * $ringR;

$formatAmount = static function ($amount) {
    if ($amount === null || $amount === '' || !is_numeric($amount)) {
        return '—';
    }
    $n = (float)$amount / 1000.0;

    return number_format($n, 1, ',', ' ') . ' ТЫС.';
};

$formatTurnoverCell = static function ($value) use ($formatAmount) {
    if ($value === null || $value === '') {
        return '—';
    }
    if (is_numeric($value)) {
        return $formatAmount($value);
    }

    return (string)$value;
};

$renderRing = static function (float $percent) use ($ringR, $ringC) {
    $p = max(0.0, min(100.0, $percent));
    $offset = $ringC * (1.0 - $p / 100.0);
    $pctText = (int)round($p);
    ?>
    <div class="personal-widgets__ring-wrap">
        <svg class="personal-widgets__ring" viewBox="0 0 88 88" aria-hidden="true">
            <circle class="personal-widgets__ring-bg" cx="44" cy="44" r="<?= (int)$ringR ?>" fill="none" stroke-width="7"/>
            <circle
                class="personal-widgets__ring-progress"
                cx="44"
                cy="44"
                r="<?= (int)$ringR ?>"
                fill="none"
                stroke-width="7"
                stroke-dasharray="<?= $ringC ?>"
                stroke-dashoffset="<?= $offset ?>"
                transform="rotate(-90 44 44)"
            />
        </svg>
        <span class="personal-widgets__ring-label"><?= Html::encode($pctText) ?> <percent>%</percent></span>
    </div>
    <?php
};

?> 
<div class="personal-widgets">
    <div class="personal-widgets__grid">
        <div class="personal-widgets__card personal-widgets__card--discount">
            <div class="personal-widgets__discount-badge">
                <svg class="personal-widgets__discount-badge-shape" xmlns="http://www.w3.org/2000/svg" width="101" height="106" viewBox="0 0 101 106" fill="none" aria-hidden="true">
                    <path d="M46.509 1.29752C48.7817 -0.432108 51.9293 -0.432108 54.2019 1.29752L62.7204 7.78067C63.8646 8.65149 65.2705 9.10828 66.708 9.07632L77.4103 8.83838C80.2655 8.77491 82.812 10.625 83.634 13.3601L86.7149 23.6122C87.1287 24.9892 87.9976 26.1851 89.1793 27.0042L97.9775 33.1024C100.325 34.7293 101.297 37.7229 100.355 40.4187L96.8213 50.5237C96.3467 51.881 96.3467 53.3592 96.8213 54.7165L100.355 64.8215C101.297 67.5174 100.325 70.5109 97.9775 72.1379L89.1793 78.236C87.9976 79.0551 87.1287 80.251 86.7149 81.6281L83.634 91.8801C82.812 94.6152 80.2655 96.4653 77.4103 96.4019L66.708 96.1639C65.2705 96.132 63.8646 96.5887 62.7204 97.4596L54.2019 103.943C51.9293 105.672 48.7817 105.672 46.509 103.943L37.9905 97.4596C36.8463 96.5887 35.4405 96.132 34.0029 96.1639L23.3006 96.4019C20.4454 96.4653 17.8989 94.6152 17.077 91.8801L13.9961 81.6281C13.5822 80.251 12.7134 79.0551 11.5316 78.236L2.7334 72.1379C0.386142 70.5109 -0.586527 67.5174 0.356165 64.8215L3.88965 54.7165C4.36427 53.3592 4.36427 51.881 3.88965 50.5237L0.356165 40.4187C-0.586527 37.7229 0.386139 34.7293 2.7334 33.1024L11.5316 27.0042C12.7134 26.1851 13.5822 24.9892 13.9961 23.6122L17.077 13.3601C17.8989 10.625 20.4454 8.77491 23.3006 8.83838L34.0029 9.07632C35.4405 9.10828 36.8463 8.6515 37.9905 7.78067L46.509 1.29752Z" fill="#744A9E"/>
                </svg>
                <span class="personal-widgets__discount-badge-text"><?= Html::encode($discountLabel) ?><percent>%</percent></span>
            </div>
            <div class="personal-widgets__discount-title">Персональная скидка</div>
        </div>

        <div style="display: none;" class="personal-widgets__card personal-widgets__card--metric">
            <div class="personal-widgets__metric-row">
                <div class="personal-widgets__metric-text">
                    <div class="personal-widgets__label">План на год</div>
                    <div class="personal-widgets__value"><?= Html::encode($formatAmount($arWidgets['plan']['amount'] ?? null)) ?></div>
                </div>
                <?php $renderRing((float)($arWidgets['plan']['percent'] ?? 0)); ?>
            </div>
        </div>

        <div style="display: none;" class="personal-widgets__card personal-widgets__card--metric">
            <div class="personal-widgets__metric-row">
                <div class="personal-widgets__metric-text">
                    <div class="personal-widgets__label">
                        Кредитная линия (<?= Html::encode((string)($arWidgets['credit']['days'] ?? 30)) ?> дней)
                    </div>
                    <div class="personal-widgets__value"><?= Html::encode($formatAmount($arWidgets['credit']['amount'] ?? null)) ?></div>
                </div>
                <?php $renderRing((float)($arWidgets['credit']['percent'] ?? 0)); ?>
            </div>
        </div>

        <div style="display: none;" class="personal-widgets__card personal-widgets__card--metric">
            <div class="personal-widgets__metric-row">
                <div class="personal-widgets__metric-text">
                    <div class="personal-widgets__label">Задолженность текущая</div>
                    <div class="personal-widgets__value"><?= Html::encode($formatAmount($arWidgets['debt']['amount'] ?? null)) ?></div>
                </div>
                <?php $renderRing((float)($arWidgets['debt']['percent'] ?? 0)); ?>
            </div>
        </div>

        <div style="display: none;" class="personal-widgets__card personal-widgets__card--turnover">
            <div class="personal-widgets__label personal-widgets__label--turnover">Оборот за период</div>
            <div class="personal-widgets__turnover-cols">
                <div class="personal-widgets__turnover-cell">
                    <?= Html::encode($formatTurnoverCell($arWidgets['turnover']['left'] ?? null)) ?>
                </div>
                <div class="personal-widgets__turnover-cell">
                    <?= Html::encode($formatTurnoverCell($arWidgets['turnover']['right'] ?? null)) ?>
                </div>
            </div>
        </div>
    </div>
</div>
