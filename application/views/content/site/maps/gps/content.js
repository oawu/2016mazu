/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
$(function () {
  // var ;
  // var 
  // var 
  var _url1 = 'http://pic.mazu.ioa.tw/api/march/1/paths.json';
  var _url2 = 'http://pic.mazu.ioa.tw/api/march/messages.json';
  var _url3 = $('#url3').val ();
  var _url4 = $('#url4').val ();


  var $map = $('#map'),
      $myLocation = $('#location'),
      $traffic = $('#traffic'),
      $body = $('body'),
      $container = $('#container'),
      $directionPanel = $('#direction_panel'),
      $travelMode = $directionPanel.find ('input[type="radio"][name="travel_mode"]'),
      $navigation = $('#navigation'),
      $length = $('#length'),
      $chatPanel = $('#chat_panel'),
      $chatPanelBottom = $chatPanel.find ('.bottom'),
      _map = null,
      _myMarker = null,
      _trafficLayer = null,
      _overflow = $body.css ('overflow'),
      _times = [],
      _infos = [],
      _markers = [],
      _directionsDisplay = null,
      _isLoadPath = false,
      _p = 0,
      _pl = 36,
      _v = 0,
      _addresss = null,
      _polyline = null,
      _isMoved = false,
      _mazu = null,
      _loadDataTime = 10000,
      _chartInfos = ['※ 別亂檢舉，偏激或令人不悅的內容再檢舉，這是非官方而且非營利網站，請幫忙分享出去給更多需要的人吧！'],
      _chartTimer = null,
      _chartTime = 5000,
      _isLoadChart = false,
      _chartId = 0,
      _c = 0,
      _cp = 30;









  function circlePath (r) { return 'M 0 0 m -' + r + ', 0 '+ 'a ' + r + ',' + r + ' 0 1,0 ' + (r * 2) + ',0 ' + 'a ' + r + ',' + r + ' 0 1,0 -' + (r * 2) + ',0';}
  function setLoation (a, n) {
    // $.ajax ({url: _url4,data: { a: a, n: n },async: true, cache: false, dataType: 'json', type: 'POST'});
  }

  function initMap () {
    _map = new google.maps.Map ($map.get (0), { zoom: 16, zoomControl: true, scrollwheel: true, scaleControl: true, mapTypeControl: false, navigationControl: true, streetViewControl: false, disableDoubleClickZoom: true, center: new google.maps.LatLng (23.569396231491233, 120.3030703338623)});
    _map.mapTypes.set ('map_style', new google.maps.StyledMapType ([{ featureType: 'transit', stylers: [{ visibility: 'simplified' }] }, { featureType: 'poi', stylers: [{ visibility: 'simplified' }] }]));
    _map.setMapTypeId ('map_style');

    google.maps.event.addListener (_map, 'zoom_changed', function () {
      // _map.zoom < 13 ? _map.zoom < 12 ? _map.zoom < 11 ? _times.forEach (function (t, i) { t.setMap (i % 4 ? null : _map); }) : _times.forEach (function (t, i) { t.setMap (i % 3 ? null : _map); }) : _times.forEach (function (t, i) { t.setMap (i % 2 ? null : _map); }) : _times.forEach (function (t, i) { t.setMap (_map); });
      // _map.zoom < 13 ? _infos.forEach (function (t, i) { t.setMap (null); }) : _infos.forEach (function (t, i) { t.setMap (_map); });
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
  function black () {
    if (!confirm ('確定檢舉？'))
      return false;
    $.ajax ({ url: _url4, data: { id: $(this).data ('id'), }, async: true, cache: false, dataType: 'json', type: 'POST', beforeSend: function () { $(this).parent ('div').remove (); }.bind ($(this))}).done (function (result) {}).fail (function () {}).complete (function () {});
  }
  function initChart (m) {
    return $('<div />').append ($('<span />').addClass (m.a ? 'icon-user' : 'icon-user2')).append ($('<span />').text (m.m)).append ($('<a />').data ('id', m.d).text ('檢舉').click (black)).append ($('<div />').text (m.i)).append ($('<div />').text ($.timeago (m.t)));
  }
  function loadCharts (isFirst) {
    if (_isLoadChart) return ;
    _isLoadChart = true;

    $.when ($.ajax (_url2 + '?t=' + new Date ().getTime (), {dataType: 'json'})).done (function (result) {
      _isLoadChart = false;
      if (++_p > _pl) return location.reload ();
      if (!result.s) return;
      result.m = result.m.filter (function (t) {
        return t.d > _chartId;
      });
      if (result.m.length < 1) return;

      if (_chartId === 0) $chatPanelBottom.empty ().append (result.m.map (initChart));
      else $chatPanelBottom.prepend (result.m.map (initChart));

      _chartId = result.m.first ().d;
    });
  }
  function initButtons () {
    $('#menu').click (function () { coverBody ('show', $container); });
    
    $chatPanelBottom.attr ('data-infos', _chartInfos).empty ();
    $('#chat').click (function () {
      _isLoadChart = false;
      _p = _chartId = 0;
      loadCharts (true);
      _chartTimer = setInterval (loadCharts.bind (null), _chartTime);
      $chatPanelBottom.empty ();
      coverBody ('msg', $container);
    });
    $('#chat_cover').click (function () {
      coverBody ('msg', $container);
      _isLoadChart = true;
      _p = _chartId = 0;
      clearInterval (_chartTimer);
      $chatPanelBottom.empty ();
    });
    $('#send').click (function () {
      var $meg = $('#msg');
      var val = $meg.val ().trim ();
      if (val.length < 1) return;

      $.ajax ({ url: _url3, data: {
        msg: val
        }, async: true, cache: false, dataType: 'json', type: 'POST',
        beforeSend: function () {
          $(this).prop ('disabled', true).text ('發佈中..');
          $meg.prop ('disabled', true);
        }.bind ($(this))})
      .done (function (result) {
        $(this).prop ('disabled', false).text ('確定送出');
        $meg.prop ('disabled', false).val ('');
        if (result.s) loadCharts (false);
      }.bind ($(this)))
      .fail (function () {})
      .complete (function () {});
    });

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
    if (_isLoadPath) return;
    _isLoadPath = true;

    $.when ($.ajax (_url1 + '?t=' + new Date ().getTime (), {dataType: 'json'})).done (function (result) {
      _isLoadPath = false;
      if (++_p > _pl) return location.reload ();
      if (_v === 0) _v = result.v;
      if (_v != result.v) return location.reload ();
      if (!result.s) { if (isFirst) window.hideLoading (); return ; }
      if (!result.p.length) { if (isFirst) window.hideLoading (); return ; }

      if (result.l > 0) $length.html (result.l);

      _times.forEach (function (t, i) { t.setMap (null); });
      _infos.forEach (function (t) { t.setMap (null); });
      _times = _markers = _infos = [];

      _markers = result.p.map (function (t, i) {
        return {
          timeString: $.timeago (t.t),
          position: new google.maps.LatLng (t.a, t.n)
        };
      });

      if (!_polyline) _polyline = new google.maps.Polyline ({ map: _map, strokeColor: 'rgba(249, 39, 114, .45)', strokeWeight: 5 });
      _polyline.setPath (_markers.map (function (t) { return t.position; }));

      if (!_mazu) _mazu = new MarkerWithLabel ({ map: _map, draggable: false, optimized: false, labelContent: '<img src="' + result.c + '" />', icon: {path: 'M 0 0'}, labelAnchor: new google.maps.Point (40 / 2, 70), labelClass: 'mazu_icon'});
      _mazu.setPosition (_markers.last ().position);
      _mazu.setZIndex (999);

      var u = parseInt (_markers.length / 10, 10);
      _times = _markers.map (function (t, i) { return i % u ? null : new MarkerWithLabel ({position: t.position, draggable: false, map: _map, zIndex: 1, icon: { path: circlePath (3), strokeColor: 'rgba(255, 68, 170, 1)', strokeWeight: 1, fillColor: 'rgba(255, 68, 170, 1)', fillOpacity: 0.5 }, labelContent: t.timeString, labelAnchor: new google.maps.Point (-5, -5), labelClass: 'time'});}).filter (function (t) { return t; });

      _infos = result.i.map (function (t, i) { return new MarkerWithLabel ({ map: _map, zIndex: i, draggable: false, raiseOnDrag: false, clickable: false, optimized: false, labelContent: '<div class="c"><div>' + t.m.map (function (u) {return '<span>' + u + '</span>';}).join ('') + '</div></div><div class="b"></div>', labelAnchor: new google.maps.Point (130 / 2, 37 + 20 - 4 + (t.m.length - 1) * 23), labelClass: 'i ' + 'n' + t.m.length, icon: {path: 'M 0 0'}, position: new google.maps.LatLng (t.a, t.n) }); });

      new google.maps.Geocoder ().geocode ({'latLng': _markers.last ().position}, function (result, status) {
        if (!((status == google.maps.GeocoderStatus.OK) && result.length && (result = result[0]) && result.formatted_address))
          return;

        if(!_addresss) _addresss = new MarkerWithLabel ({ position: _markers.last ().position, draggable: false, map: _map, labelContent: '', labelAnchor: new google.maps.Point (10, -5), labelClass: 'address', icon: {path: 'M 0 0'} });
        _addresss.labelContent = result.formatted_address;
        _addresss.setPosition (_markers.last ().position);
      });


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
    initMap ();
    initButtons ();
    loadData (true);
    setInterval (loadData, _loadDataTime);
  }
  google.maps.event.addDomListener (window, 'load', initialize);

});