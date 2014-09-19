<div class="event-banner">
    <img src="{-$result.filename}" class="event-banner-preview-image">

    <div class="event-title-box clearfix">
        <div class="event-title-box-date pull-left" title="{-$event.date->format('d.m.Y')}">
            <span class="month">{-$event.date->format('M')}</span>
            <span class="day">{-$event.date->format('d')}</span>
        </div>
        <h1 class="pull-left">{-$event.title}</h1>
    </div>
</div>
<ul class="nav nav-pills event-menu" id="event-menu">
    <li class="active"><a href="#Crop" data-toggle="pill"><i class="fa fa-picture-o"></i>&nbsp;{-"Crop Image"|translate}
        </a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade in active" id="Crop" style="padding:10px;">
        <h1 class="page-header">{-"Crop the Title-Image"|translate}</h1>

        <p><img src="{-$result.filename}" id="titleimage" width="90%" style="z-index:1"></p>

        <form action="{-"index.php?m=events&action=crop"|URL}" method="post" class="form-horizontal"
              enctype="multipart/form-data">
            <input type="hidden" name="imageid" value="{-$result.id}">
            <input type="hidden" name="eventid" value="{-$eventid}">
            <input type="hidden" name="crop-image" value="../data/uploads/{-"core.filesdir"|setting}/{-$result.filename}">
            <input type="hidden" name="edit" value="crop">
            <input type="hidden" name="crop-x" value="" id="crop-x">
            <input type="hidden" name="crop-y" value="" id="crop-y">
            <input type="hidden" name="crop-x1" value="" id="crop-x1">
            <input type="hidden" name="crop-y1" value="" id="crop-y1">
            <input type="hidden" name="img-width" value="{-$image[0]}" id="img-width">
            <input type="hidden" name="img-height" value="{-$image[1]}" id="img-height">
            <button type="submit" class="btn btn-primary" id="eventCropImageButton">{-"Save"|translate}</button>
        </form>
    </div>
</div>