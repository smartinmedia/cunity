function infoChanged(r) {
    $("#infoAlert").removeClass("alert-success, alert-danger").hide().html(res.msg);
    if (res.status) {
        $("#infoAlert").addClass("alert-success").show();
    } else
        $("#infoAlert").addClass("alert-danger").show();
}

function uploadPhoto() {
    if (uploader !== null)
        uploader.start();
}

$(document).ready(function() {
    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        $(this).siblings().removeClass("active");
        $(e.target).addClass("active");
    });

    $(document).on("click", "#event-sub-menu > a[href='#editPhoto']", function() {
        var type = $(this).data("type");
        $.ajax({
            url: siteurl + "lib/plugins/plupload/js/plupload.full.min.js",
            dataType: "script",
            success: function() {
                uploader = new plupload.Uploader({
                    runtimes: 'html5,flash,silverlight,html4',
                    browse_button: 'title-upload',
                    multi_selection: false,
                    chunk_size: "500kb",
                    unique_names: true,
                    multipart_params: {edit: "editPhoto", eventid: $("#eventid").val()},
                    url: convertUrl({module: "events", action: "edit"}),
                    flash_swf_url: siteurl + "lib/plugins/plupload/js/Moxie.swf",
                    silverlight_xap_url: siteurl + "lib/plugins/plupload/js/Moxie.xap",
                    filters: {
                        max_file_size: $("#upload_limit").val(),
                        mime_types: [
                            {title: "Image files", extensions: "jpg,gif,png"}
                        ]
                    },
                    init: {
                        FilesAdded: function(up, files) {
                            $("#selected-title-file").val(files[0].name);
                        },
                        FileUploaded: function(up, file, res) {
                            location.href = convertUrl({
                                module: "events",
                                action: "cropImage",
                                x: jQuery.parseJSON(res.response).imageid,
                                y: $("#eventid").val()
                            });
                        }
                    }
                });
                uploader.bind("Error", function(e, b) {
                    bootbox.alert(b.message);
                });
                uploader.init();
            },
            cache: true
        });
    });
}); 