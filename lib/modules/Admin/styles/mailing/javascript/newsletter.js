$(document).ready(function() {
    $('#test').click(function() {
        $('#type').val('test');
    });

    $('#send').click(function() {
        $('#type').val('live');
    });
});

function addNewsletter(res) {
    if ($('#type').val() == 'live') {
        $('#addnewslettermodal').modal('hide');
        $('#addnewslettermodal').on('hidden.bs.modal', function (e) {
            loadPage('mailing', 'newsletter');
        })
    }
}
