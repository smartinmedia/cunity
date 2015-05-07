<div class="event-banner{-if $event.filename eq NULL} event-banner-empty{-/if}"
     style="background-image:url('{-"core.siteurl"|setting}data/uploads/{-"core.filesdir"|setting}/cr_{-$event.filename}');">
    <div class="event-title-box clearfix">
        <div class="event-title-box-date pull-left" title="{-$event.date->format('d.m.Y')}">
            <span class="month">{-$event.date->format('M')}</span>
            <span class="day">{-$event.date->format('d')}</span>
        </div>
        <h1 class="pull-left">{-$event.title}</h1>
    </div>
</div>
<ul class="nav nav-pills event-menu" id="event-menu">
    <li><a href="{-"index.php?m=events&action={-$event.id}"|URL}" class="tab-no-follow"><i
                    class="fa fa-chevron-left"></i>&nbsp;{-"Back"|translate}</a></li>
    <li class="active"><a href="#Info" data-toggle="pill"><i class="fa fa-wrench"></i>&nbsp;{-"Edit"|translate}</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane fade in clearfix active" id="Info">
        <div class="col-md-3">
            <div class="list-group" id="event-sub-menu">
                <a href="#editInfo" class="list-group-item active" data-toggle="tab">{-"Info"|translate}</a>
                <a href="#editPhoto" class="list-group-item" data-toggle="tab">{-"Photo"|translate}</a>
            </div>
        </div>
        <div class="col-md-9 tab-content section-content">
            <div class="tab-pane active panel panel-default" id="editInfo">
                <div class="panel-heading">
                    <h3 class="panel-title">{-"Edit Information"|translate}</h3>
                </div>
                <div class="panel-body">
                    <div class="alert alert-block hidden" id="infoalert"></div>
                    <form action="{-"index.php?m=events&action=edit&x={-$event.id}"|URL}" method="post"
                          class="form-horizontal ajaxform">
                        <input type="hidden" name="edit" value="info">

                        <div class="form-group">
                            <label class="control-label col-md-3">{-"Title"|translate}</label>

                            <div class="col-md-9">
                                <input type="text" name="title" class="form-control" value="{-$event.title}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">{-"Description"|translate}</label>

                            <div class="col-md-9">
                                <textarea name="description" class="form-control"
                                          rows="6">{-$event.description}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">{-"Place"|translate}</label>

                            <div class="col-md-9">
                                <input type="text" name="place" class="form-control" value="{-$event.place}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">{-"Date"|translate}</label>

                            <div class="col-md-5">
                                <div class="input-group date">
                                    <input type="text" class="form-control"
                                           placeholder="{-"Select a date"|translate}" readonly="readonly" value="{-$event.date|date_format:"%d/%m/%Y"}" name="date"><span
                                            class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group time">
                                    <input type="text" class="form-control" name="time"
                                           placeholder="{-"Select a time"|translate}" value="{-$event.date|date_format:"%H:%M"}"><span class="input-group-addon"><i
                                                class="fa fa-clock-o"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">{-"Privacy"|translate}</label>

                            <div class="col-md-9">
                                <select id="event-privacy" name="privacy" class="form-control">
                                    <option value="0" {-if $event.privacy == "0" }selected="selected"{-/if}>{-"Public"|translate}</option>
                                    <option value="1" {-if $event.privacy == "1" }selected="selected"{-/if}>{-"Friends of Guests"|translate}</option>
                                    <option value="2" {-if $event.privacy == "2" }selected="selected"{-/if}>{-"Only Invited Users"|translate}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-9">
                                <div class="checkbox">
                                    <label>
                                        <input type="hidden" name="guest_invitation" value="0" />
                                        <input type="checkbox" name="guest_invitation" {-if $event.guest_invitation == "1" }checked="checked"{-/if}
                                               value="1">&nbsp;{-"Guests can invite friends"|translate}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-9 clearfix">
                                <input class="ajaxform-callback" value="infoChanged" type="hidden">
                                <button type="submit" class="btn btn-primary pull-right" id="eventsSaveInfoButton"><i
                                            class="fa fa-save"></i> {-"Save"|translate}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane panel panel-default" id="editPhoto">
                <div class="panel-heading">
                    <h3 class="panel-title">{-"Change Photo"|translate}</h3>
                </div>
                <div class="panel-body">
                    <div class="alert alert-block hidden" id="photoalert"></div>
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <label class="control-label col-md-3">{-"Title-Image"|translate}</label>

                            <div class="col-md-9">
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" id="title-upload" type="button"><i
                                                    class="fa fa-search"></i>&nbsp;{-"Select Photo"|translate}</button>
                                    </span>
                                    <input type="text" class="form-control" readonly id="selected-title-file">
                                    <input type="hidden" id="upload_limit" value="{-$upload_limit}">
                                    <input type="hidden" id="eventid" value="{-$event.id}">
                                </div>
                                <span class="help-block">{-"You can upload jpg, gif and png files with a maximum file-size of"|translate}
                                    &nbsp;{-$upload_limit}</span>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="col-md-9">
                                <button type="button" id="eventsDeleteImageButton"
                                        class="btn btn-link text-danger image-delete" data-type="profile">
                                    <i class="fa fa-trash-o"></i>&nbsp;{-"Delete current Event-Image"|translate}
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" id="eventsStartImageUploadButton"
                                        class="btn btn-primary pull-right" onclick="uploadPhoto();"
                                        data-loading-text="{-"Uploading"|translate}..."><i
                                            class="fa fa-upload"></i> {-"Upload"|translate}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>