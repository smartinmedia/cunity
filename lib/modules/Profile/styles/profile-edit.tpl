<input type="hidden" id="profile-userid" value="{-$user.userid}">
<div class="profile-banner {-if $profile.timg eq null}profile-banner-empty{-/if}"
     style="background-image: url('{-"core.siteurl"|setting}data/uploads/{-"core.filesdir"|setting}/cr_{-$profile.timg}');">
    <a class="profile-banner-image"><img src="{-$profile.pimg|image:"user":"cr_"}"></a>

    <div class="profile-banner-namebox">
        <h1 class="profile-banner-name">{-$profile.firstname}&nbsp;{-$profile.lastname}</h1>
        {-if !$profile.firstname eq ""}<h2 class="profile-banner-username">( {-$profile.username} )</h2>{-/if}
    </div>
</div>
<ul class="nav nav-pills profile-menu" id="profile-menu">
    <li><a href="{-"index.php?m=profile"|URL}" id="profile-menu-edit-back" class="tab-no-follow"><i
                    class="fa fa-chevron-left"></i>&nbsp;{-"Back"|translate}</a></li>
    <li class="active"><a href="#editAccount" id="profile-menu-edit-account" data-toggle="pill"><i
                    class="fa fa-cogs"></i>&nbsp;{-"My Account"|translate}</a></li>
    <li class=""><a href="#editPrivacy" id="profile-menu-edit-privacy" data-toggle="pill"><i class="fa fa-lock"></i>&nbsp;{-"Privacy"|translate}
        </a>
    </li>
    <li class=""><a href="#editProfileImages" id="profile-menu-edit-images" data-toggle="pill" data-type="profile"><i
                    class="fa fa-picture-o"></i>&nbsp;{-"Images"|translate}
        </a></li>
    {-*<li class=""><a href="#editProfilePins" id="profile-menu-edit-pins" data-toggle="pill"><i*}
    {-*class="fa fa-thumb-tack"></i>&nbsp;{-"Profile Pins"|translate}</a></li>*}
    <li class=""><a href="#editNotifications" id="profile-menu-edit-notifications" data-toggle="pill"><i
                    class="fa fa-envelope-o"></i>&nbsp;{-"Notifications"|translate}</a></li>
</ul>
<div class="tab-content" style="border-top:1px solid #ccc;padding-top:10px">
    <div class="tab-pane active clearfix" id="editAccount">
        <div class="col-md-3">
            <div class="list-group">
                <a href="#editAccountGeneral" class="list-group-item active"
                   data-toggle="tab">{-"General"|translate}</a>
                <a href="#editAccountChangePwd" class="list-group-item"
                   data-toggle="tab">{-"Change password"|translate}</a>
                <a href="#editAccountDelAcc" class="list-group-item" data-toggle="tab">{-"Delete Account"|translate}</a>
            </div>
        </div>
        <div class="col-md-9 tab-content section-content">
            <div class="tab-pane active panel panel-default" id="editAccountGeneral">
                <div class="panel-heading">
                    <h3 class="panel-title">{-"General"|translate}</h3>
                </div>
                <div class="panel-body">
                    <div class="alert alert-block hidden" id="generalchangedalert"></div>
                    <form action="{-"index.php?m=profile&action=edit"|URL}" method="post"
                          class="form-horizontal ajaxform">
                        <input type="hidden" name="edit" value="general">

                        <div class="form-group">
                            <label class="control-label col-md-3">E-Mail</label>

                            <div class="col-md-9">
                                <input type="email" name="email" readonly="readonly" class="form-control"
                                       value="{-$profile.email}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">{-"Username"|translate}</label>

                            <div class="col-md-9">
                                <input type="text" name="username" class="form-control" value="{-$profile.username}">
                            </div>
                        </div>
                        {-foreach $profileFields AS $i => $field}
                            <div class="form-group">
                                <label class="control-label col-md-3">{-$field.label|translate}{-if $field.required == 1}*{-/if}</label>

                                <div class="col-md-9">
                                    {-if $field.type_id == 1}
                                        <select class="form-control" name="field[{-$field.id}]"
                                                {-if $field.required}required{-/if}>
                                            <option value="">{-"Make a choice"|translate}</option>
                                            {-foreach $field.values as $j => $value}
                                                <option value="{-$value.id}"
                                                        {-if $field.value==$value.id}selected{-/if}>{-$value.value|translate}</option>
                                            {-/foreach}
                                        </select>
                                    {-elseif $field.type_id == 3}
                                        <textarea class="form-control" id="postmsg" name="field[{-$field.id}]"
                                                  placeholder="{-$field.label}">{-$field.value}</textarea>
                                    {-elseif $field.type_id == 4}
                                    <input type="text" name="field[{-$field.id}]" class="form-control"
                                           value="{-$field.value}" placeholder="{-$field.label}">
                                    {-/if}
                                </div>
                            </div>
                        {-/foreach}
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-9 clearfix">
                                <input class="ajaxform-callback" value="generalchanged" type="hidden">
                                <button type="submit" class="btn btn-primary pull-right"><i
                                            class="fa fa-save"></i> {-"Save"|translate}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane panel panel-default" id="editAccountChangePwd">
                <div class="panel-heading">
                    <h3 class="panel-title">{-"Change Password"|translate}</h3>
                </div>
                <div class="panel-body">
                    <div class="alert alert-block hidden" id="passwordchangedalert"></div>
                    <form action="{-"index.php?m=profile&action=edit"|URL}" method="post"
                          class="form-horizontal ajaxform">
                        <input type="hidden" name="edit" value="changepassword">

                        <div class="form-group">
                            <label class="control-label col-md-5">{-"Current Password"|translate}*</label>

                            <div class="col-md-7">
                                <input type="password" name="old-password" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-5">{-"New Password"|translate}*</label>

                            <div class="col-md-7">
                                <input type="password" name="new-password" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-5">{-"Repeat new Password"|translate}*</label>

                            <div class="col-md-7">
                                <input type="password" name="new-password-rep" class="form-control" required="required">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-9 clearfix">
                                <input class="ajaxform-callback" type="hidden" value="passwordchanged">
                                <button type="submit" class="btn btn-primary pull-right" id="updatepassword"><i
                                            class="fa fa-save"></i> {-"Save"|translate}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane panel panel-default" id="editAccountDelAcc">
                <div class="panel-heading">
                    <h3 class="panel-title">{-"Delete Account"|translate}</h3>
                </div>
                <div class="panel-body clearfix">
                    <div class="alert alert-danger">
                        <h4>{-"Attention!"|translate}</h4>

                        <p>{-"If you delete your account, all your data will be deleted permanentely. None will be able to restore any photos, messages or posts!"|translate}</p>

                        <p><strong>{-"Are You sure you want to delete your account?"|translate}</strong></p>
                    </div>
                    <a href="{-"index.php?m=register&action=delete"|URL}" class="btn btn-primary pull-right"
                       style="margin-top:20px">{-"Yes, I am sure. Delete my account"|translate}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane clearfix" id="editPrivacy">
        <div class="col-md-3">
            <div class="list-group">
                <a href="#editPrivacyGeneral" class="list-group-item active"
                   data-toggle="tab">{-"General"|translate}</a>
            </div>
        </div>
        <div class="col-md-9 tab-content section-content">
            <div class="tab-pane panel panel-default active" id="editPrivacyGeneral">
                <div class="panel-heading">
                    <h3 class="panel-title">{-"General"|translate}</h3>
                </div>
                <div class="panel-body">
                    <div class="alert alert-block hidden" id="privacychangedalert"></div>
                    <form action="{-"index.php?m=profile&action=edit"|URL}" method="post"
                          class="form-horizontal ajaxform"
                          id="privacyForm">
                        <input type="hidden" name="edit" value="changePrivacy">

                        <div class="form-group">
                            <label class="control-label col-md-5">{-"Who is allowed to search you?"|translate}</label>

                            <div class="col-md-7">
                                <select class="form-control" name="privacy[search]" id="privacyselect-search">
                                    <option value="0"
                                            {-if $profile.privacy.search == 0}selected{-/if}>{-"No one"|translate}</option>
                                    <option value="1"
                                            {-if $profile.privacy.search == 1}selected{-/if}>{-"Friends"|translate}</option>
                                    {-*<option value="2" {-if $profile.privacy.search == 2}selected{-/if}>{-"Friends of Friends"|translate}</option>*}
                                    <option value="3"
                                            {-if $profile.privacy.search == 3}selected{-/if}>{-"Everyboy"|translate}</option>
                                </select>
                            </div>
                        </div>
                        {-*<div class="form-group">*}
                            {-*<label class="control-label col-md-5">{-"Who is allowed to see your profile-pins?"|translate}</label>*}

                            {-*<div class="col-md-7">*}
                                {-*<select class="form-control" name="privacy[visit]" id="privacyselect-visit">*}
                                    {-*<option value="0"*}
                                            {-*{-if $profile.privacy.visit == 0}selected{-/if}>{-"No one"|translate}</option>*}
                                    {-*<option value="1"*}
                                            {-*{-if $profile.privacy.visit == 1}selected{-/if}>{-"Friends"|translate}</option>*}
                                    {-*                                <option value="2" {-if $profile.privacy.visit == 2}selected{-/if}>{-"Friends of Friends"|translate}</option>*}
                                    {-*<option value="3"*}
                                            {-*{-if $profile.privacy.visit == 3}selected{-/if}>{-"Everyboy"|translate}</option>*}
                                {-*</select>*}
                            {-*</div>*}
                        {-*</div>*}
                        <div class="form-group">
                            <label class="control-label col-md-5">{-"Who is allowed to send you messages?"|translate}</label>

                            <div class="col-md-7">
                                <select class="form-control" name="privacy[message]" id="privacyselect-message">
                                    <option value="0"
                                            {-if $profile.privacy.message == 0}selected{-/if}>{-"No one"|translate}</option>
                                    <option value="1"
                                            {-if $profile.privacy.message == 1}selected{-/if}>{-"Friends"|translate}</option>
                                    {-*<option value="2" {-if $profile.privacy.message == 2}selected{-/if}>{-"Friends of Friends"|translate}</option>*}
                                    <option value="3"
                                            {-if $profile.privacy.message == 3}selected{-/if}>{-"Everyboy"|translate}</option>
                                </select>
                            </div>
                        </div>
                        {-*<div class="form-group">*}
                            {-*<label class="control-label col-md-5">{-"Who is allowed to see your Newsfeed-Posts?"|translate}</label>*}

                            {-*<div class="col-md-7">*}
                                {-*<select class="form-control" name="privacy[posts]" id="privacyselect-posts">*}
                                    {-*<option value="0"*}
                                            {-*{-if $profile.privacy.posts == 0}selected{-/if}>{-"No one"|translate}</option>*}
                                    {-*<option value="1"*}
                                            {-*{-if $profile.privacy.posts == 1}selected{-/if}>{-"Friends"|translate}</option>*}
                                    {-*<option value="2" {-if $profile.privacy.posts == 2}selected{-/if}>{-"Friends of Friends"|translate}</option>*}
                                    {-*<option value="3"*}
                                            {-*{-if $profile.privacy.posts == 3}selected{-/if}>{-"Everyboy"|translate}</option>*}
                                {-*</select>*}
                            {-*</div>*}
                        {-*</div>*}
                        <div class="form-group">
                            <div class="col-md-offset-5 col-md-7 clearfix">
                                <input class="ajaxform-callback" value="privacychanged" type="hidden">
                                <button type="submit" class="btn btn-primary pull-right" id="saveprivacy" onclick="$('#privacychangedalert').removeClass('alert-success, alert-danger').hide().html('')"><i
                                            class="fa fa-save"></i> {-"Save"|translate}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="editProfileImages" class="tab-pane clearfix">
        <div class="col-md-3">
            <div class="list-group">
                <a href="#editProfileImage" class="list-group-item active" data-toggle="tab"
                   data-type="profile">{-"Profile Image"|translate}</a>
                <a href="#editTitleImage" class="list-group-item" data-toggle="tab"
                   data-type="title">{-"Title Image"|translate}</a>
            </div>
        </div>
        <div class="col-md-9 tab-content section-content">
            <div class="tab-pane panel panel-default active" id="editProfileImage">
                <div class="panel-heading">
                    <h3 class="panel-title">{-"Edit My Profile Image"|translate}</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-md-3">{-"Profile-Image"|translate}</label>

                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" id="profile-upload" type="button"><i
                                                    class="fa fa-search"></i>&nbsp;{-"Select Photo"|translate}</button>
                                    </span>
                                    <input type="text" class="form-control" readonly id="selected-file-profile">
                                </div>
                                <span class="help-block">{-"You can upload jpg, gif and png files with a maximum file-size of"|translate}
                                    &nbsp;{-$upload_limit}</span>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            {-if $user.profileImage != 0 }
                                <div class="col-md-9">
                                    <button type="button" class="btn btn-link text-danger image-delete"
                                            data-type="profile"><i
                                                class="fa fa-trash-o"></i>&nbsp;{-"Delete current Profile-Image"|translate}
                                    </button>
                                </div>
                            {-else}
                                <div class="col-md-9">&nbsp;</div>
                            {-/if}
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary pull-right" onclick="uploadPhoto();"
                                        data-loading-text="{-"Uploading"|translate}..."><i
                                            class="fa fa-upload"></i> {-"Upload"|translate}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane panel panel-default" id="editTitleImage">
                <div class="panel-heading">
                    <h3 class="panel-title">{-"Edit My Title Image"|translate}</h3>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-md-3">{-"Title-Image"|translate}</label>

                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" id="title-upload" type="button"><i
                                                    class="fa fa-search"></i>&nbsp;{-"Select Photo"|translate}</button>
                                    </span>
                                    <input type="text" class="form-control" readonly id="selected-file-title">
                                    <input type="hidden" id="upload_limit" value="{-$upload_limit}">
                                </div>
                                <span class="help-block">{-"You can upload jpg, gif and png files with a maximum file-size of"|translate}
                                    &nbsp;{-$upload_limit}</span>
                            </div>
                        </div>
                        <div class="form-group">
                            {-if $user.titleImage != 0 }
                                <div class="col-md-9">
                                    <button type="button" class="btn btn-link text-danger image-delete"
                                            data-type="title"><i
                                                class="fa fa-trash-o"></i>&nbsp;{-"Delete current Title-Image"|translate}
                                    </button>
                                </div>
                            {-else}
                                <div class="col-md-9">&nbsp;</div>
                            {-/if}
                            <div class="col-md-3 clearfix">
                                <button type="button" class="btn btn-primary pull-right" onclick="uploadPhoto();"
                                        data-loading-text="{-"Uploading"|translate}..."><i
                                            class="fa fa-upload"></i> {-"Upload"|translate}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="profile-pins tab-pane" id="editProfilePins">
        <div class="clearfix">
            <span class="pull-left text-info" style="padding: 7px 0">{-"Grab the pins to move them!"|translate}</span><a
                    class="btn btn-primary pull-right" data-action="add" data-toggle="modal" href="#newpinmodal"><i
                        class="fa fa-plus"></i> {-"Add a new Pin"|translate}</a>
        </div>
        <div class="clearfix" id="Pins">
            <div class="profile-pins-column pull-left" id="profilepinleft" data-column="0"></div>
            <div class="profile-pins-column pull-right" id="profilepinright" data-column="1"></div>
        </div>
        <div class="modal fade newpinmodal" id="newpinmodal" data-moveto="body">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">{-"Add a new Profile-Pin"|translate}</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal ajaxform form-validate" role="form" id="newPinForm" method="post"
                              action="{-"index.php?m=profile&action=edit"|URL}"
                              data-bv-message="{-"This field cannot be blank!"|translate}"
                                >
                            <input type="hidden" name="edit" value="pin">

                            <div class="form-group">
                                <label class="col-lg-2 control-label">{-"Title"|translate}</label>

                                <div class="col-lg-10">
                                    <input type="text" class="form-control" maxlength="53" name="title"
                                           placeholder="{-"Give the pin a title!"|translate}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{-"Type"|translate}</label>

                                <div class="col-lg-10">
                                    <select class="form-control" name="type">
                                        <option value="" selected>{-"Choose a type"|translate}</option>
                                        <option value="2">{-"Freestyle"|translate}</option>
                                        {-*<option value="1">{-"Profile-Info"|translate}</option>*}
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 control-label">{-"Icon"|translate}</label>

                                <div class="col-lg-10">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-default dropdown-toggle"
                                                data-toggle="dropdown">
                                            <span id="select-icon-toggle"
                                                  data-orig="{-"Select an icon"|translate}">&nbsp;{-"Select an icon"|translate}</span>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" id="select-icon-menu"></ul>
                                    </div>
                                </div>
                                <input type="hidden" name="iconClass" class="newpin-icon" value="">
                            </div>
                            <div class="form-group hidden" id="pin-type-1">
                                <label class="col-lg-2 control-label">{-"Content"|translate}</label>

                                <div class="col-lg-10">
                                    <span class="help-block">{-"Select Information you want to present"|translate}</span>
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="profile-info" value="email">
                                                    E-Mail
                                                </label>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="profile-info" value="birthday">
                                                    Geburtstag
                                                </label>
                                            </div>
                                        </li>
                                        <li class="list-group-item">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="profile-info" value="sex">
                                                    Geschlecht
                                                </label>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group hidden" id="pin-type-2">
                                <label class="col-lg-2 control-label">{-"Content"|translate}</label>

                                <div class="col-lg-10">
                                    <input type="text" class="hidden" name="content" id="profile-pin-summernote">
                                </div>
                            </div>
                            <input type="hidden" class="ajaxform-callback" value="pincreated">
                            <button class="hidden" type="submit"></button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="text-danger pull-left deletepin hidden" style="margin-top:7px"
                           data-confirmation="{-"Are You sure You want to delete this profile-pin?"|translate}">{-"Delete this Profile-Pin"|translate}</a>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary newpinbutton"
                                onclick="$('#newPinForm').submit();">{-"Create"|translate}</button>
                        <button type="button" class="btn btn-primary editpinbutton"
                                onclick="$('#newPinForm').submit();">{-"Update"|translate}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="editNotifications" class="tab-pane">
        <div class="col-md-3">
            <div class="list-group">
                <a href="#editNotificationsGeneral" class="list-group-item active"
                   data-toggle="tab">{-"General"|translate}</a>
            </div>
        </div>
        <div class="col-md-9 tab-content section-content">
            <div class="alert alert-block hidden" id="notificationsettingschangedalert"></div>
            <form class="tab-pane panel panel-default active ajaxform" action="{-"index.php?m=profile&action=edit"|URL}"
                  id="editProfileImage" method="post">
                <input type="hidden" name="edit" value="notifications">

                <div class="panel-heading">
                    <h3 class="panel-title">{-"Notifications-Settings"|translate}</h3>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th width="80%">
                            {-"Select which kind of Notifications you want to receive"|translate}
                        </th>
                        <th width="10%">
                            <i class="fa fa-bell tooltip-trigger"
                               data-title="{-"Display a notification on this Website"|translate}"></i>
                        </th>
                        <th width="10%">
                            <i class="fa fa-envelope tooltip-trigger" data-title="{-"Send me an email"|translate}"></i>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{-"Someone posted on your Wall"|translate}<input type="hidden" name="types[wall_post]"
                                                                             value="1"></td>
                        <td><input type="checkbox" name="alert[wall_post]" value="1"
                                   {-if $profile.notificationSettings.wall_post == 1 or $profile.notificationSettings.wall_post == 3}checked="checked"{-/if}>
                        </td>
                        <td><input type="checkbox" name="mail[wall_post]" value="1"
                                   {-if $profile.notificationSettings.wall_post == 2 or $profile.notificationSettings.wall_post == 3}checked="checked"{-/if}>
                        </td>
                    </tr>
                    <tr>
                        <td>{-"Someone sent you a message"|translate}<input type="hidden" name="types[message]"
                                                                            value="1">
                        </td>
                        <td><input type="checkbox" name="alert[message]" value="1"
                                   {-if $profile.notificationSettings.message == 1 or $profile.notificationSettings.message == 3}checked="checked"{-/if}>
                        </td>
                        <td><input type="checkbox" name="mail[message]" value="1"
                                   {-if $profile.notificationSettings.message == 2 or $profile.notificationSettings.message == 3}checked="checked"{-/if}>
                        </td>
                    </tr>
                    <tr>
                        <td>{-"Someone add you to a conversation"|translate}<input type="hidden"
                                                                                   name="types[addconversation]"
                                                                                   value="1">
                        </td>
                        <td><input type="checkbox" name="alert[addconversation]" value="1"
                                   {-if $profile.notificationSettings.addconversation == 1 or $profile.notificationSettings.addconversation == 3}checked="checked"{-/if}>
                        </td>
                        <td><input type="checkbox" name="mail[addconversation]" value="1"
                                   {-if $profile.notificationSettings.addconversation == 2 or $profile.notificationSettings.addconversation == 3}checked="checked"{-/if}>
                        </td>
                    </tr>
                    {-if ("register.allfriends"|setting == 0) }
                    <tr>
                        <td>{-"Someone add you as a friend"|translate}<input type="hidden" name="types[addfriend]"
                                                                             value="1"></td>
                        <td><input type="checkbox" name="alert[addfriend]" value="1"
                                   {-if $profile.notificationSettings.addfriend == 1 or $profile.notificationSettings.addfriend == 3}checked="checked"{-/if}>
                        </td>
                        <td><input type="checkbox" name="mail[addfriend]" value="1"
                                   {-if $profile.notificationSettings.addfriend == 2 or $profile.notificationSettings.addfriend == 3}checked="checked"{-/if}>
                        </td>
                    </tr>
                    <tr>
                        <td>{-"Someone confirms your friend-request"|translate}<input type="hidden"
                                                                                      name="types[confirmfriend]"
                                                                                      value="1">
                        </td>
                        <td><input type="checkbox" name="alert[confirmfriend]" value="1"
                                   {-if $profile.notificationSettings.confirmfriend == 1 or $profile.notificationSettings.confirmfriend == 3}checked="checked"{-/if}>
                        </td>
                        <td><input type="checkbox" name="mail[confirmfriend]" value="1"
                                   {-if $profile.notificationSettings.confirmfriend == 2 or $profile.notificationSettings.confirmfriend == 3}checked="checked"{-/if}>
                        </td>
                    </tr>
                    {-/if}
                                        {-*<tr>
                    <td>{-"Someone commented on one of your posts or photos"|translate}</td>
                    <td><input type="checkbox" name="alert[post_comment]" value="1"></td>
                    <td><input type="checkbox" name="mail[post_comment]" value="1"></td>
                    </tr>*}
                    </tbody>
                </table>
                <div class="panel-footer clearfix">
                    <input type="hidden" class="ajaxform-callback" value="notificationsUpdated">
                    <button type="submit" class="btn btn-primary pull-right" id="savenotification"><i
                                class="fa fa-save"></i>&nbsp;{-"Save"|translate}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/html" id="profilepin">
    <div class="panel panel-default" id="pin-{%=o.id%}" data-pinid="{%=o.id%}">
        <div class="panel-heading"><i class="fa fa-fw fa-{%=o.iconclass%}"></i>{%=o.title%}<a href="#"
                                                                                              data-target="#newpinmodal"
                                                                                              data-pinid="{%=o.id%}"
                                                                                              data-action="edit"
                                                                                              data-toggle="modal"
                                                                                              class="pull-right tooltip-trigger"
                                                                                              data-title="{-"edit Pin"|translate}"><i
                        class="fa fa-fw fa-cog"></i></a></div>
        {% if (o.type == 2) { %}
        <div class="panel-body">
            {%#o.content%}
        </div>
        {% } else { %}
        <table class="table panel-body" style="padding:0">
            {%#infoPin(o.content)%}
            {% } %}
        </table>
    </div>
</script>