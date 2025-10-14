<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

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

// Получаем данные пользователя
$user = $arResult['USER'];

$companies = $arResult['COMPANIES'];
$holdingsData = $arResult['HOLDINGS_DATA'];
$managers = $arResult['MANAGERS'];
$isCurrentUser = $arResult['IS_CURRENT_USER'];
$canEdit = $arResult['CAN_EDIT'];

// Настраиваем хлебные крошки
$GLOBALS["OS_BREADCRUMBS"] = [
    [
        'ITEM' => $user['NAME'] . ' ' . $user['LAST_NAME'],
        "LINK" => "#",
    ]
];

// Формируем полное имя
$fullName = trim($user['NAME'] . ' ' . $user['LAST_NAME']);
if (empty($fullName)) {
    $fullName = $user['LOGIN'];
}

// Подготавливаем SVG иконки
$arSvg = [
    'PHONE' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122L9.9 11.77a.678.678 0 0 1-.684-.24L7.5 9.5a.678.678 0 0 1-.24-.684l.122-.58L5.598 6.654a.678.678 0 0 0-1.015-.063L3.276 7.952a.678.678 0 0 0-.063 1.015l1.034 1.034a.678.678 0 0 0 1.015.063l1.794-2.307a.678.678 0 0 0 .122-.58L7.5 6.5a.678.678 0 0 1 .24-.684l2.307-1.794a.678.678 0 0 0 .063-1.015L9.098.852a.678.678 0 0 0-1.015-.063L6.279 2.49a.678.678 0 0 0-.122.58l.122.58L4.387 5.957a.678.678 0 0 0-.063 1.015L5.358 8.006a.678.678 0 0 0 1.015.063l1.794-2.307a.678.678 0 0 0 .122-.58L8.39 4.5a.678.678 0 0 1 .24-.684l2.307-1.794a.678.678 0 0 0 .063-1.015L9.698.852z" fill="currentColor"/></svg>',
    'EMAIL' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825a.5.5 0 0 1-.584 0L5 5.383V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5.383z" fill="currentColor"/></svg>'
];
?>

<!-- Основной контейнер с двумя колонками -->
<div class="user-profile-layout">
    <!-- Левая колонка: Компании -->
    <div class="user-profile__left">
        <?php if (!empty($companies)): ?>
        <div class="sale-personal-section-claims">
            <div class="sale-personal-section-claims-header">
                <div class="sale-personal-section-claims-title">
                    Компании
                </div>
            </div>
            <div class="sale-personal-section-claims-wrap">
                <div class="sale-personal-section-claims-items">
            <?php if (!empty($holdingsData)): ?>
                <?php foreach ($holdingsData as $holdingIndex => $companiesData): ?>
                <div class="companies-compact <?= $holdingIndex > 0 ? 'companies-compact--additional' : '' ?>">
                    <!-- Головная компания -->
                    <?php
                    $headCompanyData = $companiesData['head_company'];
                    $isMarketingAgent = $headCompanyData['PROPERTIES']['OS_IS_MARKETING_AGENT']['VALUE_XML_ID'] ?? '';
                    $isHeadOfHolding = $headCompanyData['PROPERTIES']['OS_COMPANY_IS_HEAD_OF_HOLDING']['VALUE_XML_ID'] ?? '';
                    $companyName = $headCompanyData['NAME'];
                    $detailUrl = $headCompanyData['DETAIL_PAGE_URL'];
                    ?>
                    <a href="<?= $detailUrl ?>" class="company-item company-item--head">
                        <div class="company-item__content">
                            <div class="company-item__name"><?= htmlspecialchars($companyName) ?></div>
                            <div class="company-item__badges">
                                <span class="badge badge--<?= ($isMarketingAgent == 'YES') ? 'active' : 'inactive' ?>">
                                    <?= ($isMarketingAgent == 'YES') ? 'Активно' : 'На модерации' ?>
                                </span>
                                <?php if ($isHeadOfHolding == 'Y'): ?>
                                    <span class="badge badge--head">Головная</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                    
                    <!-- Дочерние компании -->
                    <?php if (!empty($companiesData['child_companies'])): ?>
                        <?php foreach ($companiesData['child_companies'] as $childId): ?>
                            <?php
                            // Получаем данные дочерней компании
                            $rsChildCompany = CIBlockElement::GetById($childId);
                            if ($childCompanyElement = $rsChildCompany->GetNextElement()) {
                                $childCompanyFields = $childCompanyElement->GetFields();
                                $childCompanyProps = $childCompanyElement->GetProperties();
                                
                                $isMarketingAgent = $childCompanyProps['OS_IS_MARKETING_AGENT']['VALUE_XML_ID'] ?? '';
                                $companyName = $childCompanyFields['NAME'];
                                $detailUrl = $childCompanyFields['DETAIL_PAGE_URL'];
                            ?>
                                <?php if ($isMarketingAgent == 'YES'): ?>
                                    <a href="<?= $detailUrl ?>" class="company-item company-item--child">
                                        <div class="company-item__content">
                                            <div class="company-item__name"><?= htmlspecialchars($companyName) ?></div>
                                            <div class="company-item__badges">
                                                <span class="badge badge--<?= ($isMarketingAgent == 'YES') ? 'active' : 'inactive' ?>">
                                                    <?= ($isMarketingAgent == 'YES') ? 'Активно' : 'На модерации' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                <?php else: ?>
                                    <div class="company-item company-item--child">
                                        <div class="company-item__content">
                                            <div class="company-item__name"><?= htmlspecialchars($companyName) ?></div>
                                            <div class="company-item__badges">
                                                <span class="badge badge--<?= ($isMarketingAgent == 'YES') ? 'active' : 'inactive' ?>">
                                                    <?= ($isMarketingAgent == 'YES') ? 'Активно' : 'На модерации' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php } ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="companies-empty">
                    <p>Компании не найдены</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>
    </div>
    <!-- Конец левой колонки -->
    
    <!-- Правая колонка: Профиль пользователя -->
    <div class="user-profile__right">
        <div id="personal-manager--wrapper">
            <?php if (!empty($managers)): ?>
                <?php foreach ($managers as $index => $manager): ?>
                    <?php
                    // Получаем имя менеджера из элемента инфоблока
                    $managerFullName = $manager['NAME'] ?? 'Менеджер';
                    
                    // Получаем фото менеджера из PREVIEW_PICTURE
                    $managerPhoto = '/bitrix/templates/universe_s1/images/default-avatar.png'; // Фото по умолчанию
                    if (!empty($manager['PREVIEW_PICTURE'])) {
                        $arPhoto = CFile::GetFileArray($manager['PREVIEW_PICTURE']);
                        if ($arPhoto) {
                            $managerPhoto = $arPhoto['SRC'];
                        }
                    }

                    // Получаем должность из свойств
                    $managerPost = $manager['PROPERTIES']['WORK_POSITION']['VALUE'] ??
                                   $manager['PROPERTIES']['POSITION']['VALUE'] ?? 
                                   $manager['PROPERTIES']['DOLZHNOST']['VALUE'] ?? 
                                   'Менеджер';
                    
                    // Получаем контакты из свойств
                    $managerPhone = $manager['PROPERTIES']['PHONE']['VALUE'] ?? 
                                    $manager['PROPERTIES']['TELEFON']['VALUE'] ?? '';
                    $managerEmail = $manager['PROPERTIES']['EMAIL']['VALUE'] ?? '';
                    ?>
                    
                    <div class="manager-card-fields"<?= $index > 0 ? ' style="margin-top: 15px;"' : '' ?>>
                        <div class="manager-personal-info">
                            <div class="manager--avatar_field">
                                <img src="<?= htmlspecialchars($managerPhoto) ?>" alt="<?= htmlspecialchars($managerFullName) ?>">
                            </div>
                            <div class="manager--info">
                                <div class="field post">
                                    <span><?= htmlspecialchars($managerPost) ?></span>
                                </div>
                                <div class="field name">
                                    <span><?= htmlspecialchars($managerFullName) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="manager-action-links--wrapper">
                            <?php if (!empty($managerPhone)): ?>
                                <div class="phone-link link">
                                    <a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $managerPhone)) ?>">
                                        <div class="icon"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16.5643 12.7424L14.3315 10.5095C13.534 9.71209 12.1784 10.0311 11.8594 11.0678C11.6202 11.7855 10.8227 12.1842 10.105 12.0247C8.51012 11.626 6.35702 9.5526 5.9583 7.87797C5.71906 7.16024 6.19753 6.36279 6.91523 6.12359C7.95191 5.80461 8.27089 4.44895 7.47344 3.65151L5.2406 1.41866C4.60264 0.860447 3.64571 0.860447 3.08749 1.41866L1.57235 2.93381C0.0572004 4.5287 1.73184 8.75516 5.47983 12.5032C9.22782 16.2511 13.4543 18.0056 15.0492 16.4106L16.5643 14.8955C17.1226 14.2575 17.1226 13.3006 16.5643 12.7424Z" fill="#0065FF"></path>
                                            </svg>
                                        </div>
                                        <div class="data"><?= htmlspecialchars($managerPhone) ?></div>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($managerEmail)): ?>
                                <div class="email-link link">
                                    <a href="mailto:<?= htmlspecialchars($managerEmail) ?>">
                                        <div class="icon"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0)">
                                                    <path d="M11.9316 9.09224L17.9999 12.9285V5.09399L11.9316 9.09224Z" fill="#0065FF"></path>
                                                    <path d="M0 5.09399V12.9285L6.06825 9.09224L0 5.09399Z" fill="#0065FF"></path>
                                                    <path d="M16.8754 2.8125H1.12543C0.564055 2.8125 0.118555 3.231 0.0341797 3.76988L9.00043 9.67725L17.9667 3.76988C17.8823 3.231 17.4368 2.8125 16.8754 2.8125Z" fill="#0065FF"></path>
                                                    <path d="M10.9014 9.77188L9.30951 10.8204C9.21501 10.8823 9.10813 10.9126 9.00013 10.9126C8.89213 10.9126 8.78526 10.8823 8.69076 10.8204L7.09888 9.77075L0.0361328 14.2381C0.122758 14.7725 0.566008 15.1876 1.12513 15.1876H16.8751C17.4343 15.1876 17.8775 14.7725 17.9641 14.2381L10.9014 9.77188Z" fill="#0065FF"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0">
                                                        <rect width="18" height="18" fill="white"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </div>
                                        <div class="data"><?= htmlspecialchars($managerEmail) ?></div>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="manager-card-fields">
                    <p>Менеджеры не назначены</p>
                </div>
            <?php endif; ?>

            <div class="manager-card-fields">
                <div class="our-social_links">
                    <div class="title">
                        <span>Мы в сети</span>
                    </div>
                    <div class="links">
                        <a href="#" class="link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="19" viewBox="0 0 32 19" fill="none">
                                <path d="M30.3701 18.9987H26.9055C25.5953 18.9987 25.2006 17.9452 22.8519 15.6122C20.7993 13.6484 19.9324 13.4043 19.4132 13.4043C18.6951 13.4043 18.4993 13.6002 18.4993 14.5813V17.674C18.4993 18.5104 18.226 19.0002 16.0246 19.0002C13.8889 18.8578 11.8179 18.2137 9.98211 17.1211C8.14632 16.0284 6.59828 14.5184 5.46552 12.7155C2.77681 9.39175 0.905467 5.49164 0 1.32469C0 0.80925 0.197366 0.342042 1.18723 0.342042H4.64873C5.53839 0.342042 5.85873 0.735401 6.20792 1.6442C7.88856 6.5544 10.7564 10.8256 11.9209 10.8256C12.3673 10.8256 12.5616 10.6297 12.5616 9.52494V4.46704C12.4143 2.15963 11.18 1.96521 11.18 1.13027C11.1956 0.909987 11.2971 0.704427 11.463 0.557248C11.6288 0.41007 11.8459 0.332868 12.0682 0.342042H17.5094C18.2533 0.342042 18.4993 0.709779 18.4993 1.59295V8.42022C18.4993 9.1572 18.8181 9.40136 19.0428 9.40136C19.4891 9.40136 19.8322 9.1572 20.6505 8.34637C22.4039 6.22232 23.8369 3.85652 24.9045 1.32318C25.0133 1.01869 25.2197 0.757995 25.492 0.581054C25.7643 0.404114 26.0875 0.320684 26.4121 0.343549H29.8751C30.9136 0.343549 31.1337 0.858985 30.9136 1.59446C29.6537 4.39514 28.0952 7.0538 26.2648 9.52494C25.8914 10.0901 25.7426 10.384 26.2648 11.0471C26.608 11.5626 27.8225 12.5693 28.6378 13.5264C29.825 14.7019 30.8103 16.0613 31.5558 17.5504C31.8533 18.5089 31.3584 18.9987 30.3701 18.9987Z" fill="#222222"></path>
                            </svg>
                        </a>
                        <a href="#" class="link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="21" height="20" viewBox="0 0 21 20" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M6.02791 0.0596038C7.08723 0.010837 7.42499 0 10.1234 0C12.8218 0 13.1596 0.0117401 14.218 0.0596038C15.2764 0.107467 15.9989 0.276345 16.6311 0.521081C17.293 0.771237 17.8936 1.16227 18.3903 1.668C18.896 2.1638 19.2861 2.76345 19.5354 3.42631C19.781 4.05848 19.949 4.78095 19.9978 5.83756C20.0465 6.89868 20.0574 7.23644 20.0574 9.93396C20.0574 12.6315 20.0456 12.9701 19.9978 14.0295C19.9499 15.0861 19.781 15.8085 19.5354 16.4407C19.286 17.1036 18.8953 17.7042 18.3903 18.2008C17.8936 18.7066 17.293 19.0967 16.6311 19.3459C15.9989 19.5916 15.2764 19.7596 14.2198 19.8083C13.1596 19.8571 12.8218 19.8679 10.1234 19.8679C7.42499 19.8679 7.08723 19.8562 6.02791 19.8083C4.9713 19.7605 4.24883 19.5916 3.61667 19.3459C2.95377 19.0966 2.35314 18.7058 1.85655 18.2008C1.35135 17.7046 0.960279 17.1043 0.710535 16.4416C0.465798 15.8094 0.297824 15.087 0.249057 14.0304C0.20029 12.9692 0.189453 12.6315 0.189453 9.93396C0.189453 7.23644 0.201193 6.89778 0.249057 5.83936C0.296921 4.78094 0.465798 4.05848 0.710535 3.42631C0.960543 2.76357 1.35192 2.16325 1.85746 1.6671C2.35343 1.162 2.95343 0.770942 3.61577 0.521081C4.24793 0.276345 4.9713 0.10837 6.02791 0.0596038ZM14.1367 1.84772C13.0892 1.79985 12.7749 1.78992 10.1225 1.78992C7.47014 1.78992 7.15587 1.79985 6.10829 1.84772C5.13928 1.89197 4.61368 2.05362 4.26328 2.18999C3.8 2.3706 3.46856 2.58464 3.12087 2.93233C2.791 3.25273 2.53731 3.64314 2.37854 4.07473C2.24217 4.42513 2.08052 4.95073 2.03627 5.91974C1.9884 6.96732 1.97847 7.28159 1.97847 9.93396C1.97847 12.5863 1.9884 12.9006 2.03627 13.9482C2.08052 14.9172 2.24217 15.4428 2.37854 15.7932C2.53748 16.224 2.79125 16.615 3.12087 16.9356C3.44147 17.2652 3.83251 17.519 4.26328 17.6779C4.61368 17.8143 5.13928 17.976 6.10829 18.0202C7.15587 18.0681 7.46924 18.078 10.1225 18.078C12.7758 18.078 13.0892 18.0681 14.1367 18.0202C15.1057 17.976 15.6313 17.8143 15.9817 17.6779C16.445 17.4973 16.7765 17.2833 17.1241 16.9356C17.4538 16.615 17.7075 16.224 17.8665 15.7932C18.0029 15.4428 18.1645 14.9172 18.2088 13.9482C18.2566 12.9006 18.2666 12.5863 18.2666 9.93396C18.2666 7.28159 18.2566 6.96732 18.2088 5.91974C18.1645 4.95073 18.0029 4.42513 17.8665 4.07473C17.6859 3.61145 17.4718 3.28001 17.1241 2.93233C16.8037 2.60245 16.4133 2.34876 15.9817 2.18999C15.6313 2.05362 15.1057 1.89197 14.1367 1.84772ZM8.85367 12.9963C9.56229 13.2913 10.3513 13.3311 11.086 13.109C11.8207 12.8868 12.4555 12.4165 12.882 11.7783C13.3084 11.1401 13.5001 10.3736 13.4242 9.60985C13.3484 8.84605 13.0097 8.13228 12.466 7.59045C12.1195 7.24409 11.7004 6.97889 11.239 6.81393C10.7777 6.64896 10.2855 6.58835 9.79785 6.63644C9.31024 6.68454 8.83936 6.84014 8.41911 7.09206C7.99886 7.34398 7.63969 7.68595 7.36747 8.09334C7.09524 8.50072 6.91672 8.9634 6.84477 9.44807C6.77282 9.93273 6.80922 10.4273 6.95136 10.8962C7.09349 11.3651 7.33782 11.7967 7.66676 12.1598C7.9957 12.523 8.40107 12.8087 8.85367 12.9963ZM6.51287 6.32342C6.98701 5.84927 7.5499 5.47316 8.1694 5.21656C8.7889 4.95995 9.45288 4.82788 10.1234 4.82788C10.794 4.82788 11.4579 4.95995 12.0774 5.21656C12.6969 5.47316 13.2598 5.84927 13.734 6.32342C14.2081 6.79756 14.5842 7.36045 14.8408 7.97995C15.0974 8.59945 15.2295 9.26342 15.2295 9.93396C15.2295 10.6045 15.0974 11.2685 14.8408 11.888C14.5842 12.5075 14.2081 13.0704 13.734 13.5445C12.7764 14.5021 11.4776 15.04 10.1234 15.04C8.7692 15.04 7.47045 14.5021 6.51287 13.5445C5.5553 12.5869 5.01734 11.2882 5.01734 9.93396C5.01734 8.57975 5.5553 7.28099 6.51287 6.32342ZM16.3619 5.58831C16.4794 5.47747 16.5735 5.34418 16.6386 5.19634C16.7036 5.0485 16.7384 4.88911 16.7407 4.7276C16.7431 4.56609 16.713 4.40576 16.6523 4.25608C16.5915 4.1064 16.5014 3.97043 16.3872 3.85622C16.273 3.742 16.137 3.65187 15.9873 3.59114C15.8377 3.53042 15.6773 3.50034 15.5158 3.5027C15.3543 3.50505 15.1949 3.53979 15.0471 3.60485C14.8992 3.66991 14.7659 3.76397 14.6551 3.88147C14.4395 4.10998 14.3215 4.4135 14.3261 4.7276C14.3307 5.0417 14.4575 5.34165 14.6796 5.56378C14.9018 5.78591 15.2017 5.91272 15.5158 5.9173C15.8299 5.92188 16.1334 5.80386 16.3619 5.58831Z" fill="#222222"></path>
                            </svg>
                        </a>
                        <a href="#" class="link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="23" height="20" viewBox="0 0 23 20" fill="none">
                                <path d="M22.3989 2.47742L19.0863 18.7283C18.8392 19.8728 18.2052 20.1306 17.289 19.6139L12.3201 15.7737L9.88764 18.2105C9.64166 18.4694 9.39458 18.7283 8.83046 18.7283L9.21857 13.3725L18.4872 4.54645C18.8742 4.13975 18.3812 3.99196 17.8881 4.32534L6.36407 11.9324L1.39411 10.3445C0.301948 9.97563 0.301948 9.19889 1.64119 8.68335L20.9536 0.816248C21.9047 0.520673 22.7159 1.0385 22.3989 2.47742Z" fill="#222222"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Конец правой колонки -->
</div>
