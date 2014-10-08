$(document).ready(function () {
    $('#side-menu').metisMenu();
    $(window).bind("load", function () {
        if ($(this).width() < 768) {
            $('div.sidebar-collapse').addClass('collapse');
        } else {
            $('div.sidebar-collapse').removeClass('collapse');
        }
    }).bind("resize", function () {
        if ($(this).width() < 768) {
            $('div.sidebar-collapse').addClass('collapse');
        } else {
            $('div.sidebar-collapse').removeClass('collapse');
        }
    });

    $("#side-menu a:not(.dropdown),#side-menu .nav-second-level a").click(function (e) {
        e.preventDefault();
        loadPage($(this).data("cat"), $(this).data("page"));
        return false;
    });

    $(document).on("click", ".saveButton", function () {
        $("#page-wrapper form.ajaxform").each(function () {
            $(this).submit();
        });
    });

    $(document).on("click", "#page-wrapper .panel-heading", function (e) {
        $(this).siblings(".panel-body, .panel-footer, .table").toggle();
        e.stopPropagation();
    });

    loadPage('dashboard', 'dashboard');
});

function loadPage(cat, page) {
    $('#page-wrapper').load(convertUrl({module: "admin", action: cat, x: page}), function () {
        $("#page-wrapper form.form-validate").bootstrapValidator();
    });
    window.setTimeout(function () {
        loadExternalFile(siteurl + "lib/modules/Admin/styles/" + cat + "/css/" + page + ".css", "css");
        loadExternalFile(siteurl + "lib/modules/Admin/styles/" + cat + "/javascript/" + page + ".js", "js");
    }, 500);
}

function loadExternalFile(filename, filetype) {
    if (filetype === "js") { //if filename is a external JavaScript file
        var fileref = document.createElement('script');
        fileref.setAttribute("type", "text/javascript");
        fileref.setAttribute("src", filename);
    }
    else if (filetype === "css") { //if filename is an external CSS file
        var fileref = document.createElement("link");
        fileref.setAttribute("rel", "stylesheet");
        fileref.setAttribute("type", "text/css");
        fileref.setAttribute("href", filename);
    }
    if (typeof fileref !== "undefined")
        document.getElementsByTagName("head")[0].appendChild(fileref);
}

function showPanelResult(res) {
    if (res.status) {
        $('#' + res.panel + " > .panel-heading .panel-feedback-success").show();
        $('#' + res.panel + " > .panel-heading .panel-feedback-error").hide();
    } else {
        $('#' + res.panel + " > .panel-heading .panel-feedback-success").hide();
        $('#' + res.panel + " > .panel-heading .panel-feedback-error").show();
    }
}