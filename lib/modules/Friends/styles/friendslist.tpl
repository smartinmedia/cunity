<div class="page-buttonbar clearfix">
    <h1 class="page-header pull-left">{-"My Friends"|translate}</h1>
    <button class="btn btn-primary tooltip-trigger pull-right notification-link-friends"
            title="{-"Friend Requests"|translate}" onclick="getNotification('friends');"><i class="fa fa-question"></i>
    </button>
    {-*    <a href="{-"index.php?m=search&action=advanced"|URL}" class="btn btn-primary pull-right"><i class="fa fa-search"></i>&nbsp;{-"Search"|translate}</a>       *}
</div>
<div id="friendslist">
    <div class="loader block-loader"></div>
    <div class="list clearfix">
        <div class="alert alert-block alert-danger hidden"><p>{-"There are no users to show"|translate}</p></div>
    </div>
</div>
<script type="text/html" id="friend-template">
    <a class="friendslist-item pull-left" href="{-"index.php?m=profile&action="|URL}{%=o.username%}">
        <img src="{%=checkImage(o.pimg,'user','cr_')%}" class="img-rounded">
        <span>{%=o.name%}</span>
    </a>
</script>