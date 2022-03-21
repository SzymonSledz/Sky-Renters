<?php

require 'Routing.php';

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url( $path, PHP_URL_PATH);

Router::get('', 'DefaultController');
Router::get('home', 'DefaultController');
Router::get('login', 'DefaultController');
Router::post('login', 'SecurityController');
Router::get('register', 'DefaultController');
Router::post('register_new_user', 'SecurityController');
Router::get('admin_dashboard', 'DefaultController');
Router::get('offers', 'OfferController');
Router::get('offer_details', 'OfferController');
Router::get('profile', 'DefaultController');
Router::get('profile_edit', 'DefaultController');
Router::get('account', 'DefaultController');
Router::get('my_offers', 'OfferController');
Router::get('add_offer', 'OfferController');
Router::post('addOffer', 'OfferController');
Router::post('removeOffer', 'OfferController');
Router::get('logout', 'SecurityController');
Router::post('updateProfile', 'DefaultController');
Router::post('changePassword', 'SecurityController');

Router::run($path);
