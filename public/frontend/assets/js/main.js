(function ($) {
    ("use strict");
    // Page loading
    $(window).on("load", function () {
        $("#preloader-active").delay(450).fadeOut("slow");
        $("body").delay(450).css({
            overflow: "visible"
        });
        $("#onloadModal").modal("show");
    });
    /*-----------------
        Menu Stick
    -----------------*/
    var header = $(".sticky-bar");
    var win = $(window);
    win.on("scroll", function () {
        var scroll = win.scrollTop();
        if (scroll < 200) {
            header.removeClass("stick");
            $(".header-style-2 .categories-dropdown-active-large").removeClass("open");
            $(".header-style-2 .categories-button-active").removeClass("open");
        } else {
            header.addClass("stick");
        }
    });

    /*------ ScrollUp -------- */
    $.scrollUp({
        scrollText: '<i class="fi-rs-arrow-small-up"></i>',
        easingType: "linear",
        scrollSpeed: 900,
        animation: "fade"
    });

    /*------ Wow Active ----*/
    new WOW().init();

    // sidebar menu count

    var $menu = $('.sidebar__menu__system');

    // Find all top-level menu items (categories)
    var $topLevelItems = $menu.find('.show__item__category');

    // Count the total number of items (including nested items)
    var totalItems = $topLevelItems.length;

    // Check if there are more than 12 items
    if (totalItems > 12) {
        // Hide items beyond the first 12
        $topLevelItems.slice(12).hide();

        // Add "Show More" link with initial icon
        $menu.append('<div class="showMore">Show More <i class="fas fa-plus"></i></div>');

        // Event handler for "Show More" link
        $menu.find('.showMore').on('click', function () {
            $topLevelItems.slice(12).toggle(300); // Toggle visibility of hidden items

            // Toggle icon between plus and minus
            $(this).toggleClass('showLess');
            if ($(this).hasClass('showLess')) {
                $(this).html('Show Less <i class="fas fa-minus"></i>');
            } else {
                $(this).html('Show More <i class="fas fa-plus"></i>');
            }
        });
    }

    //sidebar sticky
    if ($(".sticky-sidebar").length) {
        $(".sticky-sidebar").theiaStickySidebar();
    }

    // // Slider Range JS
    // if ($("#slider-range").length) {
    //     $(".noUi-handle").on("click", function () {
    //         $(this).width(50);
    //     });
    //     var rangeSlider = document.getElementById("slider-range");
    //     var moneyFormat = wNumb({
    //         decimals: 0,
    //         // thousand: ",",
    //         // prefix: "$"
    //     });
    //     var start_price = document.getElementById("filter_price_start").value;
    //     var end_price = document.getElementById("filter_price_end").value;
    //     noUiSlider.create(rangeSlider, {
    //         start: [start_price, end_price],
    //         step: 1,
    //         range: {
    //             min: [1],
    //             max: [200000]
    //         },
    //         format: moneyFormat,
    //         connect: true
    //     });

    //     // Set visual min and max values and also update value hidden form inputs
    //     rangeSlider.noUiSlider.on("update", function (values, handle) {
    //         document.getElementById("slider-range-value1").innerHTML = values[0];
    //         document.getElementById("slider-range-value2").innerHTML = values[1];
    //         document.getElementsByName("min-value").value = moneyFormat.from(values[0]);
    //         document.getElementsByName("max-value").value = moneyFormat.from(values[1]);
    //     });
    // }

    /*------ Hero slider 1 ----*/
    $(".home__slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        loop: true,
        dots: true,
        arrows: false,
        autoplay: true
    });

    // benifit active
    $(".benifit__active").slick({
        dots: false,
        arrows: false,
        slidesToShow: 6,
        autoplay: true,
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 1200,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 1000,
            settings: {
                slidesToShow: 3,
            }
        },
        {
            breakpoint: 800,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 576,
            settings: {
                slidesToShow: 2,
            }
        },
        {
            breakpoint: 450,
            settings: {
                slidesToShow: 1,
            }
        }
        ]
    });

    /*-------------popular category-------------- */
    $(function (e) {
        "use strict";
        e(".popular__category__active").slick({
            slidesToShow: 8,
            slidesToScroll: 1,
            arrows: true,
            rows: 2,
            infinite: false,
            dots: false,
            appendArrows: e(".slideroffer_arrow"),
            responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 5,
                }
            },
            {
                breakpoint: 1300,
                settings: {
                    slidesToShow: 6,
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 2,
                }
            }
            ]
        })
    });

    /*-------------popular category-------------- */
    $(function (e) {
        "use strict";
        e(".fatafaty__product__active").slick({
            slidesToShow: 6,
            slidesToScroll: 1,
            arrows: true,
            infinite: false,
            dots: false,
            appendArrows: e(".fatafaty__product__arrow"),
            responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                }
            },
            {
                breakpoint: 400,
                settings: {
                    slidesToShow: 1,
                }
            }
            ]
        })
    });
    /*-------------popular category-------------- */


    // category product
    $(document).ready(function () {
        $('.category__product__active').slick({
            slidesToShow: 5,
            dots: false,
            infinite: false,
            speed: 1000,
            arrows: true,
            lazyLoad: 'ondemand',
            rows: 2,
            autoplay: false,
            prevArrow: '<button type="button" class="slick-prev"><i class="fa-solid fa-angle-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa-solid fa-angle-right"></i></button>',
            slidesToScroll: 1,
            loop: true,
            adaptiveHeight: true,
            responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                }
            },
            {
                breakpoint: 400,
                settings: {
                    slidesToShow: 1,
                }
            }
            ]
        });

        $('.nav-link').on('shown.bs.tab', function (e) {
            var targetTabId = $(e.target).attr("href");
            $(targetTabId + ' .slider').slick('setPosition');
        });
    });



    $(function (e) {
        "use strict";
        e(".featured__product").slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            arrows: true,
            infinite: false,
            rows: 2,
            dots: false,
            appendArrows: e(".featured__product__arrow"),
            responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1
                }
            }

            ]
        })
    });

    // related product
    $(function (e) {
        "use strict";
        e(".related__product__active").slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            arrows: true,
            infinite: false,
            dots: false,
            appendArrows: e(".related__roduct_arrow"),
            responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 400,
                settings: {
                    slidesToShow: 1,
                }
            }
            ]
        })
    });


    // blog active
    $(function (e) {
        "use strict";
        e(".blog__active").slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            infinite: true,
            autoplay: true,
            dots: false,
            appendArrows: e(".blog_arrow"),
            responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 1000,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                }
            },
            {
                breakpoint: 400,
                settings: {
                    slidesToShow: 1,
                }
            }
            ]
        })
    });


    $('#preloader-active1').hide();

    /*Carausel 8 columns*/
    $(".carausel-8-columns").each(function (key, item) {
        var id = $(this).attr("id");
        var sliderID = "#" + id;
        var appendArrowsClassName = "#" + id + "-arrows";

        $(sliderID).slick({
            dots: false,
            infinite: true,
            speed: 1000,
            arrows: true,
            autoplay: true,
            slidesToShow: 8,
            slidesToScroll: 1,
            loop: true,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                }
            ],
            prevArrow: '<span class="slider-btn slider-prev"><i class="fi-rs-arrow-small-left"></i></span>',
            nextArrow: '<span class="slider-btn slider-next"><i class="fi-rs-arrow-small-right"></i></span>',
            appendArrows: appendArrowsClassName
        });
    });

    /*Carausel 10 columns*/
    $(".carausel-10-columns").each(function (key, item) {
        var id = $(this).attr("id");
        var sliderID = "#" + id;
        var appendArrowsClassName = "#" + id + "-arrows";

        $(sliderID).slick({
            dots: false,
            infinite: true,
            speed: 1000,
            arrows: true,
            autoplay: false,
            slidesToShow: 10,
            slidesToScroll: 1,
            loop: true,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                }
            ],
            prevArrow: '<span class="slider-btn slider-prev"><i class="fi-rs-arrow-small-left"></i></span>',
            nextArrow: '<span class="slider-btn slider-next"><i class="fi-rs-arrow-small-right"></i></span>',
            appendArrows: appendArrowsClassName
        });
    });

    /*Carausel 4 columns*/
    $(".carausel-4-columns").each(function (key, item) {
        var id = $(this).attr("id");
        var sliderID = "#" + id;
        var appendArrowsClassName = "#" + id + "-arrows";

        $(sliderID).slick({
            dots: false,
            infinite: true,
            speed: 1000,
            arrows: true,
            autoplay: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            loop: true,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ],
            prevArrow: '<span class="slider-btn slider-prev"><i class="fi-rs-arrow-small-left"></i></span>',
            nextArrow: '<span class="slider-btn slider-next"><i class="fi-rs-arrow-small-right"></i></span>',
            appendArrows: appendArrowsClassName
        });
    });
    /*Carausel 4 columns*/
    $(".carausel-3-columns").each(function (key, item) {
        var id = $(this).attr("id");
        var sliderID = "#" + id;
        var appendArrowsClassName = "#" + id + "-arrows";

        $(sliderID).slick({
            dots: false,
            infinite: true,
            speed: 1000,
            arrows: true,
            autoplay: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            loop: true,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ],
            prevArrow: '<span class="slider-btn slider-prev"><i class="fi-rs-arrow-small-left"></i></span>',
            nextArrow: '<span class="slider-btn slider-next"><i class="fi-rs-arrow-small-right"></i></span>',
            appendArrows: appendArrowsClassName
        });
    });

    /*Fix Bootstrap 5 tab & slick slider*/

    $('button[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
        $(".carausel-4-columns").slick("setPosition");
    });

    /*------ Timer Countdown ----*/

    $("[data-countdown]").each(function () {
        var $this = $(this),
            finalDate = $(this).data("countdown");
        $this.countdown(finalDate, function (event) {
            $(this).find('.countdown-period').remove(); // Remove the period elements

            var days = event.strftime('%D');
            var hours = event.strftime('%H');
            var minutes = event.strftime('%M');
            var seconds = event.strftime('%S');

            $(this).html(
                '<span class="countdown-section"><span class="countdown-amount hover-up">' + days + '</span> Days: </span>' +
                '<span class="countdown-section"><span class="countdown-amount hover-up">' + hours + '</span> : </span>' +
                '<span class="countdown-section"><span class="countdown-amount hover-up">' + minutes + '</span> : </span>' +
                '<span class="countdown-section"><span class="countdown-amount hover-up">' + seconds + '</span></span>'
            );
        });
    });


    /*------ Product slider active 1 ----*/
    $(".product-slider-active-1").slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        fade: false,
        loop: true,
        dots: false,
        arrows: true,
        prevArrow: '<span class="pro-icon-1-prev"><i class="fi-rs-angle-small-left"></i></span>',
        nextArrow: '<span class="pro-icon-1-next"><i class="fi-rs-angle-small-right"></i></span>',
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    /*------ Testimonial active 1 ----*/
    $(".testimonial-active-1").slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        fade: false,
        loop: true,
        dots: false,
        arrows: true,
        prevArrow: '<span class="pro-icon-1-prev"><i class="fi-rs-angle-small-left"></i></span>',
        nextArrow: '<span class="pro-icon-1-next"><i class="fi-rs-angle-small-right"></i></span>',
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    /*------ Testimonial active 3 ----*/
    $(".testimonial-active-3").slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        fade: false,
        loop: true,
        dots: true,
        arrows: false,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    /*------ Categories slider 1 ----*/
    $(".categories-slider-1").slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        fade: false,
        loop: true,
        dots: false,
        arrows: false,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });

    /*----------------------------
        Category toggle function
    ------------------------------*/
    var searchToggle = $(".categories-button-active");
    searchToggle.mouseenter(function () {
        $(this).addClass("open");
        $(this).siblings(".categories-dropdown-active-large").addClass("open");
    });

    $(".categories-dropdown-active-large").mouseleave(function () {
        $(this).removeClass("open");
        $(".categories-button-active").removeClass("open");
    });

    searchToggle.mouseleave(function () {
        $(this).removeClass("open");
        $(".categories-button-active").removeClass("open");
    });

    searchToggle.on("mouseleave", function (e) {
        e.preventDefault();
        $(this).siblings(".categories-dropdown-active-large").removeClass("open");
        $(this).removeClass("open");
    });

    $(".categories-dropdown-active-large").mouseenter(function () {
        $(this).addClass("open");
        $(this).siblings(".categories-dropdown-active-large").addClass("open");
    });

    searchToggle.on("click", function (e) {
        e.preventDefault();
        if ($(this).hasClass("open")) {
            $(this).removeClass("open");
            $(this).siblings(".categories-dropdown-active-large").removeClass("open");
        } else {
            $(this).addClass("open");
            $(this).siblings(".categories-dropdown-active-large").addClass("open");
        }
    });

    /*---------------------
        Price range
    --------------------- */
    // if ($("#slider-range").length) {
    //     var sliderrange = $("#slider-range");
    //     var amountprice = $("#amount");
    //     $(function () {
    //         sliderrange.slider({
    //             range: true,
    //             min: 16,
    //             max: 400,
    //             values: [0, 300],
    //             slide: function (event, ui) {
    //                 amountprice.val("$" + ui.values[0] + " - $" + ui.values[1]);
    //             }
    //         });
    //         amountprice.val("$" + sliderrange.slider("values", 0) + " - $" + sliderrange.slider("values", 1));
    //     });
    // }

    /*-------------------------------
        Sort by active
    -----------------------------------*/
    if ($(".sort-by-product-area").length) {
        var $body = $("body"),
            $cartWrap = $(".sort-by-product-area"),
            $cartContent = $cartWrap.find(".sort-by-dropdown");
        $cartWrap.on("click", ".sort-by-product-wrap", function (e) {
            e.preventDefault();
            var $this = $(this);
            if (!$this.parent().hasClass("show")) {
                $this.siblings(".sort-by-dropdown").addClass("show").parent().addClass("show");
            } else {
                $this.siblings(".sort-by-dropdown").removeClass("show").parent().removeClass("show");
            }
        });
        /*Close When Click Outside*/
        $body.on("click", function (e) {
            var $target = e.target;
            if (!$($target).is(".sort-by-product-area") && !$($target).parents().is(".sort-by-product-area") && $cartWrap.hasClass("show")) {
                $cartWrap.removeClass("show");
                $cartContent.removeClass("show");
            }
        });
    }

    /*-----------------------
        Shop filter active
    ------------------------- */
    $(".shop-filter-toogle").on("click", function (e) {
        e.preventDefault();
        $(".shop-product-fillter-header").slideToggle();
    });
    var shopFiltericon = $(".shop-filter-toogle");
    shopFiltericon.on("click", function () {
        $(".shop-filter-toogle").toggleClass("active");
    });

    /*-------------------------------------
        Product details big image slider
    ---------------------------------------*/
    $(".pro-dec-big-img-slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        draggable: false,
        fade: false,
        asNavFor: ".product-dec-slider-small , .product-dec-slider-small-2"
    });

    /*---------------------------------------
        Product details small image slider
    -----------------------------------------*/
    $(".product-dec-slider-small").slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: ".pro-dec-big-img-slider",
        dots: false,
        focusOnSelect: true,
        fade: false,
        arrows: false,
        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 2
                }
            }
        ]
    });

    /*-----------------------
        Magnific Popup
    ------------------------*/
    $(".img-popup").magnificPopup({
        type: "image",
        gallery: {
            enabled: true
        }
    });

    /*---------------------
        Select active
    --------------------- */
    $(".select-active").select2();

    /*--- Checkout toggle function ----*/
    $(".checkout-click1").on("click", function (e) {
        e.preventDefault();
        $(".checkout-login-info").slideToggle(900);
    });

    /*--- Checkout toggle function ----*/
    $(".checkout-click3").on("click", function (e) {
        e.preventDefault();
        $(".checkout-login-info3").slideToggle(1000);
    });

    /*-------------------------
        Create an account toggle
    --------------------------*/
    $(".checkout-toggle2").on("click", function () {
        $(".open-toggle2").slideToggle(1000);
    });

    $(".checkout-toggle").on("click", function () {
        $(".open-toggle").slideToggle(1000);
    });

    /*-------------------------------------
        Checkout paymentMethod function
    ---------------------------------------*/
    paymentMethodChanged();
    function paymentMethodChanged() {
        var $order_review = $(".payment-method");

        $order_review.on("click", 'input[name="payment_method"]', function () {
            var selectedClass = "payment-selected";
            var parent = $(this).parents(".sin-payment").first();
            parent.addClass(selectedClass).siblings().removeClass(selectedClass);
        });
    }

    /*---- CounterUp ----*/
    $(".count").counterUp({
        delay: 10,
        time: 2000
    });

    // Isotope active
    $(".grid").imagesLoaded(function () {
        // init Isotope
        var $grid = $(".grid").isotope({
            itemSelector: ".grid-item",
            percentPosition: true,
            layoutMode: "masonry",
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: ".grid-item"
            }
        });
    });

    /*====== SidebarSearch ======*/
    function sidebarSearch() {
        var searchTrigger = $(".search-active"),
            endTriggersearch = $(".search-close"),
            container = $(".main-search-active");

        searchTrigger.on("click", function (e) {
            e.preventDefault();
            container.addClass("search-visible");
        });

        endTriggersearch.on("click", function () {
            container.removeClass("search-visible");
        });
    }
    sidebarSearch();

    /*====== Sidebar menu Active ======*/
    function mobileHeaderActive() {
        var navbarTrigger = $(".burger-icon"),
            endTrigger = $(".mobile-menu-close"),
            container = $(".mobile-header-active"),
            wrapper4 = $("body");

        wrapper4.prepend('<div class="body-overlay-1"></div>');

        navbarTrigger.on("click", function (e) {
            e.preventDefault();
            container.addClass("sidebar-visible");
            wrapper4.addClass("mobile-menu-active");
        });

        endTrigger.on("click", function () {
            container.removeClass("sidebar-visible");
            wrapper4.removeClass("mobile-menu-active");
        });

        $(".body-overlay-1").on("click", function () {
            container.removeClass("sidebar-visible");
            wrapper4.removeClass("mobile-menu-active");
        });
    }
    mobileHeaderActive();

    /*---------------------
        Mobile menu active
    ------------------------ */
    var $offCanvasNav = $(".mobile-menu"),
        $offCanvasNavSubMenu = $offCanvasNav.find(".dropdown");

    /*Add Toggle Button With Off Canvas Sub Menu*/
    $offCanvasNavSubMenu.parent().prepend('<span class="menu-expand"><i class="fi-rs-angle-small-down"></i></span>');

    /*Close Off Canvas Sub Menu*/
    $offCanvasNavSubMenu.slideUp();

    /*Category Sub Menu Toggle*/
    $offCanvasNav.on("click", "li a, li .menu-expand", function (e) {
        var $this = $(this);
        if (
            $this
                .parent()
                .attr("class")
                .match(/\b(menu-item-has-children|has-children|has-sub-menu)\b/) &&
            ($this.attr("href") === "#" || $this.hasClass("menu-expand"))
        ) {
            e.preventDefault();
            if ($this.siblings("ul:visible").length) {
                $this.parent("li").removeClass("active");
                $this.siblings("ul").slideUp();
            } else {
                $this.parent("li").addClass("active");
                $this.closest("li").siblings("li").removeClass("active").find("li").removeClass("active");
                $this.closest("li").siblings("li").find("ul:visible").slideUp();
                $this.siblings("ul").slideDown();
            }
        }
    });

    /*--- language currency active ----*/
    $(".mobile-language-active").on("click", function (e) {
        e.preventDefault();
        $(".lang-dropdown-active").slideToggle(900);
    });

    /*--- categories-button-active-2 ----*/
    $(".categories-button-active-2").on("click", function (e) {
        e.preventDefault();
        $(".categori-dropdown-active-small").slideToggle(900);
    });

    /*--- Mobile demo active ----*/
    var demo = $(".tm-demo-options-wrapper");
    $(".view-demo-btn-active").on("click", function (e) {
        e.preventDefault();
        demo.toggleClass("demo-open");
    });

    /*-----More Menu Open----*/
    $(".more_slide_open").slideUp();
    $(".more_categories").on("click", function () {
        $(this).toggleClass("show");
        $(".more_slide_open").slideToggle();
    });

    /*-----Modal----*/

    $(".modal").on("shown.bs.modal", function (e) {
        $(".product-image-slider").slick("setPosition");
        $(".slider-nav-thumbnails").slick("setPosition");

        $(".product-image-slider .slick-active img").elevateZoom({
            zoomType: "inner",
            cursor: "crosshair",
            zoomWindowFadeIn: 500,
            zoomWindowFadeOut: 750
        });
    });

    /*--- VSticker ----*/
    $("#news-flash").vTicker({
        speed: 500,
        pause: 3000,
        animation: "fade",
        mousePause: false,
        showItems: 1
    });

    // filter sidebar
    const filterElement = {
        filter: document.getElementById("filter"),
        crossFilter: document.getElementById("cross_tourside"),
        openFilter: document.getElementById("sidefilterBtn"),

    }

    // filterElement destructure
    let { filter, crossFilter, openFilter } = filterElement

    openFilter.addEventListener("click", function () {
        filter.classList.add("active")
    })

    crossFilter.addEventListener("click", function () {
        filter.classList.remove("active")
    })

    // window click
    window.addEventListener("click", function (event) {
        if (!sidefilterBtn.contains(event.target) && !filter.contains(event.target)) {
            filter.classList.remove("active")
        }
    });





})(jQuery);

