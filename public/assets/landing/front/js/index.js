
$(document).ready(function () {
    "use strict";
    $('.banner-carousel').owlCarousel({
        rtl: direction == '2' ? true : false,
        loop: false,
        margin: 25,
        items: 4,
        responsiveclass: true,
        responsive: {
            0: {
                items: 1,
            },
            450: {
                items: 2,
            },
            800: {
                items: 3,
            },
            1000: {
                items: 3,
            },
            1200: {
                items: 4,
            }
        }
    });
    $('.card-carousel').owlCarousel({
        rtl: direction == '2' ? true : false,
        loop: true,
        margin: 30,
        items: 4,
        nav: true,
        dots: false,
        autoplay: true,
        autoplayTimeout: 5000,
        responsiveclass: true,
        navText: ["<i class='fa-solid fa-arrow-left-long'></i>", "<i class='fa-solid fa-arrow-right-long'></i>"],
        responsive: {
            0: {
                items: 1,
            },
            700: {
                items: 2,
            },
            1000: {
                items: 2,
            },
            1200: {
                items: 3,
            },
        }
    });
    
    //======== theme-1 ========//
    $('.category-carousel').owlCarousel({
        rtl: direction == '2' ? true : false,
        loop: false,
        margin: 30,
        items: 4,
        nav: true,
        dots: false,
        autoplay: false,
        navText: ["<i class='fa-solid fa-arrow-left-long'></i>", "<i class='fa-solid fa-arrow-right-long'></i>"],
        responsive: {
            0: {
                items: 1,
            },
            700: {
                items: 3,
            },
            1000: {
                items: 3,
            },
            1200: {
                items: 5,
            },
        }
    });

    //====== theme-5 ======//
    $('#category-carousel').owlCarousel({
        rtl: direction == '2' ? true : false,
        loop: false,
        margin: 30,
        items: 4,
        nav: true,
        dots: false,
        autoplay: false,
        navText: ["<i class='fa-solid fa-arrow-left-long fs-5'></i>", "<i class='fa-solid fa-arrow-right-long fs-5'></i>"],
        responsive: {
            0: {
                items: 1,
            },
            700: {
                items: 3,
            },
            1000: {
                items: 3,
            },
            1200: {
                items: 5,
            },
        }
    });
    $('.slider-1').owlCarousel({
        rtl: direction == '2' ? true : false,
        loop: false,
        margin: 10,
        nav: false,
        ltr: false,
        responsive: {
            0: {
                items: 1
            },
            700: {
                items: 2
            },
            992: {
                items: 3
            },
            1200: {
                items: 3
            }
        }
    })
    $('.slider-2').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        dots: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    })
});

// theme-2-category-section //
$('.theme-2-category-carousel').owlCarousel({
    rtl: direction == '2' ? true : false,
    loop: false,
    margin: 25,
    items: 4,
    nav: false,
    dots: false,
    responsiveclass: true,
    ltr: false,
    responsive: {
        0: {
            items: 1,
        },
        700: {
            items: 2,
        },
        1000: {
            items: 3,
        },
        1200: {
            items: 5,
        },
    }
});
// theme-3-category-section // // theme-4-category-section //
$('.theme-3-category-carousel, .theme-4-category-carousel').owlCarousel({
    rtl: direction == '2' ? true : false,
    loop: false,
    margin: 10,
    items: 4,
    nav: false,
    dots: false,
    responsiveclass: true,
    ltr: false,
    responsive: {
        0: {
            items: 2,
        },
        768: {
            items: 4,
        },
        992: {
            items: 4,
        },
        1000: {
            items: 6,
        },
        1200: {
            items: 7,
        },
    }
});
//************ new chang in all theme ************//
//=== new chang theme-4-slider-1 ===//
$('.new-theme-4-slider-1').owlCarousel({
    rtl: direction == '2' ? true : false,
    loop: false,
    margin: 10,
    nav: false,
    dots: false,
    ltr: false,
    responsive: {
        0: {
            items: 2
        },
        700: {
            items: 3
        },
        992: {
            items: 4
        },
        1200: {
            items: 4
        }
    }
})
$('#testimonial').owlCarousel({
    rtl: direction == '2' ? true : false,
    loop: true,
    margin: 10,
    nav: false,
    dots: true,
    autoplay: true,
    autoplayTimeout: 5000,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 1
        },
        1000: {
            items: 1
        }
    }
})

//======= theme-2 =======//
$('#testimonial2').owlCarousel({
    loop: true,
    rtl: direction == '2' ? true : false,
    margin: 30,
    nav: false,
    dots: true,
    autoplay: true,
    autoplayTimeout: 5000,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 2
        },
        1000: {
            items: 4
        },
        2000: {
            items: 4
        }
    }
})

//======= theme-3 =======//
$('#testimonial3').owlCarousel({
    rtl: direction == '2' ? true : false,
    loop: true,
    margin: 10,
    nav: false,
    dots: true,
    autoplay: true,
    autoplayTimeout: 5000,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 1
        },
        1000: {
            items: 2
        },
        2000: {
            items: 1
        }
    }
})

//======= theme-4 =======//
$('#testimonial4').owlCarousel({
    rtl: direction == '2' ? true : false,
    loop: true,
    margin: 0,
    nav: false,
    dots: true,
    autoplay: true,
    autoplayTimeout: 5000,
    navText: ["<i class='fa-solid fa-arrow-left-long'></i>", "<i class='fa-solid fa-arrow-right-long'></i>"],
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 1
        },
        1000: {
            items: 3
        },
        2000: {
            items: 3
        }
    }
})

$('#banner-2').owlCarousel({
    rtl: direction == '2' ? true : false,
    loop: true,
    margin: 10,
    nav: false,
    dots: true,
    autoplay: true,
    autoplayTimeout: 5000,
    responsive: {
        0: {
            items: 1
        },
        600: {
            items: 1
        },
        2000: {
            items: 1
        }
    }
})

//=========== back top button js ===========//

var btn = $('.back-top , .back-top-rtl');

$(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
        $('#backtop').addClass('back-top-show');
    } else {
        $('#backtop').removeClass('back-top-show');
    }
});

btn.on('click', function (e) {
    e.preventDefault();
    $('html, body').animate({ scrollTop: 0 }, '300');
});



$('.cat3').owlCarousel({
    loop: true,
    margin: 10,
    nav: false,
    dots: false,
    responsive: {
        0: {
            items: 1
        },
        319: {
            items: 2
        },
        600: {
            items: 3
        },
        1000: {
            items: 5
        }
    }
})