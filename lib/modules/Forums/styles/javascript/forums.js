$(document).ready(function() {
    loadForums();
    loadCategoryCloud();
    $("#createForumForm").bootstrapValidator({
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
function loadForums() {
    sendRequest({}, "forums", "loadForums", function(res) {
        if (typeof res.result !== "undefined" && res.result !== null) {
            for (var x in res.result) {
                var boards = "";
                for (var y in res.result[x].boards)
                    boards += tmpl("board-template", res.result[x].boards[y]);
                if (res.result[x].boards.length > res.result[x].boardcount)
                    $("#forum-" + res.result[x].id + " .loadmoreboards").show();
                res.result[x].boards = boards;
                $("#forums").append(tmpl("forum-template", res.result[x]));
                if (boards === "")
                    $("#forum-" + res.result[x].id + " .noboards").show();
            }
            if ($("#forums .forum").length === 0)
                $("#no-result").show();
        }
    });
}

function forumCreated(res) {
    $("#addForum").modal('hide');
    $("#no-result").hide();
    $("#forums").append(tmpl("forum-template", res.forum));
    $("#forum-" + res.forum.id + " .noboards").show();
    $("#createForumForm").data('bootstrapValidator').resetForm(true);
}