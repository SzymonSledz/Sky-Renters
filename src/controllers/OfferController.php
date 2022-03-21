<?php

require_once 'AppController.php';
require_once __DIR__ .'/../models/Offer.php';
require_once __DIR__ .'/../models/Aircraft.php';
require_once __DIR__ .'/../repository/OfferRepository.php';
require_once __DIR__ .'/../repository/AircraftRepository.php';
require_once __DIR__ .'/../repository/LicenseRepository.php';

class OfferController extends AppController
{
    const MAX_FILE_SIZE = 1024*1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';

    private $messages = [];
    private $offerRepository;
    private $userRepository;
    private $aircraftRepository;
    private $licenseRepository;

    public function __construct()
    {
        parent::__construct();
        $this->offerRepository = new OfferRepository();
        $this->userRepository = new UserRepository();
        $this->aircraftRepository = new AircraftRepository();
        $this->licenseRepository = new LicenseRepository();
    }

    public function my_offers()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            $this->render('login');
        }
        $user = $this->userRepository->getUser($_SESSION['user']);
        $userOffers = $this->offerRepository->getUserOffers($user);
        $this->render('my_offers', ['offers'=>$userOffers, 'owner' => true]);
    }
    public function add_offer()
    {
        $categories = $this->aircraftRepository->getAllAircraftCategories();
        $licenses = $this->licenseRepository->getAllLicenseTypes();
        $landingGears = $this->aircraftRepository->getAllAircraftLandingGears();
        $this->render('add_offer', ['aircraft_categories'=>$categories, 'license_types'=>$licenses, 'landing_gears'=>$landingGears]);
    }
    public function offers()
    {
        $offers = $this->offerRepository->getOffers();
        $this->render('offers', ['offers'=>$offers]);
    }
    public function offer_details()
    {
        $id = $_GET['id'];
        $offer = $this->offerRepository->getOffer($id);
        $this->render('offer_details', ['offer'=>$offer]);
    }

    public function addOffer (){
        session_start();
        if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
            header('LOCATION: http://localhost:8080/login');
            exit();
        }
        if($this->isPost() && is_uploaded_file($_FILES['file0']['tmp_name']) && $this->validate($_FILES['file0'])){

            move_uploaded_file($_FILES['file0']['tmp_name'],
                dirname(__DIR__).self::UPLOAD_DIRECTORY.$_FILES['file0']['name']
            );
            $this->validate_additional_files($_FILES);

            //TODO validate all of this stuff
            $aircraft = new Aircraft($_POST['category'], $_POST['make'], $_POST['model'], array_values($_POST['license']), $_POST['yearOfProduction'], [$_FILES['file0']['name'], $_FILES['file1']['name'], $_FILES['file2']['name'], $_FILES['file3']['name'], $_FILES['file4']['name']], $_POST['landing_gear']);

            $address = new Address($_POST['country'], $_POST['state'], $_POST['city'], $_POST['street'], $_POST['number'], NULL, $_POST['zipCode']);
            $offer = new Offer($_POST['title'], $_POST['description'], $_POST['price'], $aircraft, $address);


            $user = $this->userRepository->getUser($_SESSION['user']);

            $this->offerRepository->addOffer($offer, $user);
            $offers = $this->offerRepository->getOffers();
            $this->render('my_offers', ['messages'=>$this->messages, 'offers'=>$offers]);
        }

        else{
        $offers = $this->offerRepository->getOffers();
        $this->render('add_offers', ['messages'=>$this->messages, 'offers'=>$offers]);
        }
    }

    private function validate(array $file): bool
    {
        if ($file['size'] > self::MAX_FILE_SIZE) {
            $this->messages[] = 'File is too large';
            return false;
        }

        if (!isset($file['type']) && !in_array($file['type'], self::SUPPORTED_TYPES)) {
            $this->messages[] = 'File type is not supported';
            return false;
        }
        return true;
    }

    private function validate_additional_files($_FILE) {

        for ($i = 1; $i < 5; $i++)
            if(is_uploaded_file($_FILES['file'.$i]['tmp_name']) && $this->validate($_FILES['file'.$i])) {

            move_uploaded_file($_FILES['file'.$i]['tmp_name'],
                dirname(__DIR__) . self::UPLOAD_DIRECTORY . $_FILES['file'.$i]['name']
            );
        }
    }

    public function removeOffer() {
        $id = $_POST['id'];
        $idArray = $this->offerRepository->getDatabaseOfferInfo($id);
        $this->offerRepository->deleteOffer($idArray[0]['id_aircraft'], $idArray[0]['id_aircraft_details'], $idArray[0]['id_address']);
        $this->my_offers();
    }



}