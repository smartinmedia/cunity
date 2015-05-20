function changeModuleStatus(identifier, status) {
    sendRequest({"action": "save", "id": identifier, "status": status, "form": "modules"}, "admin", "save", function (res) {
        if (res.status === true) {
            loadPage('modules', 'manage');
        }
    });
}

function uninstallModule(identifier) {
    bootbox.confirm("Do you really want to uninstall this module? All data for this module will be erased!", function (res) {
        if (res == true) {
            sendRequest({"action": "delete", "id": identifier, "form": "modules"}, "admin", "delete", function (res) {
                if (res.status === true) {
                    loadPage('modules', 'manage');
                }
            });
        }
    });
}
