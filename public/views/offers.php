<?php
session_start();
?>

<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="public/css/header-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/second-bar-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/offers-view-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/filters-section-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/offer-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/slider.css">
    <link rel="stylesheet" type="text/css" href="public/css/footer-style.css">
    <script type="text/javascript" src="./public/js/header-script.js" defer></script>
    <script src="https://kit.fontawesome.com/9937dcb129.js" crossorigin="anonymous"></script>
    <title>Offers</title>
</head>

<body>
<?php
if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
    require_once("public/views/template/header.php");
} else {
    require_once("public/views/template/header-logged-in.php");
}
?>

    <div class="second-bar">
        <b>Aircraft Listing</b>
    </div>
    <div class="container">
        <div>
        <?php require_once ("public/views/template/offer-filters.php");?>
        </div>
        <div class="offers">
            <?php foreach($offers as $offer):
                require ("public/views/template/offer.php");
            endforeach; ?>
        </div>
    </div>

    <footer>
        <b>Privacy policy</b>
    </footer>
    <script src="public/script/slider.js"></script>
</body>