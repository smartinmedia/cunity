$(document).ready(function () {
    $("#summernote-mail-header,#summernote-mail-footer").summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
        ],
        onblur: function (e) {
            $("input[name='mail_header']").val($("#summernote-mail-header").code());
            $("input[name='mail_footer']").val($("#summernote-mail-footer").code());
        },
        oninit: function () {
            var header = $("#summernote-mail-header").data("source"), footer = $("#summernote-mail-footer").data("source");
            $("#summernote-mail-header").code(header);
            $("#summernote-mail-footer").code(footer);

            $("input[name='mail_header']").val(header);
            $("input[name='mail_footer']").val(footer);
        }
    });
    $(".change-connection-type").change(function () {
        if ($("#connection-type-smtp").is(":checked"))
            $("#smtp-settings").show();
        else
            $("#smtp-settings").hide();
    });
});

function sendTestMail() {
    bootbox.prompt("Please enter your email! (please save changes before)", function (res) {
        if (res != "") {
            sendRequest({action: "sendTestMail", "mail": res}, "admin", "settings", function (e) {
                if (e.status === true) {
                    bootbox.alert("Message was sent successfully!");
                }
            });
        }
    });
}