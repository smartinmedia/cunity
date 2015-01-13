var scrollApi, countOffset, smileys;
scrollApi = null;
countOffset = 0;
smileys = {};
$(document).ready(function () {
    if (window.innerWidth > 1023) {
        var scroll = $("#conversation-content").jScrollPane({
            stickToBottom: true
        });
        scrollApi = scroll.data('jsp');
    }

    $("input.cusers").each(function () {
        selectedReceiver[$(this).val()] = 1;
    });


    $(document).on("mouseenter", "#conversation-content .conversation-item-me .conversation-item-content-wrap", function () {
        $(this).find("button.close").show();
    }).on("mouseleave", "#conversation-content .conversation-item-me .conversation-item-content-wrap", function () {
        $(this).find("button.close").hide();
    }).on("click", ".conversation-message-delete", function () {
        var id = $(this).data("msgid"), msg = $(this).data("msg");
        bootbox.confirm(msg, function (result) {
            if (result) {
                sendRequest({msgid: id}, "messages", "deletemessage", function (res) {
                    $("#conversation-message-" + id).remove();
                    if (scrollApi !== null)
                        scrollApi.reinitialise();
                });
            }
        });
    }).on("click", ".leaveConversation", function (e) {
        e.preventDefault();
        bootbox.dialog({
            message: $(this).data("confirmation"),
            title: "Leave Conversation",
            closeButton: true,
            buttons: {
                danger: {
                    label: "Keep Messages",
                    className: "btn-warning",
                    callback: function () {
                        leaveConversation(false);
                    }
                },
                success: {
                    label: "Delete messages",
                    className: "btn-success",
                    callback: function () {
                        leaveConversation(true);
                    }
                }
            }
        });
    }).on("keyup", ".conversation-writingbox div.message:visible", function () {

        $("#temp").html($(this).html()).find("img").each(function () {
            $(this).replaceWith("[:" + $(this).data("key") + ":]");
        });
        $("#hiddenmessagebox").val($("#temp").text());

        if ($(this).html() !== "")
            $(".conversation-writingbox input[type='submit']").prop('disabled', false);
        else
            $(".conversation-writingbox input[type='submit']").prop('disabled', true);
    }).on("click", ".emoticon-select", function () {
        var key = $(this).data("key");
        $(".conversation-writingbox div.message:visible").html(function (i, val) {
            return val + smileys[key];
        }).trigger("keyup").focus();
        $('.conversation-add-emoticon').popover('hide');
    });

    $(".conversation-add-emoticon").popover({
        html: true,
        container: 'body',
        content: function () {
            return $("#conversation-emoticon-list > div").html();
        }
    });

    $.getJSON(siteurl + "style/" + design + "/img/emoticons/emoticons.json", function (data) {
        for (x in data) {
            $("#conversation-emoticon-list > div").append('<img src="' + siteurl + 'style/' + design + '/img/emoticons/' + data[x] + '.png" data-key="' + data[x] + '" class="emoticon-select">');
            smileys[data[x]] = '<img src="' + siteurl + 'style/' + design + '/img/emoticons/' + data[x] + '.png" class="message-smiley" data-key="' + data[x] + '">';
        }
        loadMoreMessages(false);
    });

    window.setInterval(function () {
        if (!$("body").hasClass("blurred"))
            loadMoreMessages(true);
    }, 10000);
});

function loadMoreMessages(refresh) {
    $("#conversation-more").hide();
    if (refresh) {
        refreshId = $("#conversation-content .list > div:last").data("id");
        $("#refreshConversationButton > i.fa").addClass("fa-spin");
    } else
        refreshId = 0;
    sendRequest({
        conversation_id: $("#conversation_id").val(),
        offset: countOffset,
        refresh: refreshId
    }, "messages", "loadConversationMessages", function (res) {
        $("#conversation-loader").hide();
        for (x in res.messages) {
            var m = res.messages[x];
            m.pimg = checkImage(m.pimg, "user", "cr_");
            for (x in smileys)
                m.message = replaceAll('[:' + x + ':]', smileys[x], m.message);
            if (refresh)
                $("#conversation-content .list").append(tmpl("conversation-item", m));
            else
                $("#conversation-content .list").prepend(tmpl("conversation-item", m));
        }
        if (!refresh) {
            if (countOffset + 20 < $("#conversation-message-count").val())
                $("#conversation-more").show();
            countOffset += res.messages.length;
        } else
            $("#refreshConversationButton > i.fa").removeClass("fa-spin");

        if (scrollApi !== null)
            scrollApi.reinitialise();
        if ((countOffset === res.messages.length || refresh) && res.messages.length > 0) {
            if (scrollApi !== null)
                scrollApi.scrollToBottom();
            $(".mobile-page-wrapper").scrollTop(640);
        }
    });
}

function sendmessage(res) {
    $(".conversation-writingbox div.message,#temp").empty();
    for (x in smileys)
        res.data.message = res.data.message.replace('[:' + x + ':]', smileys[x]);
    $("#conversation-content .list").append(tmpl("conversation-item", res.data));
    if (scrollApi !== null) {
        scrollApi.reinitialise();
        scrollApi.scrollToBottom();
    }
    if (window.innerWidth < 768)
        $(".mobile-page-wrapper").scrollTop(640);
}

function leaveConversation(delMsgs) {
    sendRequest({
        conversation_id: $("#conversation_id").val(),
        delMsgs: delMsgs
    }, "messages", "leaveConversation", function (res) {
        location.href = convertUrl({module: "messages"});
    });
}
