<div class="page-buttonbar">
    <div class="pull-left">
        <h1 class="page-header">{-"Category"|translate}:&nbsp;{-$category.name}</h1>
    </div>
    <ul class="breadcrumb">
        <li class="tooltip-trigger" data-title="{-"Forums"|translate}"><a href="{-"index.php?m=forums"|URL}"><i
                        class="fa fa-bullhorn"></i></a></li>
        <li class="active">{-"Category"|translate}:&nbsp;{-$category.name}</li>
    </ul>
    <input type="hidden" id="category" value="{-$category.id}">
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
                    <div class="form-group">
                        <label for="thread-title" class="col-sm-2 control-label">{-"Title"|translate}</label>

                        <div class="col-sm-10">
                            <input type="text" name="title" id="thread-title" class="form-control"
                                   placeholder="{-""|translate}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{-"Description"|translate}</label>

                        <div class="col-sm-10">
                            <div id="summernote-startThread"></div>
                            <input type="hidden" name="content">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="thread-category" class="col-sm-2 control-label">{-"Category"|translate}</label>

                        <div class="col-sm-10">
                            <select class="form-control" id="thread-category">
                                <option value="">{-"Select a category"|translate}</option>
                            </select>
                        </div>
                    </div>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sendThreadForm();">{-"Create"|translate}</button>
            </div>
        </div>
    </div>
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