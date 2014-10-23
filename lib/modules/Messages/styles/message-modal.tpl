<div class="modal fade" id="messages_modal" tabindex="-1" role="dialog" aria-labelledby="messages_modal"
     aria-hidden="true" data-moveto="body">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{-"Send a message"|translate}</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hidden messagesentmessage">
                    <h4>{-"Done!"|translate}</h4>

                    <p>{-"Your message was sent successfully!"|translate}</p>
                </div>
                <div class="alert alert-danger hidden messagefailedmessage">
                    <h4>{-"Sorry!"|translate}</h4>

                    <p>{-"There was an error! Please try again later"|translate}</p>
                </div>
                <form class="form-horizontal ajaxform" action="{-"index.php?m=messages&action=startConversation"|URL}"
                      role="form" id="sendMessageForm" method="POST">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{-"To"|translate}</label>

                        <div class="col-sm-10 dropdown" style="position:relative;">
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
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{-"Message"|translate}</label>

                        <div class="col-sm-10">
                            <textarea class="form-control message-content" rows="3" name="message"
                                      placeholder="{-"Your message!"|translate}"></textarea>
                            <input type="hidden" name="source" value="messages">
                        </div>
                    </div>
                    <input type="hidden" class="ajaxform-callback" value="messageSent">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default closemodalbutton"
                        data-dismiss="modal">{-"Close"|translate}</button>
                <button type="button" id="sendmessagebutton" disabled onclick="$('#sendMessageForm').submit();"
                        class="btn btn-primary"
                        data-loading-text="{-"Please wait.."|translate}">{-"Send"|translate}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="receiver-tpl">
    <span class="label label-primary"><span class="pull-left">{%=o.name%}</span>
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