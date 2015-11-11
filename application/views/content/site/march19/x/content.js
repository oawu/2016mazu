/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $map = $('#map');
  var _map = null;
  var _markers = [];
 

  function circlePath (r) {
    return 'M 0 0 m -' + r + ', 0 '+
           'a ' + r + ',' + r + ' 0 1,0 ' + (r * 2) + ',0 ' +
           'a ' + r + ',' + r + ' 0 1,0 -' + (r * 2) + ',0';
  }

  function initMarker (position, index) {
    var marker = new google.maps.Marker ({
        map: _map,
        draggable: true,
        position: position,
      });
  }
  function initialize () {
    _map = new google.maps.Map ($map.get (0), {
        zoom: 14,
        zoomControl: true,
        scrollwheel: true,
        scaleControl: true,
        mapTypeControl: false,
        navigationControl: true,
        streetViewControl: false,
        disableDoubleClickZoom: true,
        center: new google.maps.LatLng (25.054, 121.54),
      });

    new google.maps.Marker ({
        map: _map,
        draggable: true,
        position: new google.maps.LatLng ($map.data ('lat'), $map.data ('lng')),
        icon: '/resource/image/map/spotlight-4.png'
      });
    new google.maps.Circle({
        map: _map,
        strokeColor: '#FF0000',
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: '#FF0000',
        fillOpacity: 0.35,
        center: new google.maps.LatLng ($map.data ('lat'), $map.data ('lng')),
        radius: 1000
      });

    $map.data ('places_details').forEach (function (t, i) {
      initMarker (new google.maps.LatLng (t.geometry.location.lat, t.geometry.location.lng), 0);
    });

    window.hideLoading ();
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});