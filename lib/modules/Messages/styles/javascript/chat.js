var chatBoxes = {}, chatScrollApi = [], chatStatus = true, smileys = [], currentChatPanel = 0;
function chatHearthBeat() {
    if (chatStatus) {
        sendRequest({"chatboxes": chatBoxes}, "messages", "chatHearthBeat", function (data) {
            toggleChat(typeof data.msg === "undefined");
            if (data.users.length > 0) {
                for (var x in data.users) {
                    if ($("#chat-user-" + data.users[x].userid).length > 0 && $("#chat-user-" + data.users[x].userid).is(":hidden")) {
                        $("#chat-user-" + data.users[x].userid).parent().replaceWith(tmpl("onlinefriends", data.users[x]));
                        $("#chat-user-" + data.users[x].userid).hide();
                    } else if ($("#chat-user-" + data.users[x].userid).length > 0)
                        $("#chat-user-" + data.users[x].userid).parent().replaceWith(tmpl("onlinefriends", data.users[x]));
                    else
                        $("#chat-container > .chat-list").prepend(tmpl("onlinefriends", data.users[x]));
                    if (typeof $("#chat-user-" + data.users[x].userid).data("user") === "undefined")
                        $("#chat-user-" + data.users[x].userid).data("user", data.users[x]);
                    else {
                        var d = $("#chat-user-" + data.users[x].userid).data("user");
                        d.onlineStatus = data.users[x].onlineStatus;
                        $("#chat-user-" + data.users[x].userid).data("user", d);
                    }
                }
            }
            if (data.conversations.length > 0) {
                for (var x in data.conversations) {
                    data.conversations[x].conversation_id = data.conversations[x].conversation;
                    openChatBox(data.conversations[x]);
                }
            }
            refreshStatus();
        });
    }
}

function toggleChat(activate) {
    chatStatus = activate;
    if (activate) {
        $("#chat-search").prop("disabled", false);
        $(".chat-online-box").show();
        $(".chat-offline-box").hide();
        if ($("#chat-search").val() === "offline...")
            $("#chat-search").val("");
    } else {
        $("#chat-search").prop("disabled", true).val("offline...");
        $(".chat-online-box").hide();
        $(".chat-offline-box").show();
        $("#chat-container .online-friend-item").remove();
    }
}

function changeChatStatus(st) {
    sendRequest({status: st}, "friends", "chatStatus", function (r) {
        toggleChat(st === 1);
    });
}

function chat(u) {
    if (typeof $("#chat-user-" + u).data("user").conversation_id === "undefined") {
        sendRequest({userid: u}, "messages", "getConversation", function (r) {
            var user = $("#chat-user-" + u).data("user");
            user.conversation_id = r.conversation_id;
            user.users = r.users;
            $("#chat-user-" + u).data("user", user);
            openChatBox(r);
            refreshStatus();
        });
    } else
        openChatBox($("#chat-user-" + u).data("user"));
}

function refreshStatus() {
    for (var i in chatBoxes) {
        var data = $("#chat-panel-" + i).data("conversation"), userData = $("#chat-user-" + data.partners[0]).data("user");
        $("#chat-panel-" + data.conversation_id + " .panel-heading i.fa:first").removeClass("chat-panel-status-inactive chat-panel-status-active chat-panel-status-offline fa-circle-o fa-circle");
        if ((data.partners.length === 1 && typeof userData === "undefined") || (typeof userData === "undefined" || userData.online === 0))
            $("#chat-panel-" + data.conversation_id + " .panel-heading i.fa:first").addClass("chat-panel-status-offline fa-circle-o");
        else if (data.partners.length === 1 && $("#chat-user-" + data.partners[0]).length === 1 && userData.onlineStatus === 0 && userData.online === 1)
            $("#chat-panel-" + data.conversation_id + " .panel-heading i.fa:first").addClass("fa-circle chat-panel-status-inactive");
        else if (data.partners.length === 1 && $("#chat-user-" + data.partners[0]).length === 1 && userData.onlineStatus === 1 && userData.online === 1)
            $("#chat-panel-" + data.conversation_id + " .panel-heading i.fa:first").addClass("fa-circle chat-panel-status-active");
    }
}

function updateStorageData(data) {
    var boxes = cStorage.get("chatBoxes");
    boxes[data.conversation_id] = data;
    cStorage.set("chatBoxes", boxes);
}

function openChatBox(data) {
    updateStorageData(data);

    if (typeof data.users != 'undefined') {
        var users = data.users.split(',');
    } else {
        var users = '';
    }

    data.users = "";
    data.partners = [];
    for (var u in users) {
        var p = users[u].split('|');
        data.users += p[0];
        data.partners.push(p[1]);
    }
    if ($("#chat-panel-" + data.conversation_id).length === 0) {
        chatBoxes[data.conversation_id] = 0;
        $("#chat-container .chat-boxes").prepend(tmpl("chat-panel", data));
        $("#chat-panel-" + data.conversation_id).data("conversation", data);
    }
    if (typeof data.messages !== "undefined" && data.messages.length > 0) {
        $("#chat-panel-" + data.conversation_id + " .chat-panel-list").empty();
        chatBoxes[data.conversation_id] = data.messages[0].id;
        if (data.status === 1)
            $("#chat-panel-" + data.conversation_id + " > .chat-panel").addClass("chat-panel-unread");
        if ($("body").hasClass("blurred"))
            $.ionSound.play("button_tiny");
        for (var x in data.messages) {
            for (y in smileys)
                data.messages[x].message = replaceAll('[:' + y + ':]', smileys[y], data.messages[x].message);
            if (chatBoxes[data.conversation_id] > data.messages[x].id) {
                $("#chat-panel-" + data.conversation_id + " .chat-panel-list").prepend(tmpl("chat-item", data.messages[x]));
            }
            else {
                $("#chat-panel-" + data.conversation_id + " .chat-panel-list").append(tmpl("chat-item", data.messages[x]));
            }
        }
    }
    refreshScrollbar(data.conversation_id);
    $('#closeAllChatsButton').show();
}

function closeChatBox(cid) {
    $("#chat-panel-" + cid).remove();
    var boxes = cStorage.get("chatBoxes");
    delete boxes[cid];
    cStorage.set("chatBoxes", boxes);
    delete chatBoxes[cid];
    delete chatScrollApi[cid];
    $('#closeAllChatsButton').hide();
}

function sendChatMessage(res) {
    $("#chat-panel-" + res.data.conversation_id + " input[type='text']").val("");
    for (x in smileys)
        res.data.message = res.data.message.replace('[:' + x + ':]', smileys[x]);
    $("#chat-panel-" + res.data.conversation_id + " .chat-panel-list").append(tmpl("chat-item", res.data));
    var boxes = cStorage.get("chatBoxes");
    boxes[res.data.conversation_id].messages.push(res.data);
    cStorage.set("chatBoxes", boxes);
    chatBoxes[res.data.conversation_id] = res.data.id;
    refreshScrollbar(res.data.conversation_id);
}

function refreshScrollbar(conversation_id) {
    if (typeof chatScrollApi[conversation_id] !== "undefined") {
        chatScrollApi[conversation_id].reinitialise();
        chatScrollApi[conversation_id].scrollToBottom();
    } else {
        var scroll = $("#chat-panel-" + conversation_id + " .panel-body").jScrollPane({
            stickToBottom: true
        });
        chatScrollApi[conversation_id] = scroll.data('jsp');
        chatScrollApi[conversation_id].scrollToBottom();
    }
}

function closeAllChatWindows() {
    for (var i in chatBoxes) {
        closeChatBox(i);
    }
}

function checkChatMessage(panelid) {
    return ($("#chat-panel-" + Number(panelid) + " input[name='message']").val() !== "");
}

$(document).ready(function () {
    $.getJSON(siteurl + "style/" + design + "/img/emoticons/emoticons.json", function (data) {
        for (x in data) {
            $("#chat-emoticon-list > div").append('<img src="' + siteurl + 'style/' + design + '/img/emoticons/' + data[x] + '.png" data-key="' + data[x] + '" class="emoticon-select">');
            smileys[data[x]] = '<img src="' + siteurl + 'style/' + design + '/img/emoticons/' + data[x] + '.png" class="message-smiley" data-key="' + data[x] + '">';
        }

        if (cStorage.get("chatBoxes") === null)
            cStorage.set("chatBoxes", {});
        else {
            var boxes = cStorage.get("chatBoxes");
            for (var i in boxes)
                openChatBox(boxes[i]);
        }

        $.ionSound({
            sounds: [
                "button_tiny"
            ],
            path: siteurl + "lib/plugins/ionsound/sounds/",
            multiPlay: false,
            volume: "0.6"
        });

        chatHearthBeat();
        var c = 0;
        window.setInterval(function () {
            if (!$("body").hasClass("blurred"))
                chatHearthBeat();
            else if (c % 6 === 0)
                chatHearthBeat();
            c++;
        }, 5000);

        $(document).on("click", ".chat-panel .panel-heading", function () {
            $(this).parents(".chat-panel").find(".panel-footer, .panel-body").toggle();
        }).on("click", ".chat-panel .close", function () {
            closeChatBox($(this).data("cid"));
        }).on("click", ".chat-panel-unread", function () {
            var el = $(this);
            if (el.hasClass("close"))
                return;
            sendRequest({conversation_id: el.data("cid")}, "messages", "markAsRead", function () {
                el.removeClass("chat-panel-unread");
                var boxes = cStorage.get("chatBoxes");
                boxes[el.data("cid")].status = 0;
                cStorage.set("chatBoxes", boxes);
            });
        }).on("keyup", "#chat-search", function () {
            var val = $(this).val();
            $(".chat-list .chat-user").each(function (i, obj) {
                var n = $(obj).find(".online-friend-item-name").text();
                if (n.toLowerCase().indexOf(val) === -1)
                    $(obj).hide();
                else
                    $(obj).show();
            });
        }).on("click", ".emoticon-select", function () {
            var key = $(this).data("key");
            $("#chat-panel-" + currentChatPanel + " div.chat-panel-input").html(function (i, val) {
                return val + smileys[key];
            }).trigger("keyup");
            //placeCaretAtEnd($("#chat-panel-" + currentChatPanel + " div.chat-panel-input")[0]);
            $('.chat-panel-smiley-button').popover('hide');
        }).on("keyup", "div.chat-panel-input", function (e) {
            event = e || window.event;
            currentChatPanel = $(this).data("cid");
            if (event.keyCode === 13) {
                $(this).parent("form").submit();
                $(this).empty();
                $("#hiddenChatBox-" + currentChatPanel + ", #chat-temp-" + currentChatPanel).empty();
            }
            $("#chat-temp-" + currentChatPanel).html($(this).html()).find("img").each(function () {
                $(this).replaceWith("[:" + $(this).data("key") + ":]");
            });
            $("#hiddenChatBox-" + currentChatPanel).val($("#chat-temp-" + currentChatPanel).text());
        });

        $(".chat-panel-smiley-button").popover({
            html: true,
            container: 'body',
            content: function () {
                currentChatPanel = $(this).data("cid");
                return $("#chat-emoticon-list > div").html();
            }
        });
    });
    $('#closeAllChatsButton').hide();
});