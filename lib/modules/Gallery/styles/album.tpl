<div class="page-buttonbar clearfix">
    <div class="album-owner-box pull-left">
        {-if $album.owner_type eq NULL}
            <a class="pull-left" href="{-"index.php?m=profile&action={-$album.username}"|URL}"><img
                        src="{-$album.filename|image:"user":"cr_"}" class="img-rounded thumbnail tooltip-trigger"
                        data-title="{-$album.name}"></a>
        {-else}
            <a class="pull-left" href="{-"index.php?m=events&action={-$album.owner_id}"|URL}"><img
                        src="{-$album.filename|image:"event":"thumb_"}" class="img-rounded thumbnail tooltip-trigger"
                        data-title="{-$album.eventTitle}"></a>
        {-/if}
        <div class="pull-left">
            <h1 class="page-header">
                {-$album.title}
            </h1>
            <span class="text-muted" id="description"
                  data-full="{-$album.description}">{-if {-$album.description|strlen} > 40}{-$album.description|substr:0:35}
                &nbsp;<a href="javascript:collapseDescription();">(...){-else}{-$album.description}{-/if}</a></span>
        </div>
    </div>
    {-if $album.owner_id eq $user.userid && $event.owner_type eq NULL}
        <button class="btn btn-default pull-right tooltip-trigger" data-title="{-"Edit or delete this album"|translate}"
                data-container="body" data-toggle="modal" data-target="#editalbum_modal" style="margin-left:10px"><i
                    class="fa fa-pencil"></i></button>
        <div id="imagecontextmenu" class="hidden" data-moveto="body" data-imageid="0">
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu"
                style="display:block;position:static;margin-bottom:5px;">
                {-*<li><a tabindex="-1" href="javascript:void(0);" class="editimg"><i class="fa fa-pencil"></i>&nbsp;{-"Edit Image"|translate}</a></li>
                <li class="divider"></li>*}
                <li><a tabindex="-1" href="javascript:deleteImage($('#imagecontextmenu').data('imageid'));"><i
                                class="fa fa-trash-o"></i>&nbsp;{-"Delete Image"|translate}</a></li>
            </ul>
        </div>
    {-/if}
    {-if ($album.type eq "shared" && $album.allow_upload eq 1 && !$album.userid eq $user.userid)  || (($album.type eq NULL || $album.type eq "shared")&&$album.owner_id eq $user.userid && $event.owner_type eq NULL)}
        <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#upload_modal"><i
                    class="fa fa-plus"></i>&nbsp;{-"Upload images"|translate}</button>
    {-/if}
</div>
<input type="hidden" id="albumid" value="{-$album.id}">
<div id="imagelist">
    <div class="list clearfix"></div>
    <div class="loader block-loader gallery-loader"></div>
    <div class="album-load-more hidden"><a href="javascript:loadPhotos({-$album.id});"><i class="fa fa-clock-o"></i>&nbsp;{-"Load more Photos"|translate}
        </a></div>
</div>
{-if $album.owner_id eq $user.userid && $event.owner_type eq NULL}
    <div class="modal fade" id="upload_modal" tabindex="-1" role="dialog" aria-labelledby="upload_modal"
         aria-hidden="true"
         data-moveto="body">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{-"Upload images"|translate}</h4>
                </div>
                <div id="multiuploader">
                    <div class="modal-body" id="multi_container">
                        <span class="btn btn-success" id="multi_selectfiles" data-albumid="{-$album.id}"
                              data-flash="{-"core.siteurl"|setting}/style/CunityRefreshed/modules/gallery/javascript/Moxie.swf"
                              data-silverlight="{-"core.siteurl"|setting}/style/CunityRefreshed/modules/gallery/javascript/Moxie.xap">
                            <i class="fa fa-plus"></i>
                            <span>{-"Select Files"|translate}</span>
                        </span>

                        <div class="progress progress-striped active upload-progress" id="upload_progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0"
                                 aria-valuemax="100" style="width: 0"></div>
                        </div>
                        <div id="filescontainer">
                            <div class="alert alert-info">
                                {-"No Files selected. Please use the button above to add files to the queue"|translate}
                            </div>
                            <table id="filestable">
                                <colgroup>
                                    <col width="300px">
                                    <col width="200px">
                                    <col width="36px">
                                </colgroup>
                                <tbody id="files" class="files"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <form class="form-horizontal" role="form" enctype="multipart/form-data"
                      action="{-"index.php?m=gallery&action=upload"|URL}" method="POST">
                    <input type="hidden" name="uploadtype" value="single" />
                    <div id="singleuploader" class="hidden">
                        <div class="alert alert-info">
                            {-"Select file to upload. You can upload only one image at the same time!"|translate}
                        </div>
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="input-group">
                                    <input type="hidden" name="albumid" value="{-$album.id}">
                                    <input type="hidden" class="ajaxform-callback" value="addUploaded">
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
                        <a href="#"
                           class="singleuploader_link">{-"Uploader not working? Try the simple one"|translate}</a>
                        <a href="#"
                           class="multiuploader_link hidden">{-"Have many images to upload? use the multiuploader"|translate}</a>
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">{-"Cancel"|translate}</button>
                        <button type="button" id="startupload" class="btn btn-primary"
                                data-loading-text="{-"Please wait..."|translate}"><i
                                    class="fa fa-upload"></i>&nbsp;{-"Upload"|translate}</button>
                        <button type="button" id="closeupload" class="btn btn-primary" data-dismiss="modal"><i
                                    class="fa fa-check"></i>&nbsp;{-"Finish"|translate}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editalbum_modal" tabindex="-1" role="dialog" aria-labelledby="editalbum_modal"
         aria-hidden="true" data-moveto="body">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{-"Edit album"|translate}</h4>
                </div>
                <div class="modal-body">
                    {-if $album.type eq "profile" || $album.type eq "newsfeed"}
                        <div class="alert alert-info">
                            <p>
                                <strong>{-"Please note!"|translate}</strong>&nbsp;{-"This is an automatic generated album. You cannot change the name or description"|translate}
                            </p>
                        </div>
                    {-/if}
                    <form class="form-horizontal ajaxform" role="form" id="editalbum_form"
                          action="{-"index.php?m=gallery&action=edit"|URL}" method="post">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{-"Title"|translate}</label>

                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="title" placeholder="{-"Title"|translate}"
                                       value="{-$album.title}"
                                       {-if $album.type eq "profile" || $album.type eq "newsfeed"}disabled{-/if}>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{-"Description"|translate}</label>

                            <div class="col-sm-10">
                                <textarea class="form-control" rows="3" name="description"
                                          placeholder="{-"Enter a short description for your album. Optional you can leave that blank!"|translate}"
                                          {-if $album.type eq "profile" || $album.type eq "newsfeed"}disabled{-/if}>{-$album.description}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">{-"Privacy"|translate}</label>

                            <div class="col-sm-10">
                                <div class="btn-group" data-toggle="buttons" style="width:100%">
                                    <label class="btn btn-default tooltip-trigger {-if $album.privacy eq 0}active{-/if}"
                                           {-if $album.type eq "profile" || $album.type eq "newsfeed"}disabled{-/if}
                                           data-title="{-"You can choose friends who are allowed to see this album"|translate}"
                                           data-container="#editalbum_modal" style="width:33.3333%">
                                        <input type="radio" name="privacy" value="0" id="editalbum_shared"
                                               {-if $album.privacy eq 0}selected{-/if}><i
                                                class="fa fa-check-square-o"></i>&nbsp;{-"Shared"|translate}
                                    </label>
                                    <label class="btn btn-default tooltip-trigger {-if $album.privacy eq 1}active{-/if}"
                                           data-title="{-"Only your friends can see the images"|translate}"
                                           data-container="#editalbum_modal" style="width:33.3333%">
                                        <input type="radio" name="privacy" value="1"
                                               {-if $album.privacy eq 1}selected{-/if}><i
                                                class="fa fa-group"></i>&nbsp;{-"Friends"|translate}
                                    </label>
                                    <label class="btn btn-default tooltip-trigger {-if $album.privacy eq 2}active{-/if}"
                                           data-title="{-"Every user can see the images"|translate}"
                                           data-container="#editalbum_modal" style="width:33.3333%">
                                        <input type="radio" name="privacy" value="2"
                                               {-if $album.privacy eq 2}selected{-/if}><i
                                                class="fa fa-globe"></i>&nbsp;{-"Public"|translate}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group  {-if !$album.privacy eq 0 or $album.owner_id neq $user.userid}hidden{-/if}"
                             id="editalbum_shared_options">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="user_upload" value="1"
                                               {-if $album.user_upload eq 1}checked{-/if}> {-"Friends can also upload images"|translate}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="albumid" value="{-$album.id}">
                        <input type="hidden" class="ajaxform-callback" value="albumedited">
                    </form>
                </div>
                <div class="modal-footer">
                    {-if $album.owner_id eq $user.userid && ($album.type eq NULL || $album.type eq "shared")}
                        <a href="#" class="text-danger albumdelete-link" data-title="{-"Are you sure?"|translate}"><i
                                    class="fa fa-trash-o"></i>&nbsp;{-"Delete this album"|translate}</a>
                    {-/if}
                    <button type="button" class="btn btn-default" data-dismiss="modal"
                            onclick="$('#editalbum_form')[0].reset();">{-"Cancel"|translate}</button>
                    <button type="button" id="savealbum" onclick="$('#editalbum_form').submit();"
                            class="btn btn-primary"
                            data-loading-text="{-"Please wait.."|translate}">{-"Save changes"|translate}</button>
                </div>
                <div class="hidden deletecontent">
                    <p class="text-danger">{-"If you delete this album, all images and their likes, dislikes and comments will be deleted permanently!"|translate}</p>
                    <a class="btn btn-danger btn-block finaldelete"><i
                                class="fa fa-trash-o"></i>&nbsp;{-"Sure, delete this album"|translate}</a>
                </div>
            </div>
        </div>
    </div>
{-/if}
{-include file="Gallery/styles/lightbox.tpl"}
<script type="text/html" id="imagestemplate">
    <a class="album-item pull-left" data-imageid="{%=o.id%}" id="image-{%=o.id%}" data-gallery title=""
       href="{%=checkImage(o.filename,'gallery')%}">
        <img src="{%=checkImage(o.filename,'gallery','thumb_')%}" class="gallery-cover">

        <div class="buttonbar hidden hidden-sm hidden-xs clearfix">
            <span class="buttonbar-section imagelike pull-left" data-imgid="{%=o.imageid%}">
                <i class="fa fa-smile-o like"></i>
                <span class="likecount count-badge">{%=o.likes%}</span>
            </span>
            <span class="buttonbar-section imagedislike pull-left" data-imgid="{%=o.imageid%}">
                <i class="fa fa-frown-o dislike"></i>
                <span class="dislikecount count-badge">{%=o.dislikes%}</span>
            </span>
            <span class="buttonbar-section pull-left">
                <i class="fa fa-comments-o like"></i>
                <span class="commentcount count-badge">{%=o.comments%}</span>
            </span>
        </div>
    </a>
</script>
<script type="text/html" id="noimages">
    <div class="alert alert-block alert-danger"><p>{-"This Album contains no images!"|translate}</p></div>
</script>