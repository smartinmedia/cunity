<div class="page-buttonbar">
    <h1 class="page-header pull-left">{-$thread.title}</h1>
    {-if $user.groupid > 2}
        <button class="btn btn-default pull-right tooltip-trigger" data-toggle="modal" data-target="#editThread"
                data-title="{-"Edit this Thread"|translate}"><i class="fa fa-pencil"></i></button>{-/if}
        <button class="pull-right btn btn-primary" onclick="postReply();"><i
                class="fa fa-reply"></i>&nbsp;{-"Post a reply"|translate}</button>
        <ul class="breadcrumb">
            <li class="tooltip-trigger" data-title="{-"Forums"|translate}"><a href="{-"index.php?m=forums"|URL}"><i
                        class="fa fa-bullhorn"></i></a></li>
            <li><a href="{-"index.php?m=forums&action=forum&x={-$thread.forum_id}"|URL}">{-$thread.forumtitle}</a></li>
            <li><a href="{-"index.php?m=forums&action=board&x={-$thread.board_id}"|URL}">{-$thread.boardtitle}</a></li>
            <li class="active">{-$thread.title}</li>
        </ul>
        <input type="hidden" id="thread_id" value="{-$thread.id}">
    </div>
    <div class="list" id="posts">
        <div class="loader block-loader" id="thread-loader"></div>
        <div class="alert alert-danger alert-block hidden">{-"There are no posts to show"|translate}</div>
    </div>
    <div class="thread-new clearfix">
        <h4 class="pull-left">{-"Post your reply"|translate}</h4>
        <button type="button" class="close" aria-hidden="true" onclick="hideReply();">&times;</button>
        <form role="form" class="ajaxform" method="post" action="{-"index.php?m=forums&action=postReply"|URL}"
              id="newpostForm">
            <div class="form-group">
                <div id="summernote-newpost"></div>
                <input type="hidden" name="content">
            </div>
            <input type="hidden" name="thread_id" value="{-$thread.id}">
            <input type="hidden" class="ajaxform-callback" value="replyPosted">
            <button type="submit" id="reply-button" disabled
                    class="pull-right btn btn-primary pull-right">{-"Send reply"|translate}</button>
            <button class="btn btn-default pull-right tooltip-trigger" id="thread-new-emoticon-button"
                    style="margin-right:10px" type="button" data-placement="top"
                    data-title="{-"Add an emoticon"|translate}"><i class="fa fa-smile-o"></i></button>
        </form>
    </div>
    <div id="thread-new-emoticons" class="hidden">
        <div></div>
    </div>
    <div id="thread-pagination">
        <ul class="pagination">
            <li id="thread-pagination-prev"><a href="#" data-page="prev">&laquo;</a></li>
                {-for $page=1 to $thread.postcount/20}
                <li id="thread-pagination-page-{-$page}"><a href="#" data-page="{-$page}">{-$page}</a></li>
                {-/for}
            <li id="thread-pagination-next"><a href="#" data-page="next">&raquo;</a></li>
        </ul>
    </div>
    {-if $user.groupid > 2}
        <div class="modal fade" id="editThread" tabindex="-1" role="dialog" aria-labelledby="editThread" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">{-"Edit this Thread"|translate}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal ajaxform" id="editThreadForm" role="form"
                              action="{-"index.php?m=forums&action=editThread"|URL}" method="post">
                            <div class="form-group">
                                <label for="thread-title" class="col-sm-2 control-label">{-"Title"|translate}</label>

                                <div class="col-sm-10">
                                    <input type="text" name="title" id="thread-title" class="form-control"
                                           value="{-$thread.title}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="thread-category" class="col-sm-2 control-label">{-"Category"|translate}</label>

                                <div class="col-sm-10">
                                    <select class="form-control" id="thread-category" name="category">
                                        <option value="">{-"Select a category"|translate}</option>
                                        {-foreach $categories AS $cat}
                                            <option value="{-$cat.id}"
                                                    {-if $thread.category eq $cat.id}selected{-/if}>{-$cat.name}</option>
                                        {-/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="important" value="1"
                                                   {-if $thread.important == 1}checked{-/if}>&nbsp;{-"Keep Thread always on the top"|translate}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="thread_id" value="{-$thread.id}">
                            <input type="hidden" class="ajaxform-callback" value="threadUpdated">
                            <button class="hidden" type="submit"></button>
                        </form>
                    </div>
                    <div class="modal-footer clearfix">
                        <button class="btn btn-link pull-left" onclick="deleteThread();"><i
                                class="fa fa-trash-o"></i>&nbsp;{-"Delete this Thread"|translate}</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary"
                                onclick="$('#editThreadForm').submit();">{-"Save changes"|translate}</button>
                    </div>
                </div>
            </div>
        </div>
    {-/if}
    <script id="post-template" type="text/html">
        <div class="thread-post media" id="post-{%=o.id%}" data-id="{%=o.id%}">
            <a class="pull-left media-image" href="{-"index.php?m=profile&action="|URL}{%=o.username%}"><img
                        src="{%=checkImage(o.filename,'user','cr_')%}" class="img-rounded thumbnail media-object"></a>
            <div class="media-body">
                <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}">{%=o.name%}</a><br>
                {%#o.content%}
            </div>
            <div class="media-button-line">
                {% if (2<{-$user.groupid} || o.userid == {-$user.userid}) { %}
                <button class="btn btn-primary btn-xs pull-right deletepost" data-postid="{%=o.id%}"><i
                        class="fa fa-trash-o"></i>&nbsp;{-"Delete post"|translate}</button>{% } %}
                <button class="btn btn-default btn-xs pull-right" onclick="citePost(Number('{%=o.id%}'));"><i
                        class="fa fa-quote-right"></i>&nbsp;{-"Cite post"|translate}</button>
                <div class="media-body-time">&nbsp;{%#convertDate(o.time)%}</div>
            </div>
        </div>
    </script>
    {-include file="Forums/styles/category-cloud.tpl"}