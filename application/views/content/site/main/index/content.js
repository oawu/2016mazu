/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  function conv_time_unit (sec) {
    var msec = sec * 1000,
        days = Math.floor (msec / (24 * 3600 * 1000)),
        leave1 = msec % (24 * 3600 * 1000),
        hours = Math.floor (leave1 / (3600 * 1000)),
        leave2 = leave1 % (3600 * 1000),
        minutes = Math.floor (leave2 / (60 * 1000)),
        leave3 = leave2 % (60 * 1000),
        seconds = Math.round (leave3 / 1000);
    var strs = [];
    strs.push (days > 0 ? days + '天' : null);
    strs.push (hours > 0 ? hours + '小時' : null);
    strs.push (minutes > 0 ? minutes + '分' : null);
    strs.push (seconds > 0 ? seconds + '秒' : null);

    return strs.filter (function (t) {
      return t;
    });
  }
  var $cs = $('#c span'), cc = function () {
    var strs = conv_time_unit ($cs.data ('end') - new Date ().getTime () / 1000);
    if (strs.length) $cs.html ('還剩 <b>' + strs.join ('</b><b>')  + '</b>');
    else $cs.html ($cs.data ('day') > -3 ? '正在開始，北港的鄉親們一起迎熱鬧吧' : '已經圓滿落幕，讓我們再準備倒數下一次三月十九吧！');
  };
  cc (); setInterval (cc, 1000);

  $('.scroll').OAmobileScrollView ({
    trigger_length: 30
  }).find ('a').imgLiquid ({verticalAlign: 'center'});

  var $map = $('#map');
  var _map = null;
  var _polyline = null;
  var _mazu = null;
  var _timer = null;
  var _map_load = false;
  
  function loop (i) {
    i = (i !== undefined) && ((i + 1) < _points.length) ? i + 1 : 0;

    clearTimeout (_timer);
    _timer = setTimeout (function () {
      if (!(i % 10)) mapGo (_map, _points[i]);
      markerGo (_mazu, _points[i], loop (i));
    }, 150);
  }
  function initialize () {
    _map = new google.maps.Map ($map.get (0), {
        zoom: 16,
        draggable: false,
        zoomControl: false,
        scrollwheel: false,
        scaleControl: false,
        mapTypeControl: false,
        navigationControl: false,
        streetViewControl: false,
        disableDoubleClickZoom: false,
        center: new google.maps.LatLng (23.569396231491233, 120.3030703338623),
      });
    _map.mapTypes.set ('map_style', new google.maps.StyledMapType ([{ featureType: 'transit', stylers: [{ visibility: 'simplified' }] },{ featureType: 'poi', stylers: [{ visibility: 'simplified' }] }]));
    _map.setMapTypeId ('map_style');
    _points = $map.data ('polyline').map (function (t) { return new google.maps.LatLng (t.a, t.n); });

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
      setTimeout (loop, 1000);
    }

  }

  $(window).scroll (function () {
    if (_map_load && ($map.data ('has_loaded') || ($(this).scrollTop () + $(this).height () < $map.offset ().top)))
      return;
    $map.data ('has_loaded', true);

    initialize ();
  });

  var $food = $('#food');
  var _food = null;
  var _info = null;

  function info (i) {
    if (!i) return '';
    return '<div class="c"><div><img src="' + i.o + '"/><span>' + i.t + '</span></div><div>' + i.c + '</div></div><div class="b"></div>';
  }
  function initialize_food () {
    _food = new google.maps.Map ($food.get (0), {
        zoom: 16,
        draggable: false,
        zoomControl: false,
        scrollwheel: false,
        scaleControl: false,
        mapTypeControl: false,
        navigationControl: false,
        streetViewControl: false,
        disableDoubleClickZoom: false,
        center: new google.maps.LatLng ($food.data ('store').a + 0.0015, $food.data ('store').n)
      });
    _food.mapTypes.set ('map_style', new google.maps.StyledMapType ([{ featureType: 'transit', stylers: [{ visibility: 'simplified' }] }, { featureType: 'poi', stylers: [{ visibility: 'simplified' }] }]));
    _food.setMapTypeId ('map_style');

    _info = new MarkerWithLabel ({
        draggable: false,
        raiseOnDrag: false,
        clickable: false,
        optimized: false,
        labelContent: info ($food.data ('store')),
        labelAnchor: new google.maps.Point (230 / 2, 170 + 20 - 4),
        labelClass: 'info',
        icon: {path: 'M 0 0'},
        map: _food,
        position: new google.maps.LatLng ($food.data ('store').a, $food.data ('store').n)
      });
  }

  $(window).scroll (function () {

    if (_map_load && ($food.data ('has_loaded') || ($(this).scrollTop () + $(this).height () < $food.offset ().top)))
      return;
    $food.data ('has_loaded', true);
    
    initialize_food ();
  });


  google.maps.event.addDomListener (window, 'load', function () {
    _map_load = true;
  });

  window.hideLoading ();
});