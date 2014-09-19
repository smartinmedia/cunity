<input type="hidden" id="profile-userid" value="{-$user.userid}">
<div class="profile-banner" style="background-image: url('cr_{-$profile.timg}');">
    {-if $type eq "profile"}
    <div class="profile-banner-image"><img src="{-$result.filename}"></div>
    {-else}
    <div class="profile-banner-image"><img src="cr_{-$profile.pimg}"></div>
    {-/if}
    <div class="profile-banner-namebox hidden-sm hidden-xs">
        <h1 class="profile-banner-name">{-$profile.name}</h1>
        {-if !$profile.name eq ""}<h2 class="profile-banner-username">( {-$profile.username} )</h2>{-/if}
    </div>
    {-if $type eq "title"}
    <img src="{-$result.filename}" class="profile-banner-preview-image">
    {-/if}
</div>
<div style="padding:10px;margin-top: 200px;">
    <div id="editProfileImages">
        <h1 class="page-header">{-"Crop the image"|translate}</h1>
        {-if $type eq "profile"}
        <p><img src="{-$result.filename}" id="profileimage" width="50%" style="z-index:1"></p>
        {-else}
        <p><img src="{-$result.filename}" id="titleimage" width="90%" style="z-index:1"></p>
        {-/if}
        <form action="{-"index.php?m=profile&action=edit"|URL}" method="post" class="form-horizontal"
              enctype="multipart/form-data">
            <input type="hidden" name="type" value="{-$type}">
            <input type="hidden" name="imageid" value="{-$result.id}">
            <input type="hidden" name="crop-image" value="../data/uploads/{-"core.filesdir"|setting}/{-$result.filename}">
            <input type="hidden" name="edit" value="crop">
            <input type="hidden" name="crop-x" value="" id="crop-x">
            <input type="hidden" name="crop-y" value="" id="crop-y">
            <input type="hidden" name="crop-x1" value="" id="crop-x1">
            <input type="hidden" name="crop-y1" value="" id="crop-y1">
            <input type="hidden" name="img-width" value="{-$image[0]}" id="img-width">
            <input type="hidden" name="img-height" value="{-$image[1]}" id="img-height">
            <button type="submit" class="btn btn-primary">{-"Save"|translate}</button>
        </form>
    </div>
</div>