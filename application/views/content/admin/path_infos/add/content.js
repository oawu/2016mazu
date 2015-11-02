/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $map = $('#map');
  var $fm = $('form#fm');
  var _map = null;
  var _marker = null;
  var _polyline = null;

  // $(window).resize (function () {
  //   $map.css ({'height': '100%'});
  // }).resize ();

  function initialize () {
    _map = new google.maps.Map ($map.get (0), {
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

    _polyline = new google.maps.Polyline ({
      map: _map,
      strokeColor: 'rgba(68, 77, 145, .6)',
      strokeWeight: 4,
      path: $fm.find ('input.points').map (function () {
          return new google.maps.LatLng ($(this).data ('lat'), $(this).data ('lng'));
        })
    });

    var $latitude = $fm.find ('input.ori_latitude');
    var $longitude = $fm.find ('input.ori_longitude');
    if ($latitude.length && $longitude.length)
        _marker = new google.maps.Marker ({
            map: _map,
            draggable: true,
            position: new google.maps.LatLng ($latitude.val (), $longitude.val ()),
          });

    google.maps.event.addListener (_map, 'click', function (e) {
      if (_marker)
        _marker.setPosition (e.latLng);
      else
        _marker = new google.maps.Marker ({
            map: _map,
            draggable: true,
            position: e.latLng,
          });
    });

    $fm.submit (function () {
      if (!_marker) {
        alert ('請點選位置！');
        return false;
      }
      $fm.append (
          $('<input />').attr ('type', 'hidden').attr ('name', 'latitude').val (_marker.position.lat ())
        ).append (
          $('<input />').attr ('type', 'hidden').attr ('name', 'longitude').val (_marker.position.lng ())
        );

      window.showLoading ();
    });
    window.hideLoading ();
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});