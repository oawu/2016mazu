/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  window.$body = $('body');
  window.$map = $('#map');
  window.$length = $('#length');

  function formatFloat (num, pos) {
    var size = Math.pow (10, pos);
    return Math.round (num * size) / size;
  }
  function calculateLength (points) {
    if (google.maps.geometry.spherical)
      window.$length.html (formatFloat (google.maps.geometry.spherical.computeLength (points) / 1000, 2));
  }
  
  function info (i) {
    if (!i) return '';
    return '<div class="c"><div><img src="' + i.o + '"/><span>' + i.t + '</span></div><div>' + i.c + '</div></div><div class="b"></div>';
  }
  function infosClickAction () {
    if (window.infoMarker.lastMarker)
      window.infoMarker.lastMarker.setMap (window.map);
    window.infoMarker.setPosition (this.getPosition ());

    window.infoMarker.labelContent = info (this.t);
    this.setMap (null);
    window.infoMarker.setMap (window.map);
    window.infoMarker.lastMarker = this;
    mapGo (window.map, window.infoMarker.getPosition ());
  }
  function loop (i) {
    i = (i !== undefined) && ((i + 1) < window.points.length) ? i + 1 : 0;

    clearTimeout (window.timer);
    window.timer = setTimeout (function () {
      if (!window.isMoved && !(i % 10)) mapGo (window.map, window.points[i]);
      markerGo (window.mazu, window.points[i], loop (i));
    }, 250);
  }
  function initialize () {
    window.map = new google.maps.Map (window.$map.get (0), {
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

    window.map.mapTypes.set ('map_style', new google.maps.StyledMapType ([
      { featureType: 'transit', stylers: [{ visibility: 'simplified' }] },
      { featureType: 'poi', stylers: [{ visibility: 'simplified' }] },
    ]));
    window.map.setMapTypeId ('map_style');
    
    window.points = window.$map.data ('polyline').map (function (t) {
        var position = new google.maps.LatLng (t.a, t.n);
        return position;
      });

    window.$map.data ('change').forEach (function (t) {
      if (t.p.length < 1) return ;
      
      new MarkerWithLabel ({
        map: window.map, draggable: false, optimized: false,
        labelContent: '<div><div>' + t.t + '</div></div><div></div>',
        icon: {path: 'M 0 0'},
        labelAnchor: new google.maps.Point (100 / 2, 30 + 15),
        labelClass: 'd',
        position: new google.maps.LatLng (t.p.first ()[0], t.p.first ()[1])
      });

      new google.maps.Polyline ({
        map: window.map,
        strokeColor: 'rgba(255, 3, 0, .2)',
        strokeWeight: 4,
        path: t.p.map (function (u) {
          return new google.maps.LatLng (u[0], u[1]);
        })
      });
    });

    window.infoMarker = new MarkerWithLabel ({
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

    google.maps.event.addListener (window.infoMarker, 'click', function (e) {
      if (this.lastMarker) this.lastMarker.setMap (window.map);
      this.setMap (null);
      this.lastMarker = null;
    });
    
    window.$map.data ('infos').map (function (t) {
      var position = new google.maps.LatLng (t.a, t.n);
      var m = new google.maps.Marker ({
          map: window.map,
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
      if (!window.$body.hasClass ('f')) {
        window.$body.addClass ('f');
        $(this).attr ('class', 'icon-shrink');
      } else {
        window.$body.removeClass ('f');
        $(this).attr ('class', 'icon-enlarge');
      }
      google.maps.event.trigger (window.map, 'resize');
    });

    var $container = $('#container');
    var overflow = window.$body.css ('overflow');

    $('#menu').click (function () {
      if ($container.hasClass ('show')) {
        $container.removeClass ('show');
        window.$body.css ('overflow', overflow);
      } else {
        $container.addClass ('show');
        window.$body.css ('overflow', 'hidden');
      }
    });

    if (window.points.length) {
      new google.maps.Polyline ({
        map: window.map,
        strokeColor: 'rgba(255, 3, 0, .6)',
        strokeWeight: 3,
        path: window.points
      });
      window.mazu = new MarkerWithLabel ({
          position: window.points[0],
          draggable: false,
          raiseOnDrag: false,
          clickable: true,
          zIndex: 99,
          labelZIndex: 2,
          optimized: false,
          labelContent: '<div><img src="' + window.$map.data ('icon') + '" /></div>',
          labelAnchor: new google.maps.Point (20, 70),
          labelClass: 'mazu',
          icon: {path: 'M 0 0'},
          map: window.map,
          initCallback: function (t) {}
        });
      google.maps.event.addListener (window.map, 'click', function () {
        new google.maps.event.trigger (window.infoMarker, 'click');
      });
      google.maps.event.addListener (window.map, 'dragstart', function () {
        new google.maps.event.trigger (window.infoMarker, 'click');
        window.isMoved = true;
      });
      google.maps.event.addListener (window.map, 'zoom_changed', function () {
        window.isMoved = true;
      });
      $('#add_zoom').click (function () { window.map.setZoom (window.map.zoom + 1); });
      $('#sub_zoom').click (function () { window.map.setZoom (window.map.zoom - 1); });
      setTimeout (loop, 1000);
    }
    window.hideLoading ();
  }

  addPv ('Path', $('#id').val ());
  google.maps.event.addDomListener (window, 'load', initialize);
});