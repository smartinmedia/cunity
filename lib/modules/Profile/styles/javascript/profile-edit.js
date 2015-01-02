var uploader = null;
$(document).ready(function () {
    $('form input, form select').change(function() {
        $("#privacychangedalert, #generalchangedalert, #notificationsettingschangedalert").removeClass("alert-success, alert-danger").hide();
    });
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        $(this).siblings().removeClass("active");
        $(e.target).addClass("active");
    });
    $("#profile-pin-summernote").summernote({
        onblur: function () {
            $("#newPinForm textarea[name='content']").val($('#profile-pin-summernote').code());
        },
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']]
        ],
        onpaste: function (e) {
            revalidateEditor(e.target.innerHTML);
        },
        onkeyup: function (e) {
            revalidateEditor(e.target.innerHTML);
        },
        onkeydown: function (e) {
            revalidateEditor(e.target.innerHTML);
        }
    });
    $("#newPinForm").bootstrapValidator({
        feedbackIcons: {
            valid: 'fa fa-check',
            invalid: 'fa fa-times',
            validating: 'fa fa-refresh'
        },
        excluded: "",
        message: "This field cannot be blank!",
        submitButtons: "button[type='submit']",
        fields: {
            title: {
                validators: {
                    notEmpty: {
                        message: 'A title is required!'
                    },
                    stringLength: {
                        min: 3,
                        message: "Your title is too short! (min. 3 chars)"
                    }
                }
            },
            type: {
                validators: {
                    notEmpty: {
                        message: 'Please choose a type!'
                    }
                }
            },
            content: {
                validators: {
                    notEmpty: {
                        message: 'Please enter a text!'
                    },
                    stringLength: {
                        min: 3,
                        message: "Your text is too short! (min. 3 chars)"
                    }
                },
                trigger: 'keyup'
            }
        }

    });
    $("#newpinmodal").on("show.bs.modal", function (e) {
        if ($("#select-icon-menu > li").length === 0) {
            $.getJSON(siteurl + "data/resources/fontawesome-icons.json", function (data) {
                for (x in data)
                    $("#select-icon-menu").append('<li><a href="javascript:selectIcon(\'' + data[x] + '\');"><i class="fa fa-fw fa-' + data[x] + '"></i>&nbsp;' + data[x] + '</a></li>');
            });
        }
        if ($(e.relatedTarget).data("action") === "edit") { // Edit Pin
            $("#newpinmodal .deletepin,#newpinmodal .editpinbutton").show();
            $("#newpinmodal .newpinbutton").hide();
            var pinid = $(e.relatedTarget).data("pinid");
            sendRequest({edit: "loadPinData", id: $(e.relatedTarget).data("pinid")}, "profile", "edit", function (res) {
                var form = $("#newPinForm");
                form.find("input[name='title']").val(res.title);
                form.find("select[name='type']").val(res.type);
                $('#profile-pin-summernote').code(res.content);
                $("#newPinForm textarea[name='content']").val(res.content);
                selectIcon(res.iconclass);
                form.append($("<input/>", {type: "hidden", "name": "editPin", "value": pinid}));
                $("#pin-type-1,#pin-type-2").hide();
                $("#pin-type-" + res.type).show();
            });
        } else {
            $("#newpinmodal .newpinbutton").show();
            $("#newpinmodal .deletepin,#newpinmodal .editpinbutton").hide();
            $("#newPinForm input[name='editPin']").remove();
            $("#newPinForm input[name='title'],#newPinForm select[name='type'],#newPinForm textarea[name='content']").val("");
            $("#pin-type-1,#pin-type-2").hide();
            $('#profile-pin-summernote').code("");
            selectIcon("");
            $("#newpinmodal .deletepin").hide();
        }
    });
    $("#newpinmodal .deletepin").click(function (e) {
        bootbox.confirm($(this).data("confirmation"), function (r) {
            if (r) {
                sendRequest({
                    edit: "deletePin",
                    id: $("#newPinForm input[name='editPin']").val()
                }, "profile", "edit", function (data) {
                    $("#pin-" + data.id).remove();
                    $("#newpinmodal").modal('hide');
                });
            }
        });
        e.preventDefault();
    });
    $(document).on("change", "#newpinmodal select[name='type']", function () {
        $("#pin-type-1,#pin-type-2").hide();
        $("#pin-type-" + $(this).val()).show();
    });
    $(".profile-pins-column").sortable({
        connectWith: ".profile-pins-column",
        items: ".panel",
        placeholder: "profile-pin-placeholder",
        cursor: "move",
        update: function () {
            sendRequest({
                edit: "pinPositions",
                column: $(this).data("column"),
                pins: $(this).sortable("toArray", {attribute: "data-pinid"})
            }, "profile", "edit");
        }
    });
    $(".image-delete").click(function () {
        var el = $(this);
        bootbox.confirm("Are you sure you want to remove this photo on your profile? It will still exist in your gallery", function (r) {
            if (r) {
                sendRequest({type: el.data("type"), edit: "deleteImage"}, "profile", "edit", function () {
                    location.reload();
                });
            }
        });
    });
    $(document).on("click", "#profile-menu > li > a[href='#editProfileImages'], a[href='#editProfileImage'], a[href='#editTitleImage']", function () {
        var type = $(this).data("type");
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
                                module: "profile",
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
function revalidateEditor(html) {
    $("#profile-pin-summernote").val(html).trigger('keyup');
    $("#newPinForm").data('bootstrapValidator').updateStatus('content', 'VALIDATING').validateField('content');
}

function uploadPhoto() {
    if (uploader !== null)
        uploader.start();
}
function selectIcon(iconname) {
    $("#newPinForm .newpin-icon").val(iconname);
    if (iconname !== "")
        $("#select-icon-toggle").html('<i class="fa fa-' + iconname + '"></i>&nbsp;' + iconname);
    else
        $("#select-icon-toggle").html($("#select-icon-toggle").data("orig"));
}

function pincreated(data) {
    if (typeof data.updated !== "undefined" && data.updated === true)
        $("#pin-" + data.id).replaceWith(tmpl("profilepin", data));
    else
        $("#Pins .profile-pins-column:first").append(tmpl("profilepin", data));
    $("#newpinmodal").modal('hide');
}

function passwordchanged(res) {
    $("#passwordchangedalert").removeClass("alert-success, alert-danger").hide().html(res.msg);
    if (res.status) {
        $("#passwordchangedalert").addClass("alert-success").show();
        $("input[name='old-password'],input[name='new-password'],input[name='new-password-rep']").val("");
    } else
        $("#passwordchangedalert").addClass("alert-danger").show();
}

function generalchanged(res) {
    $("#generalchangedalert").removeClass("alert-success, alert-danger").hide().html(res.msg);
    if (res.status) {
        $("#generalchangedalert").addClass("alert-success").show();
    } else
        $("#generalchangedalert").addClass("alert-danger").show();
}

function privacychanged(res) {
    $("#privacychangedalert").removeClass("alert-success, alert-danger").hide().html(res.msg);
    if (res.status) {
        $("#privacychangedalert").addClass("alert-success").show();
    } else
        $("#privacychangedalert").addClass("alert-danger").show();
}

function notificationsUpdated(res) {
    $("#notificationsettingschangedalert").removeClass("alert-success, alert-danger").hide().html(res.msg);
    if (res.status) {
        $("#notificationsettingschangedalert").addClass("alert-success").show();
    } else
        $("#notificationsettingschangedalert").addClass("alert-danger").show();
}