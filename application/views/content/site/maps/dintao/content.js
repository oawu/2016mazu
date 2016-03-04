/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var $body = $('body');
  var $map = $('#map');
  var $show_info = $('#show_info');
  var $length = $('#length');
  var _map = null;
  var _polyline = null;
  var _timer = null;
  var _points = [];
  var _mazu = null;
  var _info = null;
  var _infos = [];

  function formatFloat (num, pos) {
    var size = Math.pow (10, pos);
    return Math.round (num * size) / size;
  }
  function calculateLength (points) {
    if (google.maps.geometry.spherical)
      $length.html (formatFloat (google.maps.geometry.spherical.computeLength (points) / 1000, 2));
  }
  function getUnit (will, now) {
    var addLat = will.lat () - now.lat ();
    var addLng = will.lng () - now.lng ();
    var aveAdd = ((Math.abs (addLat) + Math.abs (addLng)) / 2);
    var unit = aveAdd < 10 ? aveAdd < 1 ? aveAdd < 0.1 ? aveAdd < 0.01 ? aveAdd < 0.001 ? aveAdd < 0.0001 ? 3 : 6 : 9 : 12 : 15 : 24 : 21;
    var lat = addLat / unit, lng = addLng / unit;

    if (!((Math.abs (lat) > 0) || (Math.abs (lng) > 0))) return null;
    return { unit: unit, lat: lat, lng: lng };
  }

  function markerMove (marker, unitLat, unitLng, unitCount, unit, callback) {
    if (unit > unitCount) {
      marker.setPosition (new google.maps.LatLng (marker.getPosition ().lat () + unitLat, marker.getPosition ().lng () + unitLng));
      clearTimeout (window.markerMoveTimer);
      window.markerMoveTimer = setTimeout (function () {
        markerMove (marker, unitLat, unitLng, unitCount + 1, unit, callback);
      }, 25);
    } else { if (callback) callback (marker); }
  }
  function markerGo (marker, will, callback) {
    var now = marker.getPosition ();
    var Unit = getUnit (will, now);
    if (!Unit) return false;
    markerMove (marker, Unit.lat, Unit.lng, 0, Unit.unit, callback);
  }

  function mapMove (map, unitLat, unitLng, unitCount, unit, callback) {
    if (unit > unitCount) {
      map.setCenter (new google.maps.LatLng (map.getCenter ().lat () + unitLat, map.getCenter ().lng () + unitLng));
      clearTimeout (window.mapMoveTimer);
      window.mapMoveTimer = setTimeout (function () {
        mapMove (map, unitLat, unitLng, unitCount + 1, unit, callback);
      }, 25);
    } else {
      if (callback)
        callback (map);
    }
  }

  function mapGo (map, will, callback) {
    var now = map.center;

    var Unit = getUnit (will, now);
    if (!Unit)
      return false;

    mapMove (map, Unit.lat, Unit.lng, 0, Unit.unit, callback);
  }

  function info (i) {
    if (!i) return '';
    return '<div class="c"><div><img src="' + i.o + '"/><span>' + i.t + '</span></div><div>' + i.c + '</div></div><div class="b"></div>';
  }
  function infosClickAction () {
    if (_info.lastMarker)
      _info.lastMarker.setMap (_map);
    _info.setPosition (this.getPosition ());

    _info.labelContent = info (this.t);
    this.setMap (null);
    _info.setMap (_map);
    _info.lastMarker = this;
    mapGo (_map, _info.getPosition ());
  }
  function loop (i) {
    i = (i !== undefined) && ((i + 1) < _points.length) ? i + 1 : 0;

    clearTimeout (_timer);
    _timer = setTimeout (function () {
      markerGo (_mazu, _points[i], loop (i));
    }, 150);
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
    
    var bounds = new google.maps.LatLngBounds ();
    _points = $map.data ('polyline').map (function (t) {
        var position = new google.maps.LatLng (t.a, t.n);
        bounds.extend (position);
        return position;
      });

    _info = new MarkerWithLabel ({
        draggable: false,
        raiseOnDrag: false,
        clickable: true,
        zIndex: 999,
        labelZIndex: 2,
        optimized: false,
        labelContent: info (),
        labelAnchor: new google.maps.Point (230 / 2, 170 + 20 - 4),
        labelClass: 'path_info',
        icon: {path: 'M 0 0'},
        lastMarker: null
      });

    google.maps.event.addListener (_info, 'click', function (e) {
      if (this.lastMarker) this.lastMarker.setMap (_map);
      this.setMap (null);
      this.lastMarker = null;
    });
    
    _infos = $map.data ('infos').map (function (t) {
      var position = new google.maps.LatLng (t.a, t.n);
      bounds.extend (position);
      var m = new google.maps.Marker ({
          map: _map,
          draggable: false,
          zIndex: 9,
          optimized: false,
          position: position,
          icon: t.i,
          t: t,
        });

      google.maps.event.addListener (m, 'click', infosClickAction);
      return m;
    });
    
    if (_infos.length) _map.fitBounds (bounds);
    
    var $zoom = $('#zoom').click (function () {
      if (!$body.hasClass ('f')) {
        $body.addClass ('f');
        $(this).attr ('class', 'icon-shrink');
      } else {
        $body.removeClass ('f');
        $(this).attr ('class', 'icon-enlarge');
      }
      google.maps.event.trigger (_map, 'resize');
      _map.fitBounds (bounds);
    });

    var $container = $('#container');
    var overflow = $body.css ('overflow');

    $('#menu').click (function () {
      if ($container.hasClass ('show')) {
        $container.removeClass ('show');
        $body.css ('overflow', overflow);
      } else {
        $container.addClass ('show');
        $body.css ('overflow', 'hidden');
      }
    });

    if (_points.length) {
      _map.fitBounds (bounds);
      _polyline = new google.maps.Polyline ({
        map: _map,
        strokeColor: 'rgba(255, 3, 0, .6)',
        strokeWeight: 3,
        path: _points
      });
      _mazu = new MarkerWithLabel ({
          position: _points[0],
          draggable: false,
          raiseOnDrag: false,
          clickable: true,
          zIndex: 99,
          labelZIndex: 2,
          optimized: false,
          labelContent: '<div><img src="' + $map.data ('icon') + '" /></div>',
          labelAnchor: new google.maps.Point (20, 70),
          labelClass: 'mazu',
          icon: {path: 'M 0 0'},
          map: _map,
          initCallback: function (t) {}
        });
      google.maps.event.addListener (_map, 'click', function () {
        new google.maps.event.trigger (_info, 'click');
      });
      google.maps.event.addListener (_map, 'dragstart', function () {
        new google.maps.event.trigger (_info, 'click');
      });
      setTimeout (loop, 1000);
    }
    window.hideLoading ();
  }

  addPv ('Path', $('#id').val ());
  google.maps.event.addDomListener (window, 'load', initialize);
});