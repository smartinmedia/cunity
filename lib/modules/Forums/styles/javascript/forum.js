$(document).ready(function() {
    loadBoards();
    loadCategoryCloud();
    $("#editForumForm").bootstrapValidator({
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
    $("#addBoardForm").bootstrapValidator({
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
});

function loadBoards() {
    $("#forum-loader").show();
    sendRequest({id: $("#forum_id").val()}, "forums", "loadBoards", function(res) {
        $("#forum-loader").hide();
        if (typeof res.result !== "undefined" && res.result !== null) {
            for (var x in res.result)
                $("#boards").append(tmpl("board-template", res.result[x]));
        }
        if ($("#boards .topic-post").length === 0)
            $("#boards .alert").show();
        else
            $("#boards .alert").hide();
    });
}

function boardCreated(res) {
    $("#addBoard").modal('hide');
    $("#boards").prepend(tmpl("board-template", res.board));
    $("#boards .alert").hide();
    $("#addBoardForm").data('bootstrapValidator').resetForm(true);
}

function forumUpdated() {
    location.reload();
}

function deleteForum() {
    bootbox.confirm("Are You sure You want to delete this forum and all board, threads and posts which belongs to it?", function(r) {
        if (r) {
            sendRequest({id: $("#forum_id").val()}, "forums", "deleteForum", function(res) {
                location.href = convertUrl({"module": "forums"});
            });
        }
    });
}