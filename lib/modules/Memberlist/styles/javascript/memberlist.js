$(document).ready(function () {
    sendRequest({}, "memberlist", "load", function (res) {
        $("#list > .block-loader").hide();
        for (x in res.result)
            if (res.result[x].status !== 0 || (res.result[x].status === 0 && res.result[x].sender === userid))
                $("#list").append(tmpl("memberlist-item", res.result[x]));
        if ($("#list .searchresult-item").length === 0)
            $("#list .alert").show();
        else
            $("#list .alert").hide();
    });
});