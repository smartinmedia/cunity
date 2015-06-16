<script type="text/javascript">
    var statisticsObject = {
        element: 'userstatistics',
        data: {-$statisticdata},
        xkey: 'period',
        ykeys: ['comments', 'posts', 'notifications', 'users'],
        labels: ['{-"Comments"|translate}', '{-"Posts"|translate}', '{-"Notifications"|translate}', '{-"Users"|translate}'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    };

</script>

<link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.3.min.css">
<link href="{-"core.siteurl"|setting}lib/plugins/morris/css/morris-0.4.3.min.css" rel="stylesheet">
<script type="text/javascript">
    scriptsToInclude = [
        '{-"core.siteurl"|setting}lib/plugins/raphael/js/raphael-min.js',
        '{-"core.siteurl"|setting}lib/plugins/morris/js/morris-0.4.3.min.js'
    ];
</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Dashboard"|translate}</h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Dashboard"|translate}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-bar-chart-o fa-fw"></i>&nbsp;{-"Statistics"|translate}
            </div>
            <div class="panel-body">
                <div id="userstatistics" class="graphbox loaderbox"></div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-sitemap fa-fw"></i>&nbsp;{-"Modules"|translate}
            </div>
            <div class="panel-body modules-overview">
                <div class="row">
                    {-foreach $modules AS $module}
                        <div class="col-sm-2">
                            <i class="fa fa-{-$module.iconClass} fa-fw fa-5x {-if $module.status == 0}text-muted{-/if}"></i>
                            <a href="{-"core.siteurl"|setting}{-$module.namespace}"
                               target="_blank">{-$module.name|translate}</a>
                        </div>
                    {-/foreach}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-info-circle fa-fw"></i>&nbsp;{-"Site Infos"|translate}
            </div>
            <table class="table">
                <tr>
                    <td>
                        {-"Cunity-Name"|translate}
                    </td>
                    <td>
                        {-"core.sitename"|setting}
                    </td>
                </tr>
                <tr>
                    <td>
                        {-"Admin Mail"|translate}
                    </td>
                    <td>
                        {-"core.contact_mail"|setting}
                    </td>
                </tr>
                <tr>
                    <td>
                        {-"Language"|translate}
                    </td>
                    <td>
                        <span class="label label-info">{-"core.language"|setting}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {-"Mailing"|translate}
                    </td>
                    <td>
                        {-if $smtp_check}
                            <span class="label label-success">SMTP {-"active"|translate}</span>
                        {-else}
                            <span class="label label-danger">SMTP {-"inactive"|translate}</span>
                        {-/if}
                    </td>
                </tr>
                <tr>
                    <td>
                        {-"Design"|translate}
                    </td>
                    <td>
                        <span class="label label-info">{-"core.design"|setting}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {-"Version"|translate}
                    </td>
                    <td>
                        <span class="label label-info">{-$version}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
