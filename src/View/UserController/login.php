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


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="bg-light modal-header">
                    <h1 class=" modal-title fs-5" id="exampleModalLabel">Registration Success! ✅</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Congratulations!, your account has been successfully created,
                    Keep in mind your email and password.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                </div>
            </div>
        </div>
    </div>

    <!-- d-flex justify-content-center align-content-center -->
    <div class="container d-flex vh-100">
        <div class="my-auto align-items-center mx-auto justify-content-center" style="min-width: 300px;">

            <?php

            if (isset($model["login"])) {
                $alert = <<<ALERT
                            <div class="alert alert-danger" role="alert">
                                Username or password is wrong! ⚠️
                            </div>
                            ALERT;
                echo $alert;
            }

            if (isset($model["registration_success"])) {
                $modal = <<<MODAL
                            <script>
                                let myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {});
                                myModal.show();
                            </script>
                            MODAL;
                echo $modal;
            }

            ?>

            <h2 class="mb-4"><?= $model["content"] ?></h2>
            <form action="login" method="POST">
                <div class=" mb-3">
                    <label for="email" class="form-label -mb-5">Email address</label>
                    <input type="email" id="email" name="email" class="form-control" id="email" aria-describedby="emailHelp">
                    <!-- <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div> -->
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label -mb-5">Password</label>
                    <input id="password" name="password" type="password" class="form-control" id="password">
                </div>
                <!-- <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                </div> -->
                <button type="submit" class="btn btn-primary">Submit</button>
                <div id="emailHelp" class="form-text">Don't have an account ?, Register <a class="text-decoration-none" href="/register">here</a></div>
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