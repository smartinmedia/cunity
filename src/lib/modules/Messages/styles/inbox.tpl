<div class="page-buttonbar clearfix">
    <h1 class="pull-left page-header">{-"Conversations"|translate}</h1>
    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#messages_modal" id="startConversation"><i
                class="fa fa-pencil"></i>&nbsp;{-"Start Conversation"|translate}</button>
</div>
<div class="alert alert-block alert-danger inbox-empty hidden"><p>{-"There are no conversations"|translate}</p></div>
<div class="inbox-loader block-loader loader"></div>
<div class="inbox-list"></div>
<script id="inbox-conversation" type="text/html">
    <article class="inbox-item clearfix">
        <img src="{%=o.image%}" class="img-rounded thumbnail pull-left">

        <div class="inbox-item-content pull-left">
            <h2>{% if (o.status == 1) { %}<span class="label label-primary">{-"new"|translate}</span>&nbsp;{% } %}<a
                        href="{-"index.php?m=messages&action="|URL}{%=o.conversation%}">{%=o.name%}</a></h2>
            <span class="inbox-item-preview">{%=o.sendername%}:&nbsp;{%#o.message%}</span>
        </div>
        <span class="inbox-item-time">{%#convertDate(o.time)%}</span>
    </article>
</script>