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
                        <td><a href="{-"index.php?m=profile&action="|URL}{-$userItem.username}" target="_blank">{-$userItem.name}</a>
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
                                    {-if $user.groupid <= 3 and $userItem.groupid != 4}
                                    <li><a href="javascript: changeUserStatus(4, {-$userItem.userid});"><i
                                                    class="fa fa-ban"></i>&nbsp;{-"Block this user"|translate}</a></li>
                                    {-/if}
                                    {-if $user.groupid <= 3 and $userItem.groupid == 4}
                                        <li><a href="javascript: changeUserStatus(1, {-$userItem.userid});"><i
                                                        class="fa fa-check"></i>&nbsp;{-"Activate this user"|translate}
                                            </a></li>
                                    {-/if}
                                    {-if $userItem.groupid <= 2}
                                        <li class="divider"></li>
                                        {-if $userItem.groupid eq 1}
                                            <li><a href="javascript: changeUserStatus(2, {-$userItem.userid});"><i
                                                            class="fa fa-wrench"></i>&nbsp;{-"Make Administrator"|translate}
                                                </a></li>
                                        {-elseif $userItem.groupid eq 2}
                                            <li><a href="javascript: changeUserStatus(1, {-$userItem.userid});"><i
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
                <li class="list-group-item">
                    <label class="checkbox">
                        <input type="checkbox" class="userlist-filter" value="3">&nbsp;{-"Cunity Owner"|translate}
                    </label>
                </li>
                <li class="list-group-item">
                    <label class="checkbox">
                        <input type="checkbox" class="userlist-filter" value="4">&nbsp;{-"Blocked Users"|translate}
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
    <form class="login-form form-horizontal ajaxform" action="{-"index.php?m=admin&action=save"|URL}"
          style="margin:10px;" name="users">
        <input type="hidden" name="action" value="save">
        <input type="hidden" class="ajaxform-callback" value="addUser">
        <input type="hidden" name="form" value="users">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">{-"Add User"|translate}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="cunity-name"
                               class="col-sm-4 control-label">{-"Firstname"|translate}*</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="" id="firstname"
                                   name="firstname" required data-bv-stringlength data-bv-stringlength-min="3"
                                   data-bv-stringlength-message="{-"Firstname is too short (min. 3 chars)"|translate}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cunity-name"
                               class="col-sm-4 control-label">{-"Lastname"|translate}*</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="" id="lastname"
                                   name="lastname" required data-bv-stringlength data-bv-stringlength-min="3"
                                   data-bv-stringlength-message="{-"Lastname is too short (min. 3 chars)"|translate}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cunity-name"
                               class="col-sm-4 control-label">{-"Username"|translate}*</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="" id="username"
                                   name="username" required data-bv-stringlength data-bv-stringlength-min="3"
                                   data-bv-stringlength-message="{-"Username is too short (min. 3 chars)"|translate}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password"
                               class="col-sm-4 control-label">{-"Password"|translate}*</label>

                        <div class="col-sm-8">
                            <input type="password" class="form-control" value="" id="password"
                                   name="password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email"
                               class="col-sm-4 control-label">{-"Password repeated"|translate}*</label>

                        <div class="col-sm-8">
                            <input type="password" class="form-control" value="" id="password-repeated"
                                   name="password-repeated" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email"
                               class="col-sm-4 control-label">{-"E-Mail"|translate}*</label>

                        <div class="col-sm-8">
                            <input type="email" class="form-control" value="" id="cunity-name"
                                   name="email" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="group"
                               class="col-sm-4 control-label">{-"Group"|translate}*</label>

                        <div class="col-sm-8">
                            <select class="form-control" name="groupid">
                                <option value="1" selected>{-"User"|translate}</option>
                                <option value="2">{-"Admin"|translate}</option>
                                <option value="3">{-"Cunity-Owner"|translate}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bb-handler="confirm" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </form>
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