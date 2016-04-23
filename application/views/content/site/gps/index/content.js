/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

function getStorage (key) { if ((typeof (Storage) !== 'undefined') && (last = localStorage.getItem (key)) && (last = JSON.parse (last))) return last; else return; }
function setStorage (key, data) { if (typeof (Storage) !== 'undefined') { localStorage.setItem (key, JSON.stringify (data)); }}

$(function () {
  window.url = {
    load_path: 'http://pic.mazu.ioa.tw/api/path/',
    set_location: $('#_url_set_location').val (),
    load_markers: 'http://pic.mazu.ioa.tw/api/march/gps.json',
    load_messages: 'http://pic.mazu.ioa.tw/api/march/messages.json',
    report: $('#_url_report').val (),
    send_message: $('#_url_send_message').val ()
  };

  function coverBody (c, $obj) {
    if ($obj.hasClass (c)) $obj.removeClass (c);
    else $obj.addClass (c);
  }
  function initMap ($map) {
    $map.get (0)._map = new google.maps.Map ($map.get (0), { zoom: 16, zoomControl: true, scrollwheel: true, scaleControl: true, mapTypeControl: false, navigationControl: true, streetViewControl: false, disableDoubleClickZoom: true, center: new google.maps.LatLng (23.569396231491233, 120.3030703338623)});
    $map.get (0)._map.mapTypes.set ('map_style', new google.maps.StyledMapType ([{ featureType: 'transit', stylers: [{ visibility: 'simplified' }] }, { featureType: 'poi', stylers: [{ visibility: 'simplified' }] }]));
    $map.get (0)._map.setMapTypeId ('map_style');
    google.maps.event.addListener ($map.get (0)._map, 'dragend', function () { $map.get (0)._isMoved = true; });

    $map.get (0).v = 0;
    $map.get (0).p = 0;
    $map.get (0).pl = 15;
    $map.get (0).loadDataTime = 65 * 1000;
    $map.get (0).z = 999;
  }
  function loadPath ($map) {
    if ($('#_path_id').val () > 0)
      $.ajax ({url: window.url.load_path + $('#_path_id').val () + '.json', async: true, cache: false, dataType: 'json', type: 'GET'})
        .done (function (r) {
          if (!(r && r.length)) return;
          $map.get (0).pathPolyline = new google.maps.Polyline ({
            map: $map.get (0)._map,
            strokeColor: 'rgba(101, 216, 238, .4)',
            strokeWeight: 8,
            path: r.map (function (p) { return new google.maps.LatLng (p.a, p.n); })
          });
        });
  }
  function loadMarkers ($map, isFirst) {
    if (window.isLoadMarkers) return ;
    window.isLoadMarkers = true;

    $.when ($.ajax (window.url.load_markers + '?t=' + new Date ().getTime (), {dataType: 'json'})).done (function (result) {
      window.isLoadMarkers = false;
      
      if ($map.get (0).v === 0) $map.get (0).v = result.v;
      if ($map.get (0).v != result.v) return location.reload ();
      if (++$map.get (0).p > $map.get (0).pl) return location.reload ();
      if ($map.get (0).markers)
        $map.get (0).markers.forEach (function (t) {
          if (t.polyline) t.polyline.setMap (null);
          if (t.marker) t.marker.setMap (null);
        });


      $map.get (0).markers = result.m.map (function (m) {
        var p = m.p.first ();

        return {
          id: m.i,
          title: m.n,
          time: m.t,
          marker: new MarkerWithLabel ({
              map: $map.get (0)._map, draggable: false, optimized: false,
              position: p ? new google.maps.LatLng (p.a, p.n) : new google.maps.LatLng (23.5676650690051, 120.30458718538284),
              icon: {path: 'M 0 0'},
              zIndex: 999 - m.i,

              labelAnchor: !m.c.length ? new google.maps.Point (100 / 2, 35 + 15) : new google.maps.Point (40 / 2, 70),
              labelContent: !m.c.length ? '<div><div>' + m.n + '</div></div><div></div>' : '<img src="' + m.c + '" /><div>' + m.n + '</div><time>' + $.timeago (m.t) + '</time>',
              labelClass: !m.c.length ? 'd' : 'm',
            }),
          polyline: new google.maps.Polyline ({
              map: $map.get (0)._map,
              strokeColor: 'rgba(249, 39, 114, .45)',
              strokeWeight: 5,
              path: m.p.map (function (t) { return new google.maps.LatLng (t.a, t.n); })
            }),
        };
      });

      if (isFirst) {
        var bounds = null;
        $map.get (0).markers.column ('marker').column ('position').forEach (function (t) { if (!bounds) bounds = new google.maps.LatLngBounds (); bounds.extend (t); });
        $map.get (0)._map.fitBounds (bounds);
        initDintaoSelect ($map);
        window.hideLoading ();
      }
    });
  }
  function setLoation (a, n) {
    $.ajax ({url: window.url.set_location,data: { a: a, n: n },async: true, cache: false, dataType: 'json', type: 'POST'});
  }
  function initMyLocation ($map) {
    $('#location').click (function () {
      $(this).text ('定位中..');

      navigator.geolocation.getCurrentPosition (function (location) {
        $(this).text ('我的位置');

        if (!$map.get (0).myMarker)
          $map.get (0).myMarker = new MarkerWithLabel ({
              map: $map.get (0)._map, draggable: false, optimized: false,
              labelContent: '<div><div>我的位置</div></div><div></div>',
              icon: {path: 'M 0 0'},
              labelAnchor: new google.maps.Point (100 / 2, 35 + 15),
              labelClass: 'd',
              zIndex: 9999,
            });

        $map.get (0).myMarker.setPosition (new google.maps.LatLng (location.coords.latitude, location.coords.longitude));
        $map.get (0)._map.setCenter (new google.maps.LatLng (location.coords.latitude, location.coords.longitude));
        setLoation (location.coords.latitude, location.coords.longitude);
      }.bind ($(this)), function () { $(this).remove (); }.bind ($(this)));
    });
  }

  function initTrafficLayer ($map) {
    $('#traffic').click (function () {
      if (!$map.get (0).trafficLayer) $map.get (0).trafficLayer = new google.maps.TrafficLayer ();

      if (!$(this).data ('isOn')) {
        $map.get (0).trafficLayer.setMap ($map.get (0)._map);
        $(this).data ('isOn', true).text ('關閉路況');
      } else {
        $map.get (0).trafficLayer.setMap (null);
        $(this).data ('isOn', false).text ('開啟路況');
      }
    });
  }

  function initDintaoSelect ($map) {
    $('#marches').empty ()
                 .attr ('class', 'n' + ($map.get (0).markers.length + 1))
                 .append ($('<span />').text ('所有陣頭位置'))
                 .append ($('<div />').append ($map.get (0).markers.map (function (t) {
                   return $('<a />').attr ('val', t.id).text (t.title + ' 目前位置');
                 })).append ($('<a />').text ('所有陣頭位置').addClass ('a')))
                 .click (function () {
                   $(this).toggleClass ('s');
                 }).on ('click', 'a', function () {
                   var $span = $(this).parents ('label').find ('span').text ($(this).text ());
                   $(this).addClass ('a').siblings ().removeClass ('a');
                     var bounds = null;
                     if (!$(this).attr ('val')) {
                       $map.get (0).markers.column ('marker').column ('position').forEach (function (t) { if (!bounds) bounds = new google.maps.LatLngBounds (); bounds.extend (t); });
                     } else  {
                       var m = $map.get (0).markers.find ('id', parseInt ($(this).attr ('val'), 10));
                       if (m) {
                         bounds = new google.maps.LatLngBounds ();
                         bounds.extend (m.marker.position);
                         m.marker.zIndex = $map.get (0).z;
                         $map.get (0).z++;
                         console.error (m.marker.zIndex);
                       }
                     }
                     if (bounds) $map.get (0)._map.fitBounds (bounds);
                 });
  }
  function initButtons ($map, $body, $container) {
    $('#menu').click (function () { coverBody ('show', $container); });
    $('#zoom').click (function () { coverBody ('f', $body); google.maps.event.trigger ($map.get (0)._map, 'resize'); });
    $('#add_zoom').click (function () { $map.get (0)._map.setZoom ($map.get (0)._map.zoom + 1); });
    $('#sub_zoom').click (function () { $map.get (0)._map.setZoom ($map.get (0)._map.zoom - 1); });
  }

  function report () {
    if (!confirm ('確定檢舉？')) return false;
    $.ajax ({ url: window.url.report, data: { id: $(this).data ('id'), }, async: true, cache: false, dataType: 'json', type: 'POST', beforeSend: function () { $(this).parent ('div').remove (); }.bind ($(this))}).done (function (result) {}).fail (function () {}).complete (function () {});
  }
  function renderMessage (m) {
    return $('<div />').append ($('<span />').addClass (m.a ? 'icon-user' : 'icon-user2')).append ($('<span />').text (m.m)).append ($('<a />').data ('id', m.d).text ('檢舉').click (report)).append ($('<div />').text (m.i)).append ($('<div />').text ($.timeago (m.t)));
  }
  function loadMessages (isFirst, $message, $messagePanelBottom) {
    if (window.isLoadMessages) return ;
    window.isLoadMessages = true;

    $.when ($.ajax (window.url.load_messages + '?t=' + new Date ().getTime (), {dataType: 'json'})).done (function (result) {
      window.isLoadMessages = false;

      if (++$message.get (0).p > $message.get (0).pl) return location.reload ();
      if (!result.s) return;

      result.m = result.m.filter (function (t) { return t.d > $message.get (0).messageId; });
      if (result.m.length < 1) return;

      if ($message.get (0).messageId === 0) $messagePanelBottom.empty ().append (result.m.map (renderMessage));
      else $messagePanelBottom.prepend (result.m.map (renderMessage));

      $message.get (0).messageId = result.m.first ().d;
    });
  }

  function initMessage ($container, $message, $messagePanelBottom) {
    $messagePanelBottom.attr ('data-infos', '※ 此服務非官方，活動資訊一切以官方為主，內容偏激或令人不悅的內容再檢舉！').empty ();
    
    $message.click (function () {
      window.isLoadMessages = false;

      $(this).get (0).messageId = 0;
      $(this).get (0).p = 0;
      $(this).get (0).pl = 100;
      $(this).get (0).loadDataTime = 10 * 1000;

      $messagePanelBottom.empty ();

      loadMessages (true, $message, $messagePanelBottom);
      $(this).get (0).messageTimer = setInterval (loadMessages.bind (this, false, $message, $messagePanelBottom), $(this).get (0).loadDataTime);

      coverBody ('msg', $container);
      setStorage ('_HAS_OPEN_MESSAGES', true);
    });

    $('#message_cover').click (function () {
      window.isLoadMessages = true;
      coverBody ('msg', $container);
      
      $message.get (0).messageId = 0;
      $message.get (0).p = 0;
      $messagePanelBottom.empty ();

      clearInterval ($message.get (0).messageTimer);
      setStorage ('_HAS_OPEN_MESSAGES', false);
    });

    $('#send').click (function () {
      var $meg = $('#msg');
      var msg = $meg.val ().trim ();
      
      if (msg.length < 1) return;

      $.ajax ({
        url: window.url.send_message, data: { msg: msg }, async: true, cache: false, dataType: 'json', type: 'POST',
        beforeSend: function () { $(this).prop ('disabled', true).text ('發佈中..'); $meg.prop ('disabled', true); }.bind ($(this))})
      .done (function (result) {
        if (result.s) loadMessages (false, $message, $messagePanelBottom);

        $(this).prop ('disabled', false).text ('確定送出');
        $meg.prop ('disabled', false).val ('');
      }.bind ($(this)))
      .fail (function () {})
      .complete (function () {});
    });
  }

  google.maps.event.addDomListener (window, 'load', function () {
    var $map = $('#map'),
        $body = $('body'),
        $container = $('#container'),
        $message = $('#message'),
        $messagePanel = $('#message_panel'),
        $messagePanelBottom = $messagePanel.find ('.bottom');

    initMap ($map);
    initButtons ($map, $body, $container);
    initMessage ($container, $message, $messagePanelBottom);

    loadMarkers ($map, true);
    setInterval (loadMarkers.bind (this, $map, false), $map.get (0).loadDataTime);

    initMyLocation ($map);
    initTrafficLayer ($map);
    

    if (getStorage ('_HAS_OPEN_MESSAGES')) $message.click ();
    
    loadPath ($map);
  });
});
