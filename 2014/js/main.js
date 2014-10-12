$(function() {
    mobileMenu();
    IEusers();
    GMap("mapDiv");
    jQueryToggle();

});

function mobileMenu() {
    var pull = $('#pull');
    menu = $('nav ul');
    menuHeight = menu.height();
    $(pull).on('click', function(e) {
        e.preventDefault();
        menu.slideToggle();
    });

    $(window).resize(function() {
        var w = $(window).width();
        if (w > 320 && menu.is(':hidden')) {
            menu.removeAttr('style');
        }
    });
}

function IEusers() {
    $(".closeBlock").click(function() {
        $("#warning").hide();
    });
}

function GMap(element) {
    var map;
    var litovel = new google.maps.LatLng(49.70460, 17.08072);
    var opletalova = new google.maps.LatLng(49.70267, 17.08162);

    var MY_MAPTYPE_ID = 'custom_style';

    function initialize() {

        var featureOpts = [
            {
                stylers: [
                    {hue: '#256ca1'},
                    {visibility: 'simplified'},
                    {gamma: 0.5},
                    {weight: 0.75}
                ]
            },
            {
                elementType: 'labels',
                stylers: [
                    {visibility: 'on'},
                ]
            },
            {
                featureType: 'water',
                stylers: [
                    {color: '#256ca1'}
                ]
            }
        ];

        var mapOptions = {
            zoom: 16,
            center: litovel,
            mapTypeControlOptions: {
                mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
            },
            mapTypeId: MY_MAPTYPE_ID,
            disableDefaultUI: false
        };

        map = new google.maps.Map(document.getElementById(element), mapOptions);

        var marker1 = new google.maps.Marker({
            position: litovel,
            map: map,
            title: 'Hala ZŠ Vítězná Litovel'
        });

        var marker2 = new google.maps.Marker({
            position: opletalova,
            map: map,
            title: 'Sokolovna Litovel'
        });



        var styledMapOptions = {
            name: 'MINICUP'
        };

        var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);

        map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
    }

    google.maps.event.addDomListener(window, 'load', initialize);

}

function jQueryToggle() {
    (function($) {
        $.toggle = function(toggleButtons, toggleTargets, toggleActive) {
            if (typeof toggleActive === "undefined")
                var toggleActive = "active";

            $(toggleTargets).each(function(index, el) {
                if (index === 0) {
                    $(el).show();
                } else {
                    $(el).hide();
                }
            });

            $(toggleTargets + ":first-of-type").show();

            $(toggleButtons + ":first-of-type").each(function(index, el) {
                $(el).addClass(toggleActive);
            });

            $(toggleButtons).click(function(event) {
                var src = $(this).data("src");
                $(toggleButtons).removeClass(toggleActive);
                $(this).addClass(toggleActive);
                $(toggleTargets).each(function() {
                    var target = $(this).data("target");
                    if (target === src) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        };
    })(jQuery);
}
;