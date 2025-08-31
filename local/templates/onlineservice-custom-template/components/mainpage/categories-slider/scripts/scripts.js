document.addEventListener("DOMContentLoaded", function() {
    $('#categoriesSlider').owlCarousel({
        loop: false,
        margin: 10,
        nav: true,
        items: 2,
        dots: false,
        responsive: {
            0: {
                items: 2
            },
            576: {
                items: 3
            },
            768: {
                items: 4
            },
            991: {
                items: 4
            },
            1200: {
                items: 4,
                margin: 10
            },
            1520: {
                items: 4,
                margin: 15
            }
        }
    })
})