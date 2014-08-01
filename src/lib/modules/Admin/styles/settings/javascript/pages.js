$(document).ready(function () {
    loadPagesList();
    $("#pages-summernote").summernote({
        height: 600,
        toolbar: [
            ['style', ['style']],
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['picture', 'link']],
            ['table', ['table']]
        ],
        onpaste: function (e) {
            revalidateEditor(e.target.innerHTML);
        },
        onkeyup: function (e) {
            revalidateEditor(e.target.innerHTML);
        },
        onkeydown: function (e) {
            revalidateEditor(e.target.innerHTML);
        },
        oninit: function () {
            var header = $("#pages-summernote").data("source");
            $("#pages-summernote").code(header);
            $("input[name='content']").val(header);
        }
    });

    $(document).on("click", ".page-table-row  .pagescontent", function () {
        $(this).css("height", "auto");
        if ($(this).height() > 200)
            $(this).siblings(".slideup").show();
    }).on("click", ".slideup", function () {
        $(this).siblings(".pagescontent").height(44);
        $(this).hide();
    }).on("click", ".editpagecancel", function () {
        cancelEdit();
    });
});
function revalidateEditor(html) {
    $("#pages-summernote").val(html).trigger('keyup');
    $("#pageform").data('bootstrapValidator').updateStatus('content', 'VALIDATING').validateField('content');
}


function loadPagesList() {
    sendRequest({action: "loadPages"}, "admin", "settings", function (res) {
        if (res.pages !== null && res.pages.length > 0)
            for (x in res.pages)
                $("#pagesbody").append(tmpl("pages-row", res.pages[x]));
    });
}

function deletePage(pageid) {
    bootbox.confirm("Are Your sure You want to delete this page and all comments?", function (e) {
        if (e) {
            sendRequest({"action": "deletePage", id: pageid}, "admin", "settings", function (res) {
                $("#page-row-" + pageid).remove();
            });
        }
    });
}

function cancelEdit() {
    $('html, body').animate({
        scrollTop: 0
    }, 700);
    $("#pages-title").val("");
    $("#editPagePanel input[name='comments']").prop("checked", false);
    $("#pages-summernote").code("").val("");
    $("#editPagePanel").find("input[name='pageid']").val(0);
    $("#editPagePanel").find(".addpageheader").show().siblings(".editpageheader").hide();
    $("#editPagePanel").find(".addpagebutton").show().siblings(".editpagebutton, .editpagecancel").hide();
}

function editPage(pageid) {
    var el = $("#page-row-" + pageid);
    $("#pages-title").val(el.find(".table-row-title").text());
    if (el.find(".comments").data("status") == 1)
        $("#editPagePanel input[name='comments']").prop("checked", true);
    else
        $("#editPagePanel input[name='comments']").prop("checked", false);
    $("#pages-summernote").code(el.find(".pagescontent").html());
    $("input[name='content']").val(el.find(".pagescontent").html());
    $("#editPagePanel").find(".addpageheader").hide().siblings(".editpageheader").show();
    $("#editPagePanel").find(".addpagebutton").hide().siblings(".editpagebutton, .editpagecancel").show();
    $("#editPagePanel").find("input[name='pageid']").val(pageid);
    $('html, body').animate({
        scrollTop: $("#editPagePanel").offset().top
    }, 700);
}

function pageCreated(res) {
    if ($("#page-row-" + res.page.id).length > 0)
        $("#page-row-" + res.page.id).replaceWith(tmpl("pages-row", res.page));
    else
        $("#pagesbody").append(tmpl("pages-row", res.page));
    cancelEdit();
}