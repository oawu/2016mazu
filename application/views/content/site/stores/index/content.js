/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

function fancy (url) {
  $.fancybox ({
      href: url,
      type: 'iframe',
      padding: 0,
      margin: 30,
      width: '100%',
      maxWidth: '800',
  });
}
$(function () {
  var $body = $('body');
  var $map = $('#map');
  var _map = null;
  var _info = null;
  var _infos = [];

  var $url = $('#url');

  if ($url.length)
    fancy ($url.val ());

  function info (i) {
    if (!i) return '';
    return '<div class="c"><div><img src="' + i.o + '"/><span>' + i.t + '</span></div><div onClick="fancy(\'' + i.u + '\');">詳細介紹..</div></div><div class="b"></div>';
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
    fancy (this.t.u);
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

    _info = new MarkerWithLabel ({
        draggable: false,
        raiseOnDrag: false,
        clickable: true,
        zIndex: 999,
        labelZIndex: 2,
        optimized: false,
        labelContent: info (),
        labelAnchor: new google.maps.Point (230 / 2, 155 + 20 - 4),
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
    // google.maps.event.addListener (_map, 'click', function (e) {
    //   google.maps.event.trigger (_info, 'click');
    // });

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

    window.hideLoading ();
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});