var currentDate = "NOW", currentTime = "", calendar = null, calendarview = "calendar";
$(document).ready(function () {
    $("#createEvent").on("shown.bs.modal", function () {
        $('#createEvent .input-group.date').datepicker({
            startDate: "now",
            autoclose: true,
            todayHighlight: true
        });
        $("#createEvent .input-group.time > input[type='text']").timepicker({
            template: false,
            showInputs: false,
            showMeridian: false
        });
    });
    $("#createEvent .input-group.date").on("changeDate", function (e) {
        currentDate = e.format(0, "yyyy-mm-dd");
        $("#startDate").val(currentDate + ' ' + currentTime);
    });
    $("#createEvent .input-group.time > input[type='text']").on("changeTime.timepicker", function (e) {
        currentTime = e.time.hours + ':' + e.time.minutes + ':00';
        $("#startDate").val(currentDate + ' ' + currentTime);
    });


    $('button[data-calendar-nav]').each(function () {
        var $this = $(this);
        $this.click(function () {
            calendar.navigate($this.data('calendar-nav'));
            showCalendar();
        });

    });
    $(".cal-month-day,.cal-month-day-has-event span[data-cal-date]").unbind();
    $("label[data-calendar-view]").click(function () {
        $(".calendar-view").hide();
        $("#" + $(this).data("calendar-view")).show();
        calendarview = $(this).data("calendar-view");
        showCalendar();
    });
    calendar = $("#calendar").calendar({
        tmpl_path: siteurl + "lib/modules/Events/styles/tmpls/",
        events_source: convertUrl({module: "events", action: "loadEvents"}),
        onAfterViewLoad: function () {
            $('.calendar-month').text(this.getTitle());
            refreshTooltip();
        }
    });

    if (window.innerWidth < 768) {
        $(".calendar-view").hide();
        $("#list").show();
        showCalendar();
    } else
        showCalendar();
});

function showCalendar() {
    if (window.innerWidth < 768)
        calendarview = "list";
    if (calendarview == "calendar") {
        if (calendar == null) {

        }
    } else if (calendarview == "list") {
        var events = calendar.getEventsBetween(calendar.getStartDate(), calendar.getEndDate());
        $("#list > div.list").empty();
        var currentDay = new Object();
        currentDay.events = new Array();
        for (x = 0; x <= events.length; x++) {
            if (x < events.length)
                var d = moment(Number(events[x].start));
            if ((x == events.length || (typeof currentDay.date != "undefined" && d.date() != currentDay.date.date())) && currentDay.events.length > 0) {
                $("#list > div.list").append(tmpl("list-event", currentDay));
                currentDay.events = new Array();
            }
            if (typeof events[x] !== "undefined") {
                currentDay.date = moment(d);
                events[x].date = currentDay.date;
                currentDay.events.push(events[x]);
                console.log(currentDay);
            }
        }
        if ($("#list > div.list > div").length == 0)
            $("#list > div.alert").show();
        else
            $("#list > div.alert").hide();
        refreshTooltip();
    }
}

function eventCreated(res) {
    location.href=convertUrl({module:"events",action:res.id});
}