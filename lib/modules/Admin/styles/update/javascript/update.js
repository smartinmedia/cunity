function update() {
    sendRequest({action: "save", form: "update"}, "admin", "save", function (res) {
        if (res.status === true) {
            loadPage('update', 'update');
        }
    });
}
