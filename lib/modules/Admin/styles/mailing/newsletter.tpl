<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Newsletter"|translate}
            <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#addnewslettermodal"><i
                        class="fa fa-plus"></i>&nbsp;{-"Add Newsletter"|translate}</button>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Mailing"|translate}</li>
            <li class="active">{-"Newsletter"|translate}</li>
        </ol>
    </div>
</div>
<form class="ajaxform form-validate" data-bv-excluded=""
      action="{-"index.php?m=admin&action=manage"|URL}" id="manageform" method="POST">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-check-square-o"></i>&nbsp;{-"Newsletter"|translate}</h4>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th width="30px">#</th>
                        <th width="160px">{-"Time"|translate}</th>
                        <th width="200px">{-"Subject"|translate}</th>
                        <th>{-"Message"|translate}</th>
                    </tr>
                    </thead>
                    <tbody id="moduletable">
                    {-foreach $newsletters as $i => $newsletter}
                        <tr>
                            <td>{-$i + 1}</td>
                            <td>{-$newsletter.time}</td>
                            <td>{-$newsletter.subject}</td>
                            <td>{-$newsletter.message}</td>
                        </tr>
                    {-/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
<div class="modal fade" id="addnewslettermodal" tabindex="-1" role="dialog" aria-labelledby="addnewslettermodal"
     aria-hidden="true">
    <form class="login-form form-horizontal ajaxform" action="{-"index.php?m=admin&action=insert"|URL}"
          style="margin:10px;" name="users">
        <input type="hidden" name="action" value="insert">
        <input type="hidden" class="ajaxform-callback" value="addNewsletter">
        <input type="hidden" name="form" value="newsletter">

        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">{-"Add Newsletter"|translate}</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="subject"
                               class="col-sm-4 control-label">{-"Subject"|translate}*</label>

                        <div class="col-sm-8">
                            <input type="text" class="form-control" value="" id="subject"
                                   name="subject" required data-bv-stringlength data-bv-stringlength-min="3"
                                   data-bv-stringlength-message="{-"Subject is too short (min. 3 chars)"|translate}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="message"
                               class="col-sm-4 control-label">{-"Message"|translate}*</label>

                        <div class="col-sm-8">
                            <textarea rows="10" class="form-control" id="message"
                                      name="message" required data-bv-stringlength data-bv-stringlength-min="3"
                                      data-bv-stringlength-message="{-"Message is too short (min. 3 chars)"|translate}"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-bb-handler="confirm" class="btn btn-default" data-dismiss="modal">Close
                    </button>
                    <input type="hidden" name="type" value="test" id="type" />
                    <button type="submit" class="btn btn-primary" name="submitButton" id="test">{-"Send test mail"|translate}</button>
                    <button type="submit" class="btn btn-primary" name="submitButton" id="send">{-"Send to users"|translate}</button>
                </div>
            </div>
        </div>
    </form>
</div>
