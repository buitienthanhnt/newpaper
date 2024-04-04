$(document).ready(function() {
    $.ajax({
        url: mostPopulatorUrl,
        type: "GET",
        success: function(result) {
            if (result.dataHtml) {
                let mostPopur = $("#most-populator")
                mostPopur.append(result.dataHtml);
                $('.weekly2-news-active').slick({
                    dots: false,
                    infinite: true,
                    speed: 500,
                    arrows: true,
                    autoplay: true,
                    loop: true,
                    slidesToShow: 3,
                    prevArrow: '<button type="button" class="slick-prev"><i class="ti-angle-left"></i></button>',
                    nextArrow: '<button type="button" class="slick-next"><i class="ti-angle-right"></i></button>',
                    slidesToScroll: 1,
                    responsive: [{
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1,
                                infinite: true,
                                dots: false,
                            }
                        },
                        {
                            breakpoint: 992,
                            settings: {
                                slidesToShow: 2,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 700,
                            settings: {
                                arrows: false,
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        },
                        {
                            breakpoint: 480,
                            settings: {
                                arrows: false,
                                slidesToShow: 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
            }
        }
    })

    // $.ajax({
    //     url: trending,
    //     type: "GET",
    //     success: function(result) {
    //         if (result.dataHtml) {
    //             let trending = $("#most-trending")
    //             trending.append(result.dataHtml);
    //             $('.recent-active').slick({
    //                 dots: false,
    //                 infinite: true,
    //                 speed: 600,
    //                 arrows: false,
    //                 slidesToShow: 3,
    //                 slidesToScroll: 1,
    //                 prevArrow: '<button type="button" class="slick-prev"> <span class="flaticon-arrow"></span></button>',
    //                 nextArrow: '<button type="button" class="slick-next"> <span class="flaticon-arrow"><span></button>',

    //                 initialSlide: 3,
    //                 loop: true,
    //                 responsive: [{
    //                         breakpoint: 1024,
    //                         settings: {
    //                             slidesToShow: 3,
    //                             slidesToScroll: 3,
    //                             infinite: true,
    //                             dots: false,
    //                         }
    //                     },
    //                     {
    //                         breakpoint: 992,
    //                         settings: {
    //                             slidesToShow: 2,
    //                             slidesToScroll: 1
    //                         }
    //                     },
    //                     {
    //                         breakpoint: 768,
    //                         settings: {
    //                             slidesToShow: 1,
    //                             slidesToScroll: 1
    //                         }
    //                     }
    //                 ]
    //             });
    //         }
    //     }
    // })
})