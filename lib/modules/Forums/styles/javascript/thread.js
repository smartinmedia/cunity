var smileys = new Object(), currentPage = 1;
$(document).ready(function() {
    if (location.hash != "")
        loadThread(Number(location.hash.substring(1)));
    else
        loadThread(currentPage);
    loadCategoryCloud();
    $.getJSON(siteurl + "style/" + design + "/img/emoticons/emoticons.json", function(data) {
        for (x in data) {
            $("#thread-new-emoticons > div").append('<img src="' + siteurl + 'style/' + design + '/img/emoticons/' + data[x] + '.png" data-key="' + data[x] + '" class="emoticon-select">');
            smileys[data[x]] = '<img src="' + siteurl + 'style/' + design + '/img/emoticons/' + data[x] + '.png" class="message-smiley" data-key="' + data[x] + '">';
        }
    });
    $("#thread-new-emoticon-button").popover({
        html: true,
        container: 'body',
        content: function() {
            return $("#thread-new-emoticons > div").html();
        }
    });
    $(document).on("click", ".emoticon-select", function() {
        var key = $(this).data("key"), current = $("#summernote-newpost").code();
        $("#summernote-newpost").code(current + smileys[key]);
        $('#thread-new-emoticon-button').popover('hide');
        $("#newpostForm input[name='content']").val($("#summernote-newpost").code());
    }).on("click", ".deletepost", function() {
        var id = $(this).data("postid");
        bootbox.confirm("Are you sure You want to delete this post?", function(r) {
            sendRequest({id: id}, "forums", "deletePost", function(res) {
                $("#post-" + id).remove();
            });
        });
    });
    $("#summernote-newpost").summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']]
        ],
        onkeyup: function(e) {
            if (e.target.innerHTML.length > 0)
                $("#reply-button").prop("disabled", false);
            else
                $("#reply-button").prop("disabled", true);
        },
        onblur: function() {
            $("#newpostForm input[name='content']").val($("#summernote-newpost").code());
        },
        oninit: function() {
            $("#summernote-newpost").code("");
        }
    });
    $("#thread-pagination a").click(function(e) {
        e.preventDefault();
        var page = $(this).data("page");
        if ($(this).parent("li").hasClass("disabled") || currentPage == page)
            return;
        if (page == "prev")
            page = Number(currentPage - 1);
        else if (page == "next")
            page = Number(currentPage + 1);
        loadThread(page);
    });
    $("#editThreadForm").bootstrapValidator({
        feedbackIcons: {
            valid: 'fa fa-check',
            invalid: 'fa fa-times',
            validating: 'fa fa-refresh'
        },
        message: "This field cannot be blank!",
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
            content: {
                validators: {
                    notEmpty: {
                        message: 'Please enter a description!'
                    },
                    stringLength: {
                        min: 3,
                        message: "Your message is too short! (min. 3 chars)"
                    }
                }
            },
            category: {
                validators: {
                    notEmpty: {
                        message: "Please choose a category"
                    }
                }
            }
        }

    });
});
function loadThread(page) {
    $("#thread-loader").show();
    sendRequest({id: $("#thread_id").val(), page: page}, "forums", "loadPosts", function(res) {
        $("#thread-loader").hide();
        if (typeof res.result !== "undefined" && res.result !== null) {
            $("#posts > .thread-post").remove();
            for (var x in res.result) {
                $("#posts").append(tmpl("post-template", res.result[x]));
                $("#post-" + res.result[x].id).data("post", res.result[x]);
            }

            $("#thread-pagination-prev,#thread-pagination-next").removeClass("disabled");
            var pages = Math.ceil(res.result[0].postcount / 20);
            if (page == pages)
                $("#thread-pagination-next").addClass("disabled");
            if (page == 1)
                $("#thread-pagination-prev").addClass("disabled");
            currentPage = page;
            $("#thread-pagination li").removeClass("active");
            location.hash = page;
            $("#thread-pagination-page-" + page).addClass("active");
        }
        if ($("#posts .thread-post").length === 0)
            $("#posts .alert").show();
        else
            $("#posts .alert").hide();
    });
}

function postReply() {
    $(".thread-new").slideDown();
    $("#reply-button").prop("disabled", true);
    $('html, body').animate({
        scrollTop: $(".thread-new").offset().top
    }, 700);
    var citepost = $("#posts .thread-post:first").data("post");
    if (Math.ceil(citepost.postcount / 20) != currentPage)
        loadThread(Math.ceil(citepost.postcount / 20));
}

function hideReply() {
    $(".thread-new").slideUp();
    $("#newpostForm").data('bootstrapValidator').resetForm(true);
    $("#summernote-newpost").code("");
}

function citePost(id) {
    var citepost = $("#post-" + id).data("post");
    $("#summernote-newpost").code("[quote=" + citepost.username + "]" + citepost.content + "[/quote]");
    $("#newpostForm input[name='content']").val($("#summernote-newpost").code());
    if (Math.ceil(citepost.postcount / 20) != currentPage)
        loadThread(Math.ceil(citepost.postcount / 20));
    $(".thread-new").slideDown();
}

function replyPosted(res) {
    $("#posts").append(tmpl("post-template", res.post));
    $("#post-" + res.post.id).data("post", res.post);
    hideReply();
}

function threadUpdated() {
    location.reload();
}

function deleteThread() {
    bootbox.confirm("Are You sure You want to delete this thread and all posts which belongs to it?", function(r) {
        if (r) {
            sendRequest({id: $("#thread_id").val()}, "forums", "deleteThread", function(res) {
                location.href = convertUrl({"module": "forums"});
            });
        }
    });
}