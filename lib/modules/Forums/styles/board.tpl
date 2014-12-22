<div class="page-buttonbar">
    <div class="pull-left">
        <h1 class="page-header">{-$board.title}</h1>
    </div>
    {-if $user.groupid > 2}
    <button class="btn btn-default pull-right tooltip-trigger" data-toggle="modal" data-target="#editBoard"
            data-title="{-"Edit this board"|translate}" ><i class="fa fa-pencil"></i></button>{-/if}
    <button class="pull-right btn btn-primary" data-toggle="modal" data-target="#startThread"><i
                class="fa fa-pencil"></i>&nbsp;{-"Start a new Thread"|translate}</button>
    <ul class="breadcrumb">
        <li class="tooltip-trigger" data-title="{-"Forums"|translate}"><a href="{-"index.php?m=forums"|URL}"><i
                        class="fa fa-bullhorn"></i></a></li>
        <li><a href="{-"index.php?m=forums&action=forum&x={-$board.forum_id}"|URL}">{-$board.parenttitle}</a></li>
        <li class="active">{-$board.title}</li>
    </ul>
    <input type="hidden" id="board_id" value="{-$board.id}">
</div>
<div class="modal fade" id="editBoard" tabindex="-1" role="dialog" aria-labelledby="editBoard" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{-"Edit this board"|translate}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal ajaxform" id="editBoardForm" role="form"
                      action="{-"index.php?m=forums&action=editBoard"|URL}" method="post">
                    <div class="form-group">
                        <label for="board-title" class="col-sm-2 control-label">{-"Title"|translate}</label>

                        <div class="col-sm-10">
                            <input type="text" name="title" id="board-title" class="form-control"
                                   value="{-$board.title}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="board-content" class="col-sm-2 control-label">{-"Description"|translate}</label>

                        <div class="col-sm-10">
                            <textarea class="form-control" id="board-content"
                                      name="description">{-$board.description}</textarea>
                        </div>
                    </div>
                    <input type="hidden" name="board_id" value="{-$board.id}">
                    <input type="hidden" class="ajaxform-callback" value="boardUpdated">
                    <button class="hidden" type="submit"></button>
                </form>
            </div>
            <div class="modal-footer clearfix">
                <button class="btn btn-link pull-left" onclick="deleteBoard();"><i
                            class="fa fa-trash-o"></i>&nbsp;{-"Delete this Board"|translate}</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">{-"Close"|translate}</button>
                <button type="button" class="btn btn-primary"
                        onclick="$('#editBoardForm').submit();">{-"Save changes"|translate}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="startThread" tabindex="-1" role="dialog" aria-labelledby="startThread" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{-"Start a new Thread"|translate}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal ajaxform" id="startThreadForm" role="form"
                      action="{-"index.php?m=forums&action=startThread"|URL}" method="post">
                    {-*<input type="hidden" name="category" value="" />*}
                    <div class="form-group">
                        <label for="thread-title" class="col-sm-2 control-label">{-"Title"|translate}</label>

                        <div class="col-sm-10">
                            <input type="text" name="title" id="thread-title" class="form-control"
                                   placeholder="{-""|translate}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{-"Message"|translate}</label>

                        <div class="col-sm-10">
                            <input name="content" type="text" id="startThreadForm-summernote">
                        </div>
                    </div>
                    {-*<div class="form-group">*}
                        {-*<label for="thread-category" class="col-sm-2 control-label">{-"Category"|translate}</label>*}

                        {-*<div class="col-sm-10">*}
                            {-*<select class="form-control" id="thread-category" name="category">*}
                                {-*<option value="">{-"Select a category"|translate}</option>*}
                                {-*{-foreach $categories AS $cat}*}
                                {-*<option value="{-$cat.id}">{-$cat.name}</option>*}
                                {-*{-/foreach}*}
                            {-*</select>*}
                        {-*</div>*}
                    {-*</div>*}
                    {-if $user.groupid > 2}
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="important"
                                           value="1">&nbsp;{-"Keep Thread always on the top"|translate}
                                </label>
                            </div>
                        </div>
                    </div>
                    {-/if}
                    <input type="hidden" name="board_id" value="{-$board.id}">
                    <input type="hidden" class="ajaxform-callback" value="threadStarted">
                    <button class="hidden" type="submit"></button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary"
                        onclick="$('#startThreadForm').submit();">{-"Create"|translate}</button>
            </div>
        </div>
    </div>
</div>
<div id="thread-new-emoticons" class="hidden">
    <div></div>
</div>
<div class="list" id="threads">
    <div class="loader block-loader" id="board-loader"></div>
    <div class="alert alert-danger alert-block hidden">{-"There are no posts to show"|translate}</div>
</div>
<script id="thread-template" type="text/html">
    <div class="topic-post media" id="post-{%=o.id%}" data-id="{%=o.id%}">
        {% if (o.important == 1) { %}
        <span class="fa fa-exclamation-circle fa-2x pull-left"></span>
        {% } else { %}
        <span class="fa fa-comment fa-2x pull-left"></span>
        {% } %}
        <div class="media-body">
            <a href="{%=convertUrl({'module':'forums','action':'thread','x':o.id})%}">{%=o.title%}</a><br>
            {%#o.content%}
        </div>
        <ul class="list-inline list-unstyled text-muted thread-buttons">
            <li><i class="fa fa-user"></i><a
                        href="{%=convertUrl({'module':'profile','action':o.username})%}">{%=o.name%}</a></li>
            <li><i class="fa fa-calendar"></i>{%#convertDate(o.time)%}</li>
            <li><i class="fa fa-comments"></i>{%=(o.postcount-1)%}&nbsp;{-"Replies"|translate}</li>
            {% if (o.categoryName != null) { %}
            <li><a class="label label-info"
                   href="{%=convertUrl({'module':'forums','action':'category','x':o.categoryTag})%}">{%=o.categoryName%}</a>
            </li>
            {% } %}
        </ul>
    </div>
</script>
{-include file="Forums/styles/category-cloud.tpl"}