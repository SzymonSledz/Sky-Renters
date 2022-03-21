<?php
session_start();
?>
<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="public/css/header-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/second-bar-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/home-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/filters-section-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/offer-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/footer-style.css">
    <script type="text/javascript" src="./public/js/header-script.js" defer></script>
    <script src="https://kit.fontawesome.com/9937dcb129.js" crossorigin="anonymous"></script>
    <title>Sky-renters</title>
</head>

<body>
    <?php
    if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
        require_once("public/views/template/header.php");
    } else {
        require_once("public/views/template/header-logged-in.php");
    }
    ?>

    <div class="container">
        <img class="desktop-image" src="public/img/home-photo.jpg">
        <div class="mobile-description">
            <b>Do you want to rent a plane?</b>
            <b>Or maybe</b>
            <b>You want to put your plane to work</b>
            <b>You’ll do all of this here</b>
        </div>
        <img class="mobile-image" src="public/img/mobile-home-photo.jpg">
        <div class="offers-section">
            <div class="desktop-filters">
            <?php require_once ("public/views/template/offer-filters.php");?>
            </div>
            <div class="offer-container">
                <a class="mobile-offers-link" href="offers">Search Aircraft</a>
                <?php foreach($offers as $offer):
                    require ("public/views/template/offer.php");
                endforeach; ?>
            </div>
        </div>
    </div>
    <?php require_once('public/views/template/footer.php');?>
</body>