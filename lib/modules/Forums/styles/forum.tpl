<div class="page-buttonbar clearfix">
    <div class="pull-left">
        <h1 class="page-header">{-$forum.title}</h1>
    </div>
    {-if $user.groupid > 2}
    <button class="btn btn-default pull-right tooltip-trigger" data-toggle="modal" data-target="#editForum"
            data-title="{-"Edit this forum"|translate}"><i class="fa fa-pencil"></i></button>{-/if}
    <button class="pull-right btn btn-primary" data-toggle="modal" data-target="#addBoard"><i class="fa fa-pencil"></i>&nbsp;{-"Create a new Board"|translate}
    </button>
    <ul class="breadcrumb">
        <li class="tooltip-trigger" data-title="{-"Forums"|translate}"><a href="{-"index.php?m=forums"|URL}"><i
                        class="fa fa-bullhorn"></i></a></li>
        <li class="active">{-$forum.title}</li>
    </ul>
    <input type="hidden" id="forum_id" value="{-$forum.id}">
</div>

<div class="list" id="boards">
    <div class="loader block-loader" id="forum-loader"></div>
    <div class="alert alert-danger alert-block hidden">{-"There are no Boards to show"|translate}</div>
</div>
<div class="modal fade" id="editForum" tabindex="-1" role="dialog" aria-labelledby="editForum" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{-"Edit this forum"|translate}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal ajaxform" id="editForumForm" role="form"
                      action="{-"index.php?m=forums&action=editForum"|URL}" method="post">
                    <div class="form-group">
                        <label for="board-title" class="col-sm-2 control-label">{-"Title"|translate}</label>

                        <div class="col-sm-10">
                            <input type="text" name="title" id="board-title" class="form-control"
                                   value="{-$forum.title}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="topic-content" class="col-sm-2 control-label">{-"Description"|translate}</label>

                        <div class="col-sm-10">
                            <textarea class="form-control" id="topic-content"
                                      name="description">{-$forum.description}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="board_permissions" value="1"
                                           {-if $forum.board_permissions eq 1}checked{-/if}>&nbsp;{-"Users are allowed to create new boards"|translate}
                                </label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="forum_id" value="{-$forum.id}">
                    <input type="hidden" class="ajaxform-callback" value="forumUpdated">
                    <button class="hidden" type="submit"></button>
                </form>
            </div>
            <div class="modal-footer clearfix">
                <button class="btn btn-link pull-left" onclick="deleteForum();"><i
                            class="fa fa-trash-o"></i>&nbsp;{-"Delete this Forum"|translate}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{-"Close"|translate}</button>
                <button type="button" class="btn btn-primary"
                        onclick="$('#editForumForm').submit();">{-"Save Changes"|translate}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addBoard" tabindex="-1" role="dialog" aria-labelledby="addBoard" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{-"Create a new board"|translate}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal ajaxform" id="addBoardForm" role="form"
                      action="{-"index.php?m=forums&action=createBoard"|URL}" method="post">
                    <div class="form-group">
                        <label for="board-title" class="col-sm-2 control-label">{-"Title"|translate}</label>

                        <div class="col-sm-10">
                            <input type="text" name="title" id="board-title" class="form-control"
                                   placeholder="{-""|translate}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="topic-content" class="col-sm-2 control-label">{-"Description"|translate}</label>

                        <div class="col-sm-10">
                            <textarea class="form-control" id="topic-content" name="description"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="forum_id" value="{-$forum.id}">
                    <input type="hidden" class="ajaxform-callback" value="boardCreated">
                    <button class="hidden" type="submit"></button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"
                        onclick="$('#addBoardForm').submit();">{-"Create"|translate}</button>
            </div>
        </div>
    </div>
</div>
<script id="board-template" type="text/html">
    <div class="topic-post media" id="post-{%=o.id%}" data-id="{%=o.id%}">
        {% if (o.important == 1) { %}
        <span class="fa fa-exclamation-circle fa-2x pull-left"></span>
        {% } else { %}
        <span class="fa fa-comment fa-2x pull-left"></span>
        {% } %}
        <div class="media-body">
            <a href="{%=convertUrl({'module':'forums','action':'board','x':o.id})%}">{%=o.title%}</a>

            <p><i>{%#o.description%}</i></p>
        </div>
    </div>
</script>
{-include file="Forums/styles/category-cloud.tpl"}