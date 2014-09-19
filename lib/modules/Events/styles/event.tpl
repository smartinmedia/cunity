<div class="event-banner{-if $event.filename eq NULL} event-banner-empty{-/if}"
     style="background-image:url('{-"core.siteurl"|setting}data/uploads/{-"core.filesdir"|setting}/cr_{-$event.filename}');">
    <div class="event-title-box clearfix">
        <div class="event-title-box-date pull-left" title="{-$event.date->format('d.m.Y')}">
            <span class="month">{-$event.date->format('M')}</span>
            <span class="day">{-$event.date->format('d')}</span>
        </div>
        <h1 class="pull-left">{-$event.title}</h1>
        <input type="hidden" id="eventid" value="{-$event.id}">
    </div>
    {-if $event.userid == $user.userid}
        <a href="{-"index.php?m=events&action={-$event.id}&x=edit"|URL}"
           class="btn btn-primary btn-lg upload-title-image-button"><i
                class="fa fa-picture-o" id="eventsUploadImageButton"></i>&nbsp;{-"Upload a Title-Image"|translate}</a>
        {-/if}
</div>
<ul class="nav nav-pills event-menu" id="event-menu">
    <li class="active"><a href="#Info" id="event-menu-info" data-toggle="pill"><i class="fa fa-info"></i>&nbsp;{-"Information"|translate}</a></li>
    <li><a href="#Wall" id="event-menu-wall" data-toggle="pill"><i class="fa fa-rss"></i>&nbsp;{-"Wall"|translate}</a></li>            
        {-if $user.userid eq $event.userid}
        <li class="pull-right hidden-sm hidden-xs"><a href="{-"index.php?m=events&action={-$event.id}&x=edit"|URL}" class="tab-no-follow"><i class="fa fa-wrench"></i>&nbsp;{-"Edit Event"|translate}</a></li>
        {-else}
        <li class="event-attending-buttons pull-right">                            
            <div class="btn-group{-if $event.status != 2} hidden{-/if} event-attending-button-2">
                <button class="btn btn-default"><i class="fa fa-check"></i>&nbsp;{-"Going"|translate}</button>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-down"></i></button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="javascript:changeEventStatus({-$event.id},1);">{-"Maybe"|translate}</a></li>       
                    <li class="divider"></li>
                    <li><a href="javascript:changeEventStatus({-$event.id},-1);">{-"Remove Event"|translate}</a></li>
                </ul>
            </div>            
            <div class="btn-group{-if $event.status != 1} hidden{-/if} event-attending-button-1">
                <button class="btn btn-default"><i class="fa fa-question"></i>&nbsp;{-"Maybe"|translate}</button>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-down"></i></button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="javascript:changeEventStatus({-$event.id},2);">{-"Going"|translate}</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:changeEventStatus({-$event.id},-1);">{-"Remove Event"|translate}</a></li>
                </ul>
            </div>            
            <div class="btn-group{-if $event.status !== 0} hidden{-/if} event-attending-button-0">
                <button class="btn btn-default"><i class="fa fa-envelope-o"></i>&nbsp;{-"Invited"|translate}</button>
                <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-caret-down"></i></button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="javascript:changeEventStatus({-$event.id},2);">{-"Going"|translate}</a></li>
                    <li><a href="javascript:changeEventStatus({-$event.id},1);">{-"Maybe"|translate}</a></li>                        
                    <li class="divider"></li>
                    <li><a href="javascript:changeEventStatus({-$event.id},-1);">{-"Remove invitation"|translate}</a></li>
                </ul>
            </div>
            <div class="btn-group dropdown {-if $event.status !== NULL} hidden{-/if} event-attending-button--1">
                <button class="btn btn-default" onclick="changeEventStatus({-$event.id}, 2);"><i class="fa fa-check"></i>&nbsp;{-"Going"|translate}</button>
                <button class="btn btn-default" onclick="changeEventStatus({-$event.id}, 1);"><i class="fa fa-question"></i>&nbsp;{-"Maybe"|translate}</button>
            </div>
        </li>
    {-/if}

    <button class="btn btn-primary pull-right{-if $event.userid !== $user.userid && (!$event.guest_invitation || $event.status !== 2)} hidden{-/if}"
            style="margin-right:10px" data-toggle="modal" data-target="#inviteModal"><i
            class="fa fa-plus"></i>&nbsp;{-"Invite Users"|translate}</button>
</ul>
<div class="tab-content">
    <div class="tab-pane fade in clearfix active" id="Info">
        <div class="row">
            <div class="col-sm-8">
                <ul class="list-group">
                    <li class="list-group-item">
                        <i class="fa fa-user fa-fw"></i>&nbsp;{-"created by"|translate}&nbsp;<a
                            href="{-"index.php?m=profile&action={-$event.username}"|URL}">{-$event.name}</a>
                    </li>
                    <li class="list-group-item">
                        <i class="fa fa-map-marker fa-fw"></i>&nbsp;{-$event.place}
                    </li>
                    <li class="list-group-item clearfix">
                        <i class="fa fa-clock-o fa-fw"></i>&nbsp;{-$event.date->format('d. M H:i')}
                    </li>
                </ul>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-info fa-fw"></i>&nbsp;{-"More Information"|translate}
                    </div>
                    <div class="panel-body">
                        {-$event.description}
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-users"></i>&nbsp;{-"Guests"|translate}&nbsp;<span
                            class="badge">{-$event.guestcount}</span>
                    </div>
                    <div class="list-group">
                        <div class="list-group-item clearfix">
                            <a href="#" class="pull-left guest-horizontal-link tooltip-trigger"
                               title="{-"Users, who will come"|translate}" data-placement="left"><i
                                    class="fa fa-fw fa-check-square-o"></i></a>

                            <div class="guest-horizontal-list pull-left" id="guestlist-attending">
                                {-if count($event.guests.attending) > 0}
                                    {-foreach $event.guests.attending AS $guest}
                                        <script>
                                            selectedReceiver[{-$guest.userid}] = 1;
                                        </script>
                                        <a href="{-"index.php?m=profile&action={-$guest.username}"|URL}"><img
                                                class="img-rounded tooltip-trigger" title="{-$guest.name}"
                                                src="{-$guest.filename|image:"user":"cr_"}"></a>
                                        {-/foreach}
                                    {-else}
                                    <span class="label label-danger">{-"No users attending"|translate}</span>
                                {-/if}
                            </div>
                        </div>
                        <div class="list-group-item clearfix">
                            <a href="#" class="pull-left guest-horizontal-link tooltip-trigger"
                               title="{-"Users, who will probably come"|translate}" data-placement="left"><i
                                    class="fa fa-fw fa-question"></i></a>

                            <div class="guest-horizontal-list pull-left" id="guestlist-maybe">
                                {-if count($event.guests.maybe) > 0}
                                    {-foreach $event.guests.maybe AS $guest}
                                        <script>
                                            selectedReceiver[{-$guest.userid}] = 1;
                                        </script>
                                        <a href="{-"index.php?m=profile&action={-$guest.username}"|URL}"><img
                                                class="img-rounded tooltip-trigger" title="{-$guest.name}"
                                                src="{-$guest.filename|image:"user":"cr_"}"></a>
                                        {-/foreach}
                                    {-else}
                                    <span class="label label-danger">{-"No users will maybe come"|translate}</span>
                                {-/if}
                            </div>
                        </div>
                        <div class="list-group-item clearfix">
                            <a href="#" class="pull-left guest-horizontal-link tooltip-trigger"
                               title="{-"Users, who are invited"|translate}" data-placement="left"><i
                                    class="fa fa-fw fa-envelope-o"></i></a>

                            <div class="guest-horizontal-list pull-left" id="guestlist-invited">
                                {-if count($event.guests.invited) > 0}
                                    {-foreach $event.guests.invited AS $guest}
                                        <script>
                                            selectedReceiver[{-$guest.userid}] = 1;
                                        </script>
                                        <a href="{-"index.php?m=profile&action={-$guest.username}"|URL}"><img
                                                class="img-rounded tooltip-trigger" title="{-$guest.name}"
                                                src="{-$guest.filename|image:"user":"cr_"}"></a>
                                        {-/foreach}
                                    {-else}
                                    <span class="label label-danger">{-"No users are invited"|translate}</span>
                                {-/if}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade in clearfix" id="Wall">
        <div class="newsfeed-container pull-left">
            <form class="newsfeed-newpost ajaxform clearfix" action="{-"index.php?m=newsfeed&action=send"|URL}"
                  method="post">
                <input type="hidden" id="newsfeed-owner-id" value="{-$event.id}">
                <input type="hidden" id="newsfeed-owner-type" value="event">
                <button class="btn btn-default pull-left newsfeed-post-option hidden-sm hidden-xs" type="button"><span
                        class="fa fa-ellipsis-v fa"></span></button>
                <div class="btn-group-vertical pull-left hidden newsfeed-post-option-buttons hidden-sm hidden-xs">
                    <button class="btn btn-default" type="button" id="eventsPostTextButton" onclick="postText();"><span
                            class="fa fa-pencil fa-fw"></span></button>
                    <button class="btn btn-default" type="button" id="eventsPostImageButton" onclick="postImage();"><span
                            class="fa fa-picture-o fa-fw"></span></button>
                </div>
                <input type="hidden" class="ajaxform-callback" value="sendPost">
                <input type="hidden" name="wall_owner_id" value="{-$event.id}">
                <input type="hidden" name="wall_owner_type" value="event">
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
                            data-title="{-$event.title}&nbsp;{-"decides who is allowed to see this post!"|translate}">
                        <span class="fa fa-lock"></span></button>
                    <input type="hidden" name="privacy" value="0" id="postPrivacy">
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
                        <input type="checkbox" class="newsfeed-filter" checked value="post"><i class="fa fa-fw fa-comment"></i>
                    </label>
                    <label class="btn btn-default tooltip-trigger active" data-title="{-"Show photos"|translate}">
                        <input type="checkbox" class="newsfeed-filter" checked value="image"><i
                            class="fa fa-fw fa-picture-o"></i>
                    </label>
                    <label class="btn btn-default tooltip-trigger active" data-title="{-"Show videos"|translate}">
                        <input type="checkbox" class="newsfeed-filter" checked value="video"><i class="fa fa-fw fa-film"></i>
                    </label>
                </div>
                <button class="btn btn-primary btn-sm btn-block" style="width:180px;margin-top:10px" id="eventsApplyFiltersButton"
                        onclick="applyFilter();">{-"Apply filter"|translate}</button>
            </section>
        </div>

    </div>
</div>
<div class="modal fade" id="inviteModal" tabindex="-1" role="dialog" aria-labelledby="inviteModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{-"Send Invitations"|translate}</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-success hidden invitationsent">
                    <h4>{-"Done!"|translate}</h4>

                    <p>{-"Invitations were sent successfully!"|translate}</p>
                </div>
                <div class="alert alert-danger hidden invitationfailed">
                    <h4>{-"Sorry!"|translate}</h4>

                    <p>{-"There was an error! Please try again later"|translate}</p>
                </div>
                <form class="form-horizontal ajaxform" action="{-"index.php?m=events&action=invite"|URL}" role="form"
                      id="inviteForm" method="POST">
                    <p class="help-block">{-"Type the names of the friends you want to invite to this event!"|translate}</p>

                    <div class="form-group">
                        <div class="col-sm-12 dropdown" style="position:relative;">
                            <div class="form-control receiver-content clearfix">
                                <div class="receiver-token"></div>
                                <input type="text" class="receiver-input" id="addGuest">
                                <i class="fa fa-spinner fa-spin pull-right fa-lg hidden" id="guest-suggestions-loader"
                                   style="margin:6px 3px"></i>
                                <i class="fa fa-warning pull-right fa-lg hidden tooltip-trigger"
                                   data-title="{-"No users found!"|translate}" data-container="#inviteModal"
                                   id="receiver-suggestions-alert" style="margin:6px 3px"></i>
                            </div>
                            <ul class="dropdown-menu receiver-suggestions" id="guest-suggestions" role="menu"
                                aria-labelledby="guest-suggestion"></ul>
                        </div>
                    </div>
                    <input type="hidden" class="ajaxform-callback" value="invitationSent">
                    <input type="hidden" name="eventid" value="{-$event.id}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{-"Close"|translate}</button>
                <button type="button" class="btn btn-primary"
                        onclick="$('#inviteForm').submit();">{-"Send"|translate}</button>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="event-invitation-result">
    <li role="presentation" class="message-searchresult-item">
        <a role="menuitem" tabindex="-1" href="javascript:addGuest({%=o.userid%},'{%=o.name%}');">
            <img src="{%=o.profileImage%}" class="img-rounded">
            <span>{%=o.name%}</span>
        </a>
    </li>
</script>
<script type="text/html" id="guest-item">
    <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}"><img class="img-rounded tooltip-trigger"
                                                                       title="{%=o.name%}"
                                                                       src="{%=checkImage(o.filename,'user','cr_')%}"></a>
</script>
