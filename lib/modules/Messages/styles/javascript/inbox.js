var smileys = new Array();
$(document).ready(function () {
    $.getJSON(siteurl + "style/" + design + "/img/emoticons/emoticons.json", function (data) {
        for (x in data) {
            smileys[data[x]] = '<img src="' + siteurl + 'style/' + design + '/img/emoticons/' + data[x] + '.png" class="message-smiley" data-key="' + data[x] + '">';
        }
        loadConversations();
    });
});

function loadConversations() {
    sendRequest({}, "messages", "load", function (res) {
        console.log('hier');

        if (typeof res.conversations !== "undefined" && res.conversations !== null && res.conversations.length > 0) {
            $(".inbox-empty,.inbox-loader").hide();
            $(".inbox-item").remove();
            for (con in res.conversations) {
                image = null;
                var c = res.conversations[con], i = 0;
                if (c.users !== null && typeof c.users === "string") {
                    us = c.users.split(",");
                    userstring = "";
                    for (user in us) {
                        if (i === 3) {
                            userstring += ", +" + (us.length - 3);
                            break;
                        }
                        var tmp = us[user].split("|");
                        userstring += ", " + tmp[0];
                        i++;
                    }
                    userstring = userstring.substr(2);
                } else if (c.users !== null && typeof c.users === "object") { // there is only one user                    
                    userstring = c.users.name;
                    image = c.users.pimg;
                } else
                    userstring = "None";
                for (x in smileys)
                    c.message = replaceAll('[:' + x + ':]', smileys[x], c.message);
                c.name = userstring;
                c.image = checkImage(image, "users", "cr_");
                $(".inbox-list").append(tmpl("inbox-conversation", c));
            }
        } else {
            $(".inbox-empty").show();
            $(".inbox-loader").hide();
        }
    });
}