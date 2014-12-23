var currentImgId = 0, currentScrollPane = null;
function fillModal(res, el) {
    currentImgId = res.id;
    if (res.caption.length > 0)
        el.find(".imagetitle").html(res.caption).show();
    else
        el.find(".imagetitle").hide();
    $("body").css("overflow", "hidden");
    el.find(".modal-body-info,.blueimp-gallery-image").show();
    el.find(".time > span").html(convertDate(res.time));
    el.find(".userinfo img").attr("src", checkImage(res.profileImage, "user", "cr_"));
    el.find(".userbox a").html(res.name).attr("href", convertUrl({module: "profile", "action": res.username}));
    if (res.likes === 0 && res.dislikes === 0)
        $(".imagelikes").hide();
    else
        $(".imagelikes").show();
    if($(window).width() > 767) {
                
        if (el.find(".blueimp-gallery-image").height() < el.find(".modal-body").innerHeight())
            el.find(".blueimp-gallery-image").css({"marginTop": (el.find(".modal-body").innerHeight() - el.find(".blueimp-gallery-image").height()) / 2});
        if (el.find(".blueimp-gallery-image").width() < el.find(".modal-body").innerWidth())
            el.find(".blueimp-gallery-image").css({"marginLeft": (el.find(".modal-body").innerWidth() - el.find(".blueimp-gallery-image").width() - 350) / 2});
    } else {
        el.find(".modal-body").css("maxHeight",$(window).height() - 12);
    }
    el.find(".image-index").html(Number($(el).data("index")) + 1);
    el.find(".image-full-count").html(res.album.photo_count);
    el.find(".lightbox-album-name").html(res.album.title);
    el.find(".lightbox-album-link").attr("href", convertUrl({module: "gallery", "action": res.album.id}));
    el.find(".modal-body-info").height(el.find(".modal-body").innerHeight() - el.find(".modal-footer").outerHeight());
    el.find(".commentbox").innerHeight(el.find(".modal-body-info").innerHeight() - el.find(".userinfo").outerHeight() - el.find(".imagetitle").outerHeight() - el.find(".newimagebox").outerHeight() - el.find(".likebox").outerHeight() - 1);
    if (res.owner_id === userid && res.owner_type === null) {
        el.find(".optionsdropdown").show();
    } else {
        el.find(".optionsdropdown").hide();
    }
    $("#lightbox-dislikes > div,#lightbox-likes > div").empty();
    if (typeof res.likes !== "undefined" && res.likes.length > 0) {
        $("#lightbox-likes > span.label").hide();
        for (x in res.likes) {
            if (x == 5)
                break;
            $("#lightbox-likes > div").prepend(tmpl("like", res.likes[x]));
        }
    } else
        $("#lightbox-likes > span.label").show();
    if (typeof res.dislikes !== "undefined" && res.dislikes.length > 0) {
        $("#lightbox-dislikes > span.label").hide();
        for (x in res.dislikes) {
            if (x == 5)
                break;
            $("#lightbox-dislikes > div").prepend(tmpl("like", res.dislikes[x]));
        }

    } else
        $("#lightbox-dislikes > span.label").show();
    if (res.ownlike !== null) {
        el.find(".imageunlike").show();
        if (res.ownlike === 1) {
            el.find(".imagedislike").hide();
            el.find(".imagelike").show();
        } else {
            el.find(".imagelike").hide();
            el.find(".imagedislike").show();
        }
    }
    if (res.comments.length > 0) {
        var comments = "";
        for (var c in res.comments) {
            res.comments[c].ref = {userid: res.userid};
            comments = tmpl("imagecomment", res.comments[c]) + comments;
        }
        el.find(".comments > .list").html(comments);
    }
    if (window.innerWidth > 1023) {
        cs = el.find(".commentbox").jScrollPane({
            stickToBottom: true
        });
        currentScrollPane = cs.data('jsp');
    }
    el.find(".image-loading").hide();
}

function deleteImage(imgid) {
    bootbox.confirm("Are you sure You want to delete this image?", function(res) {
        if (res) {
            sendRequest({"imageid": imgid}, "gallery", "deleteImage", function() {
                $("#image-" + imgid).fadeOut().remove();
                $("#blueimp-gallery").data('gallery').close();
            });
        }
    });
}

function loadImage(imgid, slideElement) {
    var el = $(slideElement);
    sendRequest({"id": imgid}, "gallery", "loadImage", function(res) {
        fillModal(res, el);
    });
}
$(document).ready(function() {
    $("#blueimp-gallery").on('slide', function(event, index, slide) {
        var imgid = $($(this).data("gallery").list[index]).data("imageid");
        loadImage(imgid, slide);
    });
    $(document).on("click", ".blueimp-gallery .imagelike", function() {
        $(".imagedislike,.imageunlike,.imagelike").prop("disabled", true);
        var el = $(this);
        like("image", currentImgId, function(res) {
            if (res.likes === 0 && res.dislikes === 0)
                $(".imagelikes").hide();
            else
                $(".imagelikes").show();

            $(".imagelikes .likecount").html(res.likes);
            $(".imagelikes .dislikecount").html(res.dislikes);
            el.hide();
            el.next().show();
            el.parent().find(".imageunlike").show();
            $(".imagedislike,.imageunlike,.imagelike").prop("disabled", false);
            if (currentScrollPane !== null)
                currentScrollPane.reinitialise();
        });
    }).on("click", ".blueimp-gallery .imagedislike", function() {
        $(".imagedislike,.imageunlike,.imagelike").prop("disabled", true);
        var el = $(this);
        dislike("image", currentImgId, function(res) {
            if (res.likes === 0 && res.dislikes === 0)
                $(".imagelikes").hide();
            else
                $(".imagelikes").show();

            $(".imagelikes .likecount").html(res.likes);
            $(".imagelikes .dislikecount").html(res.dislikes);
            el.hide();
            el.prev().show();
            el.parent().find(".imageunlike").show();
            $(".imagedislike,.imageunlike,.imagelike").prop("disabled", false);
            if (currentScrollPane !== null)
                currentScrollPane.reinitialise();
        });
    }).on("click", ".blueimp-gallery .imageunlike", function() {
        $(".imagedislike,.imageunlike,.imagelike").prop("disabled", true);
        var el = $(this);
        unlike("image", currentImgId, function(res) {
            if (res.likes === 0 && res.dislikes === 0)
                $(".imagelikes").hide();
            else
                $(".imagelikes").show();

            $(".imagelikes .likecount").html(res.likes);
            $(".imagelikes .dislikecount").html(res.dislikes);
            el.prev().show();
            el.prev().prev().show();
            el.hide();
            $(".imagedislike,.imageunlike,.imagelike").prop("disabled", false);
            if (currentScrollPane !== null)
                currentScrollPane.reinitialise();
        });
    }).on("focus", ".image-comment textarea", function() {
        $(this).animate({height: 60});
        $(this).next().show();
    }).on("blur", ".image-comment textarea", function() {
        $(this).animate({height: 40});
        $(this).next().hide();
    }).on("keydown", ".image-comment textarea", function(e) {
        var el = $(this);
        if (e.which === 13) {
            var msg = el.val();
            if (msg.length > 0) {
                comment("image", currentImgId, msg, function(res) {
                    if (parseInt(postid) > 0) {
                        loadSinglePost(postid);
                    }

                    el.val("");
                    el.trigger("blur");
                    el.parents(".modal-body-content").find(".comments").append(tmpl("imagecomment", res));
                    if (currentScrollPane !== null) {
                        currentScrollPane.reinitialise();
                        currentScrollPane.scrollToBottom();
                    }
                });
            }
            e.preventDefault();
        }
    }).on("click", ".socialbox .morecomments", function() {
        var el = $(this);
        sendRequest({
            ref_name: "image",
            ref_id: currentImgId,
            last: el.siblings("div").find(".image-comment:first").data("id"),
            limit: 10
        }, "comments", "get", function(res) {
            if (typeof res.comments !== "undefined" && res.comments.length > 0) {
                for (x in res.comments)
                    el.siblings("div").prepend(tmpl("imagecomment", res.comments[x]));
            }
            if (res.comments.length === 10)
                el.show();
            else
                el.hide();
            if (currentScrollPane !== null)
                currentScrollPane.reinitialise();
        });
    });
    $("#blueimp-gallery").on('closed', function(event, index, slide) {
        $("body").css("overflow", "auto");
        $("#blueimp-gallery").css("display", "none");
    });
    $(".image-comment textarea").unbind();
});