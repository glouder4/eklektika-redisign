document.addEventListener("DOMContentLoaded", function() {
    const body = document.querySelector('body');
    const menuIcon = document.getElementById('menuIcon');
    const mobileMenu = document.getElementById('mobileMenu');
    menuIcon.addEventListener('click', function () {
        this.classList.toggle('active');
        mobileMenu.classList.toggle('open');
        body.classList.toggle('scrollLock');
    });


    // Обработка hover для кнопок авторизации и профиля
    const authBtns = document.querySelectorAll('#top_header-auth_reg-btns--wrapper a.top_header-btn');
    const profileBtns = document.querySelectorAll('#profileBtns a.header__icon.header__icon--user');
    const profileFieldsWrapper = document.getElementById('profile_fields--wrapper');

    // Функция для очистки всех hover классов
    function clearHoverClasses() {
        if (profileFieldsWrapper) {
            profileFieldsWrapper.classList.remove('hover', 'profile-hover');
        }
    }

    // Обработка hover для кнопок авторизации
    authBtns.forEach(authBtn => {
        authBtn.addEventListener('mouseenter', function() {
            clearHoverClasses();
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

    // Обработка hover для кнопок профиля
    profileBtns.forEach(profileBtn => {
        profileBtn.addEventListener('mouseenter', function() {
            clearHoverClasses();
            if (profileFieldsWrapper) {
                profileFieldsWrapper.classList.add('profile-hover');
            }
        });

        /*profileBtn.addEventListener('mouseleave', function() {
            if (profileFieldsWrapper) {
                profileFieldsWrapper.classList.remove('profile-hover');
            }
        });*/
    });
})