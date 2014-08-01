<td colspan="2">
    <h1>{-"Your new password"|translate}</h1>

    <p>Hi {-$name},</p>

    <p>{-"You have ordered a password-reset token."|translate}</p>

    <p>{-"Click the link below to create a new password, or enter the token in the form"|translate}</p>

    <p>
        <a href="{-"index.php?m=register&action=reset&x="|URL}{-$password}">{-"index.php?m=register&action=reset&x="|URL}{-$password}</a>
    </p>

    <p><b>{-"Password-Token"|translate}: {-$password}</b></p>

    <p><b>{-"Please note:"|translate}</b>&nbsp;{-"This token is expires in 30 minutes!"|translate}</p>

    <p><i>{-"Did not ordered a new password? Forget this mail!"|translate}</i></p>
</td>