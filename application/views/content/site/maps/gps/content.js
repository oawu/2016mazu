/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  // var ;
  // var 
  // var 
  var _url1 = 'http://pic.mazu.ioa.tw/api/march/1/paths.json';
  



  var $map = $('#map'),
      $myLocation = $('#location'),
      $traffic = $('#traffic'),
      $body = $('body'),
      $container = $('#container'),
      $directionPanel = $('#direction_panel'),
      $travelMode = $directionPanel.find ('input[type="radio"][name="travel_mode"]'),
      $navigation = $('#navigation'),
      $length = $('#length'),
      _map = null,
      _fixZindexTimer = null,
      _myMarker = null,
      _trafficLayer = null,
      _overflow = $body.css ('overflow'),
      _times = [],
      _infos = [],
      _markers = [],
      _directionsDisplay = null,
      _isLoadPath = false,
      _c = 0,
      _cl = 36,
      _id = 0,
      _v = 0,
      _addresss = null,
      _polyline = null,
      _isMoved = false







  function circlePath (r) { return 'M 0 0 m -' + r + ', 0 '+ 'a ' + r + ',' + r + ' 0 1,0 ' + (r * 2) + ',0 ' + 'a ' + r + ',' + r + ' 0 1,0 -' + (r * 2) + ',0';}
  function setLoation (a, n) {
    // $.ajax ({url: _url4,data: { a: a, n: n },async: true, cache: false, dataType: 'json', type: 'POST'});
  }
  function fixZindex (t) {
    clearTimeout (_fixZindexTimer);
    _fixZindexTimer = setTimeout (function () { $('img[src$="mazu.png"]').parents ('.gmnoprint').css ({'opacity': 1}); }, t);
  }
  function initMap () {
    _map = new google.maps.Map ($map.get (0), { zoom: 16, zoomControl: true, scrollwheel: true, scaleControl: true, mapTypeControl: false, navigationControl: true, streetViewControl: false, disableDoubleClickZoom: true, center: new google.maps.LatLng (23.569396231491233, 120.3030703338623)});
    _map.mapTypes.set ('map_style', new google.maps.StyledMapType ([{ featureType: 'transit', stylers: [{ visibility: 'simplified' }] }, { featureType: 'poi', stylers: [{ visibility: 'simplified' }] }]));
    _map.setMapTypeId ('map_style');

    google.maps.event.addListener (_map, 'zoom_changed', function () {
      fixZindex (500);
      _map.zoom < 13 ? _map.zoom < 12 ? _map.zoom < 11 ? _times.forEach (function (t, i) { t.setMap (i % 4 ? null : _map); }) : _times.forEach (function (t, i) { t.setMap (i % 3 ? null : _map); }) : _times.forEach (function (t, i) { t.setMap (i % 2 ? null : _map); }) : _times.forEach (function (t, i) { t.setMap (_map); });
      _map.zoom < 13 ? _infos.forEach (function (t, i) { t.setMap (null); }) : _infos.forEach (function (t, i) { t.setMap (_map); });
    });
    google.maps.event.addListener (_map, 'dragend', function () { _isMoved = true; });
  }
  function initMazuPosition () {
    $('#position').click (function () {
      if (_markers.length > 0) _map.setCenter (_markers.last ().position);
    });
  }
  function initMyLocation () {
    $myLocation.click (function () {
      $(this).text ('定位中..');

      navigator.geolocation.getCurrentPosition (function (location) {
        $(this).text ('我的位置');

        if (!_myMarker) _myMarker = new google.maps.Marker ({ map: _map, draggable: false, optimized: false});
        _myMarker.setPosition (new google.maps.LatLng (location.coords.latitude, location.coords.longitude));
        _map.setCenter (new google.maps.LatLng (location.coords.latitude, location.coords.longitude));
        setLoation (location.coords.latitude, location.coords.longitude);
      }.bind ($(this)), function () { $(this).remove (); }.bind ($(this)));
    });
  }

  function initTrafficLayer () {
    $traffic.click (function () {
      if (!_trafficLayer) _trafficLayer = new google.maps.TrafficLayer ();

      if (!$(this).data ('isOn')) {
        _trafficLayer.setMap (_map);
        $(this).data ('isOn', true).text ('關閉路況');
      } else {
        _trafficLayer.setMap (null);
        $(this).data ('isOn', false).text ('開啟路況');
      }
    });
  }
  function coverBody (c, $obj) {
    if ($obj.hasClass (c)) {
      $obj.removeClass (c);
      // if ($body.hasClass ('f'))
        // $body.css ('overflow', _overflow);
    } else {
      $obj.addClass (c);
      // if ($body.hasClass ('f'))
        // $body.css ('overflow', 'hidden');
    }
  }
  function setDirection (origin) {
    if (origin) _directionsDisplay.mapZoom = _map.zoom;

    var request = {
      origin: _directionsDisplay.myLocation,
      destination: _markers.last ().position,
      travelMode: google.maps.TravelMode[$travelMode.filter (':checked').val ()],
    };
    
    new google.maps.DirectionsService ().route (request, function (response, status) {
      if (status != google.maps.DirectionsStatus.OK) { $navigation.text ('規劃失敗'); return ; }

      $navigation.text ('規劃完成');
      if (origin) coverBody ('dir', $container);
      _directionsDisplay.setMap (_map);
      _directionsDisplay.setDirections (response);
    });
  }
  function initNavigation () {
    _directionsDisplay = new google.maps.DirectionsRenderer ({ panel: $directionPanel.find ('.paths').get (0) });

    $navigation.click (function () {
      if (_markers.length < 1) {
        $(this).text ('規劃失敗');
        return ;
      }

      $(this).text ('定位中..');

      navigator.geolocation.getCurrentPosition (function (location) {
        $(this).text ('規劃中..');
        setLoation (location.coords.latitude, location.coords.longitude);
        _directionsDisplay.myLocation = new google.maps.LatLng (location.coords.latitude, location.coords.longitude);
        setDirection (_directionsDisplay.myLocation);
      }.bind ($(this)), function () { $(this).remove (); }.bind ($(this)));
    });

    $travelMode.change (function () {setDirection ();});
  }
  function initButtons () {
    $('#menu').click (function () { coverBody ('show', $container); });
    $('#chat').click (function () { coverBody ('msg', $container); });
    $('#chat_cover').click (function () { coverBody ('msg', $container); });
    $('#direction_cover').click (function () {
      coverBody ('dir', $container);
      _directionsDisplay.setMap (null);
      if (_markers.length > 0) _map.setCenter (_markers.last ().position);
      _map.setZoom (_directionsDisplay.mapZoom);
    });

    $('#zoom').click (function () {
      coverBody ('f', $body);
      google.maps.event.trigger (_map, 'resize');
    });

    var $btns = $('#btns');
    $btns.find ('input[type="checkbox"]').change (function () {
      if ($(this).prop ('checked')) $btns.addClass ('s');
      else $btns.removeClass ('s');
    });
    $('#add_zoom').click (function () { _map.setZoom (_map.zoom + 1); });
    $('#sub_zoom').click (function () { _map.setZoom (_map.zoom - 1); });
    $('#heatmap').click (function () {
      $(this).toggleClass ('s');
    });

    initMyLocation ();

    initMazuPosition ();

    initTrafficLayer ();

    initNavigation ();
  }

  function loadData (isFirst) {
    _isLoadPath = true;

    $.when ($.ajax (_url1 + '?t=' + new Date ().getTime (), {dataType: 'json'})).done (function (result) {
      _isLoadPath = false;
      if (++_c > _cl) return location.reload ();
      if (_v === 0) _v = result.v;
      if (_v != result.v) return location.reload ();
      if (!result.s) { if (isFirst) window.hideLoading (); return ; }

      var latlngs = result.p.filter (function (t) { return t.i > _id; }).map (function (t) { return {id: t.i, lat: t.a, lng: t.n, time: t.t}; });
      if (!latlngs.length) { if (isFirst) window.hideLoading (); return ; }

      if (result.l > 0) $length.html (result.l);

      _id = latlngs.last ().id;
      
      if (_markers.length) _markers.last ().setIcon ({ path: circlePath (4), strokeColor: 'rgba(249, 39, 114, 1)', strokeWeight: 1, fillColor: 'rgba(249, 39, 114, .8)', fillOpacity: 0.5 });

      _markers = _markers.concat (latlngs.map (function (t, i) {
        if ((i % 5 === 0) && (i !== latlngs.length - 1)) _times.push (new MarkerWithLabel ({ position: new google.maps.LatLng (t.lat, t.lng), draggable: false, raiseOnDrag: true, map: _map, labelContent: '' + $.timeago (t.time), labelAnchor: new google.maps.Point (0, 0), labelClass: 'time', icon: {path: 'M 0 0'} }));
        return new google.maps.Marker ({ map: _map, zIndex: t.id, draggable: false, optimized: false, position: new google.maps.LatLng (t.lat, t.lng), icon: i == latlngs.length - 1 ? 'img/mazu.png' : { path: circlePath (4), strokeColor: 'rgba(255, 68, 170, 1)', strokeWeight: 1, fillColor: 'rgba(255, 68, 170, 1)', fillOpacity: 0.5 } });
      }));

      new google.maps.Geocoder ().geocode ({'latLng': _markers.last ().position}, function (result, status) {
        if (!((status == google.maps.GeocoderStatus.OK) && result.length && (result = result[0]) && result.formatted_address))
          return;

        if(!_addresss) _addresss = new MarkerWithLabel ({ position: _markers.last ().position, draggable: false, raiseOnDrag: true, map: _map, labelContent: '', labelAnchor: new google.maps.Point (0, 0), labelClass: 'address', icon: {path: 'M 0 0'} });
        _addresss.labelContent = result.formatted_address;
        _addresss.setPosition (_markers.last ().position);
      });

      if (!_polyline) _polyline = new google.maps.Polyline ({ map: _map, strokeColor: 'rgba(249, 39, 114, .45)', strokeWeight: 5 });
      _polyline.setPath (_markers.map (function (t) { return t.position; }));

      
      _infos.forEach (function (t) { t.setMap (null); });
      _infos = [];
      _infos = result.i.map (function (t, i) { return new MarkerWithLabel ({ map: _map, zIndex: i, draggable: false, raiseOnDrag: false, clickable: false, optimized: false, labelContent: '<div class="c"><div>' + t.m.map (function (u) {return '<span>' + u + '</span>';}).join ('') + '</div></div><div class="b"></div>', labelAnchor: new google.maps.Point (130 / 2, 37 + 20 - 4 + (t.m.length - 1) * 23), labelClass: 'i ' + 'n' + t.m.length, icon: {path: 'M 0 0'}, position: new google.maps.LatLng (t.a, t.n) }); });
      
      fixZindex (2000);

      if (isFirst) {
        _map.setCenter (_markers.last ().position);
        if (!_isMoved) mapGo (_map, _markers.last ().position);
        // initHeatmap ();
        // if (getStorage (_storage_key3)) $s.click ();
        window.hideLoading ();
      }
    });
  }
  function initialize () {
    // _markers.push (new google.maps.LatLng (23.569396231491233, 120.3030703338623));
    initMap ();
    initButtons ();
    loadData (true);
    // setInterval (loadData, _loadDataTime);

    // navigator.geolocation.getCurrentPosition (function (location) { setLoation (location.coords.latitude, location.coords.longitude); }, function () {});
  }
  google.maps.event.addDomListener (window, 'load', initialize);

});