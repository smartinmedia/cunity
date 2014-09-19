$(document).ready(function () {
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        $(this).siblings().removeClass("active");
        $(e.target).addClass("active");
    });
    $(document).on("keydown", "#addGuest", function (e) {
        var val = $(this).val();
        if (e.which === 13)
            e.preventDefault();
        else if (e.which === 8 && val.length === 0 && $(".receiver-token > .label").length > 0) {
            delete selectedReceiver[$(".receiver-token > .label:last").find("input[name='receiver[]']").val()];
            $(".receiver-token > .label:last").remove();

        } else
            $(this).width((1 + val.length) * 10);
        if (val.length + 1 > 1) {
            $("#guest-suggestions-alert").hide();
            $("#guest-suggestions-loader").show();
            if (msgxhr)
                msgxhr.abort();
            msgxhr = sendRequest({"q": val, "friends": true}, "search", "livesearch", function (res) {
                $("#guest-suggestions-loader,#guest-suggestions-alert").hide();
                if (typeof res.users !== "undefined") {
                    $("#guest-suggestions").html("");
                    for (x in res.users) {
                        var user = res.users[x];
                        if (typeof selectedReceiver[user.userid] == "undefined")
                            $("#guest-suggestions").append(tmpl("event-invitation-result", {
                                userid: user.userid,
                                name: user.name,
                                profileImage: checkImage(user.pimg, "user", "cr_")
                            }));

                    }
                }
                if ($("#guest-suggestions .message-searchresult-item").length > 0)
                    $("#guest-suggestions").show();
                else {
                    $("#guest-suggestions").hide();
                    $("#guest-suggestions-alert").show();
                }
            });
        } else
            $("#guest-suggestions").hide();
    }).on("click", ".receiver-token > .label > .close", function () {
        delete selectedReceiver[$(this).siblings("input[name='receiver[]']").val()];
        $(this).parent(".label").remove();
    }).on("click", ".receiver-content", function () {
        $(this).find(".receiver-input").focus();
    });
});

function addGuest(userid, name) {
    $(".receiver-token").append(tmpl("receiver-tpl", {name: name, userid: userid}));
    $("#addGuest").width(10).val("");
    $("#guest-suggestions").hide().html("");
    selectedReceiver[userid] = 1;
}

function changeEventStatus(eventid, status) {
    if (status == 0) {
        bootbox.confirm("Are You sure you want to remove this event from your list?", function (a) {
            if (!a)
                return;
        });
    }
    sendRequest({eventid: eventid, status: status}, "events", "changeStatus", function (r) {
        if (r.status == true) {
            $(".event-attending-buttons > div:not(.attending-button-" + status + ")").hide();
            $(".event-attending-button-" + status).show();
            refreshGuestList();
        }
    });
}

function invitationSent(res) {
    if (res.status) {
        $("#inviteModal").modal('hide');
        refreshGuestList();
    }
}

function refreshGuestList() {
    sendRequest({eventid: $("#eventid").val()}, "events", "loadGuestList", function (res) {
        if (res.status) {
            $("#guestlist-invited,#guestlist-maybe, #guestlist-attending").empty();
            for (g in res.guests.invited) {
                $("#guestlist-invited").append(tmpl('guest-item', res.guests.invited[g]));
            }
            for (g in res.guests.maybe) {
                $("#guestlist-maybe").append(tmpl('guest-item', res.guests.maybe[g]));
            }
            for (g in res.guests.attending) {
                $("#guestlist-attending").append(tmpl('guest-item', res.guests.attending[g]));
            }
        }
    });
}