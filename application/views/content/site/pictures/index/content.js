/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var initPhotoSwipeFromDOM = function (gallerySelector) {
    var parseThumbnailElements = function (el) {
      var thumbElements = el.childNodes, numNodes = thumbElements.length, items = [], figureEl, linkEl, size, item;

      for (var i = 0; i < numNodes; i++) {
        figureEl = thumbElements[i];

        if (figureEl.nodeType !== 1) continue;

        linkEl = figureEl.children[0];
        size = linkEl.getAttribute ('data-size').split ('x');

        item = {
          w: (w = parseInt (size[0], 10)) ? w : 0,
          h: (h = parseInt (size[1], 10)) ? h : 0,
          src: linkEl.getAttribute ('src'),
          href: linkEl.getAttribute ('href')
        };

        if (figureEl.children.length > 1) {
          item.title = figureEl.children[1].innerHTML;
          item.content = figureEl.children[1].getAttribute ('data-description') + '<a href="' + linkEl.getAttribute ('href') + '">閱讀更多 »</a>' || '';
        }

        if (linkEl.children.length > 0) item.msrc = linkEl.children[0].getAttribute ('src');

        item.el = figureEl;
        items.push (item);
      }

      return items;
    };

    var closest = function closest (el, fn) {
      return el && (fn (el) ? el : closest (el.parentNode, fn));
    };

    var onThumbnailsClick = function (e) {
      e = e || window.event;
      e.preventDefault ? e.preventDefault () : e.returnValue = false;

      var eTarget = e.target || e.srcElement;
      var clickedListItem = closest (eTarget, function (el) {
        return (el.tagName && el.tagName.toUpperCase () === 'FIGURE');
      });

      if (!clickedListItem) return;

      var clickedGallery = clickedListItem.parentNode, childNodes = clickedListItem.parentNode.childNodes, numChildNodes = childNodes.length, nodeIndex = 0, index;

      for (var i = 0; i < numChildNodes; i++) {
        if (childNodes[i].nodeType !== 1) continue;

        if (childNodes[i] === clickedListItem) {
          index = nodeIndex;
          break;
        }
        nodeIndex++;
      }

      if (index >= 0) openPhotoSwipe (index, clickedGallery);
      return false;
    };

    var photoswipeParseHash = function () {
      var hash = window.location.hash.substring (1),
      params = {};

      if (hash.length < 5) return params;

      var vars = hash.split ('&');

      for (var i = 0; i < vars.length; i++) {
        if (!vars[i]) continue;

        var pair = vars[i].split ('=');
        if (pair.length < 2) continue;

        params[pair[0]] = pair[1];
      }

      if (params.gid) params.gid = parseInt (params.gid, 10);

      return params;
    };

    var openPhotoSwipe = function (index, galleryElement, disableAnimation, fromURL) {
      var pswpElement = document.querySelectorAll ('.pswp')[0], gallery, options, items;
      items = parseThumbnailElements (galleryElement);

      options = {
        showHideOpacity: true,
        galleryUID: galleryElement.getAttribute ('data-pswp-uid'),

        getThumbBoundsFn: function (index) {
          var thumbnail = items[index].el.getElementsByTagName ('a')[0], // find thumbnail
              pageYScroll = window.pageYOffset || document.documentElement.scrollTop,
              rect = thumbnail.getBoundingClientRect ();

          return {x:rect.left, y:rect.top + pageYScroll, w:rect.width};
        }
      };

      if (fromURL)
        if (options.galleryPIDs)
          for (var j = 0; j < items.length; j++) {
            if (items[j].pid == index) {
              options.index = j;
              break;
            }
          }
        else options.index = parseInt (index, 10) - 1;
      else options.index = parseInt (index, 10);

      if (isNaN (options.index) )
        return;

      if (disableAnimation) options.showAnimationDuration = 0;

      gallery = new PhotoSwipe (pswpElement, PhotoSwipeUI_Default, items, options, $(gallerySelector).find ('a').map (function () {
        return $(this).data ('id');
      }));
      gallery.init (function (id) {
        addPv ('Picture', id);
      });

      var $center = $('div.pswp__caption__center').width (Math.floor (gallery.currItem.w * gallery.currItem.fitRatio) - 20);

      gallery.listen ('beforeChange', function() {
        $center.removeClass ('show');
        $center.width (Math.floor (gallery.currItem.w * gallery.currItem.fitRatio - 20));
      });
      gallery.listen ('afterChange', function() {
        $center.addClass ('show');
      });
      gallery.listen ('resize', function() {
        $center.width (Math.floor (gallery.currItem.w * gallery.currItem.fitRatio - 20));
      });
    };

    var galleryElements = document.querySelectorAll (gallerySelector);

    for (var i = 0, l = galleryElements.length; i < l; i++) {
      galleryElements[i].setAttribute ('data-pswp-uid', i+1);
      galleryElements[i].onclick = onThumbnailsClick;
    }

    var hashData = photoswipeParseHash ();
    if (hashData.pid && hashData.gid)
      openPhotoSwipe (hashData.pid ,  galleryElements[ hashData.gid - 1 ], true, true);
  };

  initPhotoSwipeFromDOM ('article');

  window.hideLoading ();
});