<?php
session_start();
if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    header('LOCATION: http://localhost:8080/home');
    exit();
}
?>
<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="public/css/header-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/second-bar-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/my_offers-view-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/user-navi-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/offer-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/footer-style.css">
    <script type="text/javascript" src="./public/js/header-script.js" defer></script>
    <script src="https://kit.fontawesome.com/9937dcb129.js" crossorigin="anonymous"></script>
    <title>My offers</title>
</head>

<body>
<?php
if (!isset($_SESSION['user'])) {
    require_once("public/views/template/header.php");
} else {
    require_once("public/views/template/header-logged-in.php");
}
?>
    <div class="second-bar">
        <b>My offers</b>
    </div>
    <div class="container">
    <div class="user-navi">
        <?php
        require_once("public/views/template/user-navi.php");
        ?>
        </div>
        <div class="main">
                <div class="offers">
                    <?php foreach($offers as $offer):
                        require ("public/views/template/offer.php");
                    endforeach; ?>
                </div>
            <a href="add_offer">Add offer</a>
        </div>
    </div>
    <footer>
        <b>Privacy policy</b>
    </footer>
    <script src="public/script/slider.js"></script>
</body>