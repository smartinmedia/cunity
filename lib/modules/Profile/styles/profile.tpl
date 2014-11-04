<input type="hidden" id="profile-userid" value="{-$profile.userid}">
<style>@media screen and (max-width: 1023px) {
        .profile-banner {
            background-image: url('{-$profile.timg}') !important
        }
    }</style>
<div class="profile-banner{-if $profile.timg eq null} profile-banner-empty{-/if}"
     style="background-image: url('cr_{-$profile.timg}');">
    <a class="profile-banner-image"><img src="{-$profile.pimg|image:"user":"cr_"}"></a>

    <div class="profile-banner-namebox hidden-sm hidden-xs">
        <h1 class="profile-banner-name">{-$profile.name}</h1>
        {-if !$profile.name eq ""}<h2 class="profile-banner-username">( {-$profile.username} )</h2>{-/if}
    </div>
    {-if $user.userid != $profile.userid}
        <div class="nofriends-buttons btn-group friend-buttons{-if $profile.status !== null} hidden{-/if}">
            <button class="btn btn-default" data-userid="{-$profile.userid}" data-action="addasfriend"
                    data-toggle="modal"
                    data-target="#relationship-modal"><span class="fa fa-plus"></span> {-"Add as friend"|translate}
            </button>
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                {-if $profile.privacy.message eq 3}
                    <li><a href="javascript:sendMessage('{-$profile.name}',{-$profile.userid});"><i
                                    class="fa fa-envelope-o"></i> {-"Send message"|translate}</a></li>
                {-/if}
                <li class="divider"></li>
                <li><a href="#" data-userid="{-$profile.userid}" data-action="blockperson" data-toggle="modal"
                       data-target="#relationship-modal"><i class="fa fa-ban"></i> {-"Block Person"|translate}</a></li>
                {-*<li><a href="#"><i class="fa fa-bullhorn"></i> {-"Report Person"|translate}</a></li>*}
            </ul>
        </div>
        <div class="blocked-buttons btn-group friend-buttons{-if $profile.status != 0 || $profile.sender != $user.userid} hidden{-/if}">
            <button class="btn btn-default tooltip-trigger" data-title="{-"You have blocked this user"|translate}"
                    data-container="body"><span class="fa fa-ban"></span> {-"Blocked"|translate}</button>
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="#" data-target="#relationship-modal" data-toggle="modal" data-action="unblock"
                       data-userid="{-$profile.userid}"><i class="fa fa-eraser"></i> {-"Unblock"|translate}</a></li>
            </ul>
        </div>
        <div class="request-buttons btn-group friend-buttons{-if $profile.status != 1 || $profile.sender != $profile.userid} hidden{-/if}">
            <button class="btn btn-default" data-userid="{-$profile.userid}" data-action="confirmfriend"
                    data-toggle="modal"
                    data-target="#relationship-modal"><span class="fa fa-question"></span> {-"Answer Request"|translate}
            </button>
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                {-if $profile.privacy.message eq 3}
                    <li><a href="javascript:sendMessage('{-$profile.name}',{-$profile.userid});"><i
                                    class="fa fa-envelope-o"></i> {-"Send message"|translate}</a></li>
                {-/if}
                <li><a href="#" data-userid="{-$profile.userid}" data-action="blockperson" data-toggle="modal"
                       data-target="#relationship-modal"><i class="fa fa-ban"></i> {-"Block Person"|translate}</a></li>
            </ul>
        </div>
        <div class="pending-buttons btn-group friend-buttons{-if $profile.status != 1 || $profile.sender == $profile.userid} hidden{-/if}">
            <button class="btn btn-default"><span class="fa fa-clock-o"></span> {-"Request pending"|translate}</button>
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                {-if $profile.privacy.message eq 3}
                    <li><a href="javascript:sendMessage('{-$profile.name}',{-$profile.userid});"><i
                                    class="fa fa-envelope-o"></i> {-"Send message"|translate}</a></li>
                {-/if}
                <li><a href="#" data-userid="{-$profile.userid}" data-action="blockperson" data-toggle="modal"
                       data-target="#relationship-modal"><i class="fa fa-ban"></i> {-"Block Person"|translate}</a></li>
                <li class="divider"></li>
                <li><a href="#" data-userid="{-$profile.userid}" data-action="removerequest" data-toggle="modal"
                       data-target="#relationship-modal"><i class="fa fa-times"></i> {-"Remove request"|translate}</a>
                </li>
            </ul>
        </div>
        <div class="friends-buttons btn-group friend-buttons{-if $profile.status != 2} hidden{-/if}">
            <button class="btn btn-default"><span class="fa fa-check"></span> {-"Friends"|translate}</button>
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                {-if $profile.privacy.message > 0}
                    <li><a href="javascript:sendMessage('{-$profile.name}',{-$profile.userid});"><i
                                    class="fa fa-envelope-o"></i> {-"Send message"|translate}</a></li>
                {-/if}
                <li><a href="#" data-userid="{-$profile.userid}" data-action="blockperson" data-toggle="modal"
                       data-target="#relationship-modal"><i class="fa fa-ban"></i> {-"Block Person"|translate}</a></li>
                <li class="divider"></li>
                {-*<li><a href="#" data-userid="{-$profile.userid}" data-action="relationship" data-toggle="modal" data-target="#relationship-modal"><i class="fa fa-pencil"></i> {-"Change Relationship status"|translate}</a></li>*}
                <li><a href="#" data-userid="{-$profile.userid}" data-action="removefriend" data-toggle="modal"
                       data-target="#relationship-modal"><i class="fa fa-times"></i> {-"Remove as friend"|translate}</a>
                </li>
            </ul>
        </div>
        {-* <div class="relationship-buttons btn-group friend-buttons{-if $profile.status < 3} hidden{-/if}" data-userid="{-$profile.userid}">
        <button class="btn btn-default" data-action="" data-toggle="modal" data-target="#relationship-modal">
        <span class="relationship-status status-3 {-if $profile.status != 3} hidden{-/if}"><span class="fa fa-heart"></span> {-"Relationship"|translate}</span>
        <span class="relationship-status status-4 {-if $profile.status != 4} hidden{-/if}"><span class="fa fa-heart"></span> {-"Engaged"|translate}</span>
        <span class="relationship-status status-5 {-if $profile.status != 5} hidden{-/if}"><span class="fa fa-heart"></span> {-"Married"|translate}</span>            
        </button>
        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
        {-if $profile.privacy.message > 0}
        <li><a href="javascript:sendMessage('{-$profile.name}',{-$profile.userid});"><i class="fa fa-envelope-o"></i> {-"Send message"|translate}</a></li>
        {-/if}
        <li><a href="#" data-userid="{-$profile.userid}" data-action="blockperson" data-toggle="modal" data-target="#relationship-modal"><i class="fa fa-ban"></i> {-"Block Person"|translate}</a></li>                                        
        <li class="divider"></li>
        <li><a href="#" data-userid="{-$profile.userid}" data-action="relationship" data-toggle="modal" data-target="#relationship-modal"><i class="fa fa-pencil"></i> {-"Change Relationship status"|translate}</a></li>
        <li><a href="#" data-userid="{-$profile.userid}" data-action="removefriend" data-toggle="modal" data-target="#relationship-modal"><i class="fa fa-times"></i> {-"Remove as friend"|translate}</a></li>
        </ul>        
        </div>*}
    {-/if}
</div>
<ul class="nav nav-pills profile-menu" id="profile-menu">
    {-if ($profile.privacy.visit eq 1 && $profile.status > 2) || $profile.privacy.visit eq 3 || $profile.userid eq $user.userid}
        {-*<li><a href="#Pins" id="profile-menu-pins" data-toggle="pill" ><i class="fa fa-thumb-tack"></i>&nbsp;{-"Pins"|translate}</a></li>*}
    {-/if}
    <li class=""><a href="#Wall" id="profile-menu-wall" data-toggle="pill"><i
                    class="fa fa-rss"></i>&nbsp;{-"Wall"|translate}</a></li>
    <li class=""><a href="#Friends" id="profile-menu-friends" data-toggle="pill"
                    onclick="loadFriends({-$profile.userid});"><i class="fa fa-users"></i>&nbsp;{-"Friends"|translate}
            &nbsp;<span class="badge">{-if $profile.friendscount > 0}{-$profile.friendscount}{-/if}</span></a></li>
    <li class=""><a href="#Photos" id="profile-menu-photos" data-toggle="pill"
                    onclick="loadAlbums({-$profile.userid});"><i
                    class="fa fa-picture-o"></i>&nbsp;{-"Photo Albums"|translate}&nbsp;<span
                    class="badge">{-if $profile.albumscount > 0}{-$profile.albumscount}{-/if}</span></a></li>
    {-*<li class=""><a href="#Files" id="profile-menu-files" data-toggle="pill"><i class="fa fa-folder-open-o"></i>&nbsp;{-"Files"|translate}</a></li>
    <li class=""><a href="#Events" id="profile-menu-events" data-toggle="pill"><i class="fa fa-calendar"></i>&nbsp;{-"Events"|translate}</a></li>*}
    {-if $user.userid eq $profile.userid}
        <li class="pull-right hidden-sm hidden-xs"><a href="{-"index.php?m=profile&action=edit"|URL}"
                                                      id="profile-menu-edit" class="tab-no-follow"><i
                        class="fa fa-wrench"></i>&nbsp;{-"Edit profile"|translate}</a></li>
    {-/if}
</ul>
<div class="tab-content" style="border-top:1px solid #ccc">
    {-if ($profile.privacy.visit eq 1 && $profile.status > 2) || $profile.privacy.visit eq 3 || $profile.userid eq $user.userid}
        <div class="profile-pins tab-pane fade in clearfix" id="Pins">
            <div class="alert alert-block alert-danger hidden">
                <p>{-"This user does not have any profile pins!"|translate}</p></div>
            <div class="profile-pins-column pull-left"></div>
            <div class="profile-pins-column pull-right"></div>
            <div class="profile-pin-loader block-loader loader"></div>
            <script type="text/html" id="profilepin">
                <div class="panel panel-default profile-pin" id="pin-{%=o.id%}" data-pinid="{%=o.id%}">
                    <div class="panel-heading"><i class="fa fa-fw fa-{%=o.iconclass%}"></i>{%#o.title%}</div>
                    {% if (o.type == "2") { %}
                    <div class="panel-body">
                        {%#o.content%}
                    </div>
                    {% } else { %}
                    <table class="table table-striped panel-body" style="padding:0">
                        {%#infoPin(o.content)%}
                        {% } %}
                    </table>
                </div>
            </script>
        </div>
    {-/if}
    <div class="tab-pane fade in clearfix" id="Wall">
        <div class="newsfeed-container pull-left">
            <form class="newsfeed-newpost ajaxform clearfix" action="{-"index.php?m=newsfeed&action=send"|URL}"
                  method="post">
                <input type="hidden" id="newsfeed-owner-id" value="{-$profile.userid}">
                <input type="hidden" id="newsfeed-owner-type" value="profile">
                <button class="btn btn-default pull-left newsfeed-post-option hidden-sm hidden-xs" type="button"><span
                            class="fa fa-ellipsis-v fa"></span></button>
                <div class="btn-group-vertical pull-left hidden newsfeed-post-option-buttons hidden-sm hidden-xs">
                    <button class="btn btn-default" type="button" onclick="postText();" id="newsfeed-post-text-button">
                        <span class="fa fa-pencil fa-fw"></span></button>
                    <button class="btn btn-default" type="button" onclick="postImage();"
                            id="newsfeed-post-photo-button"><span class="fa fa-picture-o fa-fw"></span></button>
                </div>
                <input type="hidden" class="ajaxform-callback" value="sendPost">
                <input type="hidden" name="wall_owner_id" value="{-$profile.userid}">
                <input type="hidden" name="wall_owner_type" value="profile">
                <input type="hidden" name="type" value="post">
                <input type="hidden" name="youtubedata" value="">

                <div class="pull-right clearfix newsfeed-post-area">
                    <textarea class="form-control" id="postmsg" name="content"
                              placeholder="{-if $user.userid eq $profile.userid}{-"Anything new?"|translate}{-else}{-"Say Hello!"|translate}{-/if}"></textarea>

                    <div class="pull-left newsfeed-post-file-input">
                        <span class="loader-small hidden"></span>
                        <button class="btn btn-primary hidden" id="newsfeed-upload" type="button"><i
                                    class="fa fa-upload"></i>&nbsp;{-"Select Photo"|translate}</button>
                    </div>
                    <div class="clearfix newsfeed-post-video-box hidden"></div>
                </div>
                <div class="btn-group pull-right hidden newsfeed-post-buttons">
                    <button class="btn btn-primary tooltip-trigger" type="button"
                            data-title="{-$profile.name}&nbsp;{-"decides who is allowed to see this post!"|translate}">
                        <span class="fa fa-lock"></span></button>
                    <input type="hidden" name="privacy"
                           value="{-if $profile.privacy.post eq 3}0{-elseif $profile.privacy.post eq 1}1{-else}2{-/if}"
                           id="postPrivacy">
                    <button class="btn btn-primary newsfeed-post-button" type="submit" id="newsfeed-post-button"><span
                                class="fa fa-comment"></span>&nbsp;{-"Post"|translate}!
                    </button>
                </div>
            </form>
            {-include file="Newsfeed/styles/newsfeed-templates.tpl"}
            <div class="newsfeed-postbox">
                <div id="newsfeed-posts">
                    <div class="loader block-loader" id="newsfeed-loader"></div>
                    <div class="alert alert-danger alert-block hidden">{-"There are no posts to show"|translate}</div>
                </div>
                <div class="newsfeed-postbox-load-more hidden"><a href="javascript:load();"><i
                                class="fa fa-clock-o"></i>&nbsp;{-"Load more Posts"|translate}</a></div>
            </div>

        </div>
        <div class="sidebar right-sidebar profile-newsfeed-sidebar pull-right hidden-xs" style="display:block;">
            <section title="upcoming events" style="min-height: 500px;">
                <h3 class="sidebar-header"><i class="fa fa-filter fa-fw"></i>&nbsp;{-"Filter posts"|translate}</h3>

                <div class="btn-group btn-group-justified" data-toggle="buttons">
                    <label class="btn btn-default tooltip-trigger active" data-title="{-"Show posts"|translate}">
                        <input type="checkbox" class="newsfeed-filter" checked value="post"><i
                                class="fa fa-fw fa-comment"></i>
                    </label>
                    <label class="btn btn-default tooltip-trigger active" data-title="{-"Show photos"|translate}">
                        <input type="checkbox" class="newsfeed-filter" checked value="image"><i
                                class="fa fa-fw fa-picture-o"></i>
                    </label>
                    <label class="btn btn-default tooltip-trigger active" data-title="{-"Show videos"|translate}">
                        <input type="checkbox" class="newsfeed-filter" checked value="video"><i
                                class="fa fa-fw fa-film"></i>
                    </label>
                </div>
                <button class="btn btn-primary btn-sm btn-block" style="width:180px;margin-top:10px"
                        onclick="applyFilter();">{-"Apply filter"|translate}</button>
            </section>
        </div>
    </div>
    <div id="Friends" class="tab-pane fade in">
        <div id="friendslist">
            <div class="loader block-loader friend-loader"></div>
            <div class="list clearfix">
                <div class="alert alert-block alert-danger hidden"><p>{-"There are no users to show"|translate}</p>
                </div>
            </div>
        </div>
        <script type="text/html" id="friend-template">
            <a class="friendslist-item pull-left" href="{-"index.php?m=profile&action="|URL}{%=o.username%}">
                <img src="{%=checkImage(o.pimg,'user','cr_')%}" class="img-rounded">
                <span>{%=o.name%}</span>
            </a>
        </script>
    </div>
    <div id="Photos" class="tab-pane fade in">
        <div class="loader block-loader gallery-loader"></div>
        <div class="gallery-list clearfix"></div>
        <script type="text/html" id="albums-template">
            <div class="albumlist-item pull-left user-album-{%=o.userid%}">
                <a href="{%=convertUrl({'module':'gallery','action': o.id,'x':o.title.replace(' ','_')})%}"
                   class="albumlist-item-image"
                   style="background-image:url('{%=checkImage(o.filename,'gallery','thumb_')%}');"></a>
                <a href="{%=convertUrl({'module':'gallery','action': o.id,'x':o.title.replace(' ','_')})%}"
                   class="albumlist-item-name">
                    {% if (o.type == 'profile') { %}
                    {-"Profile Images"|translate}
                    {% } else if (o.type == 'newsfeed'){ %}
                    {-"Posted Images"|translate}
                    {% } else { %}
                    {%=o.title%}
                    {% } %}
                </a>

                <div class="albumlist-item-ownerbox clearfix">
                    <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}"><img
                                src="{%=checkImage(o.pimg,'user','cr_')%}"
                                class="albumlist-item-owner tooltip-trigger pull-left" data-title="{%=o.name%}"
                                data-placement="right"></a>
                    <i class="albumlist-item-time">{%#convertDate(o.time)%}</i>
                </div>
            </div>
        </script>
        <script type="text/html" id="noalbums">
            <div class="alert alert-block alert-danger"><p>{-"There are no albums to show!"|translate}</p></div>
        </script>
    </div>
    {-*<div id="Files" class="tab-pane fade in"></div>
    <div id="Events" class="tab-pane fade in"></div>*}
</div>
