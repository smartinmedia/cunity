<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Users & Registration"|translate}
            <button class="btn btn-success pull-right saveButton"><i class="fa fa-save"></i>&nbsp;{-"Save"|translate}
            </button>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Settings"|translate}</li>
            <li class="active">{-"Users & Registration"|translate}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default" id="users-panel">
            <div class="panel-heading">
                <i class="fa fa-users fa-fw"></i>&nbsp;{-"Users"|translate}
                <span class="pull-right text-success hidden panel-feedback-success"><i
                            class="fa fa-check"></i>&nbsp;{-"Changes Saved"|translate}</span>
                <span class="pull-right text-danger hidden panel-feedback-error"><i
                            class="fa fa-warning"></i>&nbsp;{-"An error occurred"|translate}</span>
            </div>
            <div class="panel-body ">
                <form class="form-horizontal ajaxform" method="post" action="{-"index.php?m=admin&action=save"|URL}">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">{-"Show names"|translate}</label>

                        <div class="col-sm-8">
                            <select class="form-control" name="settings-core.fullname">
                                <option {-if "core.fullname"|setting eq 0} selected{-/if}
                                                                      value="0">{-"Show only usernames"|translate}</option>
                                <option {-if "core.fullname"|setting eq 1} selected{-/if}
                                                                      value="1">{-"Show full names"|translate}</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="form" value="settings">
                    <input type="hidden" name="panel" value="users-panel">

                    <input class="ajaxform-callback" type="hidden" value="showPanelResult">
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default" id="registration-panel">
            <div class="panel-heading">
                <i class="fa fa-pencil fa-fw"></i>&nbsp;{-"Registration"|translate}
                <span class="pull-right text-success hidden panel-feedback-success"><i
                            class="fa fa-check"></i>&nbsp;{-"Changes Saved"|translate}</span>
                <span class="pull-right text-danger hidden panel-feedback-error"><i
                            class="fa fa-warning"></i>&nbsp;{-"An error occurred"|translate}</span>
            </div>
            <div class="panel-body ">
                <form class="form-horizontal ajaxform" method="post" action="{-"index.php?m=admin&action=save"|URL}">
                    <div class="form-group">
                        <label for="register-allow"
                               class="col-sm-4 control-label">{-"Who is allowed to register?"|translate}</label>

                        <div class="col-sm-8">
                            <select class="form-control" id="register-allow" name="settings-register.permissions">

                                <option value="everyone"{-if "register.permissions"|setting eq "everyone"}
                                        selected{-/if}>{-"Everyone"|translate}</option>
                                <option value="activation"{-if "register.permissions"|setting eq "activation"}
                                        selected{-/if}>{-"Everyone, but user must be activated by an administrator"|translate}</option>
                                <option value="invitation" {-if "register.permissions"|setting eq "invitation"}
                                        selected{-/if}>{-"Only users who received an invitation"|translate}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="register-notify"
                               class="col-sm-4 control-label">{-"Do you want to be notified?"|translate}</label>

                        <div class="col-sm-8">
                            <select class="form-control" id="register-notify" name="settings-register.notification">
                                <option value="1" {-if !"register.notification"|setting eq 0}
                                        selected{-/if}>{-"Send me an email, if a new user registered (and verified the account)"|translate}</option>
                                <option value="0" {-if "register.notification"|setting eq 0}
                                        selected{-/if}>{-"Do not send me an email"|translate}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="min-age"
                               class="col-sm-4 control-label">{-"Do you want a minimum age for registration?"|translate}</label>

                        <div class="col-sm-8">
                            <input type="number" value="{-"register.min_age"|setting}" id="min-age" class="form-control"
                                   name="settings-register.min_age">
                            <span class="help-block">{-"Set the age to 0 to remove the minimum age!"|translate}</span>
                        </div>
                    </div>
                    <input type="hidden" name="form" value="settings">
                    <input type="hidden" name="panel" value="registration-panel">

                    <input class="ajaxform-callback" type="hidden" value="showPanelResult">
                </form>
            </div>
        </div>
    </div>
</div>       