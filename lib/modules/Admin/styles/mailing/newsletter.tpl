<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Messages"|translate}</h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Mailing"|translate}</li>
            <li class="active">{-"Newsletter"|translate}</li>
        </ol>
    </div>
</div>
<form class="ajaxform form-validate" data-bv-excluded=""
      action="{-"index.php?m=admin&action=manage"|URL}" id="manageform" method="POST">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-check-square-o"></i>&nbsp;{-"Newsletter"|translate}</h4>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th width="30px">#</th>
                        <th width="160px">{-"Time"|translate}</th>
                        <th>{-"Subject"|translate}</th>
                        <th>{-"Message"|translate}</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody id="moduletable">
                    {-foreach $newsletters as $i => $newsletter}
                        <tr>
                            <td>{-$i + 1}</td>
                            <td>{-$newsletter.time}</td>
                            <td>{-$newsletter.subject}</td>
                            <td>{-$newsletter.message}</td>
                        </tr>
                    {-/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
