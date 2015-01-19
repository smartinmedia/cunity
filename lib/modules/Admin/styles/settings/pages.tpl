<link rel="stylesheet" href="{-"core.siteurl"|setting}lib/plugins/summernote/css/summernote.css">
<script type="text/javascript">
    scriptsToInclude = [
        '{-"core.siteurl"|setting}lib/plugins/summernote/js/summernote.min.js',
    ];
</script>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Static Pages"|translate}</h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Settings"|translate}</li>
            <li class="active">{-"Static Pages"|translate}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-files-o fa-fw"></i>&nbsp;{-"Existing Pages"|translate}
                <span class="pull-right text-success hidden panel-feedback-success"><i
                            class="fa fa-check"></i>&nbsp;{-"Changes Saved"|translate}</span>
                <span class="pull-right text-danger hidden panel-feedback-error"><i
                            class="fa fa-warning"></i>&nbsp;{-"An error occurred"|translate}</span>
            </div>
            <table class="table-striped table-responsive table">
                <thead>
                <tr>
                    <th>{-"Page-Title & Preview"|translate}</th>
                    <th width="150px">{-"Last Change"|translate}</th>
                    {-*<th width="100px">{-"Comments"|translate}</th>*}
                    <th width="200px">{-"Actions"|translate}
                </tr>
                </thead>
                <tbody id="pagesbody"></tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default" id="editPagePanel">
            <div class="panel-heading">
                <div class="addpageheader">
                    <i class="fa fa-plus fa-fw"></i>&nbsp;{-"Add new page"|translate}
                </div>
                <div class="editpageheader hidden">
                    <i class="fa fa-pencil fa-fw"></i>&nbsp;{-"Edit page"|translate}
                </div>
                <span class="pull-right text-success hidden panel-feedback-success"><i
                            class="fa fa-check"></i>&nbsp;{-"Changes Saved"|translate}</span>
                <span class="pull-right text-danger hidden panel-feedback-error"><i
                            class="fa fa-warning"></i>&nbsp;{-"An error occurred"|translate}</span>
            </div>
            <div class="panel-body">
                <form class="ajaxform form-validate" data-bv-excluded=""
                      action="{-"index.php?m=admin&action=settings"|URL}" id="pageform" method="POST"
                      data-bv-message="{-"This field is required"|translate}">
                    <div class="form-group">
                        <label for="title" class="control-label">{-"Page-Title"|translate}</label>
                        <input type="text" required data-bv-stringlength
                               data-bv-stringlength-message="{-"The title is too short (min. 3 chars)"|translate}"
                               data-bv-stringlength-min="3" name="title" class="form-control" id="pages-title"
                               placeholder="{-"Choose a short Title for this page"|translate}">
                    </div>
                    <div class="form-group">
                        <label for="title" class="control-label">{-"Page-Content"|translate}</label>
                        <input type="text" id="pages-summernote" required name="content" data-bv-trigger="keyup">
                    </div>
                    {-*<div class="form-group">
                    <div class="checkbox">
                    <label>
                    <input type="checkbox" name="comments" value="1">&nbsp;{-"Allow comments from registered users"|translate}
                    </label>
                    </div>
                    </div>*}
                    <input type="hidden" name="action" value="addPage">
                    <input type="hidden" name="pageid" value="0">
                    <input type="hidden" class="ajaxform-callback" value="pageCreated">
                </form>
            </div>
            <div class="panel-footer clearfix">
                <button class="btn btn-primary pull-right addpagebutton" onclick="$('#pageform').submit();"><i
                            class="fa fa-plus"></i>&nbsp;{-"Create page"|translate}</button>
                <button class="btn btn-primary pull-right editpagebutton hidden" onclick="$('#pageform').submit();" id="refresh"><i
                            class="fa fa-refresh"></i>&nbsp;{-"Update page"|translate}</button>
                <button class="btn btn-default pull-right editpagecancel hidden" style="margin-right:10px;"><i
                            class="fa fa-times"></i>&nbsp;{-"Cancel"|translate}</button>
            </div>
        </div>
    </div>
</div>
<script id="pages-row" type="text/html">
    <tr id="page-row-{%=o.id%}" class="page-table-row">
        <td>
            <strong><a href="{-"core.siteurl"|setting}pages/{%=o.shortlink%}" class="table-row-title"
                       target="_blank">{%#o.title%}</a></strong>

            <div class="pagescontent">{%#o.content%}</div>
            <a class="btn-sm btn btn-default slideup hidden"><i class="fa fa-chevron-up"></i>&nbsp;{-"Close"|translate}
            </a></td>
        <td>{%#convertDate(o.time)%}</td>
{-*<td class="comments" data-status="{%=o.comments%}">{% if (o.comments == 1) { %}<span class="label label-success">{-"Enabled"|translate}</span>{% } else { %}<label class="label label-danger">{-"Disabled"|translate}</label>{% } %}</td>*}
        <td>
            <button class="btn btn-info" onclick="editPage(Number('{%=o.id%}'));"><i class="fa fa-pencil"></i>&nbsp;{-"Edit"|translate}</button>
            <button class="btn btn-danger" onclick="deletePage(Number('{%=o.id%}'));"><i class="fa fa-trash-o"></i>&nbsp;{-"Delete"|translate}</button>
        </td>
    </tr>
</script>