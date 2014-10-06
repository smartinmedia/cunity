$(document).ready(function () {
    loadNotifications();
    window.setInterval(function () {
        loadNotifications();
    }, 60000);
    $(".mini-profile-notification-bar").click(function () {
        var of = $(this).offset();
        $(".notification-popover").css({top: of.top - 40, left: of.left + 120}).show();
    });
    $(document).click(function (e) {
        if (!$(".notification-popover").is(e.target) && $(".notification-popover").has(e.target).length === 0 && !$(".mini-profile-notification-bar").is(e.target) && $(".mini-profile-notification-bar").has(e.target).length === 0)
            $(".notification-popover").hide();
    });
});

function loadNotifications() {
    sendRequest({}, "notifications", "get", function (res) {
        if (res.new === null) {
            res.new = 0;
        }
        $(".notification-count").html(res.new);
        $("#notification-results").html(function () {
            var str = "";
            if (res.result !== null && typeof res.result !== "undefined" && res.result.length > 0)
                for (x in res.result)
                    str += tmpl("notification-item", res.result[x]);
            return str;
        }).show();
        $(".notification-popover-loader").hide();
        if ($("#notification-results > .notification-item").length === 0)
            $(".notification-results-alert").show();
        else
            $(".notification-results-alert").hide();
    });
}