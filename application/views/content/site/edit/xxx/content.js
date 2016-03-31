/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var $map = $('#map');
  var _markers = [];
  var _polyline = null;
  
  function setPolyline () {
    _polyline.setMap (_map);
    _polyline.setPath ([]);
    _polyline.setPath (_markers.map (function (marker) { return marker.position; }));
  }
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

    _map.mapTypes.set ('map_style', new google.maps.StyledMapType ([
      { featureType: 'transit', stylers: [{ visibility: 'simplified' }] },
      { featureType: 'poi', stylers: [{ visibility: 'simplified' }] },
    ]));
    _map.setMapTypeId ('map_style');
    
    _markers = $map.data ('paths').map (function (t) {
      var marker = new google.maps.Marker ({
          map: _map,
          draggable: true,
          zIndex: 9,
          optimized: false,
          position: new google.maps.LatLng (t.lat, t.lng),
          datas: t
        });

      google.maps.event.addListener (marker, 'dragend', function () {
        $.ajax ({
          url: $map.data ('update'),
          data: {
            id: marker.datas.id,
            lat: marker.position.lat (),
            lng: marker.position.lng (),
          },
          async: true, cache: false, dataType: 'json', type: 'POST',
          beforeSend : function () {}
        })
        .done (function (result) {
          if (!result.s) return ;
          setPolyline ();
        })
        .fail (function (result) {})
        .complete (function (result) {});
      });
      
      google.maps.event.addListener (marker, 'rightclick', function () {
        $.ajax ({
          url: $map.data ('delete'),
          data: { id: marker.datas.id },
          async: true, cache: false, dataType: 'json', type: 'POST',
          beforeSend : function () {}
        })
        .done (function (result) {
          if (!result.s) return ;
          _markers.splice (_markers.indexOf (marker), 1);
          marker.setMap (null);
          setPolyline ();
        })
        .fail (function (result) {})
        .complete (function (result) {});
      });

      return marker;
    });
    _map.setCenter (_markers[_markers.length - 1].position);

    _polyline = new google.maps.Polyline ({
        map: _map,
        strokeColor: 'rgba(255, 3, 0, .6)',
        strokeWeight: 3,
        path: _markers.map (function (marker) { return marker.position; })
      });

  }
  google.maps.event.addDomListener (window, 'load', initialize);

  window.hideLoading ();
});