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

    $('#file-upload').click(function() {
        var type = 'file';
        $.ajax({
            url: siteurl + "lib/plugins/plupload/js/plupload.full.min.js",
            dataType: "script",
            success: function () {
                uploader = new plupload.Uploader({
                    runtimes: 'html5,flash,silverlight,html4',
                    browse_button: type + '-upload',
                    multi_selection: false,
                    chunk_size: "500kb",
                    unique_names: true,
                    url: convertUrl({module: "profile", action: "edit"}),
                    flash_swf_url: siteurl + "lib/plugins/plupload/js/Moxie.swf",
                    silverlight_xap_url: siteurl + "lib/plugins/plupload/js/Moxie.xap",
                    filters: {
                        max_file_size: $("#upload_limit").val(),
                        mime_types: [
                            {title: "Image files", extensions: "jpg,gif,png"}
                        ]
                    },
                    init: {
                        FilesAdded: function (up, files) {
                            $("#selected-file-" + type).val(files[0].name);
                        },
                        FileUploaded: function (up, file, res) {
                            location.href = convertUrl({
                                module: "filesharing",
                                action: "cropImage",
                                x: jQuery.parseJSON(res.response).imageid,
                                y: type
                            });
                        },
                        BeforeUpload: function () {
                            uploader.setOption("multipart_params", {
                                "edit": "changeimage",
                                "type": type
                            });
                        }
                    }
                });
                uploader.bind("Error", function (e, b) {
                    bootbox.alert(b.message);
                });
                uploader.init();
            },
            cache: true
        });
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
