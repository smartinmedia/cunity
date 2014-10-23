<div id="blueimp-gallery" class="blueimp-gallery" data-moveto="body">
    <div class="slides"></div>
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" style="padding: 0 !important">
                    <div class="modal-body-content">
                        <button type="button" class="close" aria-hidden="true">&times;</button>
                        <div class=" block-loader image-loading"></div>
                        <div class="modal-body-info hidden">
                            <div class="userinfo clearfix">
                                <img src="" class="thumbnail pull-left">

                                <div class="userbox pull-right">
                                    <a href=""></a>
                                    <i class="time"><i class="fa fa-clock-o"></i>&nbsp;<span></span></i>
                                </div>
                            </div>
                            <div class="imagetitle">
                                <h4 class="modal-title"></h4>
                            </div>
                            <div class="socialbox likebox">
                                <button type="button" class="btn btn-success imagelike"><i class="fa fa-smile-o"></i>&nbsp;{-"Like"|translate}
                                </button>
                                <button type="button" class="btn btn-danger imagedislike"><i class="fa fa-frown-o"></i>&nbsp;{-"Dislike"|translate}
                                </button>
                                <button type="button" class="btn btn-default imageunlike tooltip-trigger hidden"
                                        data-title="{-"Remove all your Like/Dislike from this image"|translate}"><i
                                            class="fa fa-meh-o"></i></button>
                                {-*<button type="button" class="btn btn-info"><i class="fa fa-rss"></i>&nbsp;{-"Share"|translate}</button>                                *}
{-*<ul class="breadcrumb clear imagelikes">
<li><a href="javascript:getLikes('image',currentImgId,0,'{-"Likes"|translate}');"><span class="likecount"></span> {-"Likes"|translate}</a></li>
                                    <li><a href="javascript:getLikes('image',currentImgId,0,'{-"Likes"|translate}');"><span class="dislikecount"></span> {-"Dislikes"|translate}</a></li>
                                    </ul>*}
                                <div class="lightbox-likebox imagelikes clearfix">
                                    <div class="lightbox-likes pull-left">
                                        <a href="javascript:getLikes('image',currentImgId,0,'{-"Likes"|translate}');"><i class="fa fa-smile-o"></i></a>
                                        <div id="lightbox-likes" class="clearfix"><div></div><span class="label label-danger">{-"No Likes yet"|translate}</span></div>
                                    </div>
                                    <div class="lightbox-dislikes pull-right">
                                        <a href="javascript:getLikes('image',currentImgId,0,'{-"Likes"|translate}');"><i class="fa fa-frown-o"></i></a>
                                        <div id="lightbox-dislikes" class="clearfix"><div></div><span class="label label-danger">{-"No Dislikes yet"|translate}</span></div>
                                    </div>                
                                </div>
                            </div>
                            <div class="socialbox commentbox">                                
                                <div class="comments"><button style="margin-bottom:10px" class="btn btn-xs btn-default btn-block morecomments hidden" data-id="{%=o.id%}" data-type="{%=o.type%}" id="comments-more-{%=o.id%}"><i class="fa fa-clock-o"></i>&nbsp;{-"Load more comments"|translate}</button><div class="list"></div></div>
                            </div>
                            <div class="newimagebox">
                                <div class="image-comment clearfix">
                                    <img src="{-$user.pimg|image:"user":"cr_"}" class="avatar thumbnail pull-left">
                                    <div class="content-box pull-right">
                                        <textarea class="form-control" placeholder="{-"Leave a comment!"|translate}"></textarea>
                                        <i class="text-info hidden">{-"Press enter to post comment"|translate}</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default pull-left prev"><i class="fa fa-chevron-left"></i>&nbsp;{-"Previous"|translate}</button>
                            <button type="button" class="btn btn-primary next">{-"Next"|translate}&nbsp;<i class="fa fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="info-overlay clearfix">
                        <a class="pull-left lightbox-album-link" href="">{-"Image"|translate} <span class="image-index"></span> {-"of"|translate} <span class="image-full-count">{-$album.photo_count}</span> {-"from the Album"|translate}: <span class="lightbox-album-name">{-$album.title}</span></a>
                        <div class="dropdown dropup pull-right optionsdropdown hidden">
                            <a class="pull-right" role="button" data-toggle="dropdown" data-target="#"><i class="fa fa-cogs"></i>&nbsp;{-"Options"|translate}</a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a tabindex="-1" href="javascript:deleteImage(currentImgId);" class="deleteimg"><i class="fa fa-trash-o"></i>&nbsp;{-"Delete Image"|translate}</a></li>
                            </ul>
                        </div>                                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="imagecomment">
    <div class="image-comment clearfix" data-id="{%=o.id%}" id="comment-{%=o.id%}">        
        <img src="{%=checkImage(o.filename,'user','cr_')%}" class="avatar pull-left">
        <div class="content-box pull-right">
            {% if (o.userid == {-$user.userid} || o.ref.userid == {-$user.userid}) { %}                        
            <a href="javascript:deleteComment({%=o.id%})" class="close tooltip-trigger" title="{-"Delete this comment"|translate}">&times</a>
            {% } %}
            <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}">{%=o.name%}</a>
            <span class="message">{%=o.content%}</span>
            <div class="time">{%#convertDate(o.time)%}</div>
        </div>
    </div>
</script>