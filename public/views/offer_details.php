<?php
session_start();
?>
<!DOCTYPE html>
<head>
    <link rel="stylesheet" type="text/css" href="public/css/header-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/second-bar-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/user-navi-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/offer-details-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/footer-style.css">
    <script type="text/javascript" src="./public/js/img-script.js" defer></script>
    <script src="https://kit.fontawesome.com/9937dcb129.js" crossorigin="anonymous"></script>
    <title>Offer details</title>
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
        <b>Offer details</b>
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
            <!--TODO contact info-->
            <div class="offer-details">
                <div class="upper-part">
                    <div class="offer-images">
                        <div class="main-image">
                            <img id="mainImageId" src="public/uploads/<?= $offer->getAircraft()->getImage(0);?>">
                        </div>
                        <div class="other-images">
                            <img id="1" src="public/uploads/<?= $offer->getAircraft()->getImage(1);?>" onclick="swapImage(1)">
                            <img id="2" src="public/uploads/<?= $offer->getAircraft()->getImage(2);?>" onclick="swapImage(2)">
                            <img id="3" src="public/uploads/<?= $offer->getAircraft()->getImage(3);?>"  onclick="swapImage(3)">
                            <img id="4" src="public/uploads/<?= $offer->getAircraft()->getImage(4);?>"  onclick="swapImage(4)">
                        </div>
                    </div>
                    <div class="main-offer-info">
                        <div class="offer-title main-info-element">
                            <b><?= $offer->getTitle();?></b>
                        </div>
                        <div class="offer-address main-info-element">

                            <i class="fas fa-map-marker-alt"></i>
                                <?= $offer->getAddressString();?>
                        </div>
                        <div class="price main-info-element">
                            <b class="price-title">Price </b>
                            <b><?= $offer->getPrice();?>$/hour</b>
                        </div>
                    </div>
                </div>
                <div class="lower-part">
                    <div class="details-container">
                    <b>Details</b>
                    <div class="details">
                            <div>
                                <b class="detail-name">Make </b>
                                <b class="detail-value"><?= $offer->getAircraft()->getMake();?></b>
                            </div>
                        <div>
                            <b class="detail-name">Model </b>
                            <b class="detail-value"><?= $offer->getAircraft()->getModel();?></b>
                        </div>
                        <div>
                            <b class="detail-name">Licenses </b>
                            <b class="detail-value"><?=
                                $offer->getAircraft() ->printLicenseTypes();
                                ?></b>
                        </div>
                        <div>
                            <b class="detail-name">Category </b>
                            <b class="detail-value"><?= $offer->getAircraft()->getCategory();?></b>
                        </div>
                        <div>
                            <b class="detail-name">Year of production </b>
                            <b class="detail-value"><?= $offer->getAircraft()->getYearOfProduction();?></b>
                        </div>
                        <div>
                            <b class="detail-name">Landing Gear </b>
                            <b class="detail-value"><?= $offer->getAircraft()->getLandingGear();?></b>
                        </div>

                            <?php
                            $ak = array_keys($offer->getAircraft()->getDetails());
                            foreach($ak as $detail):?>
                        <div>
                                <b class="detail-name"><?= $detail ?> </b>
                                <b class="detail-value"><?= $offer->getAircraft()->getDetails()[$detail] ?> </b>
                        </div>
                            <?php endforeach; ?>
                    </div>
                    </div>
                    <div class="description-container">
                        <b class="description-title">Description</b>
                        <b class="description detail-name"><?= $offer->getDescription() ?></b>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <b>Privacy policy</b>
    </footer>
    <script src="public/script/slider.js"></script>
</body>