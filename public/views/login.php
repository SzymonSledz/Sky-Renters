<?php
session_start();
if (isset($_SESSION['user']) || isset($_SESSION['admin'])) {
    header('LOCATION: http://localhost:8080/home');
    exit();
}
?>
<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="public/css/header-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/login-view-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/footer-style.css">
    <script src="https://kit.fontawesome.com/9937dcb129.js" crossorigin="anonymous"></script>
    <title>LOGIN PAGE</title>
</head>

<body>
<?php require_once("public/views/template/header.php"); ?>

<?php require_once("public/views/template/second-bar.php"); ?>

    <div class="container">
        <div class="mobile-login-image">
            <img src="public/img/mobile-login_photo_unsplash.jpg">
        </div>
        <div class="login-container">
            <form class="login-form" action="login" method="POST">
                <div class="messages">
                    <?php if (isset($messages)) {
                        foreach ($messages as $message)
                            echo $message;
                    }
                    ?>
                </div>
                <input name="email" type="text" placeholder="email@email.com">
                <input name="password" type="password" placeholder="password">
                <button type="submit" class="login-button">LOGIN</button>
                <a class="sign-in-button" href="register">Sign in</a>
            </form>
        </div>
        <div class="login-image">
            <img src="public/img/login_photo_unsplash.jpg">
        </div>
    </div>
<?php require_once("public/views/template/footer.php"); ?>
</body>