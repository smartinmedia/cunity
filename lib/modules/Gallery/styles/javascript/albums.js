$(document).ready(function () {
    loadAlbums(0);
    $(document).on("mouseenter", ".gallery-cover", function () {

    }).on("mouseleave", ".gallery-cover", function () {

    });

    $("#newalbum_modal input[name='privacy']").change(function (e) {
        if ($("#newalbum_shared").is(":checked"))
            $("#newalbum_shared_options").show();
        else
            $("#newalbum_shared_options").hide();
    });
});

function loadAlbums(userid) {
    $(".gallery-list").html("");
    $(".gallery-loader").show();
    $("#albumlist > .alert").hide();
    sendRequest({"userid": userid}, "gallery", "overview", function (data) {
        $(".gallery-loader").hide();
        if (data.result !== null)
            for (x in data.result)
                $(".gallery-list").append(tmpl("albums-template", data.result[x]));
        if ($("#albumlist .albumlist-item").length === 0)
            $("#albumlist > .alert").show();
    });
}
function albumcreated(res) {
    location.href = res.target;
}

function applyFilter() {
    filter = 0;
    $("#albumlist .albumlist-item").hide();
    $("#albumlist > .alert").hide();
    $(".albums-filter").each(function () {
        var e = $(this);
        if (e.is(":checked")) {
            $("#albumlist .album-" + e.val()).show();
            filter++;
        }
    });
    if (filter === 0)
        $("#albumlist .albumlist-item").show();
    if ($("#albumlist .albumlist-item:visible").length === 0)
        $("#albumlist > .alert").show();

    $("#filter-dropdown").dropdown('toggle');
}
