var offset = 0, imageLoad = 18;
$(document).ready(function () {
    $(document).on("mouseenter", ".album-item", function () {
        $(this).find(".buttonbar").show();
    }).on("mouseleave", ".album-item", function () {
        $(this).find(".buttonbar").hide();
    });
    loadPhotos($("#albumid").val());
});

function loadPhotos(albumid) {
    $(".gallery-loader").show();
    sendRequest({"albumid": albumid, offset: offset, limit: imageLoad}, "gallery", "loadImages", function (res) {
        $(".gallery-loader").hide();
        if (res.result.length > 0) {
            for (x in res.result) {
                $("#imagelist > .list").append(tmpl("imagestemplate", res.result[x]));
            }
            if (res.result.length < imageLoad)
                $(".album-load-more").hide();
            else
                $(".album-load-more").show();
            offset += res.result.length;
        } else if (offset === 0) {
            $("#imagelist > .list").html(tmpl("noimages", {}));
        }
    });
}

function collapseDescription() {
    $("#description").html($("#description").data("full"));
}