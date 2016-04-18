/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var $map = $('#map');

  google.maps.event.addDomListener (window, 'load', function () {
    $map.get (0).markers = [];
    $map.get (0)._map = new google.maps.Map ($map.get (0), {
      zoom: 16,
      zoomControl: true,
      scrollwheel: true,
      scaleControl: true,
      mapTypeControl: false,
      navigationControl: true,
      streetViewControl: false,
      disableDoubleClickZoom: true,
      center: new google.maps.LatLng (23.569396231491233, 120.3030703338623),
    });

    $map.get (0)._map.mapTypes.set ('map_style', new google.maps.StyledMapType ([
      { featureType: 'transit', stylers: [{ visibility: 'off' }] },
      { featureType: 'poi', stylers: [{ visibility: 'off' }] },
    ]));
    $map.get (0)._map.setMapTypeId ('map_style');

    $map.get (0)._polyline = new google.maps.Polyline ({
        map: $map.get (0)._map,
        strokeColor: 'rgba(255, 3, 0, .2)',
        strokeWeight: 5,
        path: $map.data ('path').map (function (t) {
          return new google.maps.LatLng (t.a, t.n);
        })
      });


    google.maps.event.addListener ($map.get (0)._map, 'click', function (e) {
      $map.get (0).markers.push (new google.maps.Marker ({
        map: $map.get (0)._map,
        draggable: true,
        position: e.latLng
      }));
    });
  });
  $('#ooo').click (function () {
    $('#xxx').append ($map.get (0).markers.map (function (t, i) {
      return $('<input />').attr ('type', 'hidden').attr ('name', 'points[' + i + '][lat]').val (t.position.lat ()).add (
          $('<input />').attr ('type', 'hidden').attr ('name', 'points[' + i + '][lng]').val (t.position.lng ())
        );
    })).submit ();
  });
  window.hideLoading ();
});