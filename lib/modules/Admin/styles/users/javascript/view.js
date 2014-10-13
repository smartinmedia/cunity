function applyFilter() {
    $("#usertable > tr").hide();
    var x = 0;
    $(".userlist-filter").each(function () {
        var e = $(this);
        if (e.is(":checked")) {
            $("#usertable > tr.user-" + e.val()).show();
            x++;
        }
    });
    if (x === 0)
        $("#usertable > tr").show();
}

function changeUserStatus(groupid, userid) {
    sendRequest({
        action: "save",
        "groupid": groupid,
        "userid": userid,
        "form": "users"
    }, "admin", "save", function (res) {
        if (res.status == true) {
            loadPage('users', 'view');
        }
    });
}
