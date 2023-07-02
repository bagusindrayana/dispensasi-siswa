<?php
include_once $_SERVER['DOCUMENT_ROOT'] . "/config/application.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['login']) && $_SESSION['user']) {
    header("Location: ../index.php");
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
        integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600|Open+Sans:400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/spur.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.bundle.min.js"></script>
    <script src="/assets/js/chart-js-config.js"></script>
    <title>
        <?= $app_name ?> - LOGIN
    </title>
</head>

<body>
    <div class="form-screen">
    <img src="<?=$logo_url?>" alt="Logo Sekolah" style="width:100px;">
        <a href="/" class="spur-logo">
        
            <span>
                <?= $app_name ?>
            </span></a>
        <div class="card account-dialog">
            <div class="card-header bg-primary text-white">Silahkan login </div>
            <div class="card-body">
                <?php
                include_once "../library/session_message.php";
                ?>
                <form action="../actions/login_action.php" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" id="username" aria-describedby="usernameHelp"
                            placeholder="Username..." required name="username">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"
                            required name="password">
                    </div>

                    <div class="account-dialog-actions">
                        <button type="submit" class="btn btn-primary" name="login">LOGIN</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
    <script src="/assets/js/spur.js"></script>
</body>

</html>