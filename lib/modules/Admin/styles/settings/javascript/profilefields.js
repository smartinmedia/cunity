function removeProfileField(identifier) {
    sendRequest({
        action: "delete",
        "id": identifier,
        "form": "profilefields"
    }, "admin", "delete", function (res) {
        if (res.status == true) {
            loadPage('settings', 'profilefields');
        }
    });
}

function showProfileValues() {
    var fieldType = $('#type :selected').val();
    switch (fieldType) {
        case '1':
        case '2':
            $('#possiblevalues').show();
            break;
        default:
            $('#possiblevalues').hide();
            break;
    }
}

function addProfilefield() {
    $('#addprofilefieldmodal').modal('hide');
    $('#addprofilefieldmodal').on('hidden.bs.modal', function (e) {
        loadPage('settings', 'profilefields');
    })
}
