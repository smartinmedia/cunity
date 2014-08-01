<td colspan="2">
    <h1>{-"Thanks for your registration!"|translate}</h1>

    <p>Hi {-$name},

    <p>

    <p>{-"Please verify you email by clicking this link:"|translate}</p>
    {-assign var="verifyUrl" value="index.php?m=register&action=verify&x={-$registerSalt}"}
    <a href="{-$verifyUrl|URL}">Verify Your Account now!</a>
</td>