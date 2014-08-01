function loadCategoryCloud() {
    sendRequest({}, "forums", "loadCategories", function (res) {
        if (typeof res.result !== "undefined" && res.result !== null) {
            var cloud = "";
            for (var x in res.result)
                cloud += tmpl("category-cloud-item", res.result[x]);
        }

        if (cloud != "")
            $(".right-sidebar").html(tmpl("category-cloud", {cloud: cloud}));
    });
}