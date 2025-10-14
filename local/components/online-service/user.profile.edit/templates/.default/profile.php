<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */
/** @var array $user */

$user = $arResult['USER'];
$accessLevel = $arResult['EDIT_ACCESS_LEVEL'];

// Получаем фото пользователя
$userPhoto = '';
if (!empty($user['PERSONAL_PHOTO'])) {
    $arPhoto = CFile::GetFileArray($user['PERSONAL_PHOTO']);
    if ($arPhoto) {
        $userPhoto = $arPhoto['SRC'];
    }
}

// Получаем инициалы для placeholder аватара
$initials = '';
if (!empty($user['NAME'])) {
    $initials .= mb_substr($user['NAME'], 0, 1);
}
if (!empty($user['LAST_NAME'])) {
    $initials .= mb_substr($user['LAST_NAME'], 0, 1);
}
$initials = mb_strtoupper($initials);
?>

<div class="sale-personal-section-claims user-profile-edit-form">
    
    <div class="sale-personal-section-claims-wrap">
        <form id="user-profile-form" class="profile-edit-form">
            <input type="hidden" name="USER_ID" value="<?= $user['ID'] ?>">
            <input type="hidden" name="sessid" value="<?= bitrix_sessid() ?>">
            
            <!-- Блок с фото -->
            <div class="form-section photo-section">
                <h3 class="form-section-title">Фотография</h3>
                <div class="photo-upload-wrapper">
                    <div class="current-photo">
                        <?php if (!empty($userPhoto)): ?>
                            <img src="<?= htmlspecialchars($userPhoto) ?>" alt="Фото" id="preview-photo">
                        <?php else: ?>
                            <div class="avatar-placeholder" id="preview-photo">
                                <span><?= htmlspecialchars($initials) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="photo-controls">
                        <button type="button" class="btn-upload">
                            Загрузить фото
                        </button>
                        <input type="file" id="photo-upload" name="PERSONAL_PHOTO" accept="image/*" class="photo-input-hidden">
                        <?php if (!empty($userPhoto)): ?>
                            <button type="button" class="btn-delete-photo" id="delete-photo">Удалить фото</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Личная информация -->
            <div class="form-section">
                <h3 class="form-section-title">Личная информация</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Имя <span class="required">*</span></label>
                        <input type="text" 
                               id="name" 
                               name="NAME" 
                               value="<?= htmlspecialchars($user['NAME'] ?? '') ?>" 
                               required
                               class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="last-name">Фамилия <span class="required">*</span></label>
                        <input type="text" 
                               id="last-name" 
                               name="LAST_NAME" 
                               value="<?= htmlspecialchars($user['LAST_NAME'] ?? '') ?>" 
                               required
                               class="form-control">
                    </div>
                </div>
            </div>

            <!-- Рабочая информация -->
            <div class="form-section">
                <h3 class="form-section-title">Рабочая информация</h3>
                
                <div class="form-group">
                    <label for="work-position">Должность</label>
                    <input type="text" 
                           id="work-position" 
                           name="WORK_POSITION" 
                           value="<?= htmlspecialchars($user['WORK_POSITION'] ?? '') ?>"
                           class="form-control">
                </div>
            </div>

            <!-- Контактная информация -->
            <div class="form-section">
                <h3 class="form-section-title">Контакты</h3>
                
                <div class="form-group">
                    <label for="personal-mobile">Мобильный телефон</label>
                    <input type="tel" 
                           id="personal-mobile" 
                           name="PERSONAL_MOBILE" 
                           value="<?= htmlspecialchars($user['PERSONAL_MOBILE'] ?? '') ?>"
                           class="form-control phone-mask"
                           placeholder="+7 (___) ___-__-__">
                </div>
                
                <div class="form-group">
                    <label for="work-phone">Рабочий телефон</label>
                    <input type="tel" 
                           id="work-phone" 
                           name="WORK_PHONE" 
                           value="<?= htmlspecialchars($user['WORK_PHONE'] ?? '') ?>"
                           class="form-control phone-mask"
                           placeholder="+7 (___) ___-__-__">
                </div>
                
                <div class="form-group">
                    <label for="personal-phone">Личный телефон</label>
                    <input type="tel" 
                           id="personal-phone" 
                           name="PERSONAL_PHONE" 
                           value="<?= htmlspecialchars($user['PERSONAL_PHONE'] ?? '') ?>"
                           class="form-control phone-mask"
                           placeholder="+7 (___) ___-__-__">
                </div>
                
                <?php if ($accessLevel !== 'boss'): ?>
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" 
                           id="email" 
                           name="EMAIL" 
                           value="<?= htmlspecialchars($user['EMAIL'] ?? '') ?>" 
                           required
                           class="form-control">
                    <?php if ($accessLevel === 'boss'): ?>
                        <small class="form-text">Руководитель не может изменять email сотрудника</small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Кнопки действий -->
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <span class="btn-text">Сохранить изменения</span>
                    <span class="btn-loader" style="display: none;">
                        <svg class="spinner" viewBox="0 0 50 50">
                            <circle class="path" cx="25" cy="25" r="20" fill="none" stroke-width="5"></circle>
                        </svg>
                    </span>
                </button>
                <a href="/company/user/?id=<?= $user['ID'] ?>" class="btn-cancel-action">Отмена</a>
            </div>
        </form>
    </div>
</div>

<!-- Уведомления -->
<div id="profile-notifications" class="notifications-container"></div>

