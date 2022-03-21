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
    <link rel="stylesheet" type="text/css" href="public/css/add_offer-view-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/footer-style.css">

    <script type="text/javascript" src="./public/js/header-script.js" defer></script>
    <script src="https://kit.fontawesome.com/9937dcb129.js" crossorigin="anonymous"></script>
    <title>Add offer</title>
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
        <b>Add offer</b>
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
            <form action="addOffer" method="POST" ENCTYPE="multipart/form-data">

                <div class="messages">
                    <?php if (isset($messages)) {
                        foreach ($messages as $message)
                            echo $message;
                    }
                    ?>
                </div>

                <p>Choose license</p>
                <div class="license">
                    <div>
                    <?php foreach ($license_types as $license_type):?>
                    <input type="checkbox" id="<?= $license_type['id'] ?>" name="license[]" value="<?= $license_type['name'] ?>">
                    <label for="<?= $license_type['id'] ?>"><?= $license_type['name'] ?></label>
                    <?php endforeach; ?>
                    </div>
                </div>

                <p>Choose category</p>
                <div>
                    <?php foreach ($aircraft_categories as $aircraft_category):?>
                        <input type="radio" id="<?= $aircraft_category['id'] ?>" name="category" value="<?= $aircraft_category['name'] ?>">
                        <label for="<?= $aircraft_category['id'] ?>"><?= $aircraft_category['name'] ?></label>
                    <?php endforeach; ?>
                </div>

                <p>Choose landing gear</p>
                <div>
                    <?php foreach ($landing_gears as $landing_gear):?>
                        <input type="radio" id="<?= $landing_gear['id'] ?>" name="landing_gear" value="<?= $landing_gear['landing_gear'] ?>">
                        <label for="<?= $landing_gear['id'] ?>"><?= $landing_gear['landing_gear'] ?></label>
                    <?php endforeach; ?>
                </div>

                <p>Choose files</p>
                <input type="file" name="file0" multiple class="choose">
                <input type="file" name="file1" multiple class="choose">
                <input type="file" name="file2" multiple class="choose">
                <input type="file" name="file3" multiple class="choose">
                <input type="file" name="file4" multiple class="choose">

                <p>Aircraft's details</p>
                <div class="details">
                <input type="text" name="make" placeholder="make">
                <input type="text" name="model" placeholder="model">
                <input type="text" name="title" placeholder="title">
                <input type="number" name="yearOfProduction" placeholder="Year of Production">
                <input type="number" name="price" placeholder="price per day">
                </div>

                <p>Description</p>
                <textarea name="description" rows="5" placeholder="description"></textarea>

                <p>Address</p>
                <div class="address">
                <input name="country" type="text" placeholder="Country">
                <input name="state" type="text" placeholder="State">
                <input name="city" type="text" placeholder="City">
                <input name="street" type="text" placeholder="Street">
                <input name="number" type="number" placeholder="Number">
                <input name="zipCode" type="text" placeholder="Zip-code">
                </div>

                <button class="add-offer-button" type="submit">Add Offer</button>
            </form>
        </div>
    </div>

    <footer>
        <b>Privacy policy</b>
    </footer>
    <script src="public/script/slider.js"></script>
</body>