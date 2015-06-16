<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Filesharing"|translate}
            <button class="btn btn-success pull-right saveButton"><i class="fa fa-save"></i>&nbsp;{-"Save"|translate}
            </button>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Filesharing"|translate}</li>
            <li class="active">{-"Configuration"|translate}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-8">
        <div class="panel panel-default" id="general-panel">
            <div class="panel-heading">
                <i class="fa fa-wrench fa-fw"></i>&nbsp;{-"Settings"|translate}
                <span class="pull-right text-success hidden panel-feedback-success"><i
                            class="fa fa-check"></i>&nbsp;{-"Changes Saved"|translate}</span>
                <span class="pull-right text-danger hidden panel-feedback-error"><i
                            class="fa fa-warning"></i>&nbsp;{-"An error occurred"|translate}</span>
            </div>
            <div class="panel-body">
                <form class="form-horizontal ajaxform form-validate" id="general-form" method="post"
                      action="{-"index.php?m=admin&action=save"|URL}">
                    <div class="form-group">
                        <label for="allowed-extensions"
                               class="col-sm-3 control-label">{-"Allowed extensions"|translate}</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{-"filesharing.allowed_extensions"|setting}" id="allowed-extensions"
                                   name="settings-filesharing.allowed_extensions" placeholder="Allowed extensions">
                            <label class="help-block">Comma separated, leave blank to allow all extensions</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="allowed-extensions"
                               class="col-sm-3 control-label">{-"Max filesize in MB"|translate}</label>

                        <div class="col-sm-8">
                            <input type="number" class="form-control" value="{-"filesharing.max_filesize"|setting}" id="max-filesize"
                                   name="settings-filesharing.max_filesize" placeholder="Max filesize in MB">
                            <label class="help-block">Leave blank to disable any limit</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="allowed-extensions"
                               class="col-sm-3 control-label">{-"Files per user"|translate}</label>

                        <div class="col-sm-8">
                            <input type="number" class="form-control" value="{-"filesharing.files_user"|setting}" id="files-user"
                                   name="settings-filesharing.files_user" placeholder="Files per user">
                            <label class="help-block">Leave blank to disable any limit</label>
                        </div>
                    </div>
                    <input type="hidden" name="form" value="filesharing">
                    <input type="hidden" name="panel" value="general-panel">
                    <input class="ajaxform-callback" type="hidden" value="showPanelResult">
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-info-circle fa-fw"></i>&nbsp;{-"File information"|translate}
            </div>
            <table class="table">
                <tr>
                    <td>
                        {-"Upload limit"|translate}
                        <p class="text-warning">{-"Your PHP settings may affect"|translate}</p>
                    </td>
                    <td>
                        <span class="label label-warning">{-$upload_limit}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {-"Total number of files"|translate}
                    </td>
                    <td>
                        <span class="label label-success">{-$files|@count}</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        {-"Total size of files"|translate}
                    </td>
                    <td>
                        <span class="label label-info">{-$files_size}</span>
                    </td>
                </tr>
            </table>
        </div>
</div>
