<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Profile fields"|translate}
            <button class="btn btn-success pull-right saveButton"><i class="fa fa-save"></i>&nbsp;{-"Save"|translate}
            </button>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Settings"|translate}</li>
            <li class="active">{-"Profile fields"|translate}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
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
                                <td>{-$field.required}</td>
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