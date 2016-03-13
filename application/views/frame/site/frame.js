/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

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

$(function () {
  window.addPv = function (className, id) {
    $.ajax ({
        url: $('#ajax_pv_url').val (),
        data: { class: className, id: id },
        async: true, cache: false, dataType: 'json', type: 'POST',
    });
  };
});