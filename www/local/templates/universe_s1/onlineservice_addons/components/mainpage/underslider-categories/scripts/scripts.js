function updateBackgroundImages() {
    const categoryItems = document.querySelectorAll('.underslider-categories--category-item');
    const isMobile = window.innerWidth < 768;

    categoryItems.forEach(item => {
        const backgroundImage = isMobile 
            ? item.getAttribute('data-mobile_background')
            : item.getAttribute('data-desktop_background');
        
        item.style.backgroundImage = `url('${backgroundImage}')`;
    });
}

// Инициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', updateBackgroundImages);

// Обновление при изменении размера окна
window.addEventListener('resize', updateBackgroundImages);

