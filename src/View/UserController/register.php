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
    <form action="create_acount" method="POST">
        <label for="username">Username</label>
        <input id="username" name="username" type="text">
        <br>
        <label for="password">Password</label>
        <input id="password" name="password" type="text">
        <br>
        <label for="fullname">Nama Lengkap</label>
        <input id="fullname" name="fullname" type="text">
        <br>
        <label for="address">Alamat</label>
        <input id="address" name="address" type="text">
        <br>
        <label for="phone_number">No Telp</label>
        <input id="phone_number" name="phone_number" type="number">
        <br>
        <input disabled id="submit" type="submit" value="Daftar">
        <br>
        <p>Sudah punya akun ?, Login <a href="/login">disini</a></p>
    </form>
    <script>
        const username = document.getElementById("username");
        const password = document.getElementById("password");
        const fullname = document.getElementById("fullname");
        const address = document.getElementById("address");
        const phone_number = document.getElementById("phone_number");
        const submit = document.getElementById("submit");

        const input = document.getElementsByTagName("input");

        for (let i = 0; i < input.length - 1; i++) {
            input[i].onchange = () => {
                if (password.value != "" && username.value != "" && fullname.value != "" && address.value != "" && phone_number.value != "") {
                    submit.removeAttribute("disabled")
                }
            }
        }
    </script>
</body>

</html>