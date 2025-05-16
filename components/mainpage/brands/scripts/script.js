const scrollableBlock = document.querySelector('.brands-scrollable-block--list');
let isDragging = false;
let startX, scrollLeft;
let targetScrollLeft = scrollableBlock.scrollLeft;
let isAnimating = false;

// --- Отключение перетаскивания изображений ---
document.querySelectorAll('.brands-scrollable-block--item img').forEach(img => {
    img.setAttribute('draggable', 'false');
});

// --- Скролл колесиком ---
scrollableBlock.addEventListener('wheel', (e) => {
    e.preventDefault();
    targetScrollLeft += e.deltaY * 0.5;
    startSmoothScroll();
});

// --- Перетаскивание мышью ---
scrollableBlock.addEventListener('mousedown', (e) => {
    isDragging = true;
    startX = e.pageX - scrollableBlock.offsetLeft;
    scrollLeft = scrollableBlock.scrollLeft;
    scrollableBlock.style.cursor = 'grabbing';
    scrollableBlock.style.userSelect = 'none';
});

scrollableBlock.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    e.preventDefault();
    const x = e.pageX - scrollableBlock.offsetLeft;
    const walk = (x - startX) * 1.5;
    targetScrollLeft = scrollLeft - walk;
    startSmoothScroll();
});

document.addEventListener('mouseup', () => {
    isDragging = false;
    scrollableBlock.style.cursor = 'grab';
    scrollableBlock.style.removeProperty('user-select');
});

scrollableBlock.addEventListener('mouseleave', () => {
    isDragging = false;
    scrollableBlock.style.cursor = 'grab';
});

// --- Тач-скролл ---
scrollableBlock.addEventListener('touchstart', (e) => {
    isDragging = true;
    startX = e.touches[0].pageX - scrollableBlock.offsetLeft;
    scrollLeft = scrollableBlock.scrollLeft;
}, { passive: true });

scrollableBlock.addEventListener('touchmove', (e) => {
    if (!isDragging) return;
    e.preventDefault(); // обязательно для отключения нативного скролла
    const x = e.touches[0].pageX - scrollableBlock.offsetLeft;
    const walk = (x - startX) * 1.5;
    targetScrollLeft = scrollLeft - walk;
    startSmoothScroll();
}, { passive: false });

scrollableBlock.addEventListener('touchend', () => {
    isDragging = false;
});

// --- Плавный скролл ---
function startSmoothScroll() {
    if (isAnimating) return;
    isAnimating = true;
    smoothScroll();
}

function smoothScroll() {
    const diff = targetScrollLeft - scrollableBlock.scrollLeft;
    if (Math.abs(diff) > 0.5) {
        scrollableBlock.scrollLeft += diff * 0.1;
        requestAnimationFrame(smoothScroll);
    } else {
        isAnimating = false;
    }
}
