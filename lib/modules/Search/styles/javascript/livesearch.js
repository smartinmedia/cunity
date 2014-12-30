var ls_xhr = null;
$(document).ready(function () {
    var input = $("#searchinputfield"), of = input.offset();
    $(".livesearch-popover").css({top: of.top - 35, left: of.left + 195});
    $(document).on("keyup", input, function (e) {
        code = (e.keyCode ? e.keyCode : e.which);
        if (input.val().length > 1 && code !== 13) {
            if (ls_xhr && ls_xhr.readyState !== 4)
                ls_xhr.abort();
            $(".livesearch-popover .queryString").html(input.val());
            $(".livesearch-popover-alert,.livesearch-results-alert").hide();
            $("#livesearch-results").html("");
            $(".livesearch-popover,.livesearch-popover-loader").show();
            ls_xhr = sendRequest({"q": input.val()}, "search", "livesearch", function (res) {
                for (x in res.users) {
                    if (res.users[x].privacy.search === 3 || (res.users[x].privacy.search == 1 && res.users[x].status > 1)) {
                        $("#livesearch-results").append(tmpl("livesearch-result", res.users[x]));
                    }
                }
                $("#livesearch-results").show();
                $(".livesearch-popover-loader").hide();
                if ($("#livesearch-results > .searchresult-item").length === 0)
                    $(".livesearch-results-alert").show();
                else
                    $(".livesearch-results-alert").hide();
            });
        } else
            $(".livesearch-popover").hide();
    });
    $(document).click(function (e) {
        if (!$(".livesearch-popover").is(e.target) && $(".livesearch-popover").has(e.target).length === 0)
            $(".livesearch-popover").hide();
    });
});