/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {

  var $tdms = $('td.sm');
  var $mam = $tdms.find ('.mam');

  var $fmm = function (i, t, h) {
    return $('<div />').addClass ('mm').append (
      $('<div />').append ($('<a />').addClass ('icon-triangle-up').click (function () {
        var $p = $(this).parents ('.mm');
        $p.clone (true).insertBefore ($p.index () > 0 ? $p.prev () : mam);
        $p.remove ();
      })).append ($('<a />').addClass ('icon-triangle-down2').click (function () {
        var $p = $(this).parents ('.mm'), $x = $p.next (), $n = $p.clone (true);
        if ($x.hasClass ('mam')) $n.prependTo ($tdms);
        else $n.insertAfter ($x);
        $p.remove ();
      }))
    ).append (
      $('<input />').attr ('type', 'text').attr ('name', 'messages[' + i + ']').attr ('placeholder', '請輸入資訊..').attr ('mamxlength', 200).val (t ? t : '')
    ).append (
      $('<button />').attr ('type', 'button').addClass ('icon-bin').click (function () {
        $(this).parents ('.mm').remove ();
      })
    );
  };

  if ($tdms.data ('msm'))
    $tdms.data ('msm').forEach (function (t) {
      $fmm ($tdms.data ('i'), t).insertBefore ($mam);
      $tdms.data ('i', $tdms.data ('i') + 1);
    });

  $mam.find ('.icon-plus').click (function () {
    $fmm ($tdms.data ('i')).insertBefore ($mam);
    $tdms.data ('i', $tdms.data ('i') + 1);
  }).click ();

  var $map = $('#map');
  var $fm = $('form#fm');
  var $types = $fm.find ('input[type="radio"]');
  var _map = null;
  var _marker = null;
  var _polyline = null;

  function initialize () {
    var position = new google.maps.LatLng ($map.data ('lat'), $map.data ('lng'));
    _map = new google.maps.Map ($map.get (0), {
        zoom: 16,
        zoomControl: true,
        scrollwheel: true,
        scaleControl: true,
        mapTypeControl: false,
        navigationControl: true,
        streetViewControl: false,
        disableDoubleClickZoom: true,
        center: position,
      });

    _map.mapTypes.set ('map_style', new google.maps.StyledMapType ([
      { featureType: 'transit', stylers: [{ visibility: 'simplified' }] },
      { featureType: 'poi', stylers: [{ visibility: 'simplified' }] },
    ]));
    _map.setMapTypeId ('map_style');

    _mazu = new MarkerWithLabel ({
        position: position,
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
        
    _polyline = new google.maps.Polyline ({
      map: _map,
      strokeColor: 'rgba(68, 77, 145, .6)',
      strokeWeight: 4,
      path: $fm.find ('input.points').map (function () {
          return new google.maps.LatLng ($(this).data ('lat'), $(this).data ('lng'));
        })
    });

    _marker = new google.maps.Marker ({
        map: _map,
        draggable: true,
      });

    var $lat = $fm.find ('input.ori_lat');
    var $lng = $fm.find ('input.ori_lng');
    if ($lat.length && $lng.length)
      _marker.setPosition (new google.maps.LatLng ($lat.val (), $lng.val ()));

    google.maps.event.addListener (_map, 'click', function (e) {
      _marker.setPosition (e.latLng);
    });

    $fm.submit (function () {
      if (!_marker.position) {
        alert ('請點選位置！');
        window.hideLoading ();
        return false;
      } else {
        $fm.append (
            $('<input />').attr ('type', 'hidden').attr ('name', 'latitude').val (_marker.position.lat ())
          ).append (
            $('<input />').attr ('type', 'hidden').attr ('name', 'longitude').val (_marker.position.lng ())
          );

        window.showLoading ();
      }
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