<div class="page-buttonbar clearfix">
    <h1 class="page-header pull-left">{-"Events"|translate}</h1>
    <button class="pull-right btn btn-primary" data-toggle="modal" data-target="#createEvent"><i class="fa fa-plus"></i>&nbsp;{-"Create Event"|translate}
    </button>
    <button class="pull-right btn btn-primary" data-calendar-nav="today" id="today">{-"Today"|translate}</button>
    <div class="btn-group pull-right" data-toggle="buttons">
        <label class="btn btn-default" data-calendar-view="list"><input type="radio" name="events_view"
                                                                        id="eventsViewList"><i
                    class="fa fa-list"></i>&nbsp;{-"List"|translate}</label>
        <label class="btn btn-default active" data-calendar-view="calendar"><input type="radio" name="events_view"
                                                                                   id="eventsViewCalendar"><i
                    class="fa fa-calendar"></i>&nbsp;{-"Calendar"|translate}</label>
    </div>
</div>
<div class="calendar-head clearfix">
    <button class="btn btn-primary pull-left" data-calendar-nav="prev" id="prev"><i class="fa fa-chevron-left"></i><span
                class="visible-md">&nbsp;{-"Prev"|translate}</span></button>
    <h2 class="calendar-month pull-left">April 2014</h2>
    <button class="btn btn-primary pull-right" data-calendar-nav="next" id="next"><span
                class="visible-md">{-"Next"|translate}
            &nbsp;</span><i class="fa fa-chevron-right"></i></button>
</div>
<div id="calendar" class="calendar-view"></div>
<div id="list" class="hidden calendar-view">
    <div class="list"></div>
    <div class="alert alert-block alert-danger">{-"No Events planned in this month!"|translate}</div>
</div>
<div class="modal fade" id="createEvent" tabindex="-1" role="dialog" aria-labelledby="createEvent" aria-hidden="true">
    <form id="createEventForm" class="form-horizontal ajaxform bv-form" role="form"
          action="{-"index.php?m=events&action=createEvent"|URL}" method="post">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">{-"Create a new Event"|translate}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="event-title" class="col-sm-2 control-label">{-"Title"|translate}*</label>

                        <div class="col-sm-10">
                            <input type="text" name="title" id="event-title" class="form-control" required="required"
                                   placeholder="{-"e.g. Your Birthdayparty"|translate}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="event-description" class="col-sm-2 control-label">{-"Details"|translate}</label>

                        <div class="col-sm-10">
                            <textarea class="form-control" id="event-description" name="description"
                                      placeholder="{-"Add more Information"|translate}"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="event-place" class="col-sm-2 control-label">{-"Place"|translate}</label>

                        <div class="col-sm-10">
                            <textarea class="form-control" id="event-place" name="place"
                                      placeholder="{-"Where will your event take place?"|translate}"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="event-place" class="col-sm-2 control-label">{-"Date"|translate}*</label>

                        <div class="col-sm-5">
                            <div class="input-group date" id="datepicker">
                                <input type="text" class="form-control" placeholder="{-"Select a date"|translate}"
                                       required="required" name="date" readonly="readonly"><span
                                        class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                            <input type="hidden" id="startDate" name="start">
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group time">
                                <input type="text" class="form-control" placeholder="{-"Select a time"|translate}"
                                       required="required" name="time"><span
                                        class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="event-privacy" class="col-sm-2 control-label">{-"Privacy"|translate}</label>

                        <div class="col-sm-10">
                            <select id="event-privacy" name="privacy" class="form-control">
                                <option value="0">{-"Public"|translate}</option>
                                <option value="1">{-"Friends of Guests"|translate}</option>
                                <option value="2">{-"Only Invited Users"|translate}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="guest_invitation"
                                           value="1">&nbsp;{-"Guests can invite other users"|translate}
                                </label>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="ajaxform-callback" value="eventCreated">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{-"Close"|translate}</button>
                    <button type="submit" class="btn btn-primary"
                            onclick="$('#createEventForm').submit();">{-"Create"|translate}</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script id="list-event" type="text/html">
    <div class="panel panel-default">
        <div class="panel-heading">
            {%=o.date.format("L")%}
        </div>
        <ul class="list-group">
            {% for (var i=0; i
            <o.events.length
                    ; i++) { %}
                    {% if (o.events[i].type !='birthday' ) { %}
            <li class="list-group-item"><a
                        href="{%# convertUrl({module:'events',action:o.events[i].id+'-'+o.events[i].title.replace(new RegExp(' ','g'),'_')}) %}"><b>{%=o.events[i].date.format("LT")%}</b>&nbsp;{%=o.events[i].title%}
                </a></li>
            {% } %}
            {% } %}
        </ul>
        <div class="panel-body birthday-panel-body">
            <ul class="list-inline list-unstyled birthday-event-list">{% for (var i=0; i
                <o.events.length
                        ; i++) { %}{% if (o.events[i].type==
                'birthday') { %}
                <li><img src="{%=checkImage(o.events[i].pimg,'user','cr_')%}"
                         class="tooltip-trigger event-list-birthday thumbnail"
                         title="{-"Birthday of"|translate}&nbsp;{%=o.events[i].name%}"></li>
                {% } %}{% } %}
            </ul>
        </div>
    </div>
</script>