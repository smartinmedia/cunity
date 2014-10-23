var smileys = {};
$(document).ready(function () {
    loadCategoryCloud();
    $.getJSON(siteurl + "style/" + design + "/img/emoticons/emoticons.json", function (data) {
        for (x in data) {
            $("#thread-new-emoticons > div").append('<img src="' + siteurl + 'style/' + design + '/img/emoticons/' + data[x] + '.png" data-key="' + data[x] + '" class="emoticon-select">');
            smileys[data[x]] = '<img src="' + siteurl + 'style/' + design + '/img/emoticons/' + data[x] + '.png" class="message-smiley" data-key="' + data[x] + '">';
        }
    });
    $("#thread-new-emoticon-button").popover({
        html: true,
        container: 'body',
        content: function () {
            return $("#thread-new-emoticons > div").html();
        }
    });

    $("#startThreadForm-summernote").summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']]
            //['insert', ['picture', 'link']]
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
            $("#startThreadForm-summernote").code("");
        }
    });
    $("#editBoardForm").bootstrapValidator({
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
            description: {
                validators: {
                    notEmpty: {
                        message: 'Please enter a description!'
                    },
                    stringLength: {
                        min: 3,
                        message: "Your description is too short! (min. 3 chars)"
                    }
                }
            }
        }

    });
    $("#startThreadForm").bootstrapValidator({
        feedbackIcons: {
            valid: 'fa fa-check',
            invalid: 'fa fa-times',
            validating: 'fa fa-refresh'
        },
        excluded: "",
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
                        message: 'Please enter your message!'
                    },
                    stringLength: {
                        min: 3,
                        message: "Your message is too short! (min. 3 chars)"
                    }
                },
                trigger: 'keyup'
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
    loadThreads();
});

function revalidateEditor(html) {
    $("#startThreadForm-summernote").val(html).trigger('keyup');
    $("#startThreadForm").data('bootstrapValidator').updateStatus('content', 'VALIDATING').validateField('content');
}

function threadStarted(res) {
    location.href = convertUrl({module: "forums", action: "thread", x: res.id});
}

function loadThreads() {
    $("#board-loader").show();
    sendRequest({id: $("#board_id").val()}, "forums", "loadThreads", function (res) {
        $("#board-loader").hide();
        if (typeof res.result !== "undefined" && res.result !== null) {
            for (var x in res.result)
                $("#threads").append(tmpl("thread-template", res.result[x]));
        }
        if ($("#threads .topic-post").length === 0)
            $("#threads .alert").show();
        else
            $("#threads .alert").hide();
    });
}

function boardUpdated() {
    location.reload();
}

function deleteBoard() {
    bootbox.confirm("Are You sure You want to delete this board and all threads and posts which belongs to it?", function (r) {
        if (r) {
            sendRequest({id: $("#board_id").val()}, "forums", "deleteBoard", function (res) {
                location.href = convertUrl({"module": "forums"});
            });
        }
    });
}