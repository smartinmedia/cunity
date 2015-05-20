<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Log"|translate}</h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Statistics"|translate}</li>
            <li class="active">{-"Log"|translate}</li>
        </ol>
    </div>
</div>
<form class="ajaxform form-validate" data-bv-excluded=""
      action="{-"index.php?m=admin&action=manage"|URL}" id="manageform" method="POST">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-check-square-o"></i>&nbsp;{-"Log"|translate}</h4>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th width="30px">#</th>
                        <th>{-"Level"|translate}</th>
                        <th>{-"User"|translate}</th>
                        <th>{-"Message"|translate}</th>
                        <th>{-"Context"|translate}</th>
                    </tr>
                    </thead>
                    <tbody id="moduletable">
                    {-foreach $logdata as $i => $log}
                        <tr>
                            <td>{-$i + 1}</td>
                            <td><label class="label label-{-$log.label}">{-$log.level}</label></td>
                            <td>{-$log.user_id}</td>
                            <td>{-$log.message}</td>
                            <td>{-$log.context}</td>
                        </tr>
                    {-/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
