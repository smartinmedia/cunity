$(document).ready(function () {
    $.getJSON(siteurl + "data/resources/fontawesome-icons.json", function (data) {
        for (x in data)
            $("#select-icon-menu").append('<li><a href="javascript:selectIcon(\'' + data[x] + '\');"><i class="fa fa-fw fa-' + data[x] + '"></i>&nbsp;' + data[x] + '</a></li>');
    });
    $(".sortable-list").sortable({
        connectWith: ".sortable-list",
        items: ".list-group-item",
        placeholder: "sortable-list-group-item list-group-item",
        cursor: "move",
        update: function () {
            updateMenus();
        }
    });

    sendRequest({action: "loadPages"}, "admin", "settings", function (res) {
        if (res.pages !== null && res.pages.length > 0)
            for (x in res.pages) {
                res.pages[x] = {
                    type: "page",
                    title: res.pages[x].title,
                    content: res.pages[x].shortlink,
                    iconClass: "file"
                };
                $("#menu-add-pages > .list-group").append(tmpl("pages-list-item", res.pages[x]));
                $("#menu-page-" + res.pages[x].content).data("menu", res.pages[x]);
            }
    });

    sendRequest({action: "loadModules"}, "admin", "modules", function (res) {
        if (res.modules !== null && res.modules.length > 0)
            for (x in res.modules) {
                res.modules[x] = {
                    type: "module",
                    title: res.modules[x].name,
                    content: res.modules[x].namespace,
                    iconClass: res.modules[x].iconClass
                };
                $("#menu-add-modules > .list-group").append(tmpl("module-list-item", res.modules[x]));
                $("#menu-module-" + res.modules[x].content).data("menu", res.modules[x]);
            }
    });

    sendRequest({action: "loadMenu"}, "admin", "appearance", function (res) {
        if (res.main !== null && res.main.length > 0)
            for (x in res.main)
                $("#main-menu-list").append(tmpl("menu-item", res.main[x]));
        if (res.footer !== null && res.footer.length > 0)
            for (x in res.footer)
                $("#footer-menu-list").append(tmpl("menu-item", res.footer[x]));
    });

    $("#addMenuItemForm").bootstrapValidator({
        feedbackIcons: {
            valid: 'fa fa-check',
            invalid: 'fa fa-times',
            validating: 'fa fa-refresh'
        },
        fields: {
            title: {
                validators: {
                    notEmpty: {
                        message: "Please enter a title"
                    }
                }
            },
            content: {
                validators: {
                    uri: {
                        message: "Please enter a valid URL"
                    },
                    notEmpty: {
                        message: "Please enter an URL"
                    }
                }
            },
            menu: {
                validators: {
                    notEmpty: {
                        message: "Please choose a menu"
                    }
                }
            }
        }
    });
});

function addMainItem(el) {
    var d = $("#" + el).data("menu");
    d.menu = "main";
    d.action = "addMenuItem";
    sendRequest(d, "admin", "appearance", function (res) {
        d.id = res.data.id;
        console.log(d);
        $("#main-menu-list").append(tmpl("menu-item", d));
        updateMenus();
    });
}

function addFooterItem(el) {
    var d = $("#" + el).data("menu");
    d.menu = "footer";
    d.action = "addMenuItem";
    sendRequest(d, "admin", "appearance", function (res) {
        d.id = res.data.id;
        $("#footer-menu-list").append(tmpl("menu-item", d));
        updateMenus();
    });
}

function selectIcon(tag) {
    $("#icon-selected").val(tag);
}

function menuLinkAdded(res) {
    if (res.data.menu === "main")
        $("#main-menu-list").append(tmpl("menu-item", res.data));
    else
        $("#footer-menu-list").append(tmpl("menu-item", res.data));
}

function removeItem(id) {
    $("#menuitem_" + id).fadeOut("slow").remove();
    updateMenus();
}

function updateMenus() {
    $("#main-menu-positions").val($("#main-menu-list").sortable("toArray", {attribute: 'data-id'}).join());
    $("#footer-menu-positions").val($("#footer-menu-list").sortable("toArray", {attribute: 'data-id'}).join());
}