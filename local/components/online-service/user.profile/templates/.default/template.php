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
$isCurrentUser = $arResult['IS_CURRENT_USER'];
$canEdit = $arResult['CAN_EDIT'];

// Настраиваем хлебные крошки
$GLOBALS["OS_BREADCRUMBS"] = [
    [
        'ITEM' => "Личный кабинет",
        "LINK" => "/personal/profile/",
    ],
    [
        'ITEM' => "Компании",
        "LINK" => "/company/",
    ],
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
?>

<div class="user-profile">
    <div class="user-profile__header">
        <div class="user-profile__avatar">
            <?php if ($user['PERSONAL_PHOTO']): ?>
                <?php $photoSrc = CFile::GetPath($user['PERSONAL_PHOTO']); ?>
                <img src="<?= $photoSrc ?>" alt="<?= htmlspecialchars($fullName) ?>">
            <?php else: ?>
                <div class="user-profile__avatar-placeholder">
                    <?= mb_substr($user['NAME'] ?: $user['LOGIN'], 0, 1) ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="user-profile__info">
            <h1 class="user-profile__name"><?= htmlspecialchars($fullName) ?></h1>
            <?php if ($user['WORK_POSITION']): ?>
                <div class="user-profile__position"><?= htmlspecialchars($user['WORK_POSITION']) ?></div>
            <?php endif; ?>
            
            <div class="user-profile__contacts">
                <?php if ($user['PERSONAL_PHONE']): ?>
                    <div class="user-profile__contact">
                        <svg class="user-profile__contact-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122L9.9 11.77a.678.678 0 0 1-.684-.24L7.5 9.5a.678.678 0 0 1-.24-.684l.122-.58L5.598 6.654a.678.678 0 0 0-1.015-.063L3.276 7.952a.678.678 0 0 0-.063 1.015l1.034 1.034a.678.678 0 0 0 1.015.063l1.794-2.307a.678.678 0 0 0 .122-.58L7.5 6.5a.678.678 0 0 1 .24-.684l2.307-1.794a.678.678 0 0 0 .063-1.015L9.098.852a.678.678 0 0 0-1.015-.063L6.279 2.49a.678.678 0 0 0-.122.58l.122.58L4.387 5.957a.678.678 0 0 0-.063 1.015L5.358 8.006a.678.678 0 0 0 1.015.063l1.794-2.307a.678.678 0 0 0 .122-.58L8.39 4.5a.678.678 0 0 1 .24-.684l2.307-1.794a.678.678 0 0 0 .063-1.015L9.698.852z" fill="currentColor"/>
                        </svg>
                        <a href="tel:<?= htmlspecialchars($user['PERSONAL_PHONE']) ?>">
                            <?= htmlspecialchars($user['PERSONAL_PHONE']) ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ($user['EMAIL']): ?>
                    <div class="user-profile__contact">
                        <svg class="user-profile__contact-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383-4.708 2.825a.5.5 0 0 1-.584 0L5 5.383V14a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V5.383z" fill="currentColor"/>
                        </svg>
                        <a href="mailto:<?= htmlspecialchars($user['EMAIL']) ?>">
                            <?= htmlspecialchars($user['EMAIL']) ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ($canEdit): ?>
                <div class="user-profile__actions">
                    <button class="btn btn-outline-primary" onclick="editProfile()">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708L12.854.146zM11.5 2.207L2 11.707V14h2.293L13.793 4.5 11.5 2.207zM2 13h1v-1H2v1z" fill="currentColor"/>
                        </svg>
                        Редактировать профиль
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if (!empty($companies)): ?>
        <div class="user-profile__section">
            <h2 class="user-profile__section-title">Компании</h2>
            <div class="companies-list">
                <?php foreach ($companies as $companyData): ?>
                    <?php $company = $companyData['DATA']; ?>
                    <?php $isActive = $company['PROPERTY_OS_IS_MARKETING_AGENT_VALUE'] == 'Да'; ?>
                    
                    <div class="company-card <?= $companyData['TYPE'] === 'boss' ? 'company-card--boss' : 'company-card--employee' ?>">
                        <div class="company-card__icon">
                            <?php if ($companyData['TYPE'] === 'boss'): ?>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 2L13.09 8.26L20 9L14 14.74L15.18 22L10 18.27L4.82 22L6 14.74L0 9L6.91 8.26L10 2Z" fill="currentColor"/>
                                </svg>
                            <?php else: ?>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 5C3 3.89543 3.89543 3 5 3H9L11 5H15C16.1046 5 17 5.89543 17 7V15C17 16.1046 16.1046 17 15 17H5C3.89543 17 3 16.1046 3 15V5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                        
                        <div class="company-card__content">
                            <div class="company-card__name"><?= htmlspecialchars($company['NAME']) ?></div>
                            <div class="company-card__role">
                                <?= $companyData['TYPE'] === 'boss' ? 'Руководитель' : 'Сотрудник' ?>
                            </div>
                        </div>
                        
                        <div class="company-card__status">
                            <span class="badge badge--<?= $isActive ? 'active' : 'inactive' ?>">
                                <?= $isActive ? 'Активно' : 'На модерации' ?>
                            </span>
                        </div>
                        
                        <?php if ($isActive): ?>
                            <a href="<?= $company['DETAIL_PAGE_URL'] ?>" class="company-card__link"></a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($user['PERSONAL_CITY'] || $user['PERSONAL_STREET']): ?>
        <div class="user-profile__section">
            <h2 class="user-profile__section-title">Адрес</h2>
            <div class="user-profile__address">
                <?php if ($user['PERSONAL_STREET']): ?>
                    <div><?= htmlspecialchars($user['PERSONAL_STREET']) ?></div>
                <?php endif; ?>
                <?php if ($user['PERSONAL_CITY']): ?>
                    <div><?= htmlspecialchars($user['PERSONAL_CITY']) ?></div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Модальное окно редактирования профиля -->
<?php if ($canEdit): ?>
<div id="editProfileModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактирование профиля</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="profileEditForm">
                    <div class="form-group">
                        <label for="name">Имя</label>
                        <input type="text" class="form-control" id="name" name="NAME" value="<?= htmlspecialchars($user['NAME']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="lastName">Фамилия</label>
                        <input type="text" class="form-control" id="lastName" name="LAST_NAME" value="<?= htmlspecialchars($user['LAST_NAME']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="workPosition">Должность</label>
                        <input type="text" class="form-control" id="workPosition" name="WORK_POSITION" value="<?= htmlspecialchars($user['WORK_POSITION']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="personalPhone">Телефон</label>
                        <input type="tel" class="form-control" id="personalPhone" name="PERSONAL_PHONE" value="<?= htmlspecialchars($user['PERSONAL_PHONE']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="personalCity">Город</label>
                        <input type="text" class="form-control" id="personalCity" name="PERSONAL_CITY" value="<?= htmlspecialchars($user['PERSONAL_CITY']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="personalStreet">Адрес</label>
                        <textarea class="form-control" id="personalStreet" name="PERSONAL_STREET" rows="3"><?= htmlspecialchars($user['PERSONAL_STREET']) ?></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary" onclick="saveProfile()">Сохранить</button>
            </div>
        </div>
    </div>
</div>

<script>
function editProfile() {
    $('#editProfileModal').modal('show');
}

function saveProfile() {
    const form = document.getElementById('profileEditForm');
    const formData = new FormData(form);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    // Показываем индикатор загрузки
    const saveBtn = document.querySelector('#editProfileModal .btn-primary');
    const originalText = saveBtn.textContent;
    saveBtn.disabled = true;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2"></span>Сохранение...';
    
    // Отправляем AJAX запрос
    BX.ajax.runComponentAction('<?= $component->getName() ?>', 'updateProfile', {
        mode: 'ajax',
        data: data
    }).then(function(response) {
        if (response.data.success) {
            $('#editProfileModal').modal('hide');
            location.reload();
        } else {
            alert('Ошибка: ' + response.data.error);
        }
    }).catch(function(error) {
        alert('Ошибка сети при сохранении профиля');
        console.error(error);
    }).finally(function() {
        saveBtn.disabled = false;
        saveBtn.textContent = originalText;
    });
}
</script>
<?php endif; ?>
