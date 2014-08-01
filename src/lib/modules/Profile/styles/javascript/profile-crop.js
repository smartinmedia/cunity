function previewTitle(img, selection) {
    if (!selection.width || !selection.height)
        return;

    var scaleX = 970 / selection.width;
    var scaleY = 200 / selection.height;

    $('.profile-banner-preview-image').css({
        width: Math.round(scaleX * $("#titleimage").width()),
        height: Math.round(scaleY * $("#titleimage").height()),
        left: -Math.round(scaleX * selection.x1),
        top: -Math.round(scaleY * selection.y1)
    });

    $('#crop-x').val(selection.x1 * ($("#img-width").val() / img.width));
    $('#crop-y').val(selection.y1 * ($("#img-height").val() / img.height));
    $('#crop-x1').val(selection.x2 * ($("#img-width").val() / img.width));
    $('#crop-y1').val(selection.y2 * ($("#img-height").val() / img.height));
}
function previewProfile(img, selection) {
    if (!selection.width || !selection.height)
        return;

    var scaleX = 150 / selection.width;
    var scaleY = 150 / selection.height;

    $('.profile-banner-image img').css({
        width: Math.round(scaleX * $("#profileimage").width()),
        height: Math.round(scaleY * $("#profileimage").height()),
        marginLeft: -Math.round(scaleX * selection.x1),
        marginTop: -Math.round(scaleY * selection.y1)
    });

    $('#crop-x').val(selection.x1 * ($("#img-width").val() / img.width));
    $('#crop-y').val(selection.y1 * ($("#img-height").val() / img.height));
    $('#crop-x1').val(selection.x2 * ($("#img-width").val() / img.width));
    $('#crop-y1').val(selection.y2 * ($("#img-height").val() / img.height));
}

$(document).ready(function () {
    $('#titleimage').imgAreaSelect({
        aspectRatio: '4.85:1',
        handles: true,
        onSelectChange: previewTitle,
        x1: 0,
        y1: 0,
        x2: (970 / ($("#img-width").val() / $('#titleimage').width())),
        y2: (970 / ($("#img-width").val() / $('#titleimage').width())) / 4.84
    });
    $('#profileimage').imgAreaSelect({
        aspectRatio: '1:1',
        handles: true,
        fadeSpeed: 200,
        onSelectChange: previewProfile,
        x1: $('#profileimage').width() / 4,
        y1: $('#profileimage').width() / 4,
        x2: ($('#profileimage').width() / 4) * 3,
        y2: ($('#profileimage').width() / 4) * 3
    });
});
