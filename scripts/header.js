const menuIcon = document.getElementById('menuIcon');
const mobileMenu = document.getElementById('mobileMenu');
menuIcon.addEventListener('click', function() {
    this.classList.toggle('active');
    mobileMenu.classList.toggle('open');
});