<link rel="stylesheet" href="{-"core.siteurl"|setting}lib/plugins/summernote/css/summernote.css">
<script src="{-"core.siteurl"|setting}lib/plugins/summernote/js/summernote.min.js"></script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Layout"|translate}
            <button class="btn btn-success pull-right saveButton"><i class="fa fa-save"></i>&nbsp;{-"Save"|translate}
            </button>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Appearance"|translate}</li>
            <li class="active">{-"Layout"|translate}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" id="headline-panel">
            <div class="panel-heading">
                <i class="fa fa-files-o fa-fw"></i>&nbsp;{-"Headline"|translate}
                <span class="pull-right text-success hidden panel-feedback-success"><i
                            class="fa fa-check"></i>&nbsp;{-"Changes Saved"|translate}</span>
                <span class="pull-right text-danger hidden panel-feedback-error"><i
                            class="fa fa-warning"></i>&nbsp;{-"An error occurred"|translate}</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <strong>{-"Headline"|translate}</strong>

                        <div id="summernote-headline-header" class="loaderbox" data-source="{-"core.headline"|setting}"></div>
                    </div>
                </div>
                <form class="form-horizontal ajaxform" method="post" action="{-"index.php?m=admin&action=save"|URL}">
                    <input type="hidden" name="settings-core.headline">
                    <input type="hidden" name="form" value="headline">
                    <input class="ajaxform-callback" type="hidden" value="showPanelResult">
                </form>
            </div>
        </div>
    </div>
</div>