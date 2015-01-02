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
    <form class="form-horizontal registration-form validate"
          action="{-"index.php?m=register&action=sendRegistration"|URL}"
          method="POST">
        <div class="form-group">
            <label class="control-label col-lg-4" for="input-username">{-"Username"|translate}*</label>

            <div class="col-lg-8">
                <input type="text" autocomplete="off" required class="form-control" id="input-username"
                       placeholder="{-"Username"|translate}" name="username" value="{-$values.username}">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4" for="input-email">{-"E-Mail"|translate}*</label>

            <div class="col-lg-8">
                <input type="email" required class="form-control" id="input-email" placeholder="{-"E-Mail"|translate}"
                       name="email" value="{-$values.email}">
            </div>
        </div>
        {-if "core.fullname"|setting}
            <div class="form-group">
                <label class="control-label col-lg-4" for="input-firstname">{-"Firstname"|translate}*</label>

                <div class="col-lg-8">
                    <input type="text" autocomplete="off" required class="form-control" id="input-firstname"
                           placeholder="{-"Firstname"|translate}" name="firstname" value="{-$values.firstname}">
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-lg-4" for="input-lastname">{-"Lastname"|translate}*</label>

                <div class="col-lg-8">
                    <input type="text" autocomplete="off" required class="form-control" id="input-lastname"
                           placeholder="{-"Lastname"|translate}" name="lastname" value="{-$values.lastname}">
                </div>
            </div>
        {-/if}
        <div class="form-group">
            <label class="control-label col-lg-4" for="input-password">{-"Password"|translate}*</label>

            <div class="col-lg-8">
                <input type="password" autocomplete="off" required class="form-control" id="input-password"
                       placeholder="{-"Password"|translate}" name="password" value="{-$values.password}">
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-lg-4" for="input-password-repeat">{-"Repeat password"|translate}*</label>

            <div class="col-lg-8">
                <input type="password" autocomplete="off" required class="form-control" id="input-password-repeat"
                       placeholder="{-"Repeat password"|translate}" name="password_repeat"
                       value="{-$values.password_repeat}">
            </div>
        </div>
        {-foreach $profileFields AS $i => $field}
        <div class="form-group" style="margin-bottom:10px">
            <label class="control-label col-lg-4">{-$field.value|translate}{-if $field.required == 1}*{-/if}</label>

            {-if $field.type == 'select'}
            <div class="col-lg-8">
                <select class="form-control" name="field[{-$field.id}]" {-if $field.required}required{-/if}>
                    <option value="">{-"Make a choice"|translate}</option>
                    {-foreach $field.values as $j => $value}
                        <option value="{-$value.id}"
                                {-if $field.value==$value.id}selected{-/if}>{-$value.value|translate}</option>
                    {-/foreach}
                </select>
                {-elseif $field.type == 'radio'}
                <div class="col-lg-8">
                {-foreach $field.values as $j => $value}
                    <div class="radio-inline">
                        <input type="radio" name="field[{-$field.id}]"
                               value="{-$value.id}">{-$value.value|translate}
                    </div>
                {-/foreach}
                {-elseif $field.type == 'string'}
                <div class="col-lg-8">
                    <input type="text" {-if $field.required == 1}required="required"{-/if} class="form-control"
                           id="input-{-$field.id}"
                           placeholder="{-$field.value|translate}" name="field[{-$field.id}]"
                           value="{-$field.value}">
                    {-elseif $field.type == 'email'}
                    <div class="col-lg-8">
                        <input type="email" {-if $field.required == 1}required="required"{-/if} class="form-control"
                               id="input-{-$field.id}"
                               placeholder="{-$field.value|translate}" name="field[{-$field.id}]"
                               value="{-$field.value}">
                        {-elseif $field.type == 'text'}
                        <div class="col-lg-8">
                            <textarea {-if $field.required == 1}required="required"{-/if} class="form-control"
                                      id="input-password-repeat"
                                      placeholder="{-$field.value|translate}"
                                      name="{-$field.id}">{-$field.value}</textarea>
                            {-elseif $field.type == 'date'}
                            <div class="col-lg-7">
                                <div class="input-group date" id="datepicker">
                                    <input type="text" class="form-control" name="field[{-$field.id}]"
                                           value="{-$field.value}"
                                           readonly="readonly">
                        <span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
                        </span>
                                </div>
                                {-/if}
                            </div>
                        </div>
                        {-/foreach}
                        {-if "register.min_age"|setting > 0}
                            <div class="form-group" style="margin-bottom:10px">
                                <label class="control-label col-lg-4">{-"Birthday"|translate}</label>

                                <div class="col-lg-7">
                                    <div class="input-group date" id="datepicker">
                                        <input type="text" class="form-control" name="birthday" required="required"
                                               readonly="readonly">
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
                                        type="submit"
                                        id="registerbutton" {-*data-loading-text="{-"Checking..."|translate}"*}>{-"Register"|translate}</button>
                            </div>
                        </div>
    </form>
{-/if}
