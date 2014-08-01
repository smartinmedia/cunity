<h1 class="page-header">{-"ContactForm"|translate}</h1>
<form class="form-horizontal" id="contactForm" action="{-"index.php?m=contact&action=sendContact"|URL}" method="post">
    <div class="form-group">
        <label class="control-label col-lg-3">{-"Firstname"|translate}</label>

        <div class="col-lg-6">
            <input type="text" name="firstname" value="{-$userData.firstname}" class="form-control"
                   required {-if !$userData.firstname eq ""} readonly{-/if}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3">{-"Lastname"|translate}</label>

        <div class="col-lg-6">
            <input type="text" name="lastname" value="{-$userData.lastname}" class="form-control"
                   required {-if !$userData.lastname eq ""} readonly{-/if}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3">{-"E-Mail"|translate}</label>

        <div class="col-lg-8">
            <input type="email" name="email" value="{-$userData.email}" class="form-control"
                   required {-if !$userData.email eq ""} readonly{-/if}>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3">{-"Subject"|translate}</label>

        <div class="col-lg-8">
            <input type="text" name="subject" value="" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-lg-3">{-"Your Message"|translate}</label>

        <div class="col-lg-8">
            <textarea class="form-control" rows="6" name="message" required></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="checkbox col-lg-offset-3 col-lg-5" style="padding-left:34px">
            <input type="checkbox" name="send_copy" value="1"> {-"Send a copy to me"|translate}
        </label>

        <div class="col-lg-3">
            <input type="submit" value="{-"Send"|translate}" id="sendContactFormButton" class="
                   btn btn-primary pull-right form-control">
        </div>
    </div>
</form>