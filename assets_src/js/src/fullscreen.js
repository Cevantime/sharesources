!function(a){"use strict";function b(b,c){var d=a.createEvent("Event");d.initEvent(b,!0,!1),c.dispatchEvent(d)}function c(c){c.stopPropagation(),c.stopImmediatePropagation(),a[j.enabled]=a[f.enabled],a[j.element]=a[f.element],b(j.events.change,c.target)}function d(a){b(j.events.error,a.target)}function e(b){return function(c,d){function e(){c(),a.removeEventListener(f.events.change,e,!1)}function g(){d(new TypeError),a.removeEventListener(f.events.error,g,!1)}return b!==j.exit||a[f.element]?(a.addEventListener(f.events.change,e,!1),void a.addEventListener(f.events.error,g,!1)):void setTimeout(function(){d(new TypeError)},1)}}var f,g,h=!0,i={w3:{enabled:"fullscreenEnabled",element:"fullscreenElement",request:"requestFullscreen",exit:"exitFullscreen",events:{change:"fullscreenchange",error:"fullscreenerror"}},webkit:{enabled:"webkitFullscreenEnabled",element:"webkitCurrentFullScreenElement",request:"webkitRequestFullscreen",exit:"webkitExitFullscreen",events:{change:"webkitfullscreenchange",error:"webkitfullscreenerror"}},moz:{enabled:"mozFullScreenEnabled",element:"mozFullScreenElement",request:"mozRequestFullScreen",exit:"mozCancelFullScreen",events:{change:"mozfullscreenchange",error:"mozfullscreenerror"}},ms:{enabled:"msFullscreenEnabled",element:"msFullscreenElement",request:"msRequestFullscreen",exit:"msExitFullscreen",events:{change:"MSFullscreenChange",error:"MSFullscreenError"}}},j=i.w3;for(g in i)if(i[g].enabled in a){f=i[g];break}return!h||j.enabled in a||!f||(a.addEventListener(f.events.change,c,!1),a.addEventListener(f.events.error,d,!1),a[j.enabled]=a[f.enabled],a[j.element]=a[f.element],a[j.exit]=function(){var b=a[f.exit]();return!b&&window.Promise?new Promise(e(j.exit)):b},Element.prototype[j.request]=function(){var a=this[f.request].apply(this,arguments);return!a&&window.Promise?new Promise(e(j.request)):a}),f}(window.document);