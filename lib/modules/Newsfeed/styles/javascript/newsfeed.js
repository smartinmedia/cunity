var offset = 0, postLoad = 20, globeFilter, uploader = null;
$(document).ready(function () {
    load(false);
    $(document).on("click", ".newsfeed-post-area,.newsfeed-post-option", function () {
        $(".newsfeed-post-option").hide();
        $(".newsfeed-post-option-buttons").show();
        $(".newsfeed-post-area > textarea").outerHeight(67).focus();
        $(".newsfeed-post-buttons").show();
        load(true);
    }).on("click", ".newsfeed-post-info .like:not(.liked)", function (e) {
        var el = $(this), postid = el.data("postid"), refid = el.data("refid");
        like(el.data("type"), refid, function (res) {
            $("#post-" + postid + " .newsfeed-post-info-buttons .likecount").html(zeroReplace(res.likes, "Like"));
            $("#post-" + postid + " .newsfeed-post-info-buttons .dislikecount").html(zeroReplace(res.dislikes, "Dislike"));
            $("#post-" + postid + " .newsfeed-post-info-buttons .like").addClass("liked");
            $("#post-" + postid + " .newsfeed-post-info-buttons .dislike").removeClass("liked");
            $("#post-" + postid + " .newsfeed-post-info-buttons .unlike").show();
            loadSinglePost(postid);
        });
        e.stopPropagation();
    }).on("click", ".newsfeed-post-info .dislike:not(.liked)", function (e) {
        var el = $(this), postid = el.data("postid"), refid = el.data("refid");
        dislike(el.data("type"), refid, function (res) {
            $("#post-" + postid + " .newsfeed-post-info-buttons .likecount").html(zeroReplace(res.likes, "Like"));
            $("#post-" + postid + " .newsfeed-post-info-buttons .dislikecount").html(zeroReplace(res.dislikes, "Dislike"));
            $("#post-" + postid + " .newsfeed-post-info-buttons .dislike").addClass("liked");
            $("#post-" + postid + " .newsfeed-post-info-buttons .like").removeClass("liked");
            $("#post-" + postid + " .newsfeed-post-info-buttons .unlike").show();
            loadSinglePost(postid);
        });
        e.stopPropagation();
    }).on("click", ".newsfeed-post-info .unlike", function (e) {
        var el = $(this), postid = el.data("postid"), refid = el.data("refid");
        unlike(el.data("type"), refid, function (res) {
            $("#post-" + postid + " .newsfeed-post-info-buttons .likecount").html(zeroReplace(res.likes, "Like"));
            $("#post-" + postid + " .newsfeed-post-info-buttons .dislikecount").html(zeroReplace(res.dislikes, "Dislike"));
            $("#post-" + postid + " .newsfeed-post-info-buttons .like,#post-" + postid + " .newsfeed-post-info-buttons .dislike").removeClass("liked");
            loadSinglePost(postid);
            el.hide();
        });
        e.stopPropagation();
    }).on("click", ".newsfeed-post", function (e) {
        e.stopPropagation();
        var el = $(this), postid = el.data("id");
        if ($("#post-" + postid).hasClass("newsfeed-post-opened"))
            return;
        loadSinglePost(postid);
    }).on("click", ".newsfeed-post-close-detail", function (e) {
        $("#post-" + $(this).data("postid")).removeClass("newsfeed-post-opened").find(".newsfeed-post-detail").slideUp("fast");
        $(this).hide();
        e.stopPropagation();
    }).on("focus", ".post-comment textarea", function () {
        $(this).height(42);
        $(this).parent().find(".sendbutton").show();
    }).on("click", ".sendbutton", function () {
        var el = $("#commentbox-" + $(this).data("postid"));
        addComment(el.data("postid"), el.data("refid"), el.val(), el.data("type"));
    }).on("click", ".morecomments", function () {
        var postid = $(this).data("postid");
        sendRequest({
            ref_name: $(this).data("type"),
            ref_id: $(this).data("refid"),
            last: $("#comments-" + postid + " .post-comment:last").data("id"),
            limit: 10
        }, "comments", "get", function (res) {
            if (typeof res.comments !== "undefined" && res.comments.length > 0) {
                for (x in res.comments) {
                    var post = $("#post-" + postid).data("post");
                    res.comments[x].ref = {userid: post.userid, owner_id: post.owner_id};
                    $("#comments-" + postid).append(tmpl("newsfeed-comment", res.comments[x]));
                }
            }
            if (res.comments.length === 10)
                $("#comments-more-" + postid).show();
            else
                $("#comments-more-" + postid).hide();
        });
    }).on("click", "#newsfeed-post-button", function () {
        if (uploader !== null)
            uploader.start();
    }).on("keyup", ".newsfeed-post-area > textarea", function () {
        var youtubereg = $(this).val().match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i);
        var urlreg = $(this).val().match(/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/);
        if (youtubereg !== null) {
            var vid = youtubereg[1];
            $.getJSON("http://gdata.youtube.com/feeds/api/videos/" + vid + "?v=2&alt=json", function (data) {
                $(".newsfeed-newpost textarea").css({"borderBottomLeftRadius": 0, "borderBottomRightRadius": 0});
                $(".newsfeed-newpost .newsfeed-post-video-box").html(tmpl("newsfeed-video", data.entry)).show();
                $(".newsfeed-newpost input[name='type']").val("video");
                $(".newsfeed-newpost input[name='youtubedata']").val(JSON.stringify({
                    vid: vid,
                    title: data.entry.title.$t,
                    description: data.entry.media$group.media$description.$t.substring(0, 200),
                    link: data.entry.link[0].href,
                    thumbnail: data.entry.media$group.media$thumbnail[1].url
                }));
            });
        } else {
            $(".newsfeed-newpost textarea").css({"borderBottomLeftRadius": "", "borderBottomRightRadius": ""});
            $(".newsfeed-newpost  .newsfeed-post-video-box").hide();
            $(".newsfeed-newpost input[name='youtubedata']").val("");
            $(".newsfeed-newpost input[name='type']").val("post");
        }
    });

    window.setInterval(function () {
        if (!$("body").hasClass("blurred"))
            load(true);
    }, 25000);
});
function addComment(postid, refid, msg, type) {
    if (msg.length > 0) {
        comment(type, refid, msg, function (res) {
            $("#commentbox-" + postid).val("").height(22).parent().find(".sendbutton").hide();
            $("#comments-" + postid).prepend(tmpl("newsfeed-comment", res));
            loadSinglePost(postid);
        });
    }
}

function play(id) {
    $("#newsfeed-post-video-" + id).attr("src", $("#newsfeed-post-video-" + id).data("src")).show();
    $("#newsfeed-post-playable-" + id).hide();
}

function loadSinglePost(postid) {
    sendRequest({"postid": postid}, "newsfeed", "loadPost", function (res) {
        if ((typeof res.likes === "undefined" || res.likes.length === 0) && (typeof res.dislikes === "undefined" || res.dislikes.length === 0))
            $("#post-" + postid + " .newsfeed-post-detail-likebox").hide();
        else
            $("#post-" + postid + " .newsfeed-post-detail-likebox").show();
        $("#dislikes-" + postid + " > div,#likes-" + postid + " > div").empty();
        if (typeof res.likes !== "undefined" && res.likes.length > 0) {
            $("#likes-" + postid + " > span.label").hide();
            for (x in res.likes) {
                if (x == 5)
                    break;
                $("#likes-" + postid + " > div").prepend(tmpl("like", res.likes[x]));
            }
        } else
            $("#likes-" + postid + " > span.label").show();
        if (typeof res.dislikes !== "undefined" && res.dislikes.length > 0) {
            $("#dislikes-" + postid + " > span.label").hide();
            for (x in res.dislikes) {
                if (x == 5)
                    break;
                $("#dislikes-" + postid + " > div").prepend(tmpl("like", res.dislikes[x]));
            }

        } else
            $("#dislikes-" + postid + " > span.label").show();
        if (typeof res.comments !== "undefined" && res.comments.length > 0) {
            $("#comments-" + postid).html("");
            for (x in res.comments) {
                res.comments[x].ref = {userid: res.post.userid, owner_id: res.post.owner_id};
                $("#comments-" + postid).append(tmpl("newsfeed-comment", res.comments[x]));
            }
        }
        if (res.post.commentcount > $("#comments-" + postid + " .post-comment").length)
            $("#comments-more-" + postid).show();
        else
            $("#comments-more-" + postid).hide();
        if (res.post.commentcount > 0) {
            $('.commentcount').text(res.post.commentcount);
        }
        $("#post-" + postid).addClass("newsfeed-post-opened").find(".newsfeed-post-detail").slideDown("fast");
        $("#post-" + postid).find(".newsfeed-post-close-detail").show();
    });
}

function postText() {
    if (uploader !== null)
        uploader.destroy();
    $(".newsfeed-post-file-input > button").hide();
    $("#selected-file").empty();
    $("#newsfeed-post-button").attr("type", "submit");
}

function postImage() {
    $(".newsfeed-post-file-input > .loader-small").show();
    $.ajax({
        url: siteurl + "lib/plugins/plupload/js/plupload.full.min.js",
        dataType: "script",
        success: function () {
            uploader = new plupload.Uploader({
                runtimes: 'html5,flash,silverlight,html4',
                browse_button: 'newsfeed-upload',
                multi_selection: false,
                chunk_size: "500kb",
                unique_names: true,
                url: convertUrl({module: "gallery", action: "upload"}),
                flash_swf_url: siteurl + "lib/plugins/plupload/js/Moxie.swf",
                    silverlight_xap_url: siteurl + "lib/plugins/plupload/js/Moxie.xap",
                filters: {
                    max_file_size: '10mb',
                    mime_types: [
                        {title: "Image files", extensions: "jpg,gif,png"}
                    ]
                },
                init: {
                    FilesAdded: function (up, files) {
                        $("#selected-file").html(files[0].name);
                    },
                    FileUploaded: function (up, file, res) {
                        sendPost(jQuery.parseJSON(res.response));
                    },
                    BeforeUpload: function () {
                        $("#newsfeed-post-button").button('loading');
                        uploader.setOption("multipart_params", {
                            "wall_owner_id": $(".newsfeed-newpost input[name='wall_owner_id']").val(),
                            "wall_owner_type": $(".newsfeed-newpost input[name='wall_owner_type']").val(),
                            "content": $(".newsfeed-newpost textarea[name='content']").val(),
                            "privacy": $(".newsfeed-newpost input[name='privacy']").val(),
                            "newsfeed_post": "true"
                        });
                    }
                }
            });
            uploader.bind("Error", function (e, b) {
                bootbox.alert(b.message);
            });
            uploader.init();
            $(".newsfeed-post-file-input > .loader-small").hide();
            $(".newsfeed-post-file-input > button").show();
            $("#newsfeed-post-button").attr("type", "button");
        },
        cache: true
    });

}

function load(refresh, filter) {
    $("#newsfeed-posts > .alert-danger").hide();
    refreshId = 0;
    if (refresh) {
        refreshId = $("#newsfeed-posts > .newsfeed-post:first").data("id");
        filter = globeFilter;
        $("#newsfeed-refresh > i.fa").addClass("fa-spin");
    } else
        $("#newsfeed-loader").show();
    if (typeof filter !== "undefined" && !refresh) {
        $("#newsfeed-posts > .newsfeed-post").remove();
        globeFilter = filter;
        offset = 0;
    }
    sendRequest({
        wall_owner_id: $("#newsfeed-owner-id").val(),
        wall_owner_type: $("#newsfeed-owner-type").val(),
        offset: offset,
        refresh: refreshId,
        filter: filter
    }, "newsfeed", "load", function (res) {
        $("#newsfeed-loader").hide();
        if (typeof res.posts !== "undefined" && res.posts.length > 0) {
            for (x in res.posts) {
                res.posts[x].refid = (res.posts[x].type === "image") ? res.posts[x].refid : res.posts[x].id;
                if (refresh)
                    $("#newsfeed-posts").prepend(tmpl("newsfeed-post", res.posts[x]));
                else
                    $("#newsfeed-posts").append(tmpl("newsfeed-post", res.posts[x]));
                $("#post-" + res.posts[x].id).data("post", res.posts[x]);
            }
            if (!refresh) {
                if (res.posts.length < postLoad)
                    $(".newsfeed-postbox-load-more").hide();
                else
                    $(".newsfeed-postbox-load-more").show();
                offset += res.posts.length;
            }
        } else if (!refresh)
            $(".newsfeed-postbox-load-more").hide();
        $("#newsfeed-refresh > i.fa").removeClass("fa-spin");
        refreshAlert();
    });
}

function selectPostPrivacy(privacy) {
    $("#postPrivacy").val(privacy);
}

function sendPost(res) {
    res.new = true;
    res.refid = (res.type === "image") ? res.refid : res.id;
    $("#newsfeed-posts").prepend(tmpl("newsfeed-post", res));
    $("#post-" + res.id).data("post", res);
    $("#newsfeed-posts .newsfeed-post-new").slideDown();
    $(".newsfeed-post-option").show();
    $(".newsfeed-post-option-buttons").hide();
    $(".newsfeed-post-area > textarea").val("").outerHeight(34);
    $(".newsfeed-post-buttons").hide();
    $("#newsfeed-post-button").button('reset');

    $(".newsfeed-newpost textarea").css({"borderBottomLeftRadius": "", "borderBottomRightRadius": ""});
    $(".newsfeed-newpost  .newsfeed-post-video-box").hide();
    $(".newsfeed-newpost input[name='youtubedata']").val("");
    $(".newsfeed-newpost input[name='type']").val("post");
    postText();

    refreshAlert();
}

function trimContent(cont) {
    return cont; //.substring(0,150)+'<a href="">(...)</a>'; 
}

function zeroReplace(count, rep) {
    if (count == 0 || typeof count == "undefined" || count == null)
        return rep;
    return count;
}

function deletePost(postid) {
    bootbox.confirm("Are You sure You want to delete this post?", function (r) {
        if (r) {
            sendRequest({id: postid}, "newsfeed", "delete", function () {
                $("#post-" + postid).remove();
                refreshAlert();
            });
        }
    });
}

function refreshAlert() {
    if ($("#newsfeed-posts > .newsfeed-post").length === 0)
        $("#newsfeed-posts > .alert-danger").show();
    else
        $("#newsfeed-posts > .alert-danger").hide();
}

function applyFilter() {
    var filter = [];
    $(".newsfeed-filter").each(function () {
        var e = $(this);
        if (e.is(":checked"))
            filter.push(e.val());
    });
    $("#filter-dropdown").dropdown('toggle');
    load(false, filter);
}