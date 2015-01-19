<script type="text/javascript">
    scriptsToInclude = [
        '{-"core.siteurl"|setting}lib/plugins/js/jquery-ui-1.10.4.custom.min.js',
    ];
</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Menus"|translate}
            <button class="btn btn-success pull-right saveButton"><i class="fa fa-save"></i>&nbsp;{-"Save"|translate}
            </button>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Appearance"|translate}</li>
            <li class="active">{-"Menus"|translate}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-default" id="menu-panel">
            <div class="panel-heading">
                <i class="fa fa-list fa-fw"></i>&nbsp;{-"Menu"|translate}
                <span class="pull-right text-success hidden panel-feedback-success"><i
                            class="fa fa-check"></i>&nbsp;{-"Changes Saved"|translate}</span>
                <span class="pull-right text-danger hidden panel-feedback-error"><i
                            class="fa fa-warning"></i>&nbsp;{-"An error occurred"|translate}</span>
            </div>
            <div class="panel-body">
                <div class="clearfix">
                    <div class="pull-left panel-group panel-menu" id="menu-add">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#menu-add" href="#menu-add-modules">
                                        <i class="fa fa-sitemap"></i>&nbsp;{-"Add a module"|translate}
                                    </a>
                                </h4>
                            </div>
                            <div id="menu-add-modules" class="panel-collapse collapse in">
                                <ul class="list-group"></ul>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#menu-add" href="#menu-add-pages">
                                        <i class="fa fa-files-o"></i>&nbsp;{-"Add a page"|translate}
                                    </a>
                                </h4>
                            </div>
                            <div id="menu-add-pages" class="panel-collapse collapse">
                                <ul class="list-group"></ul>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#menu-add" href="#menu-add-custom">
                                        <i class="fa fa-link"></i>&nbsp;{-"Add a custom Link"|translate}
                                    </a>
                                </h4>
                            </div>
                            <div id="menu-add-custom" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <form class="form" action="{-"index.php?m=admin&action=appearance"|URL}"
                                          method="post" id="addMenuItemForm">
                                        <div class="form-group">
                                            <input type="text" required class="form-control input-sm" name="title"
                                                   placeholder="{-"Title"|translate}">
                                        </div>
                                        <div class="form-group">
                                            <input type="url" required class="form-control input-sm" name="content"
                                                   placeholder="{-"URL (e.g. http://www.example.com)"|translate}">
                                        </div>
                                        <div class="form-group clearfix">
                                            <div class="btn-group btn-group-justified">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-default dropdown-toggle"
                                                            data-toggle="dropdown">
                                                        <span id="select-icon-toggle"
                                                              data-orig="{-"Select an icon"|translate}">&nbsp;{-"Select an icon"|translate}</span>
                                                        <span class="caret"></span>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu" id="select-icon-menu"></ul>
                                                </div>
                                                <input type="hidden" name="iconClass" id="icon-selected">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <select class="form-control input-sm" name="menu" required>
                                                <option value="">{-"Select Menu"|translate}...</option>
                                                <option value="main">{-"Main-Menu"|translate}</option>
                                                <option value="footer">{-"Footer-Menu"|translate}</option>
                                            </select>
                                        </div>
                                        <button class="btn btn-primary btn-sm btn-block" type="submit"><i
                                                    class="fa fa-plus"></i>&nbsp;{-"Add item"|translate}</button>
                                        <input type="hidden" name="type" value="link">
                                        <input type="hidden" name="action" value="addMenuItem">
                                        <input type="hidden" class="ajaxform-callback" value="menuLinkAdded">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="pull-left panel-primary panel panel-menu">
                        <div class="panel-heading">
                            {-"Main-Menu"|translate}
                        </div>
                        <ul class="list-group sortable-list" id="main-menu-list"></ul>
                    </div>
                    <div class="pull-left panel panel-default panel-menu">
                        <div class="panel-heading">
                            {-"Footer-Menu"|translate}
                        </div>
                        <ul class="list-group sortable-list" id="footer-menu-list"></ul>
                    </div>
                </div>
            </div>
            <form class="ajaxform hidden" action="{-"index.php?m=admin&action=appearance"|URL}" method="post">
                <input type="hidden" name="action" value="updateMenu">
                <input type="hidden" name="panel" value="menu-panel">
                <input type="hidden" name="main-menu" id="main-menu-positions">
                <input type="hidden" name="footer-menu" id="footer-menu-positions">
                <input class="ajaxform-callback" type="hidden" value="showPanelResult">
            </form>
        </div>
    </div>
</div>
<script id="pages-list-item" type="text/html">
    <li class="list-group-item clearfix" id="menu-page-{%=o.content%}">
        <a href="{-"index.php?m=pages&action="|URL}{%=o.content%}">{%#o.title%}</a>

        <div class="btn-group btn-group-xs pull-right">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:addMainItem('menu-page-{%=o.content%}');"><i
                                class="fa fa-plus"></i>&nbsp;{-"Add to Main-Menu"|translate}</a></li>
                <li><a href="javascript:addFooterItem('menu-page-{%=o.content%}');"><i
                                class="fa fa-plus"></i>&nbsp;{-"Add to Footer-Menu"|translate}</a></li>
            </ul>
        </div>
    </li>
</script>
<script id="module-list-item" type="text/html">
    <li class="list-group-item clearfix" id="menu-module-{%=o.content%}">
        <a href="{-"index.php?m="|URL}{%=o.content%}"><i class="fa fa-fw fa-{%=o.iconClass%}"></i>&nbsp;{%#o.title%}</a>

        <div class="btn-group btn-group-xs pull-right">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:addMainItem('menu-module-{%=o.content%}');"><i
                                class="fa fa-plus"></i>&nbsp;{-"Add to Main-Menu"|translate}</a></li>
                <li><a href="javascript:addFooterItem('menu-module-{%=o.content%}');"><i
                                class="fa fa-plus"></i>&nbsp;{-"Add to Footer-Menu"|translate}</a></li>
            </ul>
        </div>
    </li>
</script>
<script id="menu-item" type="text/html">
    <li class="list-group-item clearfix" id="menuitem_{%=o.id%}" data-id="{%=o.id%}">
        <strong class="pull-left"><i class="fa fa-{%=o.iconClass%} fa-fw"></i>&nbsp;{%#o.title%}</strong>
        {% if (o.type == "link") { %}&nbsp;<i class="fa fa-link pull-left tooltip-trigger text-muted"
                                              title="{-"This is an external link"|translate}"></i>{% } %}
        <div class="btn-group btn-group-xs pull-right">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-chevron-down"></i>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:removeItem({%=o.id%})"><i
                                class="fa fa-trash-o"></i>&nbsp;{-"Remove Item"|translate}</a></li>
            </ul>
        </div>
    </li>
</script>