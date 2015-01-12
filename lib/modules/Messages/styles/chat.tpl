<div id="chat-container" class="clearfix">
    <div id="chat-emoticon-list" class="hidden">
        <div></div>
    </div>
    <ul class="chat-boxes list-unstyled list-inline"></ul>
    <ul class="chat-list dropdown-menu">
        <li role="presentation" class="chat-controls">
            <div class="input-group">
                <input type="search" class="form-control" id="chat-search" placeholder="{-"Search"|translate}">

                <div class="input-group-btn dropdown dropup">
                    <button class="btn btn-default chat-offline-box hidden tooltip-trigger"
                            data-title="{-"Activate Chat"|translate}" onclick="changeChatStatus(1);"><i
                                class="fa fa-power-off"></i></button>
                    <button type="button" class="btn btn-default chat-online-box hidden dropdown-toggle"
                            data-toggle="dropdown"><i class="fa fa-cogs"></i></button>
                    <ul class="dropdown-menu" role="menu">
                        <li id="closeAllChatsButton"><a href="javascript:closeAllChatWindows();"><i
                                        class="fa fa-times"></i>&nbsp;{-"Close all chat windows"|translate}</a></li>
                        <li><a href="javascript:changeChatStatus(0);"><i
                                        class="fa fa-power-off"></i>&nbsp;{-"Deactivate Chat"|translate}</a></li>
                    </ul>
                </div>
            </div>
        </li>
    </ul>
</div>
<script id="onlinefriends" type="text/html">
    <li role="presentation" class="online-friend-item">
        <a role="menuitem" tabindex="-1" href="javascript:chat({%=o.userid%})" class="clearfix chat-user"
           id="chat-user-{%=o.userid%}">
            <img src="{%=checkImage(o.pimg,'user','cr_')%}" class="img-rounded pull-left">
            <span class="pull-left online-friend-item-name">{%=o.name%}</span>
            <i class="fa online-friend-item-status-{% if (o.onlineStatus == 1 && o.chat_available == 1) { %}active fa-circle{% } else if (o.onlineStatus == 0 && o.chat_available == 1){ %}inactive fa-circle{% } else { %}offline fa-circle-o{% } %} pull-right"></i>
        </a>
    </li>
</script>
<script id="chat-panel" type="text/html">
    <li id="chat-panel-{%=o.conversation_id%}">
        <div class="panel panel-default chat-panel" data-cid="{%=o.conversation_id%}">
            <div class="panel-heading clearfix">
                <span class="pull-left">
                    {% if (o.partners.length == 1) { %}
                    <i class="fa chat-panel-status-{% if (o.online == 1 && o.onlineStatus == 1 && o.chat_available == 1) { %}active fa-circle{% } else if (o.online == 1 && o.onlineStatus == 0 && o.chat_available == 1){ %}inactive fa-circle{% } else { %}offline fa-circle-o{% } %}"></i>&nbsp;{%=o.users%}
                    {% } else { %}
                    <i class="fa fa-group"></i>&nbsp;{%=o.users%}
                    {% } %}
                    <i class="fa fa-envelope-o unread-sign"></i>
                </span>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                        data-cid="{%=o.conversation_id%}">&times;</button>
            </div>
            <div class="panel-body">
                <div class="chat-panel-list clearfix"></div>
            </div>
            <form class="ajaxform panel-footer" action="{-"index.php?m=messages&action=send"|URL}" method="POST"
                  onsubmit="checkChatMessage('{%=o.conversation_id%}');">
                <div contenteditable="true" class="chat-panel-input" placeholer="{-"Write a message"|translate}"
                     data-cid="{%=o.conversation_id%}"></div>
                <i class="fa fa-smile-o chat-panel-smiley-button" data-placement="top"
                   data-title="{-"Add an emoticon"|translate}" data-cid="{%=o.conversation_id%}"></i>
                <input type="hidden" class="ajaxform-callback" value="sendChatMessage">
                <input type="hidden" name="source" value="chat">
                <input type="hidden" name="conversation_id" value="{%=o.conversation_id%}">
                <input type="hidden" name="message" id="hiddenChatBox-{%=o.conversation_id%}">

                <div id="chat-temp-{%=o.conversation_id%}" class="hidden"></div>
            </form>
        </div>
    </li>
</script>
<script id="chat-item" type="text/html">
    <div class="conversation-item-{% if (o.sender != {-$user.userid}) { %}foreign{% } else { %}me{% } %} clearfix"
         id="conversation-message-{%=o.id%}" data-id="{%=o.id%}">
        <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}" class="hidden-xs">
            <img src="{% if (o.sender != {-$user.userid}) { %}{%=checkImage(o.pimg,'user','cr_')%}{% } else { %}{-$user.pimg|image:"user":"cr_"}{% } %}"
                 class="img-rounded tooltip-trigger" data-title="{%=o.time%}">
        </a>

        <div class="conversation-item-content-wrap">
            <div class="arrow"></div>
            <div class="conversation-item-content">
                <span class="textcontent">{-if $cuser@total > 1}<b
                            class="hidden-md hidden-lg conversation-item-content-mobile-name">{%=o.name%}</b>{-/if}{%#o.message%}</span>
            </div>
        </div>
    </div>
</script>