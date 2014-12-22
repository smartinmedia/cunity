$(document).ready(function () {
    $("#summernote-headline-header").summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear', 'style']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['picture']]
        ],
        onblur: function (e) {
            $("input[name='settings-core.headline']").val($("#summernote-headline-header").code());
        },
        oninit: function () {
            var header = $("#summernote-headline-header").data("source");
            $("#summernote-headline-header").code(header);
            $("input[name='settings-core.headline']").val(header);
        }
    });

    $("#summernote-startpage-header").summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear', 'style']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['picture']]
        ],
        onblur: function (e) {
            $("input[name='settings-core.startpageheader']").val($("#summernote-startpage-header").code());
        },
        oninit: function () {
            var header = $("#summernote-startpage-header").data("source");
            $("#summernote-startpage-header").code(header);
            $("input[name='settings-core.startpageheader']").val(header);
        }
    });
});
