<ol class="breadcrumb">
    <li><a href="{-"index.php"|URL}"><i class="fa fa-home"></i></a></li>
    <li><a href="{-"index.php?m=register"|URL}">{-"Registration"|translate}</a></li>
    <li class="active">{-"Forget Password"|translate}</li>
</ol>
{-if $error}
<div class="alert alert-block alert-danger">
    <h4>{-"sorry"|translate}</h4>

    <p>{-"We cannot find the given email address"|translate}</p>
</div>
{-/if}
<div class="well">
    <p class="help-block">{-"If you forgot or lost your password, enter your email adress your have used for registration and we will send you a link to reset your password!"|translate}</p>

    <form action="{-"index.php?m=register&action=forgetPw"|URL}" method="post" class="form-horizontal">
        <div class="form-group">
            <label for="inputEmail1" class="col-lg-1 control-label">Email</label>

            <div class="col-lg-11">
                <input type="email" class="form-control" placeholder="Email" name="email">
            </div>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary pull-right" value="{-"Send"|translate}"
                   style="margin-right:15px" name="resetPw">
        </div>
    </form>
</div>