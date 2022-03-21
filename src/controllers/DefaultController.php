<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/UserDetails.php';

class DefaultController extends AppController {
    private $offerRepository;
    private $userRepository;

    const MAX_FILE_SIZE = 1024*1024;
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/../public/uploads/';

    public function __construct()
    {
        parent::__construct();
        $this->offerRepository = new OfferRepository();
        $this->userRepository = new UserRepository();
    }
    //TODO check everywhere if admin or user session
    public function index()
    {
        $offers = $this->offerRepository->getOffers();
        $this->render('home', ['offers'=>$offers]);
    }
    public function home()
    {
        $offers = $this->offerRepository->getOffers();
        $this->render('home', ['offers'=>$offers]);
    }
    public function login()
    {
        $this->render('login');
    }
    public function register()
    {
        $this->render('register');
    }
    public function admin_dashboard() {
        session_start();
        $offers = $this->offerRepository->getOffers();
        if (isset($_SESSION['admin'])) {
            $this->render('admin_dashboard', ['admin' => $this->userRepository->getUser($_SESSION['admin']),
                'offers' => $offers]);
        }
        else $this->render('home', ['offers'=>$offers]);
    }

    public function profile()
    {
        session_start();
        if (isset($_SESSION['user']))
        $this->render('profile', ['user'=>$this->userRepository->getUser($_SESSION['user']),
            'offers'=>$this->offerRepository->getUserOffers($this->userRepository->getUser($_SESSION['user']))]);
        if (isset($_SESSION['admin']))
            $this->render('profile', ['user'=>$this->userRepository->getUser($_SESSION['admin']),
                'offers'=>$this->offerRepository->getUserOffers($this->userRepository->getUser($_SESSION['admin']))]);
    }
    public function profile_edit()
    {
        session_start();
        if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
            header('LOCATION: http://localhost:8080/login');
            exit();
        }
        if (isset($_SESSION['user']))
            $this->render('profile_edit', ['user'=>$this->userRepository->getUser($_SESSION['user']),
                'offers'=>$this->offerRepository->getUserOffers($this->userRepository->getUser($_SESSION['user']))]);
        if (isset($_SESSION['admin']))
            $this->render('profile_edit', ['user'=>$this->userRepository->getUser($_SESSION['admin']),
                'offers'=>$this->offerRepository->getUserOffers($this->userRepository->getUser($_SESSION['admin']))]);
    }

    public function account()
    {
        session_start();
        if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
            header('LOCATION: http://localhost:8080/login');
            exit();
        }
        if (isset($_SESSION['user'])) {
        $this->render('account', ['user' => $this->userRepository->getUser($_SESSION['user'])]);
        }
        if (isset($_SESSION['admin'])) {
            $this->render('account', ['user' => $this->userRepository->getUser($_SESSION['admin'])]);
        }
    }

    public function updateProfile() {
        session_start();
        if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
            header('LOCATION: http://localhost:8080/login');
            exit();
        }
        if ($this->isPost()){

            $userDetails = new UserDetails();

        if (is_uploaded_file($_FILES['file']['tmp_name']) && $this->validate($_FILES['file'])) {
            move_uploaded_file($_FILES['file']['tmp_name'],
                dirname(__DIR__).self::UPLOAD_DIRECTORY.$_FILES['file']['name']
            );
            $userDetails->setImage("public/uploads/".$_FILES['file']['name']);
        }
        $userDetails->setDescription($_POST['description']);

        if (isset($_SESSION['user'])) {
            $user = $this->userRepository->getUser($_SESSION['user']);
        }
        if (isset($_SESSION['admin'])) {
            $user = $this->userRepository->getUser($_SESSION['admin']);
        }

        $this->userRepository->updateUserProfile($user, $userDetails);

        $this->profile();
        }
        else {
            $this->profile();
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

}