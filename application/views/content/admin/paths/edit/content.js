/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var $map = $('#map');
  var $mapMenu = $('#map_menu');
  var $markerMenu = $('#marker_menu');
  var $polylineMenu = $('#polyline_menu');
  var $length = $('#length');
  var $fm = $('form#fm');
  var _map = null;
  var _markers = [];

  var getPixelPosition = function () {
    var scale = Math.pow (2, this.map.getZoom ());
    var nw = new google.maps.LatLng (
        this.map.getBounds ().getNorthEast ().lat (),
        this.map.getBounds ().getSouthWest ().lng ()
    );
    var worldCoordinateNW = this.map.getProjection ().fromLatLngToPoint (nw);
    var worldCoordinate = this.map.getProjection ().fromLatLngToPoint (this.getPosition ());
    
    return new google.maps.Point (
        (worldCoordinate.x - worldCoordinateNW.x) * scale,
        (worldCoordinate.y - worldCoordinateNW.y) * scale
    );
  };
  function formatFloat (num, pos) {
    var size = Math.pow (10, pos);
    return Math.round (num * size) / size;
  }
  function calculateLength (points) {
    if (google.maps.geometry.spherical)
      $length.html (formatFloat (google.maps.geometry.spherical.computeLength (points) / 1000, 2));
  }
  function fromLatLngToPoint (latLng, map) {
    var scale = Math.pow (2, map.getZoom ());
    var topRight = map.getProjection ().fromLatLngToPoint (map.getBounds ().getNorthEast ());
    var bottomLeft = map.getProjection ().fromLatLngToPoint (map.getBounds ().getSouthWest ());
    var worldPoint = map.getProjection ().fromLatLngToPoint (latLng);
    return new google.maps.Point ((worldPoint.x - bottomLeft.x) * scale, (worldPoint.y - topRight.y) * scale);
  }
  function setPolyline () {
    for (var i = 0; i < _markers.length; i++) {
      if (!_markers[i].polyline) {
        var polyline = new google.maps.Polyline ({
          map: _map,
          strokeColor: 'rgba(68, 77, 145, .6)',
          strokeWeight: 4,
          drawPath: function () {
            var prevPosition = this.prevMarker.getPosition ();
            var nextPosition = this.nextMarker.getPosition ();
            this.setPath ([prevPosition, nextPosition]);
            if (!this.prevMarker.map) this.prevMarker.setMap (_map);
            if (!this.nextMarker.map) this.nextMarker.setMap (_map);
            if (!this.map) this.setMap (_map);
          }
        });

        google.maps.event.addListener (polyline, 'rightclick', function (e) {
          var point = fromLatLngToPoint (e.latLng, _map);
          $polylineMenu.css ({ top: point.y, left: point.x })
                       .data ('lat', e.latLng.lat ())
                       .data ('lng', e.latLng.lng ())
                       .addClass ('show').polyline = polyline;
                              
        });
        _markers[i].polyline = polyline;
      }
      
      _markers[i].polyline.prevMarker = _markers[i - 1] ? _markers[i - 1] : _markers[i];
      _markers[i].polyline.nextMarker = _markers[i];
      _markers[i].polyline.drawPath ();
    }
    if (_markers.length > 1)
      calculateLength (_markers.map (function (t) { return t.getPosition (); }));
  }
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
        icon: {
            path: circlePath (5),
            strokeColor: 'rgba(50, 60, 140, .4)',
            strokeWeight: 1,
            fillColor: 'rgba(68, 77, 145, .95)',
            fillOpacity: 0.5
          },
        getPixelPosition: getPixelPosition
      });

    google.maps.event.addListener (marker, 'drag', setPolyline);

    google.maps.event.addListener (marker, 'rightclick', function (e) {
      var pixel = marker.getPixelPosition ();
      $markerMenu.css ({ top: pixel.y, left: pixel.x }).addClass ('show').marker = marker;
    });
    _markers.splice (index ? index : _markers.length, 0, marker);
    
    setPolyline ();
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

    $fm.find ('input.ori_points').each (function () {
      initMarker (new google.maps.LatLng ($(this).data ('lat'), $(this).data ('lng')), 0, $(this).val ());
    });
    google.maps.event.addListener (_map, 'rightclick', function (e) {
      $mapMenu.css ({ top: e.pixel.y, left: e.pixel.x })
              .data ('lat', e.latLng.lat ())
              .data ('lng', e.latLng.lng ()).addClass ('show');
    });
    google.maps.event.addListener (_map, 'mousemove', function () {
      $mapMenu.css ({ top: -100, left: -100 }).removeClass ('show');
      $markerMenu.css ({ top: -100, left: -100 }).removeClass ('show');
      $polylineMenu.css ({ top: -100, left: -100 }).removeClass ('show');
    });
    $mapMenu.find ('.add_marker').click (function () {
      initMarker (new google.maps.LatLng ($mapMenu.data ('lat'), $mapMenu.data ('lng')), 0);
      $mapMenu.css ({ top: -100, left: -100 }).removeClass ('show');
    });
    $mapMenu.find ('.add_info').click (function () {
      initInfo (new google.maps.LatLng ($mapMenu.data ('lat'), $mapMenu.data ('lng')));
      $mapMenu.css ({ top: -100, left: -100 }).removeClass ('show');
    });

    $markerMenu.find ('.del').click (function () {
      _markers.splice (_markers.indexOf ($markerMenu.marker), 1);
      $markerMenu.marker.setMap (null);
      if ($markerMenu.marker.polyline) $markerMenu.marker.polyline.setMap (null);
      setPolyline ();
      $markerMenu.css ({ top: -100, left: -100 }).removeClass ('show');
    });

    $polylineMenu.find ('.add').click (function () {
      if ($polylineMenu.polyline)
        initMarker (new google.maps.LatLng ($polylineMenu.data ('lat'), $polylineMenu.data ('lng')), _markers.indexOf ($polylineMenu.polyline.nextMarker));
      $polylineMenu.css ({ top: -100, left: -100 }).removeClass ('show');
    });

    $fm.submit (function () {
      $fm.append (_markers.map (function (t, i) {
        return $('<input />').attr ('type', 'hidden').attr ('name', 'points[' + i + '][lat]').val (t.position.lat ()).add ($('<input />').attr ('type', 'hidden').attr ('name', 'points[' + i + '][lng]').val (t.position.lng ()));
      }));

      window.showLoading ();
    });

    $('#zoom').click (function () {
      var $body = $('body');

      if (!$body.hasClass ('f')) {
        $body.addClass ('f');
        $(this).attr ('class', 'icon-shrink');
      } else {
        $body.removeClass ('f');
        $(this).attr ('class', 'icon-enlarge');
      }
      google.maps.event.trigger (_map, 'resize');
    });
    window.hideLoading ();
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});