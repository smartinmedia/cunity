<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{-"Messages"|translate}</h1>
        <ol class="breadcrumb">
            <li><a href="{-"core.siteurl"|setting}admin"><i class="fa fa-home"></i></a></li>
            <li class="active">{-"Mailing"|translate}</li>
            <li class="active">{-"Messages"|translate}</li>
        </ol>
    </div>
</div>
<form class="ajaxform form-validate" data-bv-excluded=""
      action="{-"index.php?m=admin&action=manage"|URL}" id="manageform" method="POST">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title"><i class="fa fa-check-square-o"></i>&nbsp;{-"Your messages"|translate}</h4>
                </div>
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th width="30px">#</th>
                        <th>{-"Time"|translate}</th>
                        <th>{-"User"|translate}</th>
                        <th>{-"Firstname"|translate}</th>
                        <th>{-"Lastname"|translate}</th>
                        <th>{-"E-Mail"|translate}</th>
                        <th>{-"Subject"|translate}</th>
                        <th>{-"Message"|translate}</th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody id="moduletable">
                    {-foreach $messages as $i => $message}
                        <tr>
                            <td>{-$i + 1}</td>
                            <td>{-$message.timestamp}</td>
                            <td>{-$message.userid}</td>
                            <td>{-$message.firstname}</td>
                            <td>{-$message.lastname}</td>
                            <td>{-$message.email}</td>
                            <td>{-$message.subject}</td>
                            <td>{-$message.message}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button"
                                            class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown">{-"Actions"|translate}&nbsp;<span
                                                class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="javascript: deleteMessage({-$message.contact_id});"><i
                                                        class="fa fa-pencil"></i>&nbsp;{-"Delete message"|translate}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    {-/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>
