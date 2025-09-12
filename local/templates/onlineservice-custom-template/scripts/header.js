/*
document.addEventListener("DOMContentLoaded", function() {
    const body = document.querySelector('body');
    const menuIcon = document.getElementById('menuIcon');
    const mobileMenu = document.getElementById('mobileMenu');
    menuIcon.addEventListener('click', function () {
        this.classList.toggle('active');
        mobileMenu.classList.toggle('open');
        body.classList.toggle('scrollLock');
    });

    // Обработка hover для кнопок авторизации
    const authBtns = document.querySelectorAll('#top_header-auth_reg-btns--wrapper a.top_header-btn');
    const profileFieldsWrapper = document.getElementById('profile_fields--wrapper');
    
    authBtns.forEach(authBtn => {
        authBtn.addEventListener('mouseenter', function() {
            if (profileFieldsWrapper) {
                profileFieldsWrapper.classList.add('hover');
            }
        });
        
        authBtn.addEventListener('mouseleave', function() {
            if (profileFieldsWrapper) {
                profileFieldsWrapper.classList.remove('hover');
            }
        });
    });
})*/
