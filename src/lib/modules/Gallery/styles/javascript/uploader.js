var uploader;
$(document).ready(function () {
    $("#upload_modal").on("show.bs.modal", function () {
        uploader = new plupload.Uploader({
            runtimes: 'html5,flash,silverlight,html4',
            browse_button: 'multi_selectfiles',
            container: 'multi_container',
            chunk_size: "500kb",
            unique_names: true,
            url: convertUrl({module: "gallery", action: "upload"}),
            flash_swf_url: $("#multi_selectfiles").data("flash"),
            silverlight_xap_url: $("#multi_selectfiles").data("silverlight"),
            multipart_params: {
                "albumid": $("#multi_selectfiles").data("albumid")
            },
            drop_element: "upload_modal",
            filters: {
                max_file_size: '10mb',
                mime_types: [
                    {title: "Image files", extensions: "jpg,gif,png"}
                ]
            },
            init: {
                PostInit: function () {
                    $("#files").html("").find(".alert").show();
                    $("#startupload").show();
                    $("#closeupload").hide();
                    $("#upload_progress").addClass("active").find(".progress-bar").width(0).removeClass("progress-bar-success").addClass("progress-bar-warning");
                    uploader.bind("Error", function (e, b) {
                        bootbox.alert(b.message);
                    });
                },
                FilesAdded: function (up, files) {
                    plupload.each(files, function (file) {
                        $("#files").append('<tr id="' + file.id + '" class="queue_file"><td class="filename">' + file.name + '</td><td><div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0"></div></div></td><td><a class="fa fa-trash-o removefile" href="javascript:void(0);"></a><i class="fa fa-check hidden"></i></td></tr>');
                        $("#" + file.id).on("click", "a.removefile", function () {
                            uploader.removeFile(file);
                            $("#" + file.id).fadeOut().remove();
                        });
                    });
                },
                UploadProgress: function (up, file) {
                    $("#" + file.id).find(".progress-bar").width(file.percent + '%');
                    $("#upload_progress .progress-bar").width(up.total.percent + '%');
                },
                FileUploaded: function (up, file, res) {
                    $("#" + file.id).find(".close").hide().next().show();
                    addUploaded(jQuery.parseJSON(res.response));
                },
                BeforeUpload: function () {
                    $("#startupload").button('loading');
                },
                UploadComplete: function (up, files) {
                    $("#upload_progress").removeClass("active").find(".progress-bar").removeClass("progress-bar-warning").addClass("progress-bar-success");
                    $("#startupload").hide().button('reset');
                    $("#closeupload").show();
                },
                QueueChanged: function (up) {
                    if (up.total.queued === 0)
                        $("#filescontainer .alert").show();
                    else
                        $("#filescontainer .alert").hide();
                }
            }
        });
        uploader.init();
    }).on("hidden.bs.modal", function () {
        $(".multiuploader_link").hide();
        $(".singleuploader_link").show();
        $("#multiuploader").show();
        $("#singleuploader").hide();
        $("#startupload").show();
        $("#closeupload").hide();
        uploader.destroy();
    });

    $("#startupload").click(function () {
        uploader.start();
    });

    $(".singleuploader_link").click(function () {
        $(this).hide();
        $(".multiuploader_link").show();
        $("#multiuploader").hide();
        $("#singleuploader").show();
        $("#startupload").hide();
        $("#closeupload").show();
    });

    $(".multiuploader_link").click(function () {
        $(this).hide();
        $(".singleuploader_link").show();
        $("#multiuploader").show();
        $("#singleuploader").hide();
        $("#startupload").show();
        $("#closeupload").hide();
    });
});

function addUploaded(res) {
    $("#imagelist > .list > .alert").remove();
    $("#imagelist > .list").append(tmpl("imagestemplate", {id: Number(res.imageid), filename: res.filename}));
}