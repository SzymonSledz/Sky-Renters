<?php
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    header('LOCATION: http://localhost:8080/login');
    exit();
}
?>

<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="public/css/header-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/second-bar-style.css">

    <link rel="stylesheet" type="text/css" href="public/css/user-navi-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/account-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/offer-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/footer-style.css">

    <script type="text/javascript" src="./public/js/script.js" defer></script>
    <script type="text/javascript" src="./public/js/header-script.js" defer></script>
    <script src="https://kit.fontawesome.com/9937dcb129.js" crossorigin="anonymous"></script>
    <title>Account</title>
</head>

<body>

<?php
if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    require_once("public/views/template/header.php");
} else {
    require_once("public/views/template/header-logged-in.php");
}
?>
<!--TODO contact info-->

<div class="second-bar">
    <b>My Account</b>
</div>

<div class="container">
    <?php
        require_once("public/views/template/user-navi.php");
    ?>
    <div class="main">
        <div class="name">
            <b class="account-text"><?= $user->getName() . " " . $user->getSurname();?></b>
        </div>

        <div class="email">
            <b class="account-text">Email: <?= $user->getEmail();?></b>
        </div>


        <form class="acc-change" action="changePassword" method="POST">
            <b class="account-text">New Password</b>
            <input name="password" type="password" placeholder="password">
            <input name="confirmPassword" type="password" placeholder="Confirm password">

            <button type="submit" class="">Change password</button>
        </form>

    </div>
</div>

<footer>
    <b>Privacy policy</b>
</footer>
<script src="public/script/slider.js"></script>
</body>
