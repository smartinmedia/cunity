var friendid = 0, parent = $("body");
$(document).ready(function () {
    $('#relationship-modal').on('show.bs.modal', function (e) {
        var el = $(e.relatedTarget);
        friendid = el.data("userid");
        parent = $((typeof el.data("parent") === 'undefined') ? "body" : el.data("parent"));
        $("#relationship-modal").find("." + el.data("action")).show();
        sendRequest({"userid": friendid}, "friends", "loadData", function (res) {
            $("#relationship-modal").find(".user-data-link").attr("href", $("meta[name='siteurl']").attr("content") + "profile/" + res.user.username);
            $("#relationship-modal").find(".user-data-image").attr("src", checkImage(res.user['pimg'], "user", "cr_"));
            $("#relationship-modal").find(".modal-loader").hide();
            $("#relationship-modal").find(".modal-body").show();
        });
    });

    $("#relationship-modal").on('hidden.bs.modal', function () {
        $("#relationship-modal").find(".modal-category,.modal-body").hide();
        $("#relationship-modal").find(".modal-loader").show();
    });
});

function addFriend() {
    sendRequest({"userid": friendid}, "friends", "add", function (res) {
        friendid = 0;
        parent.find(".friend-buttons").hide();
        parent.find(".pending-buttons").show();
        $("#relationship-modal").modal('hide');
    });
}

function blockFriend() {
    sendRequest({"userid": friendid}, "friends", "block", function (res) {
        friendid = 0;
        parent.find(".friend-buttons").hide();
        parent.find(".blocked-buttons").show();
        $("#relationship-modal").modal('hide');
    });
}

function changeRelationship(status) {
    sendRequest({"userid": friendid, "status": status}, "friends", "change", function (res) {
        friendid = 0;
        parent.find(".friend-buttons, .relationship-buttons .relationship-status").hide();
        parent.find(".relationship-buttons, .relationship-buttons .status-" + status).show();
        $("#relationship-modal").modal('hide');
    });
}

function confirmFriend() {
    sendRequest({"userid": friendid}, "friends", "confirm", function (res) {
        friendid = 0;
        parent.find(".friend-buttons").hide();
        parent.find(".friends-buttons").show();
        $("#relationship-modal").modal('hide');
        sendRequest({type: "friends"}, "notifications", "get", function (res) {
            if (typeof res.result !== "undefined" && res.result !== null && res.result.length > 0) {
                $(".menu-item-friends > a > .badge").html(res.result.length);
                $(".notification-link-friends").addClass("active");
            } else
                $(".notification-link-friends").removeClass("active");
        });
    });
}

//this function is for a) not confirm a request and b) delete a friendship
function removeFriend() {
    sendRequest({"userid": friendid}, "friends", "remove", function (res) {
        friendid = 0;
        parent.find(".friend-buttons").hide();
        parent.find(".nofriends-buttons").show();
        $("#relationship-modal").modal('hide');
        sendRequest({type: "friends"}, "notifications", "get", function (res) {
            if (typeof res.result !== "undefined" && res.result !== null && res.result.length > 0) {
                $(".menu-item-friends > a > .badge").html(res.result.length);
                $(".notification-link-friends").addClass("active");
            } else
                $(".notification-link-friends").removeClass("active");
        });
    });
}

function loadFriends(userid) {
    $("#friendslist > .list > .friendslist-item").remove();
    $("#friendslist .block-loader").show();
    sendRequest({"userid": userid}, "friends", "load", function (data) {
        $("#friendslist .block-loader").hide();
        if (typeof data.result !== "undefined" && data.result !== null && data.result.length > 0)
            for (x in data.result)
                $("#friendslist > .list").append(tmpl("friend-template", data.result[x]));
        if ($("#friendslist > .list .friendslist-item").length === 0)
            $("#friendslist > .list .alert").show();
        else
            $("#friendslist > .list .alert").hide();
    });
}