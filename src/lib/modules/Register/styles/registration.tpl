<div class="page-buttonbar">
    <h1 class="page-header">{-"Registration"|translate}</h1>
</div>
{-if $success}
<div class="alert alert-block alert-success"><h4 class="alert-heading">{-"Ready!"|translate}</h4>

    <p>{-"We have sent you an activation link to your email to confirm your address!"|translate}</p></div>
{-else}
{-if $error_messages.length > 0}
<div class="alert alert-block alert-success"><h4 class="alert-heading">{-"Sorry!"|translate}</h4>
    <ul>
        {-foreach $error_messages AS $message}
        <li>{-$message}</li>
        {-/foreach}
    </ul>
</div>
{-/if}
<form class="form-horizontal registration-form validate" action="{-"index.php?m=register&action=sendRegistration"|URL}"
      method="POST">
    <div class="form-group">
        <label class="control-label col-lg-4" for="input-username">{-"Username"|translate}</label>

        <div class="col-lg-8">
            <input type="text" autocomplete="off" required class="form-control" id="input-username"
                   placeholder="{-"Username"|translate}" name="username" value="{-$values.username}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-4" for="input-email">{-"E-Mail"|translate}</label>

        <div class="col-lg-8">
            <input type="email" required class="form-control" id="input-email" placeholder="{-"E-Mail"|translate}"
                   name="email" value="{-$values.email}">
        </div>
    </div>
    {-if "core.fullname"|setting}
    <div class="form-group">
        <label class="control-label col-lg-4" for="input-firstname">{-"Firstname"|translate}</label>

        <div class="col-lg-8">
            <input type="text" autocomplete="off" required class="form-control" id="input-firstname"
                   placeholder="{-"Firstname"|translate}" name="firstname" value="{-$values.firstname}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-4" for="input-lastname">{-"Lastname"|translate}</label>

        <div class="col-lg-8">
            <input type="text" autocomplete="off" required class="form-control" id="input-lastname"
                   placeholder="{-"Lastname"|translate}" name="lastname" value="{-$values.lastname}">
        </div>
    </div>
    {-/if}
    <div class="form-group">
        <label class="control-label col-lg-4" for="input-password">{-"Password"|translate}</label>

        <div class="col-lg-8">
            <input type="password" autocomplete="off" required class="form-control" id="input-password"
                   placeholder="{-"Password"|translate}" name="password" value="{-$values.password}">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-4" for="input-password-repeat">{-"Repeat password"|translate}</label>

        <div class="col-lg-8">
            <input type="password" autocomplete="off" required class="form-control" id="input-password-repeat"
                   placeholder="{-"Repeat password"|translate}" name="password_repeat"
                   value="{-$values.password_repeat}">
        </div>
    </div>
    <div class="form-group" style="margin-bottom:10px">
        <label class="control-label col-lg-4">{-"I am"|translate}</label>

        <div class="col-lg-8">
            <select class="form-control" name="sex" required>
                <option value="">{-"Select your gender"|translate}</option>
                <option value="f" {-if $values.sex=='f'}selected{-/if}>{-"Female"|translate}</option>
                <option value="m" {-if $values.sex=='m'}selected{-/if}>{-"Male"|translate}</option>
            </select>
        </div>
    </div>
    {-if "register_min_age"|setting > 0}
    <div class="form-group" style="margin-bottom:10px">
        <label class="control-label col-lg-4">{-"Birthday"|translate}</label>

        <div class="col-lg-7">
            <div class="input-group date" id="datepicker">
                <input type="text" class="form-control" name="birthday" required="required" readonly="readonly">
                        <span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
                        </span>
            </div>
        </div>
    </div>
    {-/if}
    <div class="form-group" style="margin-bottom:0">
        <div class="col-lg-offset-4 col-lg-8">
            <button class="btn btn-primary btn-block pull-right"
                    type="submit" {-*data-loading-text="{-"Checking..."|translate}"*}>{-"Register"|translate}</button>
        </div>
    </div>
</form>
{-/if}
