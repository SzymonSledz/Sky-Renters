<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('LOCATION: http://localhost:8080/login');
    exit();
}
?>
<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="public/css/header-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/second-bar-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/admin-dashboard-style.css">
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
    <div class="second-bar">
        <b>Dashboard</b>
    </div>
    <div class="main">
        <div class="adding-section">
        </div>
        <div offers-section>
            <div class="offer-container">
                <?php foreach($offers as $offer):
                    require ("public/views/template/offer.php");
                endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once('public/views/template/footer.php');?>
</body>

