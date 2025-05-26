
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.contacts--accordion-section-item').forEach(item => {
        item.addEventListener('click', () => {
            const isActive = item.classList.contains('active');

            // Закрываем все открытые элементы
            document.querySelectorAll('.contacts--accordion-section-item').forEach(el => {
                el.classList.remove('active');
            });

            // Открываем текущий, если он был закрыт
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });

    $('[data-action="onlineservice-action.forms.call.open"]').click(function(e){
        e.preventDefault();

        $('.header__callback-btn[data-action="forms.call.open"]').trigger('click');
    });

    $('.contacts--map_data--item--action[href="#feedback__form--form"]').click(function(){
        let _this = $(this).attr('href');
        $('body,html').animate({scrollTop: $(_this).offset().top - 150 }, 800);
    })
});