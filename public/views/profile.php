<?php
session_start();
?>

<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="public/css/header-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/second-bar-style.css">

    <link rel="stylesheet" type="text/css" href="public/css/user-navi-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/profile-view-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/offer-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/footer-style.css">

    <script type="text/javascript" src="./public/js/header-script.js" defer></script>
    <script src="https://kit.fontawesome.com/9937dcb129.js" crossorigin="anonymous"></script>
    <title>Profile</title>
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
        <b>My profile</b>
    </div>
    <div class="container">
        <?php
        if (isset($_SESSION['user']) || isset($_SESSION['admin'])) {
            require_once("public/views/template/user-navi.php");
        }
        else {
            require_once("public/views/template/user-navi-placeholder.php");
        }
        ?>
        <div class="main">
            <img src="<?= $user->getUserDetails()->getImage() ?>">
            <div class="name">
                <b class="profile-text"><?= $user->getName() . " " . $user->getSurname();?></b>
            </div>

            <div class="about-me">
                <div class="dropshadow-border-style main-title">
                    <h1>About me</h1>
                </div>
<!--                TODO empty field-->
                <div class="dropshadow-border-style main-title"><b class="profile-text"><?=$user->getUserDetails()->getDescription()?></b></div>


            </div>
            <div class="my-aircrafts">
                <b class="profile-text">My aircrafts</b>

                <?php foreach($offers as $offer):
                    require ("public/views/template/offer.php");
                endforeach; ?>

            </div>
            <a href="profile_edit" class="edit-button">Edit Profile</a>

        </div>
    </div>

    <footer>
        <b>Privacy policy</b>
    </footer>
    <script src="public/script/slider.js"></script>
</body>