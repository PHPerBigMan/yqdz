window.$ = window.jQuery = require('jquery');
window.weui = require('weui.js');
window.Swiper = require('swiper/dist/js/swiper.jquery.umd.min.js');

(function (doc, win) {
    var docEle = doc.documentElement,
        // evt = 'onorientationchange' in window ? 'orientationchange' : 'resize',
        evt = 'orientationchange',
        fn = function () {
            let width = window.screen.availWidth;

            if (width > 750) {
                width && (docEle.style.fontSize = 75 + 'px');
            } else {
                width && (docEle.style.fontSize = 75 * (width / 750) + 'px');
            }
        };
    win.addEventListener(evt, fn, false);
    doc.addEventListener('DOMContentLoaded', fn, false);
})(document, window);