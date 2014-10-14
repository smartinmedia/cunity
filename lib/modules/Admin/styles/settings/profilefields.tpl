<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Profile fields"|translate}
            <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#addprofilefieldmodal"><i
                        class="fa fa-plus"></i>&nbsp;{-"Add field"|translate}</button>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Settings"|translate}</li>
            <li class="active">{-"Profile fields"|translate}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" id="profilefield-panel">
            <div class="panel-heading">
                <i class="fa fa-pencil fa-fw"></i>&nbsp;{-"Profile fields"|translate}
                <span class="pull-right text-success hidden panel-feedback-success"><i
                            class="fa fa-check"></i>&nbsp;{-"Changes Saved"|translate}</span>
                <span class="pull-right text-danger hidden panel-feedback-error"><i
                            class="fa fa-warning"></i>&nbsp;{-"An error occurred"|translate}</span>
            </div>
            <div class="panel-body ">
                <form class="form-horizontal ajaxform" method="post" action="{-"index.php?m=admin&action=save"|URL}">

                    <table class="table-striped table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{-"Field"|translate}</th>
                            <th>{-"Type"|translate}</th>
                            <th>{-"Required"|translate}</th>
                            <th>{-"Actions"|translate}</th>
                        </tr>
                        </thead>
                        <tbody id="profilefieldstable">
                        {-foreach $profileFields AS $i => $field}
                            <tr class="profilefield-{-$field.value}">
                                <td>{-$i+1}</td>
                                <td>{-$field.value}</td>
                                <td>{-$field.type}</td>
                                <td>{-if $field.required == 1}<span
                                            class="label label-danger">{-"required"|translate}</span>{-else}<span
                                            class="label label-success">{-"optional"|translate}</span>{-/if}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button"
                                                class="btn btn-primary dropdown-toggle"
                                                data-toggle="dropdown">{-"Actions"|translate}&nbsp;<span
                                                    class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li><a href="javascript: editProfileField({-$field.id});"><i
                                                            class="fa fa-pencil"></i>&nbsp;{-"Edit this field"|translate}
                                                </a>
                                            </li>
                                            {-if $field.deleteable == 1}
                                                <li><a href="javascript: removeProfileField({-$field.id});"><i
                                                                class="fa fa-trash-o"></i>&nbsp;{-"Delete this field"|translate}
                                                    </a>
                                                </li>
                                            {-/if}
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        {-/foreach}
                    </table>
                    <input type="hidden" name="form" value="profilefields">
                    <input type="hidden" name="panel" value="profilefields-panel">

                    <input class="ajaxform-callback" type="hidden" value="showPanelResult">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addprofilefieldmodal" tabindex="-1" role="dialog" aria-labelledby="addprofilefieldmodal"
     aria-hidden="true">
    <form class="login-form form-horizontal ajaxform" action="{-"index.php?m=admin&action=save"|URL}"
          style="margin:10px;" name="profilefields">
        <input type="hidden" name="action" value="save" />
        <input type="hidden" class="ajaxform-callback" value="addProfilefield" />
        <input type="hidden" name="form" value="profilefield" />
        <input type="hidden" name="deleteable" value="1" />

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">{-"Add Profile field"|translate}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cunity-name"
                               class="col-sm-4 control-label">{-"Name"|translate}*</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="" id="name"
                                   name="name" required="required" data-bv-stringlength data-bv-stringlength-min="3"
                                   data-bv-stringlength-message="{-"Name is too short (min. 3 chars)"|translate}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cunity-name"
                               class="col-sm-4 control-label">{-"Type"|translate}*</label>

                        <div class="col-sm-8">
                            <select class="form-control" name="type" required="required">
                                <option value="">{-"Make a choice"|translate}</option>
                                {-foreach $fieldTypes as $i => $value}
                                    <option value="{-$i}">{-$value|translate}</option>
                                {-/foreach}
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cunity-name"
                               class="col-sm-4 control-label">{-"Required"|translate}</label>

                        <div class="col-sm-8">
                            <input type="checkbox" name="required" value="1" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cunity-name"
                               class="col-sm-4 control-label">{-"Show in registration"|translate}</label>

                        <div class="col-sm-8">
                            <input type="checkbox" name="registration" value="1" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-bb-handler="confirm" class="btn btn-default" data-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
    </form>
</div>