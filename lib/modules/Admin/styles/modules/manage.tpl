<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Manage Modules"|translate}</h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Modules"|translate}</li>
            <li class="active">{-"Manage Modules"|translate}</li>
        </ol>
    </div>
</div>
<form class="ajaxform form-validate" data-bv-excluded=""
      action="{-"index.php?m=admin&action=manage"|URL}" id="manageform" method="POST">
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><i class="fa fa-check-square-o"></i>&nbsp;{-"Your Modules"|translate}</h4>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th width="30px">#</th>
                    <th colspan="2" width="auto">{-"Module-Name"|translate}</th>
                    <th width="100px">{-"Status"|translate}</th>
                    <th width="150px"></th>
                </tr>
                </thead>
                <tbody id="moduletable">
                {-foreach $installedModules AS $i => $module}
                <tr>
                    <td style="vertical-align:middle">{-$i+1}</td>
                    <td width="30px" style="vertical-align:middle"><i class="fa fa-{-$module.iconClass}"
                                                                      style="font-size:30px"></i></td>
                    <td><p><strong>{-$module.name|translate}</strong></p><i>{-"core.siteurl"|setting}{-$module.namespace}</i>
                    </td>
                    <td>
                        {-if $module.status == 1}
                        <span class="label label-success">{-"Activated"|translate}</span>
                        {-else}
                        <label class="label label-danger">{-"Deactivated"|translate}</label>
                        {-/if}
                    </td>
                    <td class="clearfix">
                        <div class="btn-group pull-right">
                            <button type="button" class="btn btn-primary">{-"Actions"|translate}</button>
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                {-if $module.status == 1}
                                <li><a href="javascript:changeModuleStatus({-$module.id}, 0);"><i class="fa fa-power-off"></i>&nbsp;{-"Deactivate"|translate}</a></li>
                                {-else}
                                <li><a href="javascript:changeModuleStatus({-$module.id}, 1);"><i class="fa fa-power-off"></i>&nbsp;{-"Activate"|translate}</a></li>
                                {-/if}
                                {-*<li><a href="javascript:changeIcon({-$module.id});"><i class="fa fa-pencil"></i>&nbsp;{-"Change Icon"|translate}</a></li>*}
                                {-*<li class="divider"></li>*}
                                {-*<li><a href="#"><i class="fa fa-trash-o"></i>&nbsp;{-"Uninstall"|translate}</a></li>*}
                            </ul>
                        </div>
                    </td>
                </tr>
                {-/foreach}
                </tbody>
            </table>
        </div>
    </div>
    {-*<div class="col-lg-4">*}
        {-*<div class="panel panel-default">*}
            {-*<div class="panel-heading">*}
                {-*<h4 class="panel-title"><i class="fa fa-download"></i>&nbsp;{-"Import module"|translate}</h4>*}
            {-*</div>*}
            {-*<div class="panel-body">*}
                {-*<form class="form-horizontal">*}
                    {-*<div class="form-group">*}
                        {-*<div class="col-lg-12">*}
                            {-*<p class="help-block">{-"Here you can upload a new Module-Package as .zip file"|translate}</p>*}
                        {-*</div>*}
                        {-*<div class="col-lg-9">*}
                            {-*<input type="file" class="form-control" accept="application/zip">*}
                        {-*</div>*}
                        {-*<div class="col-lg-3">*}
                            {-*<button class="btn btn-primary btn-block" type="submit"><i*}
                                        {-*class="fa fa-upload"></i>&nbsp;{-"Upload"|translate}</button>*}
                        {-*</div>*}
                {-*</form>*}
            {-*</div>*}
        {-*</div>*}
    {-*</div>*}
</div>
</form>