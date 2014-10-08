function changeModuleStatus(identifier, status) {
    sendRequest({"action": "save", "id": identifier, "status": status, "form": "modules"}, "admin", "save", function (res) {
        if (res.status === true) {
            loadPage('modules', 'manage');
        }
    });
}
