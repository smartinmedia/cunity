/**
 * This tiny script just helps us demonstrate
 * what the various example callbacks are doing
 */
var Notification = (function() {
    "use strict";

    var elem,
        hiddenHandler,
        that = {};

    that.show = function(text,type) {
        if(typeof elem === "undefined")
             elem = $('<span class="alert notification-alert hidden">Cunity-Notification</span>').appendTo("body");
        clearTimeout(hiddenHandler);

        elem.addClass("alert-"+type).html(text);
        elem.fadeIn().delay(4000).fadeOut().removeClass("alert-"+type);
    };

    return that;
}());
