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