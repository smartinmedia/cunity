$(document).ready(function () {
    $('#updateButton').on('click', function () {
        var $btn = $(this).button('loading');
        update();
    });
});

function update() {
    var status = false;
    sendRequest({action: "save", form: "update"}, "admin", "save", function (res) {
        status = res.status;

        if (status === true) {
            loadPage('update', 'update');
        }
    });

    return status;
}
