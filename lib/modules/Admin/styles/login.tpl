<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login - Cunity Administration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link href="{-"core.siteurl"|setting}lib/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{-"core.siteurl"|setting}lib/plugins/bootstrap-validator/css/bootstrapValidator.min.css"
          rel="stylesheet">
    <link href="{-"core.siteurl"|setting}lib/modules/Admin/styles/css/login.css" rel="stylesheet">
    <link href="{-"core.siteurl"|setting}lib/plugins/fontawesome/css/font-awesome.css" rel="stylesheet">
    <script src="{-"core.siteurl"|setting}lib/plugins/js/jquery.min.js" type="text/javascript"></script>
    <script src="{-"core.siteurl"|setting}lib/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="{-"core.siteurl"|setting}lib/plugins/bootstrap-validator/js/bootstrapValidator.min.js"
            type="text/javascript"></script>
    <script src="{-"core.siteurl"|setting}lib/modules/Core/styles/javascript/cunity-core.js"
            type="text/javascript"></script>
    <!--[if lt IE 9]>
    <script src="{-" core.siteurl"|setting}lib/plugins/html5/js/html5.js" type="text/javascript"></script>
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="{-"core.siteurl"|setting}style/CunityRefreshed/img/cunity-logo.gif">
    </div>
    <!-- /logo -->
    <div class="login-container">
        <p>{-"Please confirm your login to enter the admin-panel!"|translate}</p>
        {-if !$message eq ""}
            <div class="alert alert-block alert-danger">{-$message}</div>
        {-/if}
        <form action="{-"index.php?m=admin&action=login"|URL}" method="post" class="form-horizontal">
            <div class="form-group">
                <label class="control-label sr-only" for="email">Your Name</label>

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                    <input type="text" id="email" name="email" placeholder="Email" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label sr-only" for="password">Password</label>

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-key"></i></span>
                    <input type="password" id="password" name="password" placeholder="{-"Password"|translate}"
                           class="form-control">
                </div>
            </div>
            <div class="form-group">
                <button class="btn btn-primary btn-large pull-right">{-"Sign In"|translate}</button>
            </div>
            <!-- .actions -->
        </form>
    </div>
    <!-- /account-container -->
    <!-- Text Under Box -->
    <div class="login-extra">
        <a href="{-"core.siteurl"|setting}"><i
                    class="fa fa-angle-double-left"></i>&nbsp;{-"Back to your Cunity"|translate}
        </a>
    </div>
    <!-- /login-extra -->
</div>
</body>
</html>