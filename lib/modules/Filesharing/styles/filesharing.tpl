<div class="page-buttonbar clearfix">
    <h1 class="page-header pull-left">{-"Filesharing"|translate}</h1>
    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#newfiles_modal" id="newfiles"><i
                class="fa fa-plus"></i>&nbsp;{-"New file"|translate}</button>
    <div class="btn-group pull-right">
        <button type="button" class="btn btn-default dropdown-toggle tooltip-trigger" id="filter-dropdown"
                data-toggle="dropdown" data-title="{-"Filter the files you want to see"|translate}"><i
                    class="fa fa-filter"></i></button>
        <ul class="dropdown-menu dropdown-checkbox-menu" role="menu" style="left:0;right:auto">
            <li class="dropdown-header">{-"Filter the files you want to see"|translate}</li>
            <li>
                <label>
                    <input type="checkbox" class="filess-filter" value="own">&nbsp;{-"My files"|translate}
                </label>
            </li>
            <li>
                <label>
                    <input type="checkbox" class="filess-filter" value="foreign">&nbsp;{-"Friends files"|translate}
                </label>
            </li>
            <li>
                <button class="btn btn-primary btn-xs btn-block"
                        onclick="applyFilter();">{-"Apply filter"|translate}</button>
            </li>
        </ul>
    </div>
</div>
<div id="fileslist">
    <div class="filesharing-loader block-loader loader"></div>
    <div class="filesharing-list clearfix"></div>
    <div class="alert alert-block alert-danger"><p>{-"There are no files to show!"|translate}</p></div>
</div>
<div class="modal fade" id="newfiles_modal" tabindex="-1" role="dialog" aria-labelledby="newfiles_modal"
     aria-hidden="true" data-moveto="body">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{-"Create new file"|translate}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{-"index.php?m=filesharing&action=create"|URL}" role="form"
                      id="newfiles_form" enctype="multipart/form-data" method="post">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{-"Title"|translate}</label>

                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="title" placeholder="{-"Title"|translate}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{-"Description"|translate}</label>

                        <div class="col-sm-10">
                            <textarea class="form-control" rows="3" name="description"
                                      placeholder="{-"Enter a short description for your files. Optional you can leave that blank!"|translate}"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{-"Privacy"|translate}</label>

                        <div class="col-sm-10">
                            <div data-toggle="buttons">
                                <label class="btn btn-default tooltip-trigger active"
                                       data-title="{-"Only your friends can see the files"|translate}"
                                       data-container="#newfiles_modal" id="friendCheckboxLabel">
                                    <input type="checkbox" checked="checked" id="friendCheckbox" disabled="disabled"><i
                                            class="fa fa-group"></i>&nbsp;{-"All friends"|translate}
                                </label>&nbsp;or&nbsp;
                                <label class="tooltip-trigger" style="width: 240px"
                                       data-title="{-"Users"|translate}"
                                       data-container="#newfiles_modal">
                                    <select name="friends" multiple="multiple" id="friendselector">
                                        {-foreach $friends as $friend }
                                            <option value="{-$friend.userid}">{-$friend.username}</option>
                                        {-/foreach}
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                        <input type="hidden" name="uploadtype" value="single" />
                        <div id="singleuploader">
                            <div class="row">
                                <div class="col-lg-10">
                                    <div class="input-group">
                                        <input class="inputCover form-control" type="text" id="fileonecover"
                                               readonly="readonly">
                                        <input class="hidden filefakeinput" name="file" type="file" id="fileone"
                                               data-rel="#fileonecover">
                                    <span class="input-group-btn">
                                        <label class="btn btn-default" for="fileone"><i
                                                    class="fa fa-search"></i>&nbsp;{-"Browse"|translate}</label>
                                        <button class="btn btn-primary" type="submit" id="submitButtonFileOne"
                                                disabled="disabled"><i class="fa fa-upload"></i>
                                        </button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default"
                                    data-dismiss="modal">{-"Cancel"|translate}</button>
                            <button type="submit" id="startupload" class="btn btn-primary"
                                    data-loading-text="{-"Please wait..."|translate}"><i
                                        class="fa fa-upload"></i>&nbsp;{-"Upload"|translate}</button>
                        </div>
                    </form>
            </div>
            {-*<div class="modal-footer">*}
                {-*<button type="button" class="btn btn-default" data-dismiss="modal">{-"Cancel"|translate}</button>*}
                {-*<button type="button" id="createfiles" onclick="$('#newfiles_form').submit();" class="btn btn-primary"*}
                        {-*data-loading-text="{-"Please wait.."|translate}">{-"Create"|translate}</button>*}
            {-*</div>*}
        </div>
    </div>
</div>
<script type="text/html" id="filess-template">
    <div class="fileslist-item pull-left user-files-{%=o.owner_id%} {% if (o.owner_id == {-$user.userid}) { %}files-own{% } else { %}files-foreign{% } %}">
        <a href="{%=convertUrl({'module':'filesharing','action': o.id,'x':o.title.replace(' ','_')})%}"
           class="fileslist-item-image"
           style="background-image:url('{%=checkImage(o.filename,'filesharing','thumb_')%}');"></a>
        <a href="{%=convertUrl({'module':'filesharing','action': o.id,'x':o.title.replace(' ','_')})%}"
           class="fileslist-item-name">{% if (o.type == 'profile') { %}{-"Profile Images"|translate}{% } else if (o.type == 'newsfeed'){ %}{-"Posted Images"|translate}{% } else { %}{%=o.title%}{% } %}</a>

        <div class="fileslist-item-ownerbox clearfix">
            <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}"><img
                        src="{%=checkImage(o.pimg,'user','cr_')%}"
                        class="fileslist-item-owner tooltip-trigger pull-left" data-title="{%=o.name%}"
                        data-placement="right"></a>
            <i class="fileslist-item-time">{%#convertDate(o.time)%}</i>
        </div>
    </div>
</script>