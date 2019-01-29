if ($.cookie("theme_csspath")) {
    $('link#theme-stylesheet').attr("href", $.cookie("theme_csspath"));
}

$(function () {

    productDetailGallery(4000);
    productQuickViewGallery();
    //map();
    carousels();
    utils();
    demo();

});

/* for demo purpose only - can be deleted */

function demo() {

    if ($.cookie("theme_csspath")) {
        $('link#theme-stylesheet').attr("href", $.cookie("theme_csspath"));
    }

    $("#colour").change(function () {

        if ($(this).val !== '') {

            var colour = $(this).val();

            var theme_csspath = 'css/style.' + $(this).val() + '.css';
            $('link#theme-stylesheet').attr("href", theme_csspath);
            $.cookie("theme_csspath", theme_csspath, {
                expires: 365,
                path: '/'
            });
        }

        return false;
    });
}


/* expandable div */

function growCategory() {
    var growDiv = document.getElementById('grow-category');
    if (growDiv.clientHeight) {
      growDiv.style.height = 0;
    } else {
      var wrapper = document.querySelector('.measuringWrapper-category');
      // var wrapper2 = document.querySelector('.tree');
      // growDiv.style.height = wrapper.clientHeight + "px";
      growDiv.style.height = "inherit";
    }
}

function growSubcategory(div) {
    var growDiv = document.getElementById('grow-category');
    var wrapper = document.querySelector('.measuringWrapper-category');
    var wrapper2 = document.querySelector('.subtree');
    growDiv.style.height = wrapper.clientHeight + wrapper2.clientHeight + "px";
      // growDiv.style.height = div.clientHeight + "px";
}

function growBrand() {
    var growDiv = document.getElementById('grow-brand');
    if (growDiv.clientHeight) {
      growDiv.style.height = 0;
    } else {
      var wrapper = document.querySelector('.measuringWrapper-brand');
      growDiv.style.height = wrapper.clientHeight + "px";
    }
}

/* =========================================
 *  carousels
 *  =======================================*/

function carousels() {

    // $('#main-slider').owlCarousel({
    //     navigation: true,
    //     // Show next and prev buttons
    //     slideSpeed: 300,
    //     paginationSpeed: 400,
    //     autoPlay: true,
    //     stopOnHover: true,
    //     singleItem: true,
    //     afterInit: ''
    // });

}

/* =========================================
 *  product detail gallery 
 *  =======================================*/

function productDetailGallery(confDetailSwitch) {
    $('.product__thumbs .thumb:first').addClass('active');
    timer = setInterval(autoSwitch, confDetailSwitch);
    $(".product__thumbs .thumb").click(function (e) {

        switchImage($(this));
        clearInterval(timer);
        timer = setInterval(autoSwitch, confDetailSwitch);
        e.preventDefault();
    });
    $('.mainImage img').hover(function () {
        clearInterval(timer);
    }, function () {
        timer = setInterval(autoSwitch, confDetailSwitch);
    });
    function autoSwitch() {
        var nextThumb = $('.product__thumbs .thumb.active').closest('div').next('div').find('.thumb');
        if (nextThumb.length == 0) {
            nextThumb = $('.product__thumbs  .thumb:first');
        }
        switchImage(nextThumb);
    }

    function switchImage(thumb) {

        $('.product__thumbs .thumb').removeClass('active');
        var bigUrl = thumb.attr('href');
        thumb.addClass('active');
        $('.mainImage img').attr('src', bigUrl);
    }
}

function productQuickViewGallery() {

    $('.quick-view').each(function () {

        var element = $(this);

        element.find('.thumb:first').addClass('active');


        element.find(".thumb").click(function (e) {

            switchImage($(this));
            e.preventDefault();
        });

    });

    function switchImage(thumb) {

        thumb.parents('.quick-view').find('.thumb').removeClass('active');
        var bigUrl = thumb.attr('href');
        thumb.addClass('active');
        thumb.parents('.quick-view').find('.quick-view-main-image img').attr('src', bigUrl);
    }
}

/* =========================================
 *  map 
 *  =======================================*/

function map() {

    var styles = [{
        "featureType": "landscape",
        "stylers": [{
            "saturation": - 100
        }, {
            "lightness": 65
        }, {
            "visibility": "on"
        }
        ]
    }, {
        "featureType": "poi",
        "stylers": [{
            "saturation": - 100
        }, {
            "lightness": 51
        }, {
            "visibility": "simplified"
        }
        ]
    }, {
        "featureType": "road.highway",
        "stylers": [{
            "saturation": - 100
        }, {
            "visibility": "simplified"
        }
        ]
    }, {
        "featureType": "road.arterial",
        "stylers": [{
            "saturation": - 100
        }, {
            "lightness": 30
        }, {
            "visibility": "on"
        }
        ]
    }, {
        "featureType": "road.local",
        "stylers": [{
            "saturation": - 100
        }, {
            "lightness": 40
        }, {
            "visibility": "on"
        }
        ]
    }, {
        "featureType": "transit",
        "stylers": [{
            "saturation": - 100
        }, {
            "visibility": "simplified"
        }
        ]
    }, {
        "featureType": "administrative.province",
        "stylers": [{
            "visibility": "off"
        }
        ]
    }, {
        "featureType": "water",
        "elementType": "labels",
        "stylers": [{
            "visibility": "on"
        }, {
            "lightness": - 25
        }, {
            "saturation": - 100
        }
        ]
    }, {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [{
            "hue": "#ffff00"
        }, {
            "lightness": - 25
        }, {
            "saturation": - 97
        }
        ]
    }
    ];
    map = new GMaps({
        el: '#map',
        lat: - 12.043333,
        lng: - 77.028333,
        zoomControl: true,
        zoomControlOpt: {
            style: 'SMALL',
            position: 'TOP_LEFT'
        },
        panControl: false,
        streetViewControl: false,
        mapTypeControl: false,
        overviewMapControl: false,
        scrollwheel: false,
        draggable: false,
        styles: styles
    });

    var image = 'img/marker.png';

    map.addMarker({
        lat: - 12.043333,
        lng: - 77.028333,
        icon: image,
        title: '',
        infoWindow: {
            content: '<p>HTML Content</p>'
        }
    });
}

/* =========================================
 *  UTILS
 *  =======================================*/

function utils() {

    /* tooltips */

    $('[data-toggle="tooltip"]').tooltip();

    /* click on the box activates the radio */

    $('#checkout').on('click', '.box.shipping-method, .box.payment-method', function (e) {
        var radio = $(this).find(':radio');
        radio.prop('checked', true);

        getOngkirForRadioButton();
    });
    /* click on the box activates the link in it */

    $('.box.clickable').on('click', function (e) {

        window.location = $(this).find('a').attr('href');
    });
    /* external links in new window*/

    $('.external').on('click', function (e) {

        e.preventDefault();
        window.open($(this).attr("href"));
    });
    /* animated scrolling */

    $('.scroll-to').click(function (event) {
        event.preventDefault();
        var full_url = this.href;
        var parts = full_url.split("#");
        var trgt = parts[1];

        $('body').scrollTo($('#' + trgt), 800, {
            offset: - 50
        });

    });

}

$.fn.alignElementsSameHeight = function () {
    $('.same-height-row').each(function () {

        var maxHeight = 0;
        var children = $(this).find('.same-height');
        children.height('auto');
        if ($(window).width() > 768) {
            children.each(function () {
                if ($(this).innerHeight() > maxHeight) {
                    maxHeight = $(this).innerHeight();
                }
            });
            children.innerHeight(maxHeight);
        }

        maxHeight = 0;
        children = $(this).find('.same-height-always');
        children.height('auto');
        children.each(function () {
            if ($(this).innerHeight() > maxHeight) {
                maxHeight = $(this).innerHeight();
            }
        });
        children.innerHeight(maxHeight);
    });
}

$(window).load(function () {

    windowWidth = $(window).width();

    $(this).alignElementsSameHeight();


});
$(window).resize(function () {

    newWindowWidth = $(window).width();

    if (windowWidth !== newWindowWidth) {
        setTimeout(function () {
            $(this).alignElementsSameHeight();
        }, 100);
        windowWidth = newWindowWidth;
    }

});

/*
back to top
*/

jQuery(document).ready(function($){
  // browser window scroll (in pixels) after which the "back to top" link is shown
  var offset = 300,
    //browser window scroll (in pixels) after which the "back to top" link opacity is reduced
    offset_opacity = 1200,
    //duration of the top scrolling animation (in ms)
    scroll_top_duration = 700,
    //grab the "back to top" link
    $back_to_top = $('.cd-top');

  //hide or show the "back to top" link
  $(window).scroll(function(){
    ( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
    if( $(this).scrollTop() > offset_opacity ) { 
      $back_to_top.addClass('cd-fade-out');
    }
  });

  //smooth scroll to top
  $back_to_top.on('click', function(event){
    event.preventDefault();
    $('body,html').animate({
      scrollTop: 0 ,
      }, scroll_top_duration
    );
  });

});

