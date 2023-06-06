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
        <input disabled id="submit" type="submit" value="Login">
        <p>Belum punya akun ? Daftar <a href="register">disini</a></p>
    </form>

    <script>
        const username = document.getElementById("username");
        const password = document.getElementById("password");
        const submit = document.getElementById("submit");

        const input = document.getElementsByTagName("input");

        for (let i = 0; i < input.length - 1; i++) {
            input[i].onchange = () => {
                if (password.value != "" && username.value != "") {
                    submit.removeAttribute("disabled")
                }
            }
        }
    </script>
</body>

</html>