<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User logged in</title>
</head>
<body>
User {{ $user->name }} logged in successfully! This window will close in a moment.
<script>
    (function() {
        window.opener.postMessage({
            success: true,
            user: {!! $user->load('roles')->toJson() !!},
            message: 'user-logged-in',
            token: '{!! $token !!}'
        }, '{!! getenv('CLIENT_ORIGIN') !!}')
    })()
</script>
</body>
</html>