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
                items: 5
            },
            1200: {
                items: 5,
                margin: 10
            },
            1520: {
                items: 5,
                margin: 15
            }
        }
    })
})