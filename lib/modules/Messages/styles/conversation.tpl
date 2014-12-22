<input type="hidden" id="conversation-message-count" value="{-$conversation.count}">
<div class="page-buttonbar clearfix">
    <ul class="nav nav-pills pull-left">
        <li><a href="{-"index.php?m=messages"|URL}"><i class="fa fa-chevron-left"></i>&nbsp;{-"Back"|translate}</a></li>
    </ul>
    <div class="conversation-partners pull-left">
        {-if !$conversation.users|@is_string}
        {-foreach $conversation.users AS $cuser}
        <input type="hidden" class="cusers" value="{-$cuser.userid}">
        <a href="{-"index.php?m=profile&action="|URL}{-$cuser.username}"><img
                    class="thumbnail img-rounded tooltip-trigger" title="{-$cuser.name}"
                    src="{-$cuser.pimg|image:"user":"cr_"}" data-container="body">{-if $cuser@total eq 1}<span
                    class="conversation-partners-name">{-$cuser.name}</span>{-/if}</a>
        {-/foreach}
        {-else}
        {-"User left the Conversation"|translate}
        {-/if}
    </div>
    <div class="btn-group pull-right">
        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-cogs"></i>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="#" class="leaveConversation"
                   data-confirmation="{-"Do You also want to delete all Your messages in this conversation?"|translate}"><i
                            class="fa fa-sign-out fa-fw"></i>&nbsp;{-"Leave Conversation"|translate}</a></li>
            <li><a href="#" class="inviteFriend" data-toggle="modal" data-target="#invite_modal"><i
                            class="fa fa-plus fa-fw"></i>&nbsp;{-"Invite Friend"|translate}</a></li>
        </ul>
    </div>
    <button class="btn btn-default pull-right tooltip-trigger" id="refreshConversationButton"
            data-title="{-"Check for new messages manually"|translate}" onclick="loadMoreMessages(true);"><i
                class="fa fa-refresh"></i></button>
</div>
<div id="conversation-content">
    <div id="conversation-loader" class="block-loader loader"></div>
    <div id="conversation-more" class="hidden"><a href="javascript:loadMoreMessages();"><i class="fa fa-clock-o"></i>&nbsp;{-"Load more messages"|translate}
        </a></div>
    <div class="list clearfix"></div>
</div>

<form class="conversation-writingbox ajaxform clearfix" method="POST"
      action="{-"index.php?m=messages&action=send"|URL}">
    <div class="hidden-sm hidden-md hidden-lg">
        <div class="input-group hidden-sm hidden-md hidden-lg">
            <span class="input-group-btn">
                <button class="btn btn-default conversation-add-emoticon" type="button"
                        data-title="{-"Add an emoticon"|translate}" data-placement="top"><i class="fa fa-smile-o"></i>
                </button>
            </span>

            <div contenteditable="true" class="form-control message responsive"
                 placeholer="{-"Write your message"|translate}"></div>
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit"><i class="fa fa-chevron-right"></i></button>
            </span>
        </div>
        <!-- /input-group -->
    </div>
    <div class="hidden-xs">
        <img src="{-$user.pimg|image:"user":"cr_"}" class="thumbnail img-rounded pull-left">

        <div contenteditable="true" class="form-control pull-left message"
             placeholer="{-"Write a message"|translate}"></div>
    </div>
    <div id="temp" class="hidden"></div>
    <input type="hidden" name="message" id="hiddenmessagebox">
    <input type="hidden" class="ajaxform-callback" value="sendmessage">
    <input type="hidden" name="source" value="messages">
    <input type="hidden" name="conversation_id" id="conversation_id" value="{-$conversation.conversation_id}">
    <input type="submit" disabled value="{-"Send"|translate}" class="btn btn-primary pull-right hidden-xs"
           data-loading-text="{-"Sending"|translate}...">
    <button class="btn btn-default pull-right conversation-add-emoticon hidden-xs" style="margin-right:10px"
            type="button" data-placement="left" data-title="{-"Add an emoticon"|translate}"><i
                class="fa fa-smile-o"></i></button>
</form>
<div id="conversation-emoticon-list" class="hidden">
    <div></div>
</div>
<div class="modal fade" id="invite_modal" tabindex="-1" role="dialog" aria-labelledby="invite_modal" aria-hidden="true"
     data-moveto="body">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{-"Invite friends to conversation"|translate}</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hidden messagesentmessage">
                    <h4>{-"Done!"|translate}</h4>

                    <p>{-"Your message was sent successfully!"|translate}</p>
                </div>
                <form class="form-horizontal ajaxform" action="{-"index.php?m=messages&action=invite"|URL}" role="form"
                      id="inviteForm" method="POST">
                    <p class="help-block">{-"Type the names of the friends you want to invite to the conversation!"|translate}</p>

                    <div class="form-group">
                        <div class="col-sm-12 dropdown" style="position:relative;">
                            <div class="form-control receiver-content clearfix">
                                <div class="receiver-token"></div>
                                <input type="text" class="receiver-input" id="addReceiver">
                                <i class="fa fa-spinner fa-spin pull-right fa-lg hidden"
                                   id="receiver-suggestions-loader" style="margin:6px 3px"></i>
                                <i class="fa fa-warning pull-right fa-lg hidden tooltip-trigger"
                                   data-title="{-"No users found!"|translate}" data-container="#messages_modal"
                                   id="receiver-suggestions-alert" style="margin:6px 3px"></i>
                            </div>
                            <ul class="dropdown-menu receiver-suggestions" id="receiver-suggestions" role="menu"
                                aria-labelledby="receiver-suggestion"></ul>
                        </div>
                    </div>
                    <input type="hidden" class="ajaxform-callback" value="messageSent">
                    <input type="hidden" name="conversation_id" value="{-$conversation.conversation_id}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default closemodalbutton"
                        data-dismiss="modal">{-"Close"|translate}</button>
                <button type="submit" onclick="$('#inviteForm').submit();" class="btn btn-primary"
                        data-loading-text="{-"Please wait.."|translate}">{-"Invite"|translate}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="conversation-item">
    <div class="conversation-item-{% if (o.sender != {-$user.userid}) { %}foreign{% } else { %}me{% } %} clearfix"
         id="conversation-message-{%=o.id%}" data-id="{%=o.id%}">
        <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}" class="hidden-xs">
            <img src="{% if (o.sender != {-$user.userid}) { %}{%=o.pimg%}{% } else { %}{-$user.pimg|image:"user":"cr_"}{% } %}"
                 class="thumbnail img-rounded tooltip-trigger"
                 data-title="{%=o.name%}&nbsp;{-"sent from"|translate}&nbsp;{% if (o.source == "messages") { %}{-"Messages"|translate}{% } else { %}{-"Chat"|translate}{% } %}">
        </a>

        <div class="conversation-item-content-wrap">
            {% if (o.sender == {-$user.userid}) { %}
            <button type="button" class="close tooltip-trigger hidden conversation-message-delete"
                    data-msgid="{%=o.id%}" data-msg="{-"Are You sure You want to delete this message?"|translate}"
                    data-title="{-"Delete message"|translate}">&times;</button>
            {% } %}
            <div class="arrow"></div>
            <div class="conversation-item-content">
                <span class="textcontent">{-if $cuser@total > 1}<b
                            class="hidden-md hidden-lg conversation-item-content-mobile-name">{%=o.name%}</b>{-/if}{%#o.message%}</span>
                <span class="message-time">&nbsp;{%#convertDate(o.time)%}</span>
            </div>
        </div>
    </div>
</script>
<script type="text/html" id="receiver-tpl">
    <span class="label label-primary">{%=o.name%}
        <button type="button" class="close">&times;</button><input type="hidden" name="receiver[]"
                                                                   value="{%=o.userid%}"></span>
</script>
<script type="text/html" id="message-modal-result">
    <li role="presentation" class="message-searchresult-item">
        <a role="menuitem" tabindex="-1" href="javascript:addReceiver({%=o.userid%},'{%=o.name%}')">
            <img src="{%=o.profileImage%}" class="img-rounded">
            <span>{%=o.name%}</span>
        </a>
    </li>
</script>