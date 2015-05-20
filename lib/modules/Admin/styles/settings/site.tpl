<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Site"|translate}
            <button class="btn btn-success pull-right saveButton"><i class="fa fa-save"></i>&nbsp;{-"Save"|translate}
            </button>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Settings"|translate}</li>
            <li class="active">{-"Site"|translate}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="panel panel-default" id="general-panel">
            <div class="panel-heading">
                <i class="fa fa-wrench fa-fw"></i>&nbsp;{-"General"|translate}
                <span class="pull-right text-success hidden panel-feedback-success"><i
                            class="fa fa-check"></i>&nbsp;{-"Changes Saved"|translate}</span>
                <span class="pull-right text-danger hidden panel-feedback-error"><i
                            class="fa fa-warning"></i>&nbsp;{-"An error occurred"|translate}</span>
            </div>
            <div class="panel-body">
                <form class="form-horizontal ajaxform form-validate" id="general-form" method="post"
                      action="{-"index.php?m=admin&action=save"|URL}">
                    <div class="form-group">
                        <label for="cunity-name"
                               class="col-sm-4 control-label">{-"Name of the Cunity"|translate}</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="{-"core.sitename"|setting}" id="cunity-name"
                                   name="settings-core.sitename" required data-bv-stringlength
                                   data-bv-stringlength-min="3"
                                   data-bv-stringlength-message="{-"Your site name is too short (min. 3 chars)"|translate}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cunity-name" class="col-sm-4 control-label">{-"Url of the Cunity"|translate}</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" placeholder="e.g. (http://www.example.com)"
                                   id="cunity-name" value="{-"core.siteurl"|setting}" name="settings-core.siteurl"
                                   required data-bv-stringlength data-bv-stringlength-min="11"
                                   data-bv-stringlength-message="{-"Please enter a valid url (must start with http:// or https:// !)"|translate}">
                            <span class="help-block"><strong>{-"Take care!"|translate}</strong>&nbsp;{-"If the URL is not correct, Cunity will not work correctly!"|translate}</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="contact-mail" class="col-sm-4 control-label">{-"Contact E-Mail"|translate}</label>

                        <div class="col-sm-8">
                            <input type="email" class="form-control" value="{-"core.contact_mail"|setting}"
                                   id="contact-mail"
                                   name="settings-core.contact_mail" required
                                   data-bv-emailaddress-message="{-"Please enter a valid email-address!"|translate}">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="beta" class="col-sm-4 control-label">{-"Beta"|translate}</label>

                        <div class="col-sm-8">
                            <div class="checkbox">
                                <label>
                                    <input type="hidden" name="settings-core.beta" value="0" />
                                    <input type="checkbox" name="settings-core.beta"
                                            {-if "core.beta"|setting == 1}
                                                checked="checked"
                                            {-/if}
                                           value="1">&nbsp;{-"Use beta version"|translate}
                                </label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="form" value="settings">
                    <input type="hidden" name="panel" value="general-panel">
                    <input class="ajaxform-callback" type="hidden" value="showPanelResult">
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="panel panel-default" id="language-panel">
            <div class="panel-heading">
                <i class="fa fa-globe fa-fw"></i>&nbsp;{-"Localization"|translate}
                <span class="pull-right text-success hidden panel-feedback-success"><i
                            class="fa fa-check"></i>&nbsp;{-"Changes Saved"|translate}</span>
                <span class="pull-right text-danger hidden panel-feedback-error"><i
                            class="fa fa-warning"></i>&nbsp;{-"An error occurred"|translate}</span>
            </div>
            <div class="panel-body ">
                <form class="form-horizontal ajaxform" method="post" action="{-"index.php?m=admin&action=save"|URL}">
                    <div class="form-group">
                        <label for="cunity-name" class="col-sm-4 control-label">{-"Language"|translate}</label>

                        <div class="col-sm-8">
                            <select class="form-control" name="settings-core.language">
                                {-foreach $availableLanguages AS $language}
                                    {-if {-"core.language"|setting} eq $language}
                                        <option value="{-$language}" selected>{-$language|translate}</option>
                                    {-else}
                                        <option value="{-$language}">{-$language|translate}</option>
                                    {-/if}
                                {-/foreach}
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="form" value="settings">
                    <input type="hidden" name="panel" value="language-panel">
                    <input class="ajaxform-callback" type="hidden" value="showPanelResult">
                </form>
            </div>
        </div>
        <div class="panel panel-default" id="theme-panel">
            <div class="panel-heading">
                <i class="fa fa-picture-o fa-fw"></i>&nbsp;{-"Theme"|translate}
                <span class="pull-right text-success hidden panel-feedback-success"><i
                            class="fa fa-check"></i>&nbsp;{-"Changes Saved"|translate}</span>
                                <span class="pull-right text-danger hidden panel-feedback-error"><i
                                            class="fa fa-warning"></i>&nbsp;{-"An error occurred"|translate}</span>
            </div>
            <div class="panel-body ">
                <form class="form-horizontal ajaxform" method="post" action="{-"index.php?m=admin&action=save"|URL}">
                    <div class="form-group">
                        <label for="cunity-name" class="col-sm-4 control-label">{-"Select a Theme"|translate}</label>

                        <div class="col-sm-8">
                            <select class="form-control" name="settings-core.design">
                                {-foreach $availableDesigns AS $design}
                                    {-if "design"|setting eq $design[0]}
                                        <option value="{-$design[0]}" selected>{-$design[1]|translate}</option>
                                    {-else}
                                        <option value="{-$design[0]}">{-$design[1]|translate}</option>
                                    {-/if}
                                {-/foreach}
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="form" value="settings">
                    <input type="hidden" name="panel" value="theme-panel">
                    <input class="ajaxform-callback" type="hidden" value="showPanelResult">
                </form>
            </div>
        </div>
    </div>
</div>