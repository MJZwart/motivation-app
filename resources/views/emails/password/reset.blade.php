<html>

<body>
    Hi. Password.

    <a href="{{ url('/reset-password?token=' . $token . '&email=' . $email)}}" target="_blank">Reset</a>
</body>
</html>