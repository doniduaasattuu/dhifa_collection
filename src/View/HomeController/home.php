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
    <h3><?= "Hello " . $_SESSION["username"] ?></h3>

    <button id="logout">Logout</button>
</body>
<script>
    const logout = document.getElementById("logout");
    logout.onclick = () => {
        <?php session_destroy() ?>
        window.location = "/";
    }
</script>

</html>