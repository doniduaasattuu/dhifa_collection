<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <title><?= $model["title"] ?></title>
</head>



<body>
    <div class="container d-flex vh-100">
        <div class="my-auto align-items-center mx-auto justify-content-center" style="min-width: 300px;">

            <?php

            if (isset($model["email_duplicate"])) {
                $alert = <<<ALERT
                    <div class="alert alert-danger" role="alert">
                        Email is used! ⚠️
                    </div>
                    ALERT;
                echo $alert;
            }

            if (isset($model["field_cannot_empty"])) {
                $alert = <<<ALERT
                <div class="alert alert-danger" role="alert">
                    Field cannot empty! ⚠️
                </div>
                ALERT;
                echo $alert;
            }

            ?>

            <h2 class="mb-4"><?= $model["content"] ?></h2>
            <form action="register" method="POST">
                <div class=" mb-3">
                    <label for="exampleInputEmail1" class="form-label -mb-5">Email address</label>
                    <input type="email" id="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label -mb-5">Password</label>
                    <input id="password" name="password" type="password" class="form-control" id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                    <label for="fullname" class="form-label -mb-5">Fullname</label>
                    <input id="text" name="fullname" type="fullname" class="form-control" id="fullname">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label -mb-5">Address</label>
                    <input id="text" name="address" type="address" class="form-control" id="address">
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label -mb-5">Phone Number</label>
                    <input id="number" name="phone_number" type="phone_number" class="form-control" id="phone_number">
                </div>
                <!-- <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div> -->
                <button type="submit" class="btn btn-primary">Sign Up</button>
                <div id="emailHelp" class="form-text">Already have account ?, Login <a class="text-decoration-none" href="/login">here</a></div>
            </form>
        </div>

    </div>


    <!-- <h1><?= $model["content"] ?></h1>
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
    </script> -->
</body>

</html>