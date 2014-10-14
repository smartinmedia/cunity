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