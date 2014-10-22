<script id="newsfeed-post" type="text/html">
    <div class="newsfeed-post clearfix {% if (o.new) { %} newsfeed-post-new{% } %}" id="post-{%=o.id%}"
         data-id="{%=o.id%}">
        <div class="newsfeed-post-user pull-left">
            <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}"><img
                        src="{%=checkImage(o.pimg,'user','cr_')%}" class="img-rounded thumbnail"></a>
        </div>
        <div class="newsfeed-post-content pull-left clearfix">
            {% if (o.title) { %}
            <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}">{%=o.name%}</a>&nbsp;<i
                    class="fa fa-caret-right newsfeed-post-link-caret"></i>&nbsp;<a
                    href="{-"index.php?m=events&action="|URL}{%=o.eventid%}"><i
                        class="fa fa-calendar fa-fw"></i>&nbsp;{%=o.title%}</a>
            {% } else if (o.receivername) { %}
            <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}">{%=o.name%}</a>&nbsp;<i
                    class="fa fa-caret-right newsfeed-post-link-caret"></i>&nbsp;<a
                    href="{-"index.php?m=profile&action="|URL}{%=o.receiverusername%}">{%=o.receivername%}</a>
            {% } else { %}
            <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}">{%=o.name%}</a>
            {% } %}
            {% if (o.type == "image") { %}
            <p>{%#o.caption%}</p>
            <a class="newsfeed-post-image" data-imageid="{%=o.content%}" id="image-{%=o.content%}" data-gallery title=""
               href="{%=checkImage(o.filename,'gallery')%}"><img src="{%=checkImage(o.filename,'gallery','prev_')%}"
                                                                 class="thumbnail"></a>
            {% } else if(o.type == "video") { %}
            <p>{%#trimContent(o.content.content)%}</p>

            <div class="newsfeed-post-video" style="margin-top:10px">
                <div class="playable" id="newsfeed-post-playable-{%=o.refid%}">
                    <img src="{%=o.content.video.thumbnail%}" class="newsfeed-post-video-thumbnail thumbnail">
                    <a class="overlay" href="javascript:play({%=o.refid%});"><i class="fa fa-play-circle-o"></i></a>
                </div>
                <iframe height="360" width="480" src="" frameborder="0" allowfullscreen
                        class="newsfeed-post-video-frame hidden"
                        data-src="//www.youtube.com/embed/{%=o.content.video.vid%}"
                        id="newsfeed-post-video-{%=o.refid%}"></iframe>
                <div style="padding-top:10px">
                    <p><a href="{%=o.content.video.link%}"
                          class="newsfeed-post-video-link-title">{%=o.content.video.title%}</a></p>
                    <i class="text-muted">www.youtube.com</i>

                    <p class="newsfeed-post-video-description">{%=o.content.video.description%}</p>
                </div>
            </div>
            {% } else { %}
            <p>{%#trimContent(o.content)%}</p>
            {% } %}
            <div class="newsfeed-post-content-time"><span
                        class="fa fa-fw fa-privacy-{%=o.privacy%}"></span>&nbsp;{%#convertDate(o.time)%}</div>
            <div class="newsfeed-post-info clearfix">
                <ul class="list-inline newsfeed-post-info-buttons pull-right hidden-xs hidden-sm">
                    <li class="like tooltip-trigger{% if (o.liked == 0) { %} liked{% } %}" data-type="{%=o.type%}"
                        data-postid="{%=o.id%}" data-refid="{%=o.refid%}"
                        data-title="{%=o.likecount%}&nbsp;{-"Likes"|translate}"><i class="fa fa-smile-o"></i>&nbsp;<span
                                class="likecount">{%=zeroReplace(o.likecount,'{-"Like"|translate}')%}</span></li>
                    <li class="dislike tooltip-trigger{% if (o.liked == 1) { %} liked{% } %}" data-type="{%=o.type%}"
                        data-postid="{%=o.id%}" data-refid="{%=o.refid%}"
                        data-title="{%=o.dislikecount%}&nbsp;{-"Dislikes"|translate}"><i class="fa fa-frown-o"></i>&nbsp;<span
                                class="dislikecount">{%=zeroReplace(o.dislikecount,'{-"Dislike"|translate}')%}</span>
                    </li>
                    <li class="unlike {% if (o.liked == null) { %}hidden {% } %}tooltip-trigger" data-type="{%=o.type%}"
                        data-postid="{%=o.id%}" data-refid="{%=o.refid%}"
                        data-title="{-"Remove all your likes"|translate}"><i
                                class="fa fa-meh-o"></i>&nbsp;{-"Unlike"|translate}</li>
                    <li class="comment tooltip-trigger" data-title="{%=o.commentcount%}&nbsp;{-"Comments"|translate}"><i
                                class="fa fa-comment-o"></i>&nbsp;<span
                                class="commentcount">{%=zeroReplace(o.commentcount,'{-"Comment"|translate}')%}</span>
                    </li>
                    {% if (o.userid == {-$user.userid} || o.owner_id == {-$user.userid}) { %}
                    <li class="options" data-toggle="dropdown" data-postid="{%=o.refid%}"><i class="fa fa-cogs"></i>&nbsp;{-"Options"|translate}
                    </li>
                    <ul class="dropdown-menu newsfeed-post-info-dropdown" role="menu" aria-labelledby="dropdownMenu1">
                        <li><a href="javascript:deletePost({%=o.id%});" class="deletepost"><i
                                        class="fa fa-trash-o fa-fw"></i>&nbsp;{-"Delete post"|translate}</a></li>
                    </ul>
                    {% } %}
                </ul>
                <div class="btn-group btn-group-justified visible-xs">
                    <div class="btn-group">
                        <button class="btn btn-default like tooltip-trigger{% if (o.liked == 0) { %} liked{% } %}"
                                data-postid="{%=o.id%}" data-type="{%=o.type%}" data-refid="{%=o.refid%}"
                                data-title="{%=o.likecount%}&nbsp;{-"Likes"|translate}"><i class="fa fa-smile-o"></i>&nbsp;<span
                                    class="likecount">{%=zeroReplace(o.likecount,'{-"Like"|translate}')%}</span>
                        </button>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-default dislike tooltip-trigger{% if (o.liked == 1) { %} liked{% } %}"
                                data-postid="{%=o.id%}" data-type="{%=o.type%}" data-refid="{%=o.refid%}"
                                data-title="{%=o.dislikecount%}&nbsp;{-"Dislikes"|translate}"><i
                                    class="fa fa-frown-o"></i>&nbsp;<span
                                    class="dislikecount">{%=zeroReplace(o.dislikecount,'{-"Dislike"|translate}')%}</span>
                        </button>
                    </div>
                    <div class="btn-group">
                        {-*<button class="btn btn-default unlike {% if (o.liked == null) { %}hidden {% } %}tooltip-trigger" data-postid="{%=o.refid%}" data-title="{-"Remove all your likes"|translate}"><i class="fa fa-meh-o"></i>&nbsp;{-"Unlike"|translate}</span></button>*}
                        <button class="btn btn-default comment tooltip-trigger"
                                data-title="{%=o.commentcount%}&nbsp;{-"Comments"|translate}"><i
                                    class="fa fa-comment-o"></i>&nbsp;<span
                                    class="commentcount">{%=zeroReplace(o.commentcount,'{-"Comment"|translate}')%}</span>
                        </button>
                    </div>
                    {% if (o.userid == {-$user.userid} || o.owner_id == {-$user.userid}) { %}
                    <div class="btn-group">
                        <ul class="dropdown-menu newsfeed-post-info-dropdown" role="menu"
                            aria-labelledby="dropdownMenu1">
                            <li><a href="javascript:deletePost({%=o.id%});" class="deletepost"><i
                                            class="fa fa-trash-o fa-fw"></i>&nbsp;{-"Delete post"|translate}</a></li>
                        </ul>
                        <button class="options btn btn-default" data-toggle="dropdown" data-postid="{%=o.id%}"><i
                                    class="fa fa-cogs"></i>{-*&nbsp;{-"Options"|translate}*}</button>
                    </div>
                    {% } %}
                </div>
            </div>
        </div>
        <div class="newsfeed-post-detail hidden" id="newsfeed-post-detail-{%=o.id%}">
            <div class="newsfeed-post-detail-likebox hidden clearfix">
                <div class="newsfeed-post-detail-likes pull-left">
                    <a href="javascript:getLikes('{%=o.type%}',{%=o.refid%},0,'{-"Likes"|translate}');"><i
                                class="fa fa-smile-o"></i></a>

                    <div id="likes-{%=o.id%}" class="clearfix">
                        <div></div>
                        <span class="label label-danger">{-"No Likes yet"|translate}</span></div>
                </div>
                <div class="newsfeed-post-detail-dislikes pull-right">
                    <a href="javascript:getLikes('{%=o.type%}',{%=o.refid%},1,'{-"Dislikes"|translate}');"><i
                                class="fa fa-frown-o"></i></a>

                    <div id="dislikes-{%=o.id%}" class="clearfix">
                        <div></div>
                        <span class="label label-danger">{-"No Dislikes yet"|translate}</span></div>
                </div>
            </div>
            <div class="newsfeed-post-detail-commentbox">
                <div class="post-comment clearfix">
                    <img src="{-$user.pimg|image:"user":"cr_"}" class="avatar img-rounded pull-left hidden-xs">

                    <div class="content-box pull-left" style="padding-bottom:0">
                        <textarea class="form-control" id="commentbox-{%=o.id%}"
                                  placeholder="{-"Leave a comment!"|translate}" data-type="{%=o.type%}"
                                  data-refid="{%=o.refid%}" data-postid="{%=o.id%}"></textarea>
                        <button class="btn btn-primary btn-xs pull-right sendbutton hidden" data-postid="{%=o.id%}"
                                style="margin-top:5px">{-"Comment"|translate}</button>
                    </div>
                </div>
                <div id="comments-{%=o.id%}"></div>
                <button class="btn btn-xs btn-default btn-block morecomments" data-refid="{%=o.refid%}"
                        data-postid="{%=o.id%}" data-type="{%=o.type%}" id="comments-more-{%=o.id%}"><i
                            class="fa fa-clock-o"></i>&nbsp;{-"Load more comments"|translate}</button>
            </div>
        </div>
        <i class="fa fa-chevron-up hidden newsfeed-post-close-detail" data-postid="{%=o.id%}"></i>
    </div>
</script>
<script id="newsfeed-comment" type="text/html">
    <div class="post-comment clearfix" data-id="{%=o.id%}" id="comment-{%=o.id%}">
        <img src="{%=checkImage(o.filename,'user','cr_')%}" class="avatar pull-left">

        <div class="content-box pull-left">
            {% if (o.userid == {-$user.userid} || o.ref.owner_id == {-$user.userid} || o.ref.userid == {-$user.userid}) { %}
            <a href="javascript:deleteComment({%=o.id%});" class="close tooltip-trigger"
               title="{-"Delete this comment"|translate}">&times</a>
            {% } %}
            <a href="{-"index.php?m=profile&action="|URL}{%=o.username%}">{%=o.name%}</a>
            <span class="message">{%#o.content%}</span>

            <div class="time">{%#convertDate(o.time)%}</div>
        </div>
    </div>
</script>
<script id="newsfeed-video" type="text/html">
    <img src="{%=o.media$group.media$thumbnail[1].url%}" class="newsfeed-post-video-thumbnail thumbnail">
    <div>
        <a href="{%=o.link[0].href%}" class="newsfeed-post-video-link-title">{%=o.title.$t%}</a>
        <br>
        <small><i class="text-muted">www.youtube.com</i></small>
        <p>{%=o.media$group.media$description.$t.substring(0,150)%} [...]</p>
    </div>
</script>
{-include file="Gallery/styles/lightbox.tpl"}