var origShow = jQuery.fn.show, origHide = jQuery.fn.hide, inactiveCounter = 0, cStorage = new CunityStorage("session");
jQuery.fn.show = function() {
    $(this).removeClass("hidden");
    return origShow.apply(this, arguments);
};
jQuery.fn.hide = function() {
    $(this).addClass("hidden");
    return origHide.apply(this, arguments);
};

$.ajaxSetup({
    cache: false
});

function onBlur() {
    document.body.className = 'blurred';
}
function onFocus() {
    document.body.className = 'focused';
}

function sendRequest(requestData, module, action, callback) {
    if ($("body").hasClass("blurred"))
        inactiveCounter++;
    else
        inactiveCounter = 0;
    if (inactiveCounter >= 30) //30 for 5 minutes! (30 x 10 seconds)
        requestData.inactive = true;

    if (typeof requestData !== "undefined") {
        return $.ajax({
            url: convertUrl({module: module, action: action}),
            dataType: "json",
            data: requestData,
            type: 'POST',
            success: function(data) {
                if (typeof data.session !== "undefined" && data.session === 0) {
                    alert("Your session is timed out! Please login again!");
                    location.href = convertUrl({module: "start"});
                } else if (data.status == false) {
                    if (typeof data.msg == "undefined")
                        data.msg = "Error";
                    bootbox.alert(data.msg);
                } else if (typeof callback == 'function') {
                    callback(data);
                    refreshTime();
                }
            }
        });
    }
    return null;
}

function convertUrl(data) {
    if (modrewrite) {
        var str = siteurl + data.module;
        if (typeof data.action !== "undefined" && data.action !== null)
            str += "/" + data.action;
        if (typeof data.x !== "undefined" && data.x !== null)
            str += "/" + data.x;
        if (typeof data.y !== "undefined" && data.y !== null)
            str += "/" + data.y;
        return str;
    } else {
        var str = siteurl + "index.php?m=" + data.module;
        if (typeof data.action !== "undefined" && data.x !== null)
            str += "&action=" + data.action;
        if (typeof data.x !== "undefined" && data.x !== null)
            str += "&x=" + data.y;
        if (typeof data.y !== "undefined" && data.x !== null)
            str += "&y=" + data.y;
        return str;
    }
}

function checkImage(filename, type, prefix) {
    prefix = (typeof prefix === "undefined") ? "" : prefix;
    if (filename === null || typeof filename === "undefined" || filename.length === 0)
        return siteurl + 'style/' + design + '/img/placeholders/noimg-' + type + '.png';
    return prefix + filename;
}
function getErrorMessage(tplname) {
    return tmpl(tplname, {});
}

function like(ref_name, ref_id, callback) {
    sendRequest({"ref_name": ref_name, "ref_id": ref_id}, "likes", "like", callback);
}

function dislike(ref_name, ref_id, callback) {
    sendRequest({"ref_name": ref_name, "ref_id": ref_id}, "likes", "dislike", callback);
}

function unlike(ref_name, ref_id, callback) {
    sendRequest({"ref_name": ref_name, "ref_id": ref_id}, "likes", "unlike", callback);
}

function comment(ref_name, ref_id, content, callback) {
    sendRequest({"ref_name": ref_name, "ref_id": ref_id, "content": content}, "comments", "add", callback);
}

function deleteComment(comment_id) {
    bootbox.confirm("Are You sure you want to delete this comment?", function(r) {
        if (r) {
            sendRequest({"comment_id": comment_id}, "comments", "remove", function() {
                $("#comment-" + comment_id).remove();
            });
        }
    });
}

function getLikes(refname, refid, dislike, title) {
    sendRequest({"ref_name": refname, "ref_id": refid, "dislike": dislike}, "likes", "get", function(res) {
        if (typeof res.likes !== "undefined" && res.likes.length > 0) {
            bootbox.dialog({
                message: function() {
                    var msg = "";
                    for (x in res.likes)
                        msg += tmpl("like-list", res.likes[x]);
                    return '<div class="likesmodalbox">' + msg + '</div>';
                },
                title: title,
                buttons: {
                    main: {
                        label: "Ok",
                        className: "btn-primary"
                    }
                },
                className: "likelistmodal"
            });
        }
    });
}

function refreshTime() {
    $(".timestring").each(function() {
        var el = $(this);
        el.replaceWith(function() {
            return convertDate(el.data("source"));
        });
    });
    refreshTooltip();
}

function refreshTooltip() {
    $(".tooltip").remove();
    $(".tooltip-trigger").tooltip({
        container: 'body'
    });
}

function convertDate(timestring) {
    if (typeof timestring === "undefined" || timestring === "" || timestring === null)
        return "NaN";
    var now = new Date();
    var then = new Date(timestring.replace(/-/g, '/'));
    then.setMinutes(then.getMinutes() + now.getTimezoneOffset() * (-1));
    return '<span class="tooltip-trigger timestring" data-source="' + timestring + '" data-title="' + moment(then).format("LLLL") + '">' + moment(then).fromNow() + '</span>';
}

function escapeRegExp(string) {
    return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
}

function replaceAll(find, replace, str) {
    return str.replace(new RegExp(escapeRegExp(find), 'g'), replace);
}

function CunityStorage(type) {
    if (type === "session")
        this.storage = sessionStorage;
    else if (type === "local")
        this.storage = localStorage;

    this.set = function(key, value) {
        if (typeof value === "object")
            value = JSON.stringify(value);
        this.storage.setItem(key, value);
    };

    this.get = function(key) {
        var v = this.storage.getItem(key);
        try {
            return JSON.parse(v);
        } catch (e) {
            return v;
        }
    };

    this.destroy = function() {
        this.storage.clear();
    };
}

function sendAjaxForm(form) {
    $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        dataType: "json"
    }).done(function(data) {
        form.find("input[type='submit'],button[type='submit']").button('reset');
        if (form.find(".ajaxform-callback").length > 0) {
            var callback = window[form.find(".ajaxform-callback").val()];
            if (data.status === false) {
                bootbox.alert(data.msg);
            } else if (typeof callback === "function")
                callback(data);
        }
    });
}

$(document).ready(function() {

    //noinspection ConstantIfStatementJS
    if (/*@cc_on!@*/false) { // check for Internet Explorer
        document.onfocusin = onFocus;
        document.onfocusout = onBlur;
    } else {
        window.onfocus = onFocus;
        window.onblur = onBlur;
    }


    $(".sidebar").css("minHeight", window.innerHeight - 101);
    $('input.filefakeinput').change(function() {
        $($(this).data("rel")).val($(this).val());
    });

    $('.dropdown-menu').on('click', function(e) {
        if ($(this).hasClass('dropdown-checkbox-menu')) {
            e.stopPropagation();
        }
    });

    $('.btn[data-loading-text]').click(function() {
        $(this).button('loading');
    });

    $("a[href=\"#\"],a[href=\"\"]").click(function(e) {
        e.preventDefault();
    });

    $("#mobile-slide-nav > .mobile-menu").html($(".main-menu > .nav").html());
    $(".page-buttonbar .btn:not(.desktop-only)").each(function() {
        var el = $(this);
        $("#mobile-option").append('<li role="presentation"><a role="menuitem" tabindex="-1" onclick="' + el.attr("onclick") + '">' + el.data("title") + '</a></li>');
    });

    $("form.form-validate").bootstrapValidator();

    $(document).on("click", "#menu-trigger", function() {
        if (login) {
            if ($("body").hasClass("menu-active"))
                $("body").removeClass("menu-active");
            else
                $("body").addClass("menu-active");
        } else
            location.href = convertUrl({module: "start"});
    }).on("mouseenter", ".notification-general-item:not(.read)", function() {
        var el = $(this);
        sendRequest({id: el.data("id")}, "notifications", "markRead", function() {
            el.addClass("read");
            el.find(".label-new").fadeOut("slow");
        });
    });

    $(document).on("click", "a.close", function(e) {
        e.stopPropagation();
    });

    $(document).on("submit", "form.ajaxform", function(e) {
        e.preventDefault();
        var form = $(this);
        form.find("input[type='submit'],button[type='submit']").button('loading');
        if (form.prop("ajaxform-send") === true)
            return;
        if (form.hasClass("bv-form")) {
            form.data("bootstrapValidator").validate();
            if (!form.data("bootstrapValidator").isValid())
                return;
        }
        if (form.attr("enctype") === "multipart/form-data") {
            var name = "ajaxformframe" + Math.random();
            var frame = $("<iframe/>", {"name": name, class: "hidden"}).appendTo(form);
            form.attr("target", name).prop("ajaxform-send", true).submit();
            frame.on("load", function() {
                form.find("input[type='submit'],button[type='submit']").button('reset');
                if (form.find(".ajaxform-callback").length > 0) {
                    console.log(frame.contents().find('body').html());
                    var callback = window[form.find(".ajaxform-callback").val()];
                    if (typeof callback === "function")
                        callback(jQuery.parseJSON(frame.contents().find('body').html()));
                }
            });
        } else {
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize(),
                dataType: "json"
            }).done(function(data) {
                form.find("input[type='submit'],button[type='submit']").button('reset');
                if (form.find(".ajaxform-callback").length > 0) {
                    var callback = window[form.find(".ajaxform-callback").val()];
                    if (data.status === false) {
                        bootbox.alert(data.msg);
                    } else if (typeof callback === "function")
                        callback(data);
                }
            });
        }
    }).on("click", function(e) {
        if ($(e.target).attr("id") !== "notification-dropdown" && $("#notification-dropdown").length > 0)
            $("#notification-dropdown").hide();
    });

    $("#infoModal").on('show.bs.modal', function(e) {
        $("#infoModal").find(".modal-title").html($(e.relatedTarget).data("title"));
        if (typeof $(e.relatedTarget).data("href") !== "undefined")
            $("#infoModal").find(".modal-body").html('<iframe src="' + $(e.relatedTarget).data("href") + '" style="border:0;width:100%"></iframe>').css("padding", 10);
    });

    $("*[data-moveto]").each(function() {
        $(this).appendTo($(this).data("moveto")).removeAttr("data-moveto");
    });
});