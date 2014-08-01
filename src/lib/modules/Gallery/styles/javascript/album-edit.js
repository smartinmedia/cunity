$(document).ready(function () {
    $(document).on("contextmenu", ".album-item", function (e) {
        $("#imagecontextmenu").css({
            left: e.pageX,
            top: e.pageY
        }).data("imageid", $(this).data("imageid")).show();
        return false;
    }).click(function () {
        $("#imagecontextmenu").hide();
    }).on("click", ".finaldelete", function () {
        sendRequest({albumid: $("#albumid").val()}, "gallery", "deleteAlbum", function (res) {
            location.href = convertUrl({module: "gallery"});
        });
    });
    $(".albumdelete-link").popover({
        placement: 'top',
        content: $("#editalbum_modal .deletecontent").html(),
        container: "#editalbum_modal",
        html: true
    });
    $("#editalbum_modal").on("hidden.bs.modal", function () {
        $("#editalbum_modal .albumdelete-link").popover('hide');
    });
    $("#editalbum_modal input[name='type']").change(function () {
        if ($("#editalbum_shared").is(":checked"))
            $("#editalbum_shared_options").show();
        else
            $("#editalbum_shared_options").hide();
    });
});

function albumedited(res) {
    location.reload();
}