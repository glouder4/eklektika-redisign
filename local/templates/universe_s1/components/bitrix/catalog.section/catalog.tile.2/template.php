<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Json;
use intec\core\helpers\FileHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CAllMain $APPLICATION
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (!Loader::includeModule('intec.core'))
    return;

$arNavigation = !empty($arResult['NAV_RESULT']) ? [
    'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
    'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
    'NavNum' => $arResult['NAV_RESULT']->NavNum
] : [
    'NavPageCount' => 1,
    'NavPageNomer' => 1,
    'NavNum' => $this->randString()
];

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$sTemplateContainer = $sTemplateId.'-'.$arNavigation['NavNum'];

$arVisual = $arResult['VISUAL'];

$arVisual['NAVIGATION']['LAZY']['BUTTON'] =
    $arVisual['NAVIGATION']['LAZY']['BUTTON'] &&
    $arNavigation['NavPageNomer'] < $arNavigation['NavPageCount'];

$iCounter = 1;
$iItemsCount = count($arResult['ITEMS']);
$bGiftShowed = false;
$arSvg = [
    'PRICE_DIFFERENCE' => FileHelper::getFileData(__DIR__.'/svg/price.difference.svg')
];

/**
 * @var Closure $vCounter
 * @var Closure $dData
 * @var Closure $vButtons
 * @var Closure $vImage
 * @var Closure $vPrice
 * @var Closure $vPurchase
 * @var Closure $vQuantity
 * @var Closure $vSku
 * @var Closure $vSkuExtended
 * @var Closure $vQuickView
 */
include(__DIR__.'/parts/counter.php');
include(__DIR__.'/parts/data.php');
include(__DIR__.'/parts/buttons.php');
include(__DIR__.'/parts/image.php');
include(__DIR__.'/parts/price.php');
include(__DIR__.'/parts/purchase.php');
include(__DIR__.'/parts/quantity.php');
include(__DIR__.'/parts/sku.php');

$figmaTagsData = [
    '33386' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#4BD783"/><path d="M20.058 13.6348C22.1533 16.5891 20.5504 19.9729 19.7752 21.2929C19.6615 21.4775 19.5088 21.6351 19.3278 21.7545C19.1469 21.874 18.942 21.9524 18.7275 21.9843C17.2085 22.2462 13.4266 22.5396 11.3942 19.5853C9.39325 16.7986 9.47706 11.8958 9.65516 9.55956C9.66218 9.38551 9.71044 9.21561 9.79596 9.06385C9.88148 8.9121 10.0018 8.78282 10.1471 8.68667C10.2923 8.59051 10.4583 8.53023 10.6315 8.51078C10.8046 8.49134 10.9798 8.51328 11.1428 8.5748C13.3952 9.22433 18.0885 10.8481 20.058 13.6348Z" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.9971 12.6602C15.3967 15.488 18.5529 20.0784 20.333 23.332" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'title' => 'ЭКО'
    ],
    '33387' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#744A9E"/><path d="M21.2468 19.4493C21.2468 19.9134 21.0625 20.3586 20.7343 20.6868C20.4061 21.015 19.961 21.1993 19.4968 21.1993C19.0327 21.1993 18.5876 21.015 18.2594 20.6868C17.9312 20.3586 17.7468 19.9134 17.7468 19.4493C17.7468 18.9851 17.9312 18.54 18.2594 18.2118C18.5876 17.8836 19.0327 17.6992 19.4968 17.6992C19.961 17.6992 20.4061 17.8836 20.7343 18.2118C21.0625 18.54 21.2468 18.9851 21.2468 19.4493ZM14.2468 19.4493C14.2468 19.9134 14.0625 20.3586 13.7343 20.6868C13.4061 21.015 12.961 21.1993 12.4968 21.1993C12.0327 21.1993 11.5876 21.015 11.2594 20.6868C10.9312 20.3586 10.7468 19.9134 10.7468 19.4493C10.7468 18.9851 10.9312 18.54 11.2594 18.2118C11.5876 17.8836 12.0327 17.6992 12.4968 17.6992C12.961 17.6992 13.4061 17.8836 13.7343 18.2118C14.0625 18.54 14.2468 18.9851 14.2468 19.4493Z" stroke="#744A9E" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.75 19.4503H14.25M9 10H16C16.9898 10 17.4847 10 17.792 10.308C18.1 10.6146 18.1 11.1095 18.1 12.1001V18.0503M18.45 11.7501H19.7107C20.2917 11.7501 20.5822 11.7501 20.823 11.8866C21.0638 12.0224 21.2129 12.2716 21.5118 12.77L22.7011 14.7511C22.8495 14.9989 22.9237 15.1235 22.9622 15.2607C23 15.3986 23 15.5428 23 15.8319V17.7003C23 18.3548 23 18.6817 22.8593 18.9253C22.7671 19.0849 22.6346 19.2175 22.475 19.3096C22.2314 19.4503 21.9045 19.4503 21.25 19.4503M9 16.3002V17.7003C9 18.3548 9 18.6817 9.1407 18.9253C9.23285 19.0849 9.36539 19.2175 9.525 19.3096C9.7686 19.4503 10.0955 19.4503 10.75 19.4503M9 12.1001H13.2M9 14.2001H11.8" stroke="#744A9E" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'title' => 'В пути'
    ],
    '33388' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#EF4A85"/><g clip-path="url(#clip0_1711_12132)"><path d="M21.28 17.3912L17.39 21.2812C17.2494 21.4217 17.0588 21.5006 16.86 21.5006C16.6613 21.5006 16.4706 21.4217 16.33 21.2812L8.61001 13.5612C8.57122 13.5242 8.54121 13.4789 8.52218 13.4287C8.50315 13.3785 8.49557 13.3247 8.50001 13.2712L9.09001 9.44123C9.09483 9.34997 9.13325 9.26371 9.19787 9.19909C9.26249 9.13447 9.34875 9.09605 9.44001 9.09123L13.27 8.50123C13.3235 8.49679 13.3773 8.50437 13.4275 8.5234C13.4776 8.54243 13.5229 8.57244 13.56 8.61123L21.28 16.3312C21.4205 16.4719 21.4994 16.6625 21.4994 16.8612C21.4994 17.06 21.4205 17.2506 21.28 17.3912V17.3912Z" stroke="#EF4A85" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.1101 12.6113C12.3863 12.6113 12.6101 12.3875 12.6101 12.1113C12.6101 11.8352 12.3863 11.6113 12.1101 11.6113C11.834 11.6113 11.6101 11.8352 11.6101 12.1113C11.6101 12.3875 11.834 12.6113 12.1101 12.6113Z" stroke="#EF4A85" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12132"><rect width="14" height="14" fill="white" transform="translate(8 8)"/></clipPath></defs></svg>',
        'title' => 'Распродажа'
    ],
    '33389' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#FF9900"/><path d="M21.5 13.5C21.5 13.3674 21.4473 13.2402 21.3536 13.1464C21.2598 13.0527 21.1326 13 21 13H20V12C20 11.7348 19.8946 11.4804 19.7071 11.2929C19.5196 11.1054 19.2652 11 19 11H9.5C9.23478 11 8.98043 11.1054 8.79289 11.2929C8.60536 11.4804 8.5 11.7348 8.5 12V18C8.5 18.2652 8.60536 18.5196 8.79289 18.7071C8.98043 18.8946 9.23478 19 9.5 19H19C19.2652 19 19.5196 18.8946 19.7071 18.7071C19.8946 18.5196 20 18.2652 20 18V17H21C21.1326 17 21.2598 16.9473 21.3536 16.8536C21.4473 16.7598 21.5 16.6326 21.5 16.5V13.5Z" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.25 13.5V16.5" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.25 13.5V16.5" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.25 13.5V16.5" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'title' => 'Быстрая зарядка'
    ],
    '33390' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#4BD783"/><path d="M14.9999 20.2489C15.7234 20.2489 16.3099 19.6624 16.3099 18.9389C16.3099 18.2154 15.7234 17.6289 14.9999 17.6289C14.2764 17.6289 13.6899 18.2154 13.6899 18.9389C13.6899 19.6624 14.2764 20.2489 14.9999 20.2489Z" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.53 16.0001C12.8552 15.6663 13.2439 15.401 13.6733 15.2198C14.1027 15.0386 14.564 14.9453 15.03 14.9453C15.4961 14.9453 15.9574 15.0386 16.3867 15.2198C16.8161 15.401 17.2049 15.6663 17.53 16.0001" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.3601 14.3089C10.9688 13.6966 11.6926 13.2107 12.4897 12.8792C13.2869 12.5476 14.1417 12.377 15.0051 12.377C15.8685 12.377 16.7233 12.5476 17.5205 12.8792C18.3176 13.2107 19.0414 13.6966 19.6501 14.3089" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 12.4492C9.35343 11.5954 10.3667 10.918 11.482 10.4559C12.5973 9.99372 13.7928 9.75586 15 9.75586C16.2072 9.75586 17.4027 9.99372 18.518 10.4559C19.6333 10.918 20.6466 11.5954 21.5 12.4492" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'title' => 'Беспроводная зарядка'
    ],
    '33391' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#FF5B36"/><g clip-path="url(#clip0_1711_12181)"><path d="M11 13H9.5C9.23478 13 8.98043 13.1054 8.79289 13.2929C8.60536 13.4804 8.5 13.7348 8.5 14V16C8.5 16.2652 8.60536 16.5196 8.79289 16.7071C8.98043 16.8946 9.23478 17 9.5 17H11V13Z" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 16.9994L14.91 19.8094C15.0549 19.9096 15.2236 19.9698 15.3992 19.9838C15.5748 19.9979 15.751 19.9653 15.91 19.8894C16.0824 19.8119 16.2293 19.6872 16.3337 19.5297C16.4382 19.3722 16.4958 19.1883 16.5 18.9994V10.9994C16.5043 10.8198 16.4601 10.6424 16.3721 10.4858C16.2841 10.3292 16.1556 10.1991 16 10.1094C15.841 10.0334 15.6648 10.0009 15.4892 10.0149C15.3136 10.029 15.1449 10.0891 15 10.1894L11 12.9994" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M20.5 12C21.1953 12.8406 21.5519 13.9104 21.5 15C21.485 16.235 21.1397 17.4435 20.5 18.5" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 13.5C18.8476 13.9203 19.0259 14.4552 19 15C19.0259 15.5448 18.8476 16.0797 18.5 16.5" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12181"><rect width="14" height="14" fill="white" transform="translate(8 8)"/></clipPath></defs></svg>',
        'title' => 'Мощный звук'
    ],
    '33392' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#FF9900"/><g clip-path="url(#clip0_1711_12188)"><path d="M18.4995 15.9994C18.5018 15.3754 18.3373 14.762 18.0228 14.2229C17.7084 13.6838 17.2555 13.2386 16.7112 12.9334C16.1668 12.6282 15.5508 12.4741 14.9268 12.4871C14.3029 12.5 13.6938 12.6796 13.1625 13.0071C12.6313 13.3347 12.1974 13.7983 11.9056 14.35C11.6138 14.9017 11.4749 15.5213 11.5031 16.1447C11.5313 16.7682 11.7258 17.3727 12.0662 17.8958C12.4067 18.4188 12.8808 18.8413 13.4395 19.1194V20.8394C13.4421 20.9411 13.4843 21.0378 13.5572 21.1088C13.6301 21.1797 13.7278 21.2195 13.8295 21.2194H16.1695C16.2712 21.2195 16.3689 21.1797 16.4418 21.1088C16.5146 21.0378 16.5569 20.9411 16.5595 20.8394V19.0894C17.1357 18.8026 17.6217 18.3624 17.964 17.8172C18.3062 17.2721 18.4915 16.6431 18.4995 15.9994V15.9994Z" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.9995 8.80859V10.3086" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.9995 10.2383L17.9395 11.3083" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M21.2798 13.3398H19.7798" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M11 10.2383L12.06 11.3083" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.71973 13.3398H10.2197" stroke="#FF9900" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12188"><rect width="14" height="14" fill="white" transform="translate(8 8)"/></clipPath></defs></svg>',
        'title' => 'Подсветка'
    ],
    '33393' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#3636ED"/><g clip-path="url(#clip0_1711_12197)"><path d="M20 17C20 13.5 15 8.5 15 8.5C15 8.5 10 13.5 10 17C10.0698 18.2585 10.6348 19.4382 11.5717 20.2814C12.5086 21.1246 13.7411 21.5627 15 21.5C16.2589 21.5627 17.4914 21.1246 18.4283 20.2814C19.3652 19.4382 19.9302 18.2585 20 17V17Z" stroke="#3636ED" stroke-linecap="round" stroke-linejoin="round"/><path d="M15 8.5V21.5" stroke="#3636ED" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.5 20.9014V11.4414" stroke="#3636ED" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12197"><rect width="14" height="14" fill="white" transform="translate(8 8)"/></clipPath></defs></svg>',
        'title' => 'Влагостойкость'
    ],
    '33394' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#57B0EA"/><g clip-path="url(#clip0_1711_12137)"><path d="M19.25 17V10C19.25 9.60218 19.092 9.22064 18.8107 8.93934C18.5294 8.65804 18.1478 8.5 17.75 8.5V8.5C17.3522 8.5 16.9706 8.65804 16.6893 8.93934C16.408 9.22064 16.25 9.60218 16.25 10V17C15.8302 17.3148 15.5202 17.7537 15.3637 18.2546C15.2073 18.7554 15.2124 19.2928 15.3783 19.7906C15.5442 20.2883 15.8626 20.7213 16.2882 21.0281C16.7139 21.3349 17.2253 21.5 17.75 21.5C18.2747 21.5 18.7861 21.3349 19.2118 21.0281C19.6374 20.7213 19.9558 20.2883 20.1217 19.7906C20.2876 19.2928 20.2927 18.7554 20.1363 18.2546C19.9798 17.7537 19.6698 17.3148 19.25 17Z" stroke="#57B0EA" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.1426 9.21289L10.9997 10.07L11.8569 9.21289" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.21387 12.8549L9.07101 11.9978L8.21387 11.1406" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.8569 14.7849L10.9997 13.9277L10.1426 14.7849" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.7849 11.1406L12.9277 11.9978L13.7849 12.8549" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.49951 10.5L10.1424 11.1429" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.1424 12.8555L9.49951 13.4983" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.4993 10.5L11.8564 11.1429" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.8564 12.8555L12.4993 13.4983" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M10.9995 10.0703V13.9275" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M9.0708 12H12.9279" stroke="#57B0EA" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12137"><rect width="14" height="14" fill="white" transform="translate(7.75 8)"/></clipPath></defs></svg>',
        'title' => 'Сохраняет холод 5 часов'
    ],
    '33395' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#FF5B36"/><path d="M20.25 17.5V10.5C20.25 10.1022 20.092 9.72064 19.8107 9.43934C19.5294 9.15804 19.1478 9 18.75 9V9C18.3522 9 17.9706 9.15804 17.6893 9.43934C17.408 9.72064 17.25 10.1022 17.25 10.5V17.5C16.8302 17.8148 16.5202 18.2537 16.3637 18.7546C16.2073 19.2554 16.2124 19.7928 16.3783 20.2906C16.5442 20.7883 16.8626 21.2213 17.2882 21.5281C17.7139 21.8349 18.2253 22 18.75 22C19.2747 22 19.7861 21.8349 20.2118 21.5281C20.6374 21.2213 20.9558 20.7883 21.1217 20.2906C21.2876 19.7928 21.2927 19.2554 21.1363 18.7546C20.9798 18.2537 20.6698 17.8148 20.25 17.5Z" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M11.499 13.75C12.1894 13.75 12.749 13.1904 12.749 12.5C12.749 11.8096 12.1894 11.25 11.499 11.25C10.8087 11.25 10.249 11.8096 10.249 12.5C10.249 13.1904 10.8087 13.75 11.499 13.75Z" stroke="#FF5B36" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.7529 12.5L13.5829 13.36L13.8029 14.8L12.3629 14.58L11.5029 15.75L10.6429 14.58L9.20293 14.8L9.42293 13.36L8.25293 12.5L9.42293 11.64L9.20293 10.2L10.6429 10.42L11.5029 9.25L12.3629 10.42L13.8029 10.2L13.5829 11.64L14.7529 12.5Z" stroke="#FF5B36" stroke-width="0.7" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'title' => 'Держит тепло 9 часов'
    ],
    '33396' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#222222"/><g clip-path="url(#clip0_1711_12216)"><path d="M18.1802 15.2491C18.1811 14.7205 18.3502 14.2059 18.6631 13.7799C18.9759 13.3538 19.4162 13.0383 19.9202 12.8791C19.7249 12.5194 19.456 12.2049 19.1309 11.9562C18.8058 11.7075 18.432 11.5302 18.0337 11.4358C17.6355 11.3415 17.2218 11.3322 16.8197 11.4085C16.4176 11.4849 16.0361 11.6453 15.7002 11.8791C15.5482 11.9669 15.3757 12.0131 15.2002 12.0131C15.0247 12.0131 14.8522 11.9669 14.7002 11.8791C14.3366 11.6485 13.9288 11.4964 13.503 11.4326C13.0772 11.3688 12.6428 11.3947 12.2275 11.5086C11.8123 11.6225 11.4255 11.822 11.0919 12.0942C10.7583 12.3663 10.4852 12.7052 10.2902 13.0891C9.73313 14.115 9.54264 15.3003 9.75019 16.4491C9.91872 18.0269 10.6002 19.5059 11.6902 20.6591C12.0495 20.9999 12.5182 21.2021 13.0127 21.2296C13.5072 21.2571 13.9953 21.1081 14.3902 20.8091C14.6174 20.641 14.8926 20.5503 15.1752 20.5503C15.4578 20.5503 15.733 20.641 15.9602 20.8091C16.3391 21.1152 16.8142 21.2771 17.3012 21.2661C17.7882 21.2552 18.2555 21.072 18.6202 20.7491C19.4435 19.918 20.0303 18.8824 20.3202 17.7491C19.7195 17.6661 19.1701 17.3658 18.7758 16.9052C18.3815 16.4445 18.1696 15.8554 18.1802 15.2491Z" stroke="#222222" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.1802 9.75L17.6802 8.25" stroke="#222222" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12216"><rect width="14" height="14" fill="white" transform="translate(8 7.75)"/></clipPath></defs></svg>',
        'title' => 'Iphone 12-16'
    ],
    '33397' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#744A9E"/><g clip-path="url(#clip0_1711_12221)"><path d="M15 18.25C16.933 18.25 18.5 16.683 18.5 14.75C18.5 12.817 16.933 11.25 15 11.25C13.067 11.25 11.5 12.817 11.5 14.75C11.5 16.683 13.067 18.25 15 18.25Z" stroke="#744A9E" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 11.88V9.25C17 8.98478 16.8946 8.73043 16.7071 8.54289C16.5196 8.35536 16.2652 8.25 16 8.25H14C13.7348 8.25 13.4804 8.35536 13.2929 8.54289C13.1054 8.73043 13 8.98478 13 9.25V11.88" stroke="#744A9E" stroke-linecap="round" stroke-linejoin="round"/><path d="M17 17.6191V20.2491C17 20.5144 16.8946 20.7687 16.7071 20.9562C16.5196 21.1438 16.2652 21.2491 16 21.2491H14C13.7348 21.2491 13.4804 21.1438 13.2929 20.9562C13.1054 20.7687 13 20.5144 13 20.2491V17.6191" stroke="#744A9E" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12221"><rect width="14" height="14" fill="white" transform="translate(8 7.75)"/></clipPath></defs></svg>',
        'title' => 'Whatch'
    ],
    '33398' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#57B0EA"/><g clip-path="url(#clip0_1711_12227)"><path d="M10.3477 8.25C9.61024 8.24892 8.89826 8.51947 8.34766 9.01V13.49C8.89826 13.9805 9.61024 14.2511 10.3477 14.25C10.514 14.2652 10.6813 14.2652 10.8477 14.25V20.04C10.8477 20.3715 10.9794 20.6895 11.2138 20.9239C11.4482 21.1583 11.7661 21.29 12.0977 21.29C12.4292 21.29 12.7471 21.1583 12.9815 20.9239C13.216 20.6895 13.3477 20.3715 13.3477 20.04V11.25C13.3477 10.4544 13.0316 9.69129 12.469 9.12868C11.9064 8.56607 11.1433 8.25 10.3477 8.25V8.25Z" stroke="#57B0EA" stroke-linecap="round" stroke-linejoin="round"/><path d="M19.3477 8.25C20.0851 8.24892 20.7971 8.51947 21.3477 9.01V13.49C20.7971 13.9805 20.0851 14.2511 19.3477 14.25C19.1813 14.2652 19.014 14.2652 18.8477 14.25V20.04C18.8477 20.3715 18.716 20.6895 18.4815 20.9239C18.2471 21.1583 17.9292 21.29 17.5977 21.29C17.2661 21.29 16.9482 21.1583 16.7138 20.9239C16.4794 20.6895 16.3477 20.3715 16.3477 20.04V11.25C16.3477 10.4544 16.6637 9.69129 17.2263 9.12868C17.7889 8.56607 18.552 8.25 19.3477 8.25V8.25Z" stroke="#57B0EA" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12227"><rect width="14" height="14" fill="white" transform="translate(7.84766 7.75)"/></clipPath></defs></svg>',
        'title' => 'Earphones'
    ],
    '33399' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#4BD783"/><g clip-path="url(#clip0_1711_12232)"><path d="M8.01953 8.5V21.5H21.0195" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M15.0195 21.5C15.0195 19.6435 14.282 17.863 12.9693 16.5503C11.6565 15.2375 9.87605 14.5 8.01953 14.5" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.01953 21.5L10.5195 19" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M13.0195 16.5L14.5195 15" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.5195 13L18.0195 11.5" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/><path d="M20.0195 9.5L21.0195 8.5" stroke="#4BD783" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12232"><rect width="14" height="14" fill="white" transform="translate(7.51953 8)"/></clipPath></defs></svg>',
        'title' => 'Регулируемый угол'
    ],
    '33400' => [
        'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none"><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" fill="white"/><rect x="0.5" y="0.5" width="29" height="29" rx="14.5" stroke="#FF5B36"/><g clip-path="url(#clip0_1711_12241)"><path d="M16.8098 20.07C16.358 20.5217 15.8217 20.8801 15.2315 21.1246C14.6413 21.3691 14.0086 21.4949 13.3698 21.4949C12.0795 21.4949 10.8421 20.9823 9.92978 20.07C9.01743 19.1577 8.50488 17.9203 8.50488 16.63C8.50488 15.3397 9.01743 14.1023 9.92978 13.19L14.6198 8.5L16.8098 10.69L12.5098 15C12.2334 15.3398 12.0928 15.7701 12.1153 16.2076C12.1377 16.6451 12.3216 17.0587 12.6314 17.3684C12.9411 17.6782 13.3547 17.8621 13.7922 17.8845C14.2297 17.907 14.6599 17.7664 14.9998 17.49L19.2998 13.19L21.4998 15.38L16.8098 20.07Z" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M17.1895 15.3086L19.3795 17.4986" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/><path d="M12.5 10.6191L14.69 12.8091" stroke="#FF5B36" stroke-linecap="round" stroke-linejoin="round"/></g><defs><clipPath id="clip0_1711_12241"><rect width="14" height="14" fill="white" transform="translate(8 8)"/></clipPath></defs></svg>',
        'title' => 'Magnetic adsorption'
    ],
];
$arSkuExtended = [];

if ($arVisual['OFFERS']['USE'] && $arVisual['OFFERS']['VIEW'] === 'extended')
    foreach ($arVisual['OFFERS']['EXTENDED'] as $sKeySide => $sCode)
        if (!empty($sCode))
            foreach ($arResult['SKU_PROPS'] as $sKeySku => $arSku)
                if ($arSku['code'] === 'P_'.$sCode)
                    $arSkuExtended[$sKeySide] = $arSku;

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-catalog-tile-2'
    ],
    'data' => [
        'borders' => $arVisual['BORDERS']['USE'] ? 'true' : 'false',
        'columns-desktop' => $arVisual['COLUMNS']['DESKTOP'],
        'columns-mobile' => $arVisual['COLUMNS']['MOBILE'],
        'wide' => $arVisual['WIDE'] ? 'true' : 'false',
        'properties' => !empty($arResult['SKU_PROPS']) ? Json::encode($arResult['SKU_PROPS'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true) : '',
        'button' => $arResult['ACTION'] !== 'none' ? 'true' : 'false'
    ]
]) ?>
    <?php if ($arVisual['GIFTS']['SHOW'] && $arVisual['GIFTS']['POSITION'] === 'top') { ?>
        <?php include(__DIR__.'/parts/sale.products.gift.section.php'); ?>
    <?php } ?>
    <?php if ($arVisual['NAVIGATION']['TOP']['SHOW']) { ?>
        <div class="catalog-section-navigation catalog-section-navigation-top" data-pagination-num="<?= $arNavigation['NavNum'] ?>">
            <!-- pagination-container -->
            <?= $arResult['NAV_STRING'] ?>
            <!-- pagination-container -->
        </div>
    <?php } ?>
    <!-- items-container -->
    <?= Html::beginTag('div', [
        'class' => [
            'catalog-section-items',
            'intec-grid' => [
                '',
                'wrap',
                'a-v-stretch',
                'a-h-start'
            ]
        ],
        'data' => [
            'role' => 'items',
            'filtered' => !empty($arResult['OFFERS_FILTERED_APPLY']) ? Json::encode($arResult['OFFERS_FILTERED_APPLY'], JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true) : '',
            'entity' => $sTemplateContainer
        ]
    ]) ?>
        <?php foreach ($arResult['ITEMS'] as $arItem) {

            $sId = $sTemplateId.'_'.$arItem['ID'];
            $sAreaId = $this->GetEditAreaId($sId);
            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

            $sData = Json::encode($dData($arItem), JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true);

            $bSkuExtended = $arItem['DATA']['OFFER'] && $arVisual['OFFERS']['VIEW'] === 'extended' && !empty($arSkuExtended);

            $arSkuProps = [];

            if (!empty($arResult['SKU_PROPS']))
                $arSkuProps = $arResult['SKU_PROPS'];
            else if (!empty($arItem['SKU_PROPS']))
                $arSkuProps = $arItem['SKU_PROPS'];

        ?>
            <?= Html::beginTag('div', [
                'id' => $sAreaId,
                'class' => Html::cssClassFromArray([
                    'catalog-section-item' => true,
                    'intec-grid-item' => [
                        $arVisual['COLUMNS']['DESKTOP'] => true,
                        '500-1' => $arVisual['COLUMNS']['DESKTOP'] <= 4 && $arVisual['COLUMNS']['MOBILE'] == 1,
                        '800-2' => $arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                        '1000-3' => $arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 3,
                        '700-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                        '720-3' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                        '950-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 2,
                        '1200-3' => !$arVisual['WIDE'] && $arVisual['COLUMNS']['DESKTOP'] > 3
                    ]
                ],  true),
                'data' => [
                    'id' => $arItem['ID'],
                    'role' => 'item',
                    'products' => 'main',
                    'data' => $sData,
                    'expanded' => 'false',
                    'available' => $arItem['CAN_BUY'] ? 'true' : 'false',
                    'entity' => 'items-row',
                    'properties' => !empty($arSkuProps) ? Json::encode($arSkuProps, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_APOS, true) : '',
                ]
            ]) ?>
                <div class="catalog-section-item-wrapper" data-borders-style="<?= $arVisual['BORDERS']['STYLE'] ?>">
                    <div class="catalog-section-item-base">
                        <?php if ($arVisual['NAME']['POSITION'] == 'top') { ?>
                            <div class="catalog-section-item-name" data-align="<?= $arVisual['NAME']['ALIGN'] ?>">
                                <?= Html::tag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', $arItem['NAME'], [
                                    'class' => 'intec-cl-text-hover',
                                    'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $arItem['DETAIL_PAGE_URL'] : null,
                                    'data-role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : null
                                ]) ?>
                            </div>
                        <?php } ?>
                        <div class="catalog-section-item-image-container">
                            <?php $vImage($arItem) ?>
                            <?php if ($arResult['QUICK_VIEW']['USE'] && !$arResult['QUICK_VIEW']['DETAIL']) { ?>
                                <div class="catalog-section-item-quick-view intec-ui-align">
                                    <!--noindex-->
                                    <div class="catalog-section-item-quick-view-button" data-role="quick.view">
                                        <div class="catalog-section-item-quick-view-button-icon">
                                            <i class="intec-ui-icon intec-ui-icon-eye-1"></i>
                                        </div>
                                        <div class="catalog-section-item-quick-view-button-text">
                                            <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_2_QUICK_VIEW') ?>
                                        </div>
                                    </div>
                                    <!--/noindex-->
                                </div>
                            <?php } ?>
                            <!--noindex-->                        
                            <div class="catalog-section-item-marks marker-flex">
                                <div class="widget c-markers c-markers-template-1" data-orientation="<?= $arVisual['ORIENTATION'] ?>">
                                    <?php if (!empty($arItem['PROPERTIES']['TAGS_FIGMA']['VALUE_ENUM_ID'])) { ?>
                                        <div class="widget-markers-wrap tag-flex-markers">
                                            <?php foreach ($arItem['PROPERTIES']['TAGS_FIGMA']['VALUE_ENUM_ID'] as $enumId) { 
                                                if (isset($figmaTagsData[$enumId])) {
                                                    $tagData = $figmaTagsData[$enumId];
                                            ?>
                                                    <div class="figma-tag" title="<?= htmlspecialchars($tagData['title']) ?>">
                                                        <?= $tagData['svg'] ?>
                                                    </div>
                                            <?php 
                                                }
                                            } ?>
                                        </div>
                                    <?php } ?>
                                </div>

                                <style>
                                    .figma-tag {
                                        position: relative;
                                        display: inline-block;
                                        pointer-events: all;
                                        cursor: pointer;
                                    }

                                    .figma-tag:hover::after,
                                    .figma-tag:hover::before {
                                        opacity: 1;
                                        visibility: visible;
                                        transform: translateX(5%) translateY(70px);
                                    }

                                    .figma-tag::after {
                                        content: attr(title);
                                        position: absolute;
                                        bottom: calc(100% + 8px);
                                        left: 50%;
                                        transform: translateX(-50%) translateY(5px);
                                        background: #FFFFFF;
                                        color: #858585;
                                        padding: 6px 12px;
                                        border-radius: 6px;
                                        font-size: 13px;
                                        white-space: nowrap;
                                        opacity: 0;
                                        visibility: hidden;
                                        transition: all 0.2s ease;
                                        z-index: 1000;
                                        pointer-events: none;
                                        font-family: Arial, sans-serif;
                                        line-height: 1.3;
                                    }

                                </style>
                                <?php $APPLICATION->includeComponent(
                                    'intec.universe:main.markers',
                                    'template.3', [
                                        'HIT' => $arItem['DATA']['MARKS']['VALUES']['HIT'],
                                        'NEW' => $arItem['DATA']['MARKS']['VALUES']['NEW'],
                                        'SALE' => $arItem['DATA']['MARKS']['VALUES']['SALE'],
                                        'RECOMMEND' => $arItem['DATA']['MARKS']['VALUES']['RECOMMEND'],
                                        'ORIENTATION' => $arVisual['MARKS']['ORIENTATION']
                                    ],
                                    $component,
                                    ['HIDE_ICONS' => 'Y']
                                ) ?>
                            </div>
                            <?php if ($arItem['DATA']['DELAY']['USE'] || $arItem['DATA']['COMPARE']['USE'])
                                $vButtons($arItem);
                            ?>
                            <?php if ($bSkuExtended) {
                                $vSkuExtended($arSkuExtended);
                            } ?>
                            <!--/noindex-->
                        </div>
                        <!--noindex-->
                        <?php if ($arVisual['VOTE']['SHOW']) { ?>
                            <div class="catalog-section-item-vote" data-align="<?= $arVisual['VOTE']['ALIGN'] ?>">
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:iblock.vote',
                                    'template.1', [
                                        'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                                        'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                                        'ELEMENT_ID' => $arItem['ID'],
                                        'ELEMENT_CODE' => $arItem['CODE'],
                                        'MAX_VOTE' => '5',
                                        'VOTE_NAMES' => [
                                            0 => '1',
                                            1 => '2',
                                            2 => '3',
                                            3 => '4',
                                            4 => '5'
                                        ],
                                        'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                                        'CACHE_TIME' => $arParams['CACHE_TIME'],
                                        'DISPLAY_AS_RATING' => $arVisual['VOTE']['MODE'] === 'rating' ? 'rating' : 'vote_avg',
                                        'SHOW_RATING' => 'N'
                                    ],
                                    $component,
                                    ['HIDE_ICONS' => 'Y']
                                ) ?>
                            </div>
                        <?php } ?>

                        <?php $APPLICATION->includeComponent(
                            'intec.universe:main.markers',
                            'template.1', [
                                'HIT' => $arItem['DATA']['MARKS']['VALUES']['HIT'],
                                'NEW' => $arItem['DATA']['MARKS']['VALUES']['NEW'],
                                'SALE' => $arItem['DATA']['MARKS']['VALUES']['SALE'],
                                'RECOMMEND' => $arItem['DATA']['MARKS']['VALUES']['RECOMMEND'],
                                'ORIENTATION' => $arVisual['MARKS']['ORIENTATION'],
                                'TAGS_NAME' =>  $arItem['PROPERTIES']['TAGS_FIGMA'],
                                'TAGS_VALUE' =>  $arItem['PROPERTIES']['TAGS_FIGMA']
                            ],
                            $component,
                            ['HIDE_ICONS' => 'Y']
                        ) ?>
                        <? /* Общий блок после фотки */ ?>
                        <?php
                        // Вывод артикула по умолчанию — из первого оффера или из основного товара
                        $defaultArtikul = null;

                        if (!empty($arItem['OFFERS'])) {
                            $firstOffer = $arItem['OFFERS'][0];
                            $defaultArtikul = $firstOffer['PROPERTIES']['ARTIKUL_POSTAVSHCHIKA']['VALUE'] ?: null;
                        } else {
                            $defaultArtikul = $arItem['PROPERTIES']['CML2_ARTICLE']['VALUE'] ?? null;
                        }
                        ?>

                        <?php if ($defaultArtikul): ?>
                            <div class="catalog-section-item-quantity-wrap articule-style" id="articul-display-<?= $arItem['ID'] ?>">
                                Артикул: <?= htmlspecialchars($defaultArtikul) ?>
                            </div>
                        <?php endif; ?>

                        <!-- Скрытые данные всех артикулов по цветам -->
                        <?php if (!empty($arItem['OFFERS'])): ?>
                            <div id="all-offers-artikuls-<?= $arItem['ID'] ?>" style="display:none;">
                                <?php foreach ($arItem['OFFERS'] as $offer): 
                                    $colorId = $offer['PROPERTIES']['TSVET']['VALUE_ENUM_ID'] ?? '';
                                    if (!$colorId) continue;
                                    $artikul = $offer['PROPERTIES']['ARTIKUL_POSTAVSHCHIKA']['VALUE'] ?? '';
                                ?>
                                    <div class="offer-artikul"
                                        data-color-id="<?= htmlspecialchars($colorId) ?>"
                                        data-artikul="<?= htmlspecialchars($artikul) ?>"></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <?/*php if ($arItem['DATA']['QUANTITY']['SHOW']) { ?>
                            <div class="catalog-section-item-quantity-wrap">
                                <?php $vQuantity($arItem) ?>
                            </div>
                        <?php } */?>
                        <!--/noindex-->
                        <?php if ($arVisual['NAME']['POSITION'] == 'middle') { ?>
                            <div class="catalog-section-item-name" data-align="<?= $arVisual['NAME']['ALIGN'] ?>">
                                <?= Html::tag($arResult['QUICK_VIEW']['DETAIL'] ? 'div' : 'a', $arItem['NAME'], [
                                    'class' => [
                                        'intec-cl-text-hover',
                                        'section-item-name',
                                        'section-item-name-style-cust', 
                                    ],
                                    'href' => !$arResult['QUICK_VIEW']['DETAIL'] ? $arItem['DETAIL_PAGE_URL'] : null,
                                    'data' => [
                                        'role' => $arResult['QUICK_VIEW']['DETAIL'] ? 'quick.view' : 'offer.link',
                                        'id' => $arItem['ID'],
                                    ]
                                ]) ?>
                            </div>
                        <?php } ?>
                        <?php if ($arItem['DATA']['PRICE']['SHOW']) {
                            $arPrice = null;

                            if (!empty($arItem['ITEM_PRICES']))
                                $arPrice = ArrayHelper::getFirstValue($arItem['ITEM_PRICES']);

                            $vPrice($arPrice);
                        } ?>
                        
                        <? /* Склад: \ В пути: */ ?>
                        <?php include(__DIR__ . '/parts/store_time-puti.php'); ?>
                        <? /* */ ?>
                        
                        
                        <?/*php $codeProps = ["CML2_ARTICLE", "MATERIAL",  "APPLICATION_TYPES"];?>
                        <?php foreach ($codeProps as $prop) {
                            /*
                            echo'<pre>';
                            print_r($arItem['OFFERS']);
                            echo'</pre>';
                            */
                            
                            /*
                            echo'<pre>';
                            print_r($arItem['OFFERS'][0]['PROPERTIES']['POSTAVSHCHIK']['VALUE']);
                            echo'<br><br>';
                            print_r($arItem['OFFERS'][0]['PROPERTIES']['OSTATOK_V_PUTI']['VALUE']);
                            echo'</pre>';
                            *//*
                            if ($arItem["PROPERTIES"][$prop]["VALUE"]) {?>
                            
                                <div class="catalog-section-item-prop">
                                    <div class="catalog-section-item-prop-name">
                                        <?=$arItem["PROPERTIES"][$prop]["NAME"];?>:
                                    </div>
                                    <div class="catalog-section-item-prop-value">
                                        <?=is_array($arItem["PROPERTIES"][$prop]["VALUE"])?implode(", ",$arItem["PROPERTIES"][$prop]["VALUE"]) : $arItem["PROPERTIES"][$prop]["VALUE"];?>
                                    </div>
                                </div>
                            <?php }
                        }*/?>
                        <?php if ($arItem['DATA']['TIMER']['SHOW']) { ?>
                            <div class="catalog-section-item-timer">
                                <?php include(__DIR__ . '/parts/timer.php'); ?>
                            </div>
                        <?php } ?>
                    </div>
                    <!--noindex-->
                    <div class="catalog-section-item-advanced">
                        <?php if ($arVisual['OFFERS']['USE'] && $arItem['DATA']['OFFER'] && !empty($arSkuProps))
                            $vSku($arSkuProps);
                        ?>
                        <?php if ($arItem['ACTION'] !== 'none') { ?>
                            <div class="catalog-section-item-purchase-container intec-grid intec-grid-a-v-center">
                                <?php if ($arItem['DATA']['COUNTER']['SHOW'])
                                    $vCounter($arItem);
                                ?>
                                <div class="catalog-section-item-purchase intec-grid-item intec-grid-item-shrink-1">
                                    <div class="catalog-section-item-purchase-desktop">
                                        <?php $vPurchase($arItem) ?>
                                    </div>
                                    <div class="catalog-section-item-purchase-mobile">
                                        <?php $vPurchase($arItem, true) ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <!--/noindex-->
                </div>
            <?= Html::endTag('div') ?>
            <?php if ($arVisual['GIFTS']['SHOW'] && $arVisual['GIFTS']['POSITION'] === 'middle' && !$bGiftShowed) { ?>
                <?php if ($iItemsCount > $arVisual['COLUMNS']['DESKTOP'] && $arVisual['COLUMNS']['DESKTOP'] == $iCounter ||
                    $iItemsCount <= $arVisual['COLUMNS']['DESKTOP'] && $iItemsCount == $iCounter) { ?>
                    <?php include(__DIR__.'/parts/sale.products.gift.section.php'); ?>
                    <?php $bGiftShowed = true; ?>
                <?php } ?>
            <?php } ?>
            <?php $iCounter++ ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
    <!-- items-container -->
    <?php if ($arVisual['NAVIGATION']['LAZY']['BUTTON']) { ?>
        <div class="catalog-section-more" data-use="show-more-<?= $arNavigation['NavNum'] ?>">
            <div class="catalog-section-more-button">
                <div class="catalog-section-more-icon intec-cl-svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <path d="M16.5059 9.00153L15.0044 10.5015L13.5037 9.00153"  stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4.75562 4.758C5.84237 3.672 7.34312 3 9.00137 3C12.3171 3 15.0051 5.6865 15.0051 9.0015C15.0051 9.4575 14.9496 9.9 14.8536 10.3268" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1.4939 8.99847L2.9954 7.49847L4.49615 8.99847"  stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M13.2441 13.242C12.1574 14.328 10.6566 15 8.99838 15C5.68263 15 2.99463 12.3135 2.99463 8.99853C2.99463 8.54253 3.05013 8.10003 3.14613 7.67328" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="catalog-section-more-text intec-cl-text">
                    <?= Loc::getMessage('C_CATALOG_SECTION_CATALOG_TILE_1_LAZY_TEXT') ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($arVisual['NAVIGATION']['BOTTOM']['SHOW']) { ?>
        <div class="catalog-section-navigation catalog-section-navigation-bottom" data-pagination-num="<?= $arNavigation['NavNum'] ?>">
            <!-- pagination-container -->
            <?= $arResult['NAV_STRING'] ?>
            <!-- pagination-container -->
        </div>
    <?php } ?>
    <?php if ($arVisual['GIFTS']['SHOW'] && $arVisual['GIFTS']['POSITION'] === 'bottom') { ?>
        <?php include(__DIR__.'/parts/sale.products.gift.section.php'); ?>
    <?php } ?>
    <?php include(__DIR__.'/parts/script.php') ?>
<?= Html::endTag('div') ?>
<style>
.intec-cl-background {
    background-color: #744A9E !important;
    fill: #744A9E !important;
}
</style>