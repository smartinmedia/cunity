<h1 class="page-header">{-"Reset Your password"|translate}</h1>
{-if $success}
<div class="alert alert-block alert-success"><h4 class="alert-heading">{-"Done!"|translate}</h4>

    <p>{-"You've changed your password successfully! You can now login"|translate}</p></div>
{-else}
<form class="form-horizontal reset-form" action="{-"index.php?m=register&action=reset"|URL}" method="POST"
      onsubmit="return checkRegistration();">
    <div class="form-group {-if !empty($error_messages.email)}has-feedback has-error{-/if}">
        <label class="control-label col-lg-4" for="input-email">{-"Confirm your email"|translate}</label>

        <div class="col-lg-6">
            <input type="text" class="form-control" id="input-email" name="email" value="{-$values.email}">
            <span class="fa fa-exclamation-triangle form-control-feedback"></span>
            <span class="help-block">{-$error_messages.email}</span>
        </div>
    </div>
    <div class="form-group {-if !empty($error_messages.token)}has-feedback has-error{-/if}">
        <label class="control-label col-lg-4" for="input-token">{-"Password-Token"|translate}</label>

        <div class="col-lg-6">
            <input type="number" class="form-control" id="input-token" name="token"
                   value="{-if $smarty.get.x eq ""}{-$values.token}{-else}{-$smarty.get.x}{-/if}">
            <span class="fa fa-exclamation-triangle form-control-feedback"></span>
            <span class="help-block">{-$error_messages.token}</span>
        </div>
    </div>
    <div class="form-group {-if !empty($error_messages.password)}has-feedback has-error{-/if}">
        <label class="control-label col-lg-4" for="input-password">{-"New password"|translate}</label>

        <div class="col-lg-6">
            <input type="password" class="form-control" id="input-password" name="password">
            <span class="fa fa-exclamation-triangle form-control-feedback"></span>
            <span class="help-block">{-$error_messages.password}</span>
        </div>
    </div>
    <div class="form-group {-if !empty($error_messages.password)}has-feedback has-error{-/if}">
        <label class="control-label col-lg-4" for="input-password_repeat">{-"Repeat new password"|translate}</label>

        <div class="col-lg-6">
            <input type="password" class="form-control" id="input-password_repeat" name="password_repeat">
            <span class="fa fa-exclamation-triangle form-control-feedback"></span>
            <span class="help-block">{-$error_messages.password_repeat}</span>
        </div>
    </div>
    <div class="form-group" style="margin-bottom:0">
        <div class="col-lg-offset-4 col-lg-6">
            <button class="btn btn-primary btn-block pull-right" type="submit"
                    data-loading-text="{-"Checking..."|translate}">{-"Register"|translate}</button>
        </div>
    </div>
</form>
{-/if}