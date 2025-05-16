$('#categoriesSlider').owlCarousel({
    loop:false,
    margin: 10,
    nav:true,
    items:2,
    dots: false,
    responsive:{
        0:{
            items:2
        },
        576:{
            items:3
        },
        1200:{
            items:5,
            margin: 10,
        },
        1520:{
            items:5,
            margin: 15,
        }
    }
})