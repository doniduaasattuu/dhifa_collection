<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $model["title"] ?></title>
</head>

<body>
    <h1><?= $model["content"] ?></h1>
    <form action="login_request" method="POST">
        <label for="username">Username</label>
        <input id="username" name="username" type="text">
        <br>
        <label for="password">Password</label>
        <input id="password" name="password" type="password">
        <br>
        <input type="submit" value="Login">
        <p>Belum punya akun ? Daftar <a href="register">disini</a></p>
    </form>
</body>

</html>