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

    <div class="container">
        <div class="d-flex h-100 justify-content-center ">
            <form class="align-items-center" style="max-width: 300px" action="/login_request" method="POST">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <!-- <h1><?= $model["content"] ?></h1>
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
    </script> -->
</body>

</html>