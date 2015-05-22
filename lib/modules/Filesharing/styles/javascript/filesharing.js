$(document).ready(function () {
    loadFiles();
    $(document).on("mouseenter", ".filesharing-cover", function () {

    }).on("mouseleave", ".filesharing-cover", function () {

    });

    $("#newfiles_modal input[name='privacy']").change(function (e) {
        if ($("#newfiles_shared").is(":checked"))
            $("#newfiles_shared_options").show();
        else
            $("#newfiles_shared_options").hide();
    });
});

function loadFiles() {
    $(".filesharing-list").html("");
    $(".filesharing-loader").show();
    $("#fileslist > .alert").hide();
    //sendRequest({"userid": userid}, "filesharing", "overview", function (data) {
    //    $(".filesharing-loader").hide();
    //    if (data.result !== null)
    //        for (x in data.result) {
    //            $(".filesharing-list").append(tmpl("filess-template", data.result[x]));
    //        }
    //    if ($("#fileslist .fileslist-item").length === 0)
    //        $("#fileslist > .alert").show();
    //});
    $(".filesharing-loader").hide();
    $("#fileslist > .alert").show();
}
