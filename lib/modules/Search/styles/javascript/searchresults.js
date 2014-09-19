$(document).ready(function () {
    sendRequest({"q": $("#term").val()}, "search", "livesearch", function (res) {
        $("#list > .block-loader").hide();
        for (x in res.users)
            if (res.users[x].status !== 0)
                $("#list").append(tmpl("memberlist-item", res.users[x]));
        if ($("#list .searchresult-item").length === 0)
            $("#list .alert").show();
        else
            $("#list .alert").hide();
    });
});