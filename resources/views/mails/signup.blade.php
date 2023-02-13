Please click the following link to verify your email address, {{$name}}: <a href="https://localhost/seminarska2023/public/users/verifyMail?n={{$name}}&c={{$verificationCode}}">https://localhost/seminarska2023/public/users/verifyMail?n={{$name}}&c={{$verificationCode}}</a>

<br>
Until email is verified, this link will delete the record from database: <a href="https://localhost/seminarska2023/public/users/deleteBeforeVerified?c={{$verificationCode}}">https://localhost/seminarska2023/public/users/deleteBeforeVerified?c={{$verificationCode}}</a>
{{--<form action="https://localhost/seminarska2023/public/users/verifyMail" method="POST">
    <input type="">
</form>--}}
