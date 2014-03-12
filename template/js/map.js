 //Defining map as a global variable to access from other functions
var map;
function initMap () {

      //Enabling new cartography and themes
      google.maps.visualRefresh = true;

      //Setting starting options of map
      var mapOptions = {
            center: new google.maps.LatLng(49.70431, 17.08154),
            zoom: 16,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            disableDefaultUI: true
      };

      //Getting map DOM element
      var mapElement = document.getElementById('mapDiv');

      //Creating a map with DOM element which is just obtained
      map = new google.maps.Map(mapElement, mapOptions);

      startButtonEvents();
}
google.maps.event.addDomListener(window, 'load', initMap);

