$(document).ready(function () {
    loadCategoryCloud();
    loadCategoryThreads();
});
function loadCategoryThreads() {
    $("#board-loader").show();
    sendRequest({cat: $("#category").val()}, "forums", "loadThreads", function (res) {
        $("#board-loader").hide();
        if (typeof res.result !== "undefined" && res.result !== null) {
            for (var x in res.result)
                $("#threads").append(tmpl("thread-template", res.result[x]));
        }
        if ($("#threads .topic-post").length === 0)
            $("#threads .alert").show();
        else
            $("#threads .alert").hide();
    });
}