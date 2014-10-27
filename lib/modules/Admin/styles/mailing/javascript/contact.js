function deleteMessage(identifier) {
    sendRequest({"action": "delete", "id": identifier, "status": status, "form": "mailing"}, "admin", "delete", function (res) {
        if (res.status === true) {
            loadPage('mailing', 'contact');
        }
    });
}
