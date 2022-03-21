<div class="desktop-header">
    <header>
        <nav>
            <ul>
                <li>
                    <a href="home" class="button">Home</a>
                </li>
                <li>
                    <a href="#" class="button">About Us</a>
                </li>
                <li>
                    <a href="#" class="button">Contact Us</a>
                </li>
                <li>
                    <a href="offers" class="button">Offers</a>
                </li>
                <li>
                    <a href="profile" class="button">My profile</a>
                </li>
                <?php if (isset($_SESSION['admin'])) {?>
                <li>
                    <a href="admin_dashboard" class="button">Dashboard</a>
                </li>
                <?php } ?>
                <li>
                    <a href="logout" class="button">Logout</a>
                </li>
            </ul>
        </nav>
    </header>
</div>
<div class="mobile-header">
    <header>
        <div class="navi-mobile-bar">
            <div class="bars-title">
            <div id="bars" class="bars">
                <i class="fas fa-bars"></i>
            </div>
            <div class="app-title">
                <b>Sky - Renters</b>
<!--                <i class="fas fa-angle-left back-icon"></i>-->
            </div>
            </div>
            <nav id="mobile-nav-links" class="mobile-nav bars-hidden">
                <ul>
                    <li>
                        <a href="#" class="button">About Us</a>
                    </li>
                    <li>
                        <a href="#" class="button">Contact Us</a>
                    </li>
                    <li>
                        <a href="offers" class="button">Offers</a>
                    </li>
                    <li>
                        <a href="profile" class="button">My profile</a>
                    </li>
                    <li>
                        <a href="account">Account</a>
                    </li>
                    <li>
                        <a href="my_offers">My Offers</a>
                    </li>
                    <li>
                        <a href="add_offer">Add offer</a>
                    </li>
                    <?php if (isset($_SESSION['admin'])) {?>
                        <li>
                            <a href="admin_dashboard" class="button">Dashboard</a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="logout" class="button">Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
</div>
