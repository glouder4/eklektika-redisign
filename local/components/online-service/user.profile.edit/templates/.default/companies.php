<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */

$user = $arResult['USER'];
$companies = $arResult['COMPANIES'];
$allCompanies = $arResult['ALL_COMPANIES'];
$accessLevel = $arResult['EDIT_ACCESS_LEVEL'];
?>

<div class="sale-personal-section-claims user-companies-edit">
    <div class="sale-personal-section-claims-header">
        <div class="sale-personal-section-claims-title">
            Управление компаниями
        </div>
        <a href="/company/user/?id=<?= $user['ID'] ?>" class="btn-cancel">
            Назад к профилю
        </a>
    </div>
    
    <div class="sale-personal-section-claims-wrap">
        
        <!-- Текущие компании -->
        <div class="companies-list-section">
            <h3 class="section-title">Компании пользователя</h3>
            
            <?php if (!empty($companies)): ?>
                <div class="companies-table-wrapper">
                    <table class="companies-table">
                        <thead>
                            <tr>
                                <th>Название компании</th>
                                <th>Роль</th>
                                <th>Статус</th>
                                <th class="actions-column">Действия</th>
                            </tr>
                        </thead>
                        <tbody id="companies-list">
                            <?php foreach ($companies as $company): ?>
                                <?php
                                $isMarketingAgent = $company['PROPERTIES']['OS_IS_MARKETING_AGENT']['VALUE_XML_ID'] ?? '';
                                $isHeadOfHolding = $company['PROPERTIES']['OS_COMPANY_IS_HEAD_OF_HOLDING']['VALUE_XML_ID'] ?? '';
                                ?>
                                <tr data-company-id="<?= $company['ID'] ?>" data-role="<?= $company['ROLE'] ?>">
                                    <td class="company-name">
                                        <a href="<?= $company['DETAIL_PAGE_URL'] ?>" target="_blank">
                                            <?= htmlspecialchars($company['NAME']) ?>
                                        </a>
                                        <?php if ($isHeadOfHolding == 'Y'): ?>
                                            <span class="badge badge--head">Головная</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="company-role">
                                        <span class="role-badge role-<?= $company['ROLE'] ?>">
                                            <?= htmlspecialchars($company['ROLE_NAME']) ?>
                                        </span>
                                    </td>
                                    <td class="company-status">
                                        <span class="badge badge--<?= ($isMarketingAgent == 'YES') ? 'active' : 'inactive' ?>">
                                            <?= ($isMarketingAgent == 'YES') ? 'Активно' : 'На модерации' ?>
                                        </span>
                                    </td>
                                    <td class="actions-column">
                                        <div class="action-buttons">
                                            <?php if ($accessLevel === 'admin' && $company['ROLE'] === 'employee'): ?>
                                                <button type="button" 
                                                        class="btn-action btn-change-role" 
                                                        data-company-id="<?= $company['ID'] ?>"
                                                        data-new-role="boss"
                                                        title="Назначить руководителем">
                                                    ↑ Руководитель
                                                </button>
                                            <?php endif; ?>
                                            
                                            <?php if ($accessLevel === 'admin' && $company['ROLE'] === 'boss'): ?>
                                                <button type="button" 
                                                        class="btn-action btn-change-role" 
                                                        data-company-id="<?= $company['ID'] ?>"
                                                        data-new-role="employee"
                                                        title="Понизить до сотрудника">
                                                    ↓ Сотрудник
                                                </button>
                                            <?php endif; ?>
                                            
                                            <button type="button" 
                                                    class="btn-action btn-danger btn-detach" 
                                                    data-company-id="<?= $company['ID'] ?>"
                                                    title="Отвязать от компании">
                                                Отвязать
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-companies">
                    <p>Пользователь не привязан ни к одной компании</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Добавление в компанию -->
        <?php if (!empty($allCompanies)): ?>
        <div class="add-company-section">
            <h3 class="section-title">Добавить в компанию</h3>
            
            <div class="add-company-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="company-select">Выберите компанию</label>
                        <select id="company-select" class="form-control">
                            <option value="">-- Выберите компанию --</option>
                            <?php foreach ($allCompanies as $company): ?>
                                <?php
                                // Проверяем, не привязан ли уже к этой компании
                                $isAlreadyAttached = false;
                                foreach ($companies as $userCompany) {
                                    if ($userCompany['ID'] == $company['ID']) {
                                        $isAlreadyAttached = true;
                                        break;
                                    }
                                }
                                
                                if (!$isAlreadyAttached):
                                ?>
                                    <option value="<?= $company['ID'] ?>">
                                        <?= htmlspecialchars($company['NAME']) ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="role-select">Роль</label>
                        <select id="role-select" class="form-control">
                            <option value="employee">Сотрудник</option>
                            <?php if ($accessLevel === 'admin'): ?>
                                <option value="boss">Руководитель</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" id="btn-attach-company" class="btn-add">
                            Добавить
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Уведомления -->
<div id="companies-notifications" class="notifications-container"></div>

<!-- Модальное окно подтверждения -->
<div id="confirm-modal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Подтверждение</h3>
            <button type="button" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <p id="confirm-message"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-confirm">Подтвердить</button>
            <button type="button" class="btn-cancel-modal">Отмена</button>
        </div>
    </div>
</div>

