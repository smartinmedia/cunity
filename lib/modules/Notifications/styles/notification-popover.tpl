<div class="popover right notification-popover hidden">
    <div class="arrow"></div>
    <h3 class="popover-title">
        {-"Notifications"|translate}&nbsp;<span class="badge notification-count">123</span>
    </h3>

    <div class="popover-content hidden" id="notification-results"></div>
    <div class="popover-content notification-results-alert hidden">
        <div class="alert alert-block alert-danger"><p>{-"No notifications"|translate}</p></div>
    </div>
    <div class="popover-content block-loader loader notification-popover-loader"></div>
</div>

<script type="text/html" id="notification-item">
    <div class="notification-item media media-condensed" data-id="{%=o.id%}">
        <a class="pull-left" href="{-"index.php?m=profile&action="|URL}{%=o.username%}">
            <img alt="{%=o.name%}" src="{%=checkImage(o.pimg,'user','cr_')%}" class="img-rounded media-object">
        </a>

        <div class="media-body">
            <h4 class="media-heading"><a href="{%=o.target%}">{%=o.name%}</a></h4>{% if (o.unread == 1) { %}<span
                class="label label-new label-primary">{-"new"|translate}</span>{% } %}
            <span class="media-body-time">{%#convertDate(o.time)%}</span>
            <span class="notification-item-message">{%=o.message%}</span>
        </div>
    </div>
</script>