<div class="page-buttonbar clearfix">
    <h1 class="page-header pull-left">{-"Newsfeed"|translate}</h1>

    <div class="btn-group pull-right">
        <button type="button" class="btn btn-default dropdown-toggle tooltip-trigger desktop-only" id="filter-dropdown"
                data-toggle="dropdown" data-title="{-"Filter the posts you want to see"|translate}"><i
                    class="fa fa-filter"></i></button>
        <ul class="dropdown-menu dropdown-checkbox-menu" role="menu" style="left:0;right:auto">
            <li class="dropdown-header">{-"Filter the posts you want to see"|translate}</li>
            <li>
                <label class="checkbox">
                    <input type="checkbox" class="newsfeed-filter" value="post">{-"Status posts"|translate}
                </label>
            </li>
            <li>
                <label class="checkbox">
                    <input type="checkbox" class="newsfeed-filter" value="image">{-"Photos"|translate}
                </label>
            </li>
            <li>
                <label class="checkbox">
                    <input type="checkbox" class="newsfeed-filter" value="video">{-"Videos"|translate}
                </label>
            </li>
            <li>
                <button class="btn btn-primary btn-xs btn-block desktop-only"
                        onclick="applyFilter();">{-"Apply filter"|translate}</button>
            </li>
        </ul>
    </div>
    <button class="btn btn-default pull-right tooltip-trigger" data-title="{-"Check for new posts manually"|translate}"
            onclick="load(true);" id="newsfeed-refresh"><i class="fa fa-refresh"></i></button>
</div>
<form class="newsfeed-newpost ajaxform clearfix" action="{-"index.php?m=newsfeed&action=send"|URL}" method="post">
    <input type="hidden" id="newsfeed-owner-id" value="0">
    <input type="hidden" id="newsfeed-owner-type" value="newsfeed">
    <button class="btn btn-default pull-left newsfeed-post-option hidden-sm hidden-xs" type="button"><span
                class="fa fa-ellipsis-v fa"></span></button>
    <div class="btn-group-vertical pull-left hidden newsfeed-post-option-buttons hidden-sm hidden-xs">
        <button class="btn btn-default" type="button" onclick="postText();"><span class="fa fa-pencil fa-fw"></span>
        </button>
        <button class="btn btn-default" type="button" onclick="postImage();"><span class="fa fa-picture-o fa-fw"></span>
        </button>
    </div>
    <input type="hidden" class="ajaxform-callback" value="sendPost">
    <input type="hidden" name="wall_owner_id" value="{-$user.userid}">
    <input type="hidden" name="wall_owner_type" value="profile">
    <input type="hidden" name="type" value="post">
    <input type="hidden" name="youtubedata" value="">

    <div class="pull-right clearfix newsfeed-post-area">
        <textarea class="form-control" id="postmsg" name="content" placeholder="{-"What's up?"|translate}"></textarea>

        <div class="pull-left newsfeed-post-file-input">
            <span class="loader-small hidden"></span>
            <button class="btn btn-primary hidden" id="newsfeed-upload" type="button"><i
                        class="fa fa-search"></i>&nbsp;{-"Select Photo"|translate}</button>
            <span class="text-muted" id="selected-file"></span>
        </div>
        <div class="clearfix newsfeed-post-video-box hidden"></div>
    </div>
    <div class="btn-group pull-right hidden newsfeed-post-buttons">
        <button class="btn btn-primary tooltip-trigger"
                data-title="{-"Select who will be allowed to see this post"|translate}" data-toggle="dropdown"><span
                    class="fa fa-lock"></span>&nbsp;<i class="caret"></i></button>
        <input type="hidden" name="privacy" value="0" id="postPrivacy">
        <button class="btn btn-primary newsfeed-post-button" type="submit" id="newsfeed-post-button"><span
                    class="fa fa-comment"></span>&nbsp;{-"Post"|translate}!
        </button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="privacy-dropdown">
            <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:selectPostPrivacy(0);"><i
                            class="fa fa-globe fa-fw"></i>&nbsp;{-"Public"|translate}</a></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:selectPostPrivacy(1);"><i
                            class="fa fa-users fa-fw"></i>&nbsp;{-"Friends"|translate}</a></li>
            <li role="presentation" class="divider"></li>
            <li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:selectPostPrivacy(2);"><i
                            class="fa fa-user fa-fw"></i>&nbsp;{-"Only Me"|translate}</a></li>
        </ul>
    </div>
</form>
<div class="newsfeed-postbox">
    <div id="newsfeed-posts">
        <div class="loader block-loader" id="newsfeed-loader"></div>
        <div class="alert alert-danger alert-block hidden">{-"There are no posts to show"|translate}</div>
    </div>
    <button class="btn btn-block btn-primary newsfeed-postbox-load-more hidden" onclick="load();"><i
                class="fa fa-clock-o"></i>&nbsp;{-"Load more Posts"|translate}</button>
</div>
{-include file="Newsfeed/styles/newsfeed-templates.tpl"}