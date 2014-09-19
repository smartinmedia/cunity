$(document).ready(function () {
    $("#profile-menu > li > a:first").trigger("click");
    if ($("#Pins").length > 0)
        loadProfilePins();
});

function loadProfilePins() {
    sendRequest({userid: $("#profile-userid").val()}, "profile", "getpins", function (res) {
        $(".profile-pin-loader").hide();
        for (x in res.result) {
            var pin = res.result[x];
            if (pin.column === 0)
                $("#Pins .profile-pins-column:first").append(tmpl("profilepin", pin));
            else
                $("#Pins .profile-pins-column:last").append(tmpl("profilepin", pin));
        }
        if ($("#Pins .profile-pin").length === 0)
            $("#Pins .alert-block").show();
        else
            $("#Pins .alert-block").hide();
    });
}

function infoPin(content) {
    if (content !== "") {
        var data = $.parseJSON(content), result = "";
        for (n in data) {
            result += '<tr><td>' + n + '</td><td>' + data[n] + '</td></tr>';
        }
        return result;
    }
}