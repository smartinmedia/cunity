var offset = 0, imageLoad = 18;

function isScrolledIntoView(elem)
{
    var $elem = $(elem);
    var $window = $(window);

    var docViewTop = $window.scrollTop();
    var docViewBottom = docViewTop + $window.height();

    var elemTop = $elem.offset().top;
    var elemBottom = elemTop + $elem.height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}

$(document).ready(function () {
    $(window).scroll(function(event) {
        if(isScrolledIntoView('.album-load-more')) {
            loadPhotos($("#albumid").val());
        }
    });

    $(document).on("mouseenter", ".album-item", function () {
        $(this).find(".buttonbar").show();
    }).on("mouseleave", ".album-item", function () {
        $(this).find(".buttonbar").hide();
    });
    loadPhotos($("#albumid").val());
    $('#fileone').change(function () {
        if ($('#fileone').val() == '') {
            $('#submitButtonFileOne').attr('disabled', 'disabled');
        } else {
            $('#submitButtonFileOne').removeAttr('disabled');
        }
    });
    $('#filetwo').change(function () {
        if ($('#fileone').val() == '') {
            $('#submitButtonFileTwo').attr('disabled', 'disabled');
        } else {
            $('#submitButtonFileTwo').removeAttr('disabled');
        }
    });
    $('#filethree').change(function () {
        if ($('#fileone').val() == '') {
            $('#submitButtonFileThree').attr('disabled', 'disabled');
        } else {
            $('#submitButtonFileThree').removeAttr('disabled');
        }
    });
});

function loadPhotos(albumid, reload) {
    if (typeof reload == 'undefined' ||
        reload != true) {
        reload = false;
    }

    if (reload) {
        offset = 0;
        $("#imagelist > .list").empty();
    }

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