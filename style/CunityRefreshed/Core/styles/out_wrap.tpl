<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="siteurl" content="{-"core.siteurl"|setting}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="generator" content="Cunity -  your private social network">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{-$meta.title|translate}&nbsp;|&nbsp;{-"core.sitename"|setting}</title>
        <link href="{-"core.siteurl"|setting}lib/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="{-"core.siteurl"|setting}lib/plugins/fontawesome/css/font-awesome.css" rel="stylesheet">
        <link type="text/css" href="{-"core.siteurl"|setting}style/CunityRefreshed/css/style.css" rel="stylesheet" media="screen and (min-width:1024px)">
        <link type="text/css" href="{-"core.siteurl"|setting}style/CunityRefreshed/css/mobile.css" rel="stylesheet" media="screen and (max-width: 1023px)">
        <link href="{-"core.siteurl"|setting}style/CunityRefreshed/img/favicon-default.gif" rel="icon" type="image/x-icon">
        {-if !$css_head eq ""}
            <link rel="stylesheet" type="text/css" href="{-"core.siteurl"|setting}lib/modules/Core/styles/css/cunity.min.css.php?files={-$css_head}">
        {-/if}
        <script src="{-"core.siteurl"|setting}lib/plugins/js/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript">var modrewrite = {-$modrewrite}, siteurl = "{-"core.siteurl"|setting}", userid = {-$user.userid}, design = "CunityRefreshed", login = {-if empty($user)}false{-else}true{-/if};</script>
        {-*<script src="{-"core.siteurl"|setting}style/CunityRefreshed/javascript/jquery.touchSwipe.min.js"></script>*}
        <script src="{-"core.siteurl"|setting}lib/plugins/js/jquery.mousewheel.min.js" type="text/javascript"></script>
        <script src="{-"core.siteurl"|setting}lib/modules/Core/styles/javascript/cunity-core.js" type="text/javascript"></script>                
        <script src="{-"core.siteurl"|setting}lib/plugins/js/bootbox.min.js" type="text/javascript"></script>
        <script src="{-"core.siteurl"|setting}lib/plugins/js/moment.min.js" type="text/javascript"></script>
        <script src="{-"core.siteurl"|setting}lib/plugins/ionsound/js/ion.sound.min.js" type="text/javascript"></script>
        <script src="{-"core.siteurl"|setting}lib/plugins/js/tmpl.min.js" type="text/javascript"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js" type="text/javascript"></script>            
        <script src="{-"core.siteurl"|setting}lib/plugins/js/html5shiv.min.js"></script>
        <script src="{-"core.siteurl"|setting}lib/plugins/js/respond.min.js"></script>
        {-$script_head}
        <base href="{-"core.siteurl"|setting}data/uploads/{-"core.filesdir"|setting}/">        
    </head>
    <body>        
        <div class="head">
            <div class="content">
                <div class="headline"><a href="{-"core.siteurl"|setting}">{-"core.headline"|setting|html_entity_decode}</a></div>
            </div>
        </div>
        <nav id="mobile-slide-nav">
            {-if !empty($user)}
                <ul class="head-menu list-unstyled list-inline">
                    <li><a href="#"><i class="fa fa-search"></i></a></li>
                    <li class="notification-link-general"><a href="javascript:getNotification('general');"><i class="fa fa-bell"></i></a></li>                    
                </ul>   
                <section class="mini-profile clearfix" title="Your short profile">
                    <img src="{-$user.pimg|image:"user":"cr_"}" alt="{-"Your Profile"|translate}" class="pull-left img-rounded thumbnail">
                    <a href="{-"index.php?m=profile"|URL}" class="pull-left">{-$user.name}</a>
                </section>
            {-/if}
            <ul class="nav nav-list mobile-menu"><li><a href="{-"index.php?m=start"|URL}"><i class="fa fa-home fa-fw"></i>&nbsp;{-"Startpage"|translate}</a></li></ul>
            <footer>
                <small class="copyright">Cunity &reg; powered by <a href="http://smartinmedia.com/" target="_blank">Smart In Media</a></small>
                <ul class="footer-menu list-unstyled">
                    <li><a href="{-"index.php?m=pages&action=legalnotice"|URL}">{-"Legal-Notice"|translate}</a></li>
                    <li><a href="{-"index.php?m=pages&action=privacy"|URL}">{-"Privacy"|translate}</a></li>
                    <li><a href="{-"index.php?m=pages&action=terms"|URL}">{-"Terms and Conditions"|translate}</a></li>
                    <li><a href="{-"index.php?m=contact"|URL}">{-"Contact"|translate}</a></li>
                </ul>
            </footer>
        </nav>
        <div class="mobile-page-wrapper">
            <div class="mobile-head clearfix dropdown">
                {-*<button class="btn btn-primary pull-left"><i class="fa fa-bars"></i></button>*}
                <div style="overflow:hidden;" class="pull-left">
                    <i id="menu-trigger" class="fa fa-bars"><img src="{-"core.siteurl"|setting}style/CunityRefreshed/img/cunity-logo-26.gif"></i>                
                </div>
                <h1 class="pull-left">{-$meta.title|translate}</h1>
                {-if !empty($user)}
                    <i data-toggle="dropdown" data-target="#mobile-drop" class="fa fa-ellipsis-v pull-right"></i>        
                    <ul class="dropdown-menu" role="menu" aria-labelledby="option-drop" id="mobile-option"></ul>
                {-/if}
            </div>
            <div class="head-shadow"></div>        
            <div class="main clearfix">
                <div class="sidebar pull-left left-sidebar">
                    {-if !empty($user)}
                        <section class="mini-profile media" title="Your short profile">
                            <div class="pull-left">
                                <img src="{-$user.pimg|image:"user":"cr_"}" alt="{-"Your Profile"|translate}" class="img-rounded thumbnail media-object">
                            </div>
                            <div class="media-body">
                                <a href="{-"index.php?m=profile"|URL}" class="media-heading">{-$user.name}</a>
                                <span class="mini-profile-notification-bar">
                                    <i class="fa fa-bell"></i>&nbsp;<span class="label label-primary notification-count">0</span>
                                </span>
                            </div>                            
                        </section>
                        <section title="{-"Search"|translate}" class="sidebar-search">
                            <form action="{-"index.php?m=search"|URL}" method="get" onsubmit="return ($('#searchinputfield').val().length > 0);">
                                <div class="input-group" style="width:199px">
                                    <input type="text" class="form-control" id="searchinputfield" name="q" placeholder="{-"Search"|translate}" autocomplete="off">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit"><i class="fa fa-search fa-fw"></i></button>
                                    </span>
                                </div>
                            </form>   
                        </section>
                        <section class="main-menu">
                            <ul class="nav nav-list">
                                {-foreach $menu->getMainMenu()  AS $menuItem}
                                    {-if $menuItem.type=="module"}
                                        <li class="{-if $meta.module eq $menuItem.content}active {-/if}"><a href="{-"index.php?m={-$menuItem.content}"|URL}" id="main-menu-item-{-$menuItem.content}"><i class="fa fa-{-$menuItem.iconClass} fa-fw"></i>{-$menuItem.title|translate}<span class="badge pull-right"></span></a></li>
                                            {-elseif $menuItem.type == "page"}
                                        <li class="{-if $meta.module eq $menuItem.content}active {-/if}><a href="{-"index.php?m=pages&action={-$menuItem.content}"|URL}" id="main-menu-item-{-$menuItem.content}"><i class="fa fa-{-$menuItem.iconClass} fa-fw"></i>{-$menuItem.title|translate}<span class="badge pull-right"></span></a></li>
                                            {-else}
                                        <li class=""><a href="{-$menuItem.content}" target="_blank" id="main-menu-item-{-$menuItem.content}"><i class="fa fa-{-$menuItem.iconClass} fa-fw"></i>{-$menuItem.title}<span class="badge pull-right"></span></a></li>
                                            {-/if}
                                        {-/foreach}
                                        {-if !empty($user) && $user->isAdmin()}                                    
                                    <li class="main-menu-item-administration"><a href="{-"index.php?m=admin"|URL}" id="main-menu-item-administration"><i class="fa fa-cogs fa-fw"></i>&nbsp;{-"Administration"|translate}</a></li>
                                    {-/if}
                                <li  class="main-menu-item-logout"><a href="{-"index.php?m=register&action=logout"|URL}" id="main-menu-item-logout"><i class="fa fa-sign-out fa-fw"></i>&nbsp;{-"Logout"|translate}</a></li>
                            </ul>
                        </section>
                    {-else}
                        <section title="Login" class="login-sidebar">
                            <h3><i class="fa fa-sign-in fa-fw"></i>&nbsp;{-"Login"|translate}</h3>
                            <form class="form-horizontal sidebar-login-form" role="form"  style="margin-bottom:10px" action="{-"index.php?m=register&action=login"|URL}" method="post">
                                <div class="form-group">
                                    <label for="inputEmail1" class="sr-only">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="inputPassword1" class="sr-only">{-"Password"|translate}</label>
                                    <input type="password" class="form-control" name="password" placeholder="{-"Password"|translate}">
                                    <a class="help-block" style="margin-bottom:0;" href="{-"index.php?m=register&action=forgetPw"|URL}">{-"I forgot my password"|translate}</a>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="save-login"> {-"Remember me"|translate}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary pull-right">{-"Log in"|translate}</button>
                                </div>
                            </form>
                        </section>
                    {-/if}
                    <footer>
                        <small class="copyright">Cunity &reg;<br />powered by <a href="http://smartinmedia.com/" target="_blank">Smart In Media</a></small>
                        <ul class="footer-menu list-unstyled">
                            {-foreach $menu->getFooterMenu()  AS $menuItem}
                                {-if $menuItem.type=="module"}
                                    <li class="footer-menu-item-{-$menuItem.id}" id="footer-menu-item-{-$menuItem.id}"><a href="{-"index.php?m={-$menuItem.content}"|URL}">{-$menuItem.title|translate}</a></li>
                                    {-elseif $menuItem.type == "page"}
                                    <li class="footer-menu-item-{-$menuItem.id}" id="footer-menu-item-{-$menuItem.id}"><a href="{-"index.php?m=pages&action={-$menuItem.content}"|URL}">{-$menuItem.title|translate}</a></li>
                                    {-else}
                                    <li class="footer-menu-item-{-$menuItem.id}" id="footer-menu-item-{-$menuItem.id}"><a href="{-$menuItem.content}">{-$menuItem.title|translate}</a></li>
                                    {-/if}
                                {-/foreach}
                        </ul>
                    </footer>
                </div>
                <div class="content pull-left">                    
                    {-include file="$tpl_name"}
                    {-if empty($user)}
                        <section title="Login" class="mobile-login">                            
                            <form class="form-horizontal sidebar-login-form" role="form"  style="margin-bottom:10px" action="{-"index.php?m=register&action=login"|URL}" method="post">
                                <div class="form-group">
                                    <label for="inputEmail1" class="sr-only">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Email">
                                </div>
                                <div class="form-group" style="margin-bottom:0;">
                                    <label for="inputPassword1" class="sr-only">{-"Password"|translate}</label>
                                    <input type="password" class="form-control" name="password" placeholder="{-"Password"|translate}">
                                    <a class="help-block" style="margin-bottom:0;" href="{-"index.php?m=register&action=forgetPw"|URL}">{-"I forgot my password"|translate}</a>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="save-login"> {-"Remember me"|translate}
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block">{-"Log in"|translate}</button>
                                </div>
                            </form>
                        </section>
                    {-/if}
                </div>
                <div class="sidebar pull-right right-sidebar">
                    {-if !empty($announcements)}
                        <section title="{-"Announcements"|translate}" style="min-height: 500px;">
                            <h3><i class="fa fa-bullhorn fa-fw"></i>&nbsp;{-"Announcements"|translate}</h3>
                            {-foreach $announcements AS $ann}
                                <div class="panel panel-{-$ann.type}">
                                    <div class="panel-heading">{-$ann.title}</div>
                                    <div class="panel-body">{-$ann.content}</div>
                                </div>                            
                            {-/foreach}
                        </section>
                    {-/if}
                </div>
            </div>                    
        </div>
        {-if !empty($user) && {-"messages.chat"|setting} eq 1}{-include file="Messages/styles/chat.tpl"}{-/if}
        <div class="modal fade" id="infoModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Info Box</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">{-"Close"|translate}</button>
                    </div>
                </div>
            </div>
        </div>                         
        <script id="like" type="text/html"><a href="{-"index.php?m=profile&action="|URL}{%=o.username%}"><img class="img-rounded tooltip-trigger" title="{%=o.name%}" src="{%=checkImage(o.filename,'user','cr_')%}" data-container="body"></a></script>
        <script id="like-list" type="text/html"><a href="{-"index.php?m=profile&action="|URL}{%=o.username%}" class="tooltip-trigger likelist-item" data-title="{%=o.name%}"><img alt="{%=o.name%}" src="{%=checkImage(o.filename,'user','cr_')%}" class="thumbnail"></a></script>
                {-include file="Friends/styles/relation-modal.tpl"}
                {-include file="Search/styles/livesearch.tpl"}
                {-include file="Notifications/styles/notification-popover.tpl"}                
                {-include file="Messages/styles/message-modal.tpl"}

    </body>
</html>