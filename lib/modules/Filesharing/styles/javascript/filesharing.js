$(document).ready(function () {
    loadFiles();
    $(document).on("mouseenter", ".filesharing-cover", function () {

    }).on("mouseleave", ".filesharing-cover", function () {

    });

    $("#newfiles_modal input[name='privacy']").change(function () {
        if ($("#newfiles_shared").is(":checked"))
            $("#newfiles_shared_options").show();
        else
            $("#newfiles_shared_options").hide();
    });

    $('#friendselector').select2().on('select2-selecting', function () {
        $('#friendCheckbox').attr('checked', false);
        $('#friendCheckboxLabel').removeClass('active').addClass('disabled') ;
    }).on("select2-removed", function () {
        if ($('#friendselector').select2('data').length === 0) {
            $('#friendCheckbox').click();
            $('#friendCheckboxLabel').removeClass('disabled');
        }
    });

    $('#file-upload').click(function () {
        $.ajax({
            url: siteurl + "lib/plugins/plupload/js/plupload.full.min.js",
            datatype: "script",
            success: function () {
                uploader = new plupload.Uploader({
                    runtimes: 'html5,flash,silverlight,html4',
                    browse_button: 'file-upload',
                    multi_selection: false,
                    chunk_size: "500kb",
                    unique_names: true,
                    url: convertUrl({module: "filesharing", action: "create"}),
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
                            $("#selected-file-" + 'file').val(files[0].name);
                        },
                        FileUploaded: function (up, file, res) {
                            location.href = convertUrl({
                                module: "filesharing",
                                action: "cropImage",
                                x: jQuery.parseJSON(res.response).imageid,
                                y: 'file'
                            });
                        },
                        BeforeUpload: function () {
                            uploader.setOption("multipart_params", {
                                "edit": "changeimage",
                                "'file'": 'file'
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
    $("#fileslist > .alert").hide();
    $(".filesharing-loader").show();
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

function filescreated() {
    location.href = convertUrl({module: "filesharing"});
}
