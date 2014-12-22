<div class="page-buttonbar clearfix">
    <h1 class="page-header pull-left">{-"Memberlist"|translate}</h1>
    {-*<a href="{-"index.php?m=search&action=advanced"|URL}" class="btn btn-primary pull-right"><i class="fa fa-search"></i>&nbsp;{-"Search"|translate}</a>   *}
</div>
<div id="list">
    <div class="loader block-loader"></div>
    <div class="list">
        <div class="alert alert-block alert-danger hidden"><p>{-"There are no users to show"|translate}</p></div>
    </div>
</div>
<script type="text/html" id="memberlist-item">
    <article class="searchresult-item clearfix" id="searchresult-item-{%=o.userid%}">
        <img src="{%=checkImage(o.pimg,'user','cr_')%}" class="img-rounded thumbnail pull-left">

        <div class="searchresult-item-content pull-left">
            <h2><a href="{-"index.php?m=profile&action="|URL}{%=o.username%}">{%=o.name%}</a></h2>
        </div>
        <div class="pull-right nofriends-buttons btn-group btn-group-sm friend-buttons{% if (o.status !== null){ %} hidden{% } %}">
            <button class="btn btn-default" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}"
                    data-action="addasfriend" data-toggle="modal" data-target="#relationship-modal"><span
                        class="fa fa-plus"></span> {-"Add as friend"|translate}</button>
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                {% if (o.privacy.message == 3){ %}
                <li><a href="javascript:sendMessage('{%=o.name%}',{%=o.userid%})"><i
                                class="fa fa-envelope-o"></i> {-"Send message"|translate}</a></li>
                {% } %}
                <li class="divider"></li>
                <li><a href="#" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}"
                       data-action="blockperson" data-toggle="modal" data-target="#relationship-modal"><i
                                class="fa fa-ban"></i> {-"Block Person"|translate}</a></li>
                {-*<li><a href="#"><i class="fa fa-bullhorn"></i> {-"Report Person"|translate}</a></li>*}
            </ul>
        </div>
        <div class="pull-right blocked-buttons btn-group btn-group-sm friend-buttons{% if (o.status != 0 || o.sender != userid){ %} hidden{% } %}">
            <button class="btn btn-default tooltip-trigger" data-title="{-"You have blocked this user"|translate}"
                    data-container="body"><span class="fa fa-ban"></span> {-"Blocked"|translate}</button>
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="#" data-target="#relationship-modal" data-parent="#searchresult-item-{%=o.userid%}"
                       data-toggle="modal" data-action="unblock" data-userid="{%=o.userid%}"><i
                                class="fa fa-eraser"></i> {-"Unblock"|translate}</a></li>
            </ul>
        </div>
        <div class="pull-right request-buttons btn-group btn-group-sm friend-buttons{% if (o.status != 1 || o.sender == userid){ %} hidden{% } %}">
            <button class="btn btn-default" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}"
                    data-action="confirmfriend" data-toggle="modal" data-target="#relationship-modal"><span
                        class="fa fa-question"></span> {-"Answer Request"|translate}</button>
            <button class="btn btn-default dropdown-toggle" data-parent="#searchresult-item-{%=o.userid%}"
                    data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                {% if (o.privacy.message == 3){ %}
                <li><a href="javascript:sendMessage('{%=o.name%}',{%=o.userid%})"><i
                                class="fa fa-envelope-o"></i> {-"Send message"|translate}</a></li>
                {% } %}
                <li><a href="#" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}"
                       data-action="blockperson" data-toggle="modal" data-target="#relationship-modal"><i
                                class="fa fa-ban"></i> {-"Block Person"|translate}</a></li>
            </ul>
        </div>
        <div class="pull-right pending-buttons btn-group btn-group-sm friend-buttons{% if (o.status != 1 || o.receiver == userid){ %} hidden{% } %}">
            <button class="btn btn-default"><span class="fa fa-clock-o"></span> {-"Request pending"|translate}</button>
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                {% if (o.privacy.message == 3){ %}
                <li><a href="javascript:sendMessage('{%=o.name%}',{%=o.userid%})"><i
                                class="fa fa-envelope-o"></i> {-"Send message"|translate}</a></li>
                {% } %}
                <li><a href="#" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}"
                       data-action="blockperson" data-toggle="modal" data-target="#relationship-modal"><i
                                class="fa fa-ban"></i> {-"Block Person"|translate}</a></li>
                <li class="divider"></li>
                <li><a href="#" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}"
                       data-action="removerequest" data-toggle="modal" data-target="#relationship-modal"><i
                                class="fa fa-times"></i> {-"Remove request"|translate}</a></li>
            </ul>
        </div>
        <div class="pull-right friends-buttons btn-group btn-group-sm friend-buttons{% if (o.status != 2){ %} hidden{% } %}">
            <button class="btn btn-default"><span class="fa fa-check"></span> {-"Friends"|translate}</button>
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
            <ul class="dropdown-menu">
                {% if (o.privacy.message > 0){ %}
                <li><a href="javascript:sendMessage('{%=o.name%}',{%=o.userid%})"><i
                                class="fa fa-envelope-o"></i> {-"Send message"|translate}</a></li>
                {% } %}
                <li><a href="#" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}"
                       data-action="blockperson" data-toggle="modal" data-target="#relationship-modal"><i
                                class="fa fa-ban"></i> {-"Block Person"|translate}</a></li>
                <li class="divider"></li>
{-*                <li><a href="#" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}" data-action="relationship" data-toggle="modal" data-target="#relationship-modal"><i class="fa fa-pencil"></i> {-"Change Relationship status"|translate}</a></li>*}
<li><a href="#" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}" data-action="removefriend" data-toggle="modal" data-target="#relationship-modal"><i class="fa fa-times"></i> {-"Remove as friend"|translate}</a></li>
</ul>
</div>
{-* <div class="pull-right relationship-buttons btn-group btn-group-sm friend-buttons{% if (o.status < 3){ %} hidden{% } %}" data-userid="{%=o.userid%}">
<button class="btn btn-default" data-action="" data-parent="#searchresult-item-{%=o.userid%}" data-toggle="modal" data-target="#relationship-modal">
<span class="relationship-status status-3 {% if (o.status != 3){ %} hidden{% } %}"><span class="fa fa-heart"></span> {-"Relationship"|translate}</span>
<span class="relationship-status status-4 {% if (o.status != 4){ %} hidden{% } %}"><span class="fa fa-heart"></span> {-"Engaged"|translate}</span>
        <span class="relationship-status status-5 {% if (o.status != 5){ %} hidden{% } %}"><span class="fa fa-heart"></span> {-"Married"|translate}</span>            
        </button>
        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
        <ul class="dropdown-menu">
        <li><a href="javascript:sendMessage('{%=o.name%}',{%=o.userid%});"><i class="fa fa-envelope-o"></i> {-"Send message"|translate}</a></li>
        <li><a href="#" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}" data-action="blockperson" data-toggle="modal" data-target="#relationship-modal"><i class="fa fa-ban"></i> {-"Block Person"|translate}</a></li>                                        
        <li class="divider"></li>
        <li><a href="#" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}" data-action="relationship" data-toggle="modal" data-target="#relationship-modal"><i class="fa fa-pencil"></i> {-"Change Relationship status"|translate}</a></li>
        <li><a href="#" data-userid="{%=o.userid%}" data-parent="#searchresult-item-{%=o.userid%}" data-action="removefriend" data-toggle="modal" data-target="#relationship-modal"><i class="fa fa-times"></i> {-"Remove as friend"|translate}</a></li>
        </ul>        
        </div>*}
    </article>    
</script>