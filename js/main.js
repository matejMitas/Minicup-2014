$(function() {
    mobileMenu();
    IEusers();
    GMap("mapDiv");
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

function GMap (element) {
    var map;
    var litovel = new google.maps.LatLng(49.70460, 17.08072);
    var opletalova = new google.maps.LatLng(49.70267, 17.08162);

var MY_MAPTYPE_ID = 'custom_style';

function initialize() {

  var featureOpts = [
    {
      stylers: [
        { hue: '#256ca1' },
        { visibility: 'simplified' },
        { gamma: 0.5 },
        { weight: 0.75 }
      ]
    },
    {
      elementType: 'labels',
      stylers: [
        { visibility: 'on' },
      ]
    },
    {
      featureType: 'water',
      stylers: [
        { color: '#256ca1' }
      ]
    }
  ];

  var mapOptions = {
    zoom: 14,
    center: litovel,
    mapTypeControlOptions: {
      mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
    },
    mapTypeId: MY_MAPTYPE_ID,
    disableDefaultUI: true
  };

  map = new google.maps.Map(document.getElementById(element),
      mapOptions);

  var marker1 = new google.maps.Marker({
      position: litovel,
      map: map,
      title: 'Hello World!'
  });

  var marker2 = new google.maps.Marker({
      position: opletalova,
      map: map,
      title: 'Hello World!'
  });



  var styledMapOptions = {
    name: 'Custom Style'
  };

  var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);

  map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
}

google.maps.event.addDomListener(window, 'load', initialize);

}