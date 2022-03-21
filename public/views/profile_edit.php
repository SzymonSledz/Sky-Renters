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
    <link rel="stylesheet" type="text/css" href="public/css/user-navi-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/profile-view-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/profile-edit.css">
    <link rel="stylesheet" type="text/css" href="public/css/offer-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/footer-style.css">
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
        <b>My profile</b>
    </div>
    <div class="container">
        <?php
            require_once("public/views/template/user-navi.php");
        ?>
        <form action="updateProfile" method="POST" ENCTYPE="multipart/form-data">
        <div class="main">
            <img src="<?= $user->getUserDetails()->getImage() ?>">
                <input type="file" name="file" value="<?= $user->getUserDetails()->getImage() ?>" multiple class="choose">
            <div class="name">
                <b><?= $user->getName() . " " . $user->getSurname();?></b>
            </div>
            <div class="about-me">
                <div class="dropshadow-border-style main-title">
                    <h1>About me</h1>
                </div>
                <textarea id="my-description" class="dropshadow-border-style main-title" name="description" rows="5" placeholder="description"><?=$user->getUserDetails()->getDescription()?></textarea>
            </div>
            <div class="my-aircrafts">
                <b>My aircrafts</b>
                <?php foreach($offers as $offer):
                    require ("public/views/template/offer.php");
                endforeach; ?>
            </div>
                <div class="messages">
                    <?php if (isset($messages)) {
                        foreach ($messages as $message)
                            echo $message;
                    }
                    ?>
                </div>
            <button class="edit-button" type="submit">Save changes</button>
            </form>
        </div>
    </div>

    <footer>
        <b>Privacy policy</b>
    </footer>
    <script src="public/script/slider.js"></script>
</body>