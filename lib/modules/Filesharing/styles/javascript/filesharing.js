$(document).ready(function () {
    $('#fileSearch').keyup(function () {
        var searchText = $('#fileSearch').val();

        $('.searchresult-item').each(function(index, element) {
            if ($('#'+element.id).find('.fileTitle')[0].innerHTML.indexOf(searchText) == -1 &&
                $('#'+element.id).find('.fileDescription')[0].innerHTML.indexOf(searchText) == -1)
            {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
    });

    $(document).on("click", ".deleteFile", function () {
        var id = $(this).data("fileid"), msg = $(this).data("msg");
        bootbox.confirm(msg, function (result) {
            if (result) {
                sendRequest({fileid: id}, "filesharing", "delete", function (res) {
                    $("#filesharing-item-" + id).remove();
                    if (scrollApi !== null)
                        scrollApi.reinitialise();
                });
            }
        });
    });

    $("#newfiles_modal input[name='privacy']").change(function () {
        if ($("#newfiles_shared").is(":checked"))
            $("#newfiles_shared_options").show();
        else
            $("#newfiles_shared_options").hide();
    });

    $('#friendselector').select2().on('select2-selecting', function () {
        $('#friendCheckbox').attr('checked', false);
        $('#friendCheckboxLabel').removeClass('active').addClass('disabled') ;
    }).on("select2-removed", function () {
        if ($('#friendselector').select2('data').length === 0) {
            $('#friendCheckbox').click();
            $('#friendCheckboxLabel').removeClass('disabled');
        }
    });

    loadFiles();
});

function loadFiles() {
    $(".filesharing-list").html("");
    $("#fileslist > .alert").hide();
    $(".filesharing-loader").show();
    sendRequest({"userid": userid}, "filesharing", "listfiles", function (data) {
        $(".filesharing-loader").hide();
        if (data.result !== null)
            for (x in data.result) {
                $(".filesharing-list").append(tmpl("files-template", data.result[x]));
            }
        if (data.result.length === 0) {
            $("#fileslist > .alert").show();
        }
    });
}

function checkSize(max_img_size)
{
    var input = document.getElementById("fileone");
    // check for browser support (may need to be modified)
    if(input.files && input.files.length == 1)
    {
        if (input.files[0].size > max_img_size)
        {
            alert("The file must be less than " + Math.ceil(max_img_size/1024/1024) + "MB");
            $("input[type='submit'],button[type='submit']").button('reset');
            return false;
        } else {
            return true;
        }
    }

    return false;
}
