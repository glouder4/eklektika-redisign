/**
 * Класс для управления редактированием профиля пользователя
 */
class UserProfileEdit {
    constructor(options) {
        this.componentPath = options.componentPath;
        this.ajaxPath = options.ajaxPath || options.componentPath + '/ajax.php';
        this.userId = options.userId;
        this.type = options.type;
        this.sessid = options.sessid;
        
        console.log('AJAX путь:', this.ajaxPath);
        
        this.init();
    }
    
    init() {
        if (this.type === 'profile') {
            this.initProfileForm();
        } else if (this.type === 'companies') {
            this.initCompaniesManagement();
        }
    }
    
    /**
     * Инициализация формы редактирования профиля
     */
    initProfileForm() {
        console.log('initProfileForm вызван');
        
        const form = document.getElementById('user-profile-form');
        if (!form) {
            console.error('Форма user-profile-form не найдена!');
            return;
        }
        
        console.log('Форма найдена:', form);
        
        // Обработка загрузки фото
        const photoUpload = document.getElementById('photo-upload');
        console.log('Input фото:', photoUpload);
        
        if (photoUpload) {
            photoUpload.addEventListener('change', (e) => this.handlePhotoUpload(e));
            console.log('Обработчик change добавлен на input');
        }
        
        // Явный обработчик клика на кнопку
        const uploadBtn = document.querySelector('.btn-upload');
        console.log('Кнопка загрузки:', uploadBtn);
        
        if (uploadBtn && photoUpload) {
            uploadBtn.addEventListener('click', (e) => {
                console.log('Клик по кнопке загрузки!');
                // Не используем e.preventDefault() - может блокировать file dialog
                photoUpload.click();
                console.log('photoUpload.click() вызван');
            }, false);
            console.log('Обработчик клика добавлен на кнопку');
        } else {
            console.error('Кнопка или input не найдены!', {uploadBtn, photoUpload});
        }
        
        // Обработка удаления фото
        const deletePhotoBtn = document.getElementById('delete-photo');
        if (deletePhotoBtn) {
            deletePhotoBtn.addEventListener('click', () => this.handlePhotoDelete());
            console.log('Обработчик удаления фото добавлен');
        }
        
        // Маски для телефонов
        this.initPhoneMasks();
        
        // Обработка отправки формы
        form.addEventListener('submit', (e) => this.handleProfileSubmit(e));
        console.log('Обработчик submit добавлен');
    }
    
    /**
     * Инициализация управления компаниями
     */
    initCompaniesManagement() {
        // Обработка отвязки от компании
        const detachButtons = document.querySelectorAll('.btn-detach');
        detachButtons.forEach(btn => {
            btn.addEventListener('click', (e) => this.handleDetach(e));
        });
        
        // Обработка смены роли
        const changeRoleButtons = document.querySelectorAll('.btn-change-role');
        changeRoleButtons.forEach(btn => {
            btn.addEventListener('click', (e) => this.handleChangeRole(e));
        });
        
        // Обработка добавления в компанию
        const attachBtn = document.getElementById('btn-attach-company');
        if (attachBtn) {
            attachBtn.addEventListener('click', () => this.handleAttach());
        }
        
        // Модальное окно
        this.initModal();
    }
    
    /**
     * Инициализация масок для телефонов
     */
    initPhoneMasks() {
        const phoneInputs = document.querySelectorAll('.phone-mask');
        phoneInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                let formatted = '';
                
                if (value.length > 0) {
                    if (value[0] === '8') value = '7' + value.substring(1);
                    if (value[0] !== '7') value = '7' + value;
                    
                    formatted = '+7';
                    if (value.length > 1) {
                        formatted += ' (' + value.substring(1, 4);
                    }
                    if (value.length >= 5) {
                        formatted += ') ' + value.substring(4, 7);
                    }
                    if (value.length >= 8) {
                        formatted += '-' + value.substring(7, 9);
                    }
                    if (value.length >= 10) {
                        formatted += '-' + value.substring(9, 11);
                    }
                }
                
                e.target.value = formatted;
            });
        });
    }
    
    /**
     * Обработка загрузки фото
     */
    handlePhotoUpload(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // Проверка типа файла
        if (!file.type.match('image.*')) {
            this.showNotification('Пожалуйста, выберите изображение', 'error');
            return;
        }
        
        // Проверка размера (макс 5MB)
        if (file.size > 5 * 1024 * 1024) {
            this.showNotification('Размер файла не должен превышать 5MB', 'error');
            return;
        }
        
        // Предпросмотр
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('preview-photo');
            if (preview) {
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Фото';
                    img.id = 'preview-photo';
                    preview.parentNode.replaceChild(img, preview);
                }
            }
        };
        reader.readAsDataURL(file);
    }
    
    /**
     * Обработка удаления фото
     */
    handlePhotoDelete() {
        if (!confirm('Вы уверены, что хотите удалить фото?')) {
            return;
        }
        
        // Очищаем поле загрузки
        const photoUpload = document.getElementById('photo-upload');
        if (photoUpload) {
            photoUpload.value = '';
        }
        
        // Показываем placeholder
        const preview = document.getElementById('preview-photo');
        if (preview && preview.tagName === 'IMG') {
            // Получаем инициалы из формы
            const name = document.getElementById('name')?.value || '';
            const lastName = document.getElementById('last-name')?.value || '';
            const initials = (name.charAt(0) + lastName.charAt(0)).toUpperCase();
            
            const placeholder = document.createElement('div');
            placeholder.className = 'avatar-placeholder';
            placeholder.id = 'preview-photo';
            placeholder.innerHTML = `<span>${initials}</span>`;
            preview.parentNode.replaceChild(placeholder, preview);
        }
        
        this.showNotification('Фото будет удалено после сохранения', 'info');
    }
    
    /**
     * Обработка отправки формы профиля
     */
    async handleProfileSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('.btn-save');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoader = submitBtn.querySelector('.btn-loader');
        
        // Показываем лоадер
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoader.style.display = 'block';
        
        try {
            const formData = new FormData(form);
            const fields = {};
            
            // Собираем данные формы, исключая файл (он обрабатывается отдельно)
            for (let [key, value] of formData.entries()) {
                if (key !== 'USER_ID' && key !== 'sessid' && key !== 'PERSONAL_PHOTO') {
                    fields[key] = value;
                }
            }
            
            // Проверяем, есть ли новое фото
            const photoInput = form.querySelector('#photo-upload');
            if (photoInput && photoInput.files.length > 0) {
                // Если есть файл, используем FormData для отправки
                formData.append('action', 'saveProfile');
                formData.append('userId', this.userId);
                formData.append('sessid', this.sessid);
                formData.append('fields', JSON.stringify(fields));
                
                const response = await fetch(this.ajaxPath, {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.data.success) {
                    this.showNotification(result.data.message || 'Профиль успешно обновлен', 'success');
                    setTimeout(() => {
                        window.location.href = '/company/user/' + this.userId+'/';
                    }, 1500);
                } else {
                    this.showNotification(result.data.error || 'Ошибка при сохранении', 'error');
                    submitBtn.disabled = false;
                    btnText.style.display = 'block';
                    btnLoader.style.display = 'none';
                }
            } else {
                // Если файла нет, используем JSON как обычно
                const response = await this.callAjaxAction('saveProfile', {
                    userId: this.userId,
                    fields: fields
                });
                
                if (response.data.success) {
                    this.showNotification(response.data.message || 'Профиль успешно обновлен', 'success');
                    setTimeout(() => {
                        window.location.href = '/company/user/' + this.userId+'/';
                    }, 1500);
                } else {
                    this.showNotification(response.data.error || 'Ошибка при сохранении', 'error');
                    submitBtn.disabled = false;
                    btnText.style.display = 'block';
                    btnLoader.style.display = 'none';
                }
            }
        } catch (error) {
            console.error('Ошибка:', error);
            this.showNotification('Произошла ошибка при сохранении', 'error');
            submitBtn.disabled = false;
            btnText.style.display = 'block';
            btnLoader.style.display = 'none';
        }
    }
    
    /**
     * Обработка отвязки от компании
     */
    handleDetach(e) {
        const companyId = e.target.dataset.companyId;
        const row = e.target.closest('tr');
        const companyName = row.querySelector('.company-name a').textContent;
        
        this.showConfirmModal(
            `Вы уверены, что хотите отвязать пользователя от компании "${companyName}"?`,
            async () => {
                try {
                    const response = await this.callAjaxAction('detachFromCompany', {
                        userId: this.userId,
                        companyId: companyId
                    });
                    
                    if (response.data.success) {
                        this.showNotification(response.data.message || 'Пользователь отвязан от компании', 'success');
                        row.remove();
                        
                        // Проверяем, остались ли компании
                        const tbody = document.getElementById('companies-list');
                        if (tbody && tbody.children.length === 0) {
                            location.reload();
                        }
                    } else {
                        this.showNotification(response.data.error || 'Ошибка при отвязке', 'error');
                    }
                } catch (error) {
                    this.showNotification('Произошла ошибка', 'error');
                }
            }
        );
    }
    
    /**
     * Обработка смены роли
     */
    handleChangeRole(e) {
        const companyId = e.target.dataset.companyId;
        const newRole = e.target.dataset.newRole;
        const row = e.target.closest('tr');
        const companyName = row.querySelector('.company-name a').textContent;
        
        const roleText = newRole === 'boss' ? 'руководителем' : 'сотрудником';
        
        this.showConfirmModal(
            `Назначить пользователя ${roleText} компании "${companyName}"?`,
            async () => {
                try {
                    const response = await this.callAjaxAction('changeRole', {
                        userId: this.userId,
                        companyId: companyId,
                        newRole: newRole
                    });
                    
                    if (response.data.success) {
                        this.showNotification(response.data.message || 'Роль успешно изменена', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        this.showNotification(response.data.error || 'Ошибка при смене роли', 'error');
                    }
                } catch (error) {
                    this.showNotification('Произошла ошибка', 'error');
                }
            }
        );
    }
    
    /**
     * Обработка добавления в компанию
     */
    async handleAttach() {
        const companySelect = document.getElementById('company-select');
        const roleSelect = document.getElementById('role-select');
        
        const companyId = companySelect.value;
        const role = roleSelect.value;
        
        if (!companyId) {
            this.showNotification('Выберите компанию', 'error');
            return;
        }
        
        try {
            const response = await this.callAjaxAction('attachToCompany', {
                userId: this.userId,
                companyId: companyId,
                role: role
            });
            
            if (response.data.success) {
                this.showNotification(response.data.message || 'Пользователь добавлен в компанию', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                this.showNotification(response.data.error || 'Ошибка при добавлении', 'error');
            }
        } catch (error) {
            this.showNotification('Произошла ошибка', 'error');
        }
    }
    
    /**
     * Вызов AJAX действия компонента
     */
    async callAjaxAction(action, data) {
        const url = this.ajaxPath;
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: action,
                data: data,
                sessid: this.sessid
            })
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        return await response.json();
    }
    
    /**
     * Показ уведомления
     */
    showNotification(message, type = 'info') {
        const container = document.getElementById('profile-notifications') || 
                         document.getElementById('companies-notifications');
        
        if (!container) return;
        
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <span>${message}</span>
            <button class="notification-close">&times;</button>
        `;
        
        container.appendChild(notification);
        
        // Закрытие по клику
        const closeBtn = notification.querySelector('.notification-close');
        closeBtn.addEventListener('click', () => {
            notification.remove();
        });
        
        // Автоматическое закрытие через 5 секунд
        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
    
    /**
     * Инициализация модального окна
     */
    initModal() {
        const modal = document.getElementById('confirm-modal');
        if (!modal) return;
        
        const closeBtn = modal.querySelector('.modal-close');
        const cancelBtn = modal.querySelector('.btn-cancel-modal');
        
        if (closeBtn) {
            closeBtn.addEventListener('click', () => this.hideModal());
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => this.hideModal());
        }
        
        // Закрытие по клику вне модального окна
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.hideModal();
            }
        });
    }
    
    /**
     * Показ модального окна подтверждения
     */
    showConfirmModal(message, onConfirm) {
        const modal = document.getElementById('confirm-modal');
        if (!modal) return;
        
        const messageEl = document.getElementById('confirm-message');
        if (messageEl) {
            messageEl.textContent = message;
        }
        
        const confirmBtn = modal.querySelector('.btn-confirm');
        if (confirmBtn) {
            // Удаляем старые обработчики
            const newBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
            
            // Добавляем новый обработчик
            newBtn.addEventListener('click', () => {
                this.hideModal();
                onConfirm();
            });
        }
        
        modal.style.display = 'flex';
    }
    
    /**
     * Скрытие модального окна
     */
    hideModal() {
        const modal = document.getElementById('confirm-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    }
}

