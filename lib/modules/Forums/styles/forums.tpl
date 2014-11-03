<div class="page-buttonbar clearfix">
    <h1 class="page-header pull-left">{-"Forums"|translate}</h1>
    {-if $user.groupid == 3}
        <button type="button" class="pull-right btn btn-primary" data-toggle="modal" data-target="#addForum" data-forumid="{%=o.id%}"><i
            class="fa fa-plus"></i>&nbsp;{-"New Forum"|translate}</button>{-/if}
</div>
<div class="alert alert-block alert-danger hidden">{-"Currently there are no forums"|translate}</div>
<div class="list" id="forums"></div>
<div class="modal fade" id="addForum" tabindex="-1" role="dialog" aria-labelledby="addForum" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{-"Create a new Forum"|translate}</h4>
            </div>
            <div class="modal-body">
                <form id="createForumForm" class="form-horizontal ajaxform" role="form"
                      action="{-"index.php?m=forums&action=createForum"|URL}" method="post">
                    <div class="form-group">
                        <label for="forum-title" class="col-sm-2 control-label">{-"Title"|translate}</label>

                        <div class="col-sm-10">
                            <input type="text" data-bv-notempty-message="t" required name="title" id="forum-title"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="forum-content" class="col-sm-2 control-label">{-"Description"|translate}</label>

                        <div class="col-sm-10">
                            <textarea required data-bv-notempty-message="d" class="form-control" id="forum-content"
                                      name="description"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="board_permissions"
                                           value="1">&nbsp;{-"Users are allowed to create new boards"|translate}
                                </label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="ajaxform-callback" value="forumCreated">
                    <button type="submit" class="hidden">submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{-"Close"|translate}</button>
                <button type="button" class="btn btn-primary"
                        onclick="$('#createForumForm').submit();">{-"Create"|translate}</button>
            </div>
        </div>
    </div>
</div>
<script id="forum-template" type="text/html">
    <div class="panel panel-default forum" id="forum-{%=o.id%}">
        <div class="panel-heading clearfix">
            <h4 class="pull-left"><a
                        href="{%=convertUrl({'module':'forums','action':'forum','x':o.id})%}">{%=o.title%}</a></h4>
        </div>
        <div class="panel-body">
            <strong>{-"Description"|translate}:</strong>
            <i>{%#o.description%}</i>
        </div>
        <div class="list-group">
            {%#o.boards%}
            <div class="list-group-item loadmoreboards hidden">
                <button class="btn btn-block btn-primary btn-sm">{-"Load more boards"|translate}</button>
            </div>
            <div class="list-group-item noboards hidden">
                <div class="alert alert-block alert-danger">
                    {-"This Forum does not have any boards yet"|translate}
                </div>
            </div>
        </div>
    </div>
</script>
<script id="board-template" type="text/html">
    <div class="list-group-item media">
        <span class="fa fa-comment fa-2x pull-left list-group-item-icon"></span>

        <div class="media-body">
            <a href="{%=convertUrl({'module':'forums','action':'board','x':o.id})%}"><h4
                        class="list-group-item-heading">{%=o.title%}</h4></a>

            <p class="list-group-item-text">{%#o.description%}</p>
        </div>
    </div>
</script>
{-include file="Forums/styles/category-cloud.tpl"}