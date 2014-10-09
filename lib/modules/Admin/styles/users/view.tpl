<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Userlist"|translate}
            <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#addusermodal"><i
                        class="fa fa-plus"></i>&nbsp;{-"Add User"|translate}</button>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Userlist"|translate}</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-10">
        <div class="panel panel-default">
            {-*<div class="panel-body">
            <form class="form-inline" role="form">
            <div class="form-group">
            <label for="usertable-searchfield">{-"Search by name"|translate}</label>
            <input type="email" class="form-control" id="usertable-searchfield" placeholder="{-"Search"|translate}">
                        </div>
                        </form>
                        </div>*}
            <table class="table-striped table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{-"Name"|translate}</th>
                    <th>{-"E-Mail"|translate}</th>
                    <th>{-"Registered"|translate}</th>
                    <th>{-"Last Action"|translate}</th>
                    <th>{-"Status"|translate}</th>
                    <th>{-"Actions"|translate}</th>
                </tr>
                </thead>
                <tbody id="usertable">
                {-foreach $users AS $i => $userItem}
                    <tr class="user-{-$userItem.groupid}">
                        <td>{-$i+1}</td>
                        <td><a href="{-"index.php?m=profile&action="|URL}{-$userItem.username}">{-$userItem.name}</a>
                        </td>
                        <td>{-$userItem.email}</td>
                        <td>{-$userItem.registered}</td>
                        <td>{-$userItem.lastAction}</td>
                        <td>
                            {-if $userItem.groupid eq 1}
                                <span class="label label-success">{-"Active"|translate}</span>
                            {-elseif $userItem.groupid eq 0}
                                <label class="label label-default">{-"Not verified"|translate}</label>
                            {-elseif $userItem.groupid eq 2}
                                <label class="label label-warning">{-"Administrator"|translate}</label>
                            {-elseif $userItem.groupid eq 3}
                                <label class="label label-info">{-"Cunity-Owner"|translate}</label>
                            {-elseif $userItem.groupid eq 4}
                                <label class="label label-danger">{-"Blocked"|translate}</label>
                            {-/if}
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button type="button"
                                        class="btn btn-primary dropdown-toggle" {-if $user.userid eq $userItem.userid || $userItem.groupid eq 3} disabled{-/if}
                                        data-toggle="dropdown">{-"Actions"|translate}&nbsp;<span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="javascript: changeUserStatus(null, {-$userItem.userid});"><i
                                                    class="fa fa-trash-o"></i>&nbsp;{-"Delete this user"|translate}</a>
                                    </li>
                                    <li><a href="javascript: changeUserStatus(4, {-$userItem.userid});"><i
                                                    class="fa fa-ban"></i>&nbsp;{-"Block this user"|translate}</a></li>
                                    {-if $user.groupid eq 0}
                                        <li><a href="javascript: changeUserStatus(1, {-$userItem.userid});"><i
                                                        class="fa fa-check"></i>&nbsp;{-"Activate this user"|translate}
                                            </a></li>
                                    {-/if}
                                    {-if $userItem.groupid < 2}
                                        <li class="divider"></li>
                                        {-if $userItem.groupid == 1}
                                            <li><a href="javascript: changeUserStatus('groupid',2);"><i class="fa fa-wrench"></i>&nbsp;{-"Make Administrator"|translate}
                                                </a></li>
                                        {-elseif $userItem.groupid eq 2}
                                            <li><a href="javascript: changeUserStatus('groupid',1);"><i
                                                            class="fa fa-ban"></i>&nbsp;{-"Remove Administrator"|translate}
                                                </a></li>
                                        {-/if}
                                    {-/if}
                                </ul>
                            </div>
                        </td>
                    </tr>
                {-/foreach}
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-filter"></i>&nbsp;{-"Filter the list"|translate}
            </div>
            <ul class="list-group">
                <li class="list-group-item">
                    <label class="checkbox">
                        <input type="checkbox" class="userlist-filter" value="1">&nbsp;{-"Active Users"|translate}
                    </label>
                </li>
                <li class="list-group-item">
                    <label class="checkbox">
                        <input type="checkbox" class="userlist-filter" value="0">&nbsp;{-"Not verified Users"|translate}
                    </label>
                </li>
                <li class="list-group-item">
                    <label class="checkbox">
                        <input type="checkbox" class="userlist-filter" value="2">&nbsp;{-"Administrators"|translate}
                    </label>
                </li>
            </ul>
            <div class="panel-footer">
                <button class="btn btn-block btn-primary" onclick="applyFilter();">{-"Apply filter"|translate}</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="addusermodal" tabindex="-1" role="dialog" aria-labelledby="addusermodal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script id="usertable-row" type="text/html">
    <tr>
        <td>{%=o.i%}</td>
        <td>{%=o.name%}</td>
        <td>{%=o.email%}</td>
        <td>{%#convertDate(o.registered)%}</td>
        <td>{%#convertDate(o.lastLogin)%}</td>
        <td>
            {% if (o.status == "active") { %}
            <span class="label label-success">{-"Active"|translate}</span>
            {% } else if(o.status == "notverified") { %}
            <label class="label label-danger">{-"Not verified"|translate}</label>
            {% } else if(o.status == "inactive") { %}
            <label class="label label-warning">{-"Inactive"|translate}</label>
            {% } else if(o.status == "admin") { %}
            <label class="label label-info">{-"Administrator"|translate}</label>
            {% } %}
        </td>
        <td>
            <div class="btn-group">
                <button type="button" class="btn btn-primary">{-"Actions"|translate}</button>
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                </ul>
            </div>
        </td>
    </tr>
</script>