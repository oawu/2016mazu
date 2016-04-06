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
  var _isMoved = false;
  var _infos = [];

  function formatFloat (num, pos) {
    var size = Math.pow (10, pos);
    return Math.round (num * size) / size;
  }
  function calculateLength (points) {
    if (google.maps.geometry.spherical)
      $length.html (formatFloat (google.maps.geometry.spherical.computeLength (points) / 1000, 2));
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
      if (!_isMoved && !(i % 10)) mapGo (_map, _points[i]);
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
    
    _points = $map.data ('polyline').map (function (t) {
        var position = new google.maps.LatLng (t.a, t.n);
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
        labelClass: 'info',
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
    
    
    var $zoom = $('#zoom').click (function () {
      if (!$body.hasClass ('f')) {
        $body.addClass ('f');
        $(this).attr ('class', 'icon-shrink');
      } else {
        $body.removeClass ('f');
        $(this).attr ('class', 'icon-enlarge');
      }
      google.maps.event.trigger (_map, 'resize');
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
        _isMoved = true;
      });
      google.maps.event.addListener (_map, 'zoom_changed', function () {
        _isMoved = true;
      });
      setTimeout (loop, 1000);
    }
    window.hideLoading ();
  }

  addPv ('Path', $('#id').val ());
  google.maps.event.addDomListener (window, 'load', initialize);
});