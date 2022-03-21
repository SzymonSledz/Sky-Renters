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
    <link rel="stylesheet" type="text/css" href="public/css/register-view-style.css">
    <link rel="stylesheet" type="text/css" href="public/css/footer-style.css">
    <script src="https://kit.fontawesome.com/9937dcb129.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="./public/js/script.js" defer></script>
    <title>REGISTER PAGE</title>
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
        <div class="logo">
            <img src="public/img/logo.svg">
            <b>Sky - Renters</b>
        </div>
    </div>
    <div class="container">
        <div class="login-container">
            <form class="login-form" action="register_new_user" method="POST">
                <div class="messages">
                    <?php if (isset($messages)) {
                        foreach ($messages as $message)
                            echo $message;
                    }
                    ?>
                </div>
                <input name="name" type="text" placeholder="Name">
                <input name="surname" type="text" placeholder="Surname">
                <input name="age" type="number" placeholder="Age">
                <input name="country" type="text" placeholder="Country">
                <input name="state" type="text" placeholder="State">
                <input name="town" type="text" placeholder="Town">
                <input name="street" type="text" placeholder="Street">
                <input name="number" type="number" placeholder="Number">
                <input name="localNumber" type="number" placeholder="Local Number">
                <input name="zipCode" type="text" placeholder="Zip-code">
                <input name="email" type="text" placeholder="email@email.com">
                <input name="password" type="password" placeholder="password">
                <input name="confirmPassword" type="password" placeholder="Confirm password">
                <!-- TODO checkboxes for licenses -->
                <select class="license-select" name="license" id="license">
                    <option value="All Licenses">All Licenses</option>
                    <option value="PPL">PPL</option>
                    <option value="LAPL(A)">LAPL(A)</option>
                    <option value="UAP(L)">UAP(L)</option>
                    <option value="Ultra Light">Ultra Light</option>
                </select>
                <button type="submit" class="sign-in-button">Register</button>
            </form>
        </div>
    </div>

    <footer>
        <b>Privacy policy</b>
    </footer>
</body>