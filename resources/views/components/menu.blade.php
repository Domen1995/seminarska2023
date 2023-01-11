@auth
    <h2>Logged in</h2>
@else
    <h2>Not logged in</h2>
@endauth

<div class="menu">
    <a href="{{BASEURL}}/users/loginForm">Login</a>
    <a href="{{BASEURL}}/users/registrationForm">Register</a>
</div>
