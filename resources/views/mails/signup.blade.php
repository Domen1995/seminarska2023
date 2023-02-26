Please click the following link to verify your email address, {{$name}}: <a href="https://{{$_SERVER["SERVER_ADDR"]}}/users/verifyMail?n={{$name}}&c={{$verificationCode}}">https://{{$_SERVER["SERVER_ADDR"]}}/users/verifyMail?n={{$name}}&c={{$verificationCode}}</a>
{{--"https://localhost/seminarska2023/public--}}
<br>
Until email is verified, this link will delete the record from database: <a href="https://{{$_SERVER["SERVER_ADDR"]}}/users/deleteBeforeVerified?c={{$verificationCode}}">https://{{$_SERVER["SERVER_ADDR"]}}/users/deleteBeforeVerified?c={{$verificationCode}}</a>
{{--<form action="https://localhost/seminarska2023/public/users/verifyMail" method="POST">
    <input type="">
</form>--}}
