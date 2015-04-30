<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>{-"Administration"|translate} - Cunity</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>

    <link rel="stylesheet" href="{-"core.siteurl"|setting}lib/plugins/bootstrap/css/bootstrap.min.css">
    <link href="{-"core.siteurl"|setting}lib/plugins/fontawesome/css/font-awesome.css" rel="stylesheet">
    <link href="{-"core.siteurl"|setting}lib/modules/Admin/styles/css/cunity-admin.css" rel="stylesheet"/>
    <link href="{-"core.siteurl"|setting}lib/plugins/bootstrap-validator/css/bootstrapValidator.min.css"
          rel="stylesheet">
    <link href="{-"core.siteurl"|setting}style/CunityRefreshed/img/favicon-default.gif" rel="icon" type="image/x-icon">
    {-if !$css_head eq ""}
        <link rel="stylesheet" type="text/css"
              href="{-"core.siteurl"|setting}lib/modules/Core/styles/css/cunity.min.css.php?files={-$css_head}">
    {-/if}
    <script src="{-"core.siteurl"|setting}lib/plugins/js/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">var modrewrite = '{-$modrewrite}', siteurl = "{-"core.siteurl"|setting}", userid = {-$user.userid}, design = "CunityRefreshed", login = {-if empty($user)}false{-else}true{-/if};</script>
    <script src="{-"core.siteurl"|setting}lib/modules/Core/styles/javascript/cunity-core.js"
            type="text/javascript"></script>
    <script src="{-"core.siteurl"|setting}lib/modules/Admin/styles/javascript/cunity-admin.js"
            type="text/javascript"></script>
    <script src="{-"core.siteurl"|setting}lib/plugins/js/jquery.metisMenu.min.js"></script>
    <script src="{-"core.siteurl"|setting}lib/plugins/js/bootbox.min.js" type="text/javascript"></script>
    <script src="{-"core.siteurl"|setting}lib/plugins/js/moment.min.js" type="text/javascript"></script>
    <script src="{-"core.siteurl"|setting}lib/plugins/bootstrap-validator/js/bootstrapValidator.min.js"
            type="text/javascript"></script>
    <script src="{-"core.siteurl"|setting}lib/plugins/ionsound/js/ion.sound.min.js" type="text/javascript"></script>
    <script src="{-"core.siteurl"|setting}lib/plugins/js/tmpl.min.js" type="text/javascript"></script>
    <script src="{-"core.siteurl"|setting}lib/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="{-"core.siteurl"|setting}lib/plugins/js/html5shiv.min.js"></script>
    <script src="{-"core.siteurl"|setting}lib/plugins/js/respond.min.js"></script>
    <script type="text/javascript">
        scriptsToInclude = [];
        $(document).ajaxComplete(function () {
            $.each(scriptsToInclude, function (index, value) {
                var jsTag = document.createElement('script');
                jsTag.type = "text/javascript";
                jsTag.src = value;
                document.getElementsByTagName('head')[0].appendChild(jsTag);
            });
        });
    </script>
    <script type="text/javascript" src="https://smartinmedia.atlassian.net/s/42378e90dfaf39e171e8e7e09ac085a4-T/en_US-c7kv9d/64007/21/1.4.19/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-US&collectorId=dd7adc95"></script>
</head>

<body>
<div id="wrapper">

    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                <span class="sr-only">{-"Toggle navigation"|translate}</span><i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="{-"core.siteurl"|setting}admin">{-"core.sitename"|setting}</a>
        </div>
        <!-- /.navbar-header -->

        <ul class="nav navbar-top-links navbar-right">
            <li>
                <a href="{-"core.siteurl"|setting}" data-title="{-"Back to your cunity"|translate}"
                   class="tooltip-trigger"
                   data-placement="left">
                    {-"Back to your Cunity"|translate}&nbsp;<i class="fa fa-sign-out fa-fw"></i>
                </a>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-top-links -->

    </nav>
    <!-- /.navbar-static-top -->
    {-if $isAdmin && $hasUpdate }<div class="alert alert-warning alert-dismissible" role="alert">{-"New Update available"|translate}</div>{-/if}
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li>
                    <a data-cat="dashboard" data-page="dashboard"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                </li>
                <li>
                    <a href="#" class="dropdown"><i class="fa fa-cogs fa-fw"></i> {-"Settings"|translate}<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a data-cat="settings" data-page="site">{-"Site"|translate}</a>
                        </li>
                        <li>
                            <a data-cat="settings" data-page="users">{-"Users & Registration"|translate}</a>
                        </li>
                        <li>
                            <a data-cat="settings" data-page="profilefields">{-"Profile fields"|translate}</a>
                        </li>
                        <li>
                            <a data-cat="settings" data-page="mail">{-"Mail"|translate}</a>
                        </li>
                        <li>
                            <a data-cat="settings" data-page="pages">{-"Static pages"|translate}</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#" class="dropdown"><i class="fa fa-picture-o fa-fw"></i> {-"Appearance"|translate}<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a data-cat="appearance" data-page="menus">{-"Menus"|translate}</a>
                        </li>
                        {-*<li>*}
                            {-*<a data-cat="appearance" data-page="sidebar">{-"Right Sidebar"|translate}</a>*}
                        {-*</li>*}
                        <li>
                            <a data-cat="appearance" data-page="layout">{-"Layout"|translate}</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a data-cat="modules" data-page="manage"><i class="fa fa-sitemap fa-fw"></i> {-"Modules"|translate}
                    </a>
                </li>
                <li>
                    <a data-cat="users" data-page="view"><i class="fa fa-group fa-fw"></i> {-"Userlist"|translate}</a>
                </li>
                <li>
                    <a href="#" class="dropdown"><i class="fa fa-envelope-o fa-fw"></i> {-"Messages"|translate}<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a data-cat="mailing" data-page="contact">{-"Messages"|translate}</a>
                        </li>
                        <li>
                            <a data-cat="mailing" data-page="newsletter">{-"Newsletter"|translate}</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a data-cat="update" data-page="update"><i
                                class="fa fa-undo fa-fw"></i>&nbsp;{-"Update"|translate}</a>
                </li>
                <li>
                    <a href="#" class="dropdown"><i class="fa fa-bar-chart-o fa-fw"></i> {-"Statistics"|translate}<span
                                class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        {-*<li>*}
                            {-*<a data-cat="statistics" data-page="reports">{-"Site Reports"|translate}</a>*}
                        {-*</li>*}
                        <li>
                            <a data-cat="statistics" data-page="logs">{-"Log"|translate}</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a data-cat="cunity" data-page="about"><img
                                src="{-"core.siteurl"|setting}style/CunityRefreshed/img/cunity-logo-26.png" width="16px"
                                height="16px">&nbsp;{-"About Cunity"|translate}</a>
                </li>
            </ul>
            <!-- /#side-menu -->
        </div>
        <!-- /.sidebar-collapse -->
    </nav>
    <!-- /.navbar-static-side -->

    <div id="page-wrapper"></div>

    <footer id="cunity-copyright" class="hidden-sm">
        <a href="http://www.cunity.net" class="pull-left"><img
                    src="{-"core.siteurl"|setting}style/CunityRefreshed/img/cunity-logo-sm.gif"></a>

        <div class="pull-left" style="padding: 4px 10px;font-size: 13px">
            <small class="copyright">Powered by <a href="http://cunity.net/" target="_blank">Cunity</a></small>
        </div>
    </footer>

</div>
</body>
</html>
