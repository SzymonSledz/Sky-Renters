<?php

require_once 'AppController.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/Address.php';
require_once __DIR__.'/../repository/UserRepository.php';

class SecurityController extends AppController
{

    public function login (){
        $userRepository = new UserRepository();

        if (!$this->isPost()) {
            return $this->render('login');
        }

        $email = $_POST["email"];
        $password = $_POST["password"];

        $user = $userRepository->getUser($email);

        if (!$user) {
            return $this->render('login', ['messages' => ['User with this email does not exist!']]);
        }

        if(!password_verify($password, $user->getPassword())) {
            $this->render('login', ['messages' => ['Wrong password']]);
        }
        else {
            session_start();
            if ($user->getUserType() === "user") {
                $_SESSION['user'] = htmlspecialchars($_POST['email']);
                header('Location: http://localhost:8080/home');
            }
            if ($user->getUserType() === "admin") {
                $_SESSION['admin'] = htmlspecialchars($_POST['email']);
                header('Location: http://localhost:8080/admin_dashboard');
            }
        }
    }

    public function logout() {
        ob_start();
        session_start();
        session_unset();
        unset($_SESSION['user']);
        session_regenerate_id(true);
        session_unset();
        session_destroy();
        session_write_close();
        setcookie(session_name(),'',0,'/');
        header('LOCATION: http://localhost:8080/home');
    }

    public function register_new_user() {

        $userRepository = new UserRepository();
        if (!$this->isPost()) {
            return $this->render('register', ['messages' => ['Post']]);
        }

        //TODO validate data !zipCode
        $name = $_POST["name"];
        $surname = $_POST["surname"];
        $age = $_POST["age"];
        $country = $_POST["country"];
        $state = $_POST["state"];
        $town = $_POST["town"];
        $street = $_POST["street"];
        $number = $_POST["number"];
        $localNumber = $_POST["localNumber"];
        $zipCode = $_POST["zipCode"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];

        $address = new Address($country, $state, $town, $street, $number, $localNumber,$zipCode);

        if ($password === $confirmPassword) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            $user = new User(0, $email,$password, $name, $surname);
            $user->setAddress($address);

            if ($userRepository->getUser($email)) {
                return $this->render('register', ['messages' => ['User with this email already exists']]);
            }

            $userRepository->addUser($user);
            header('LOCATION: http://localhost:8080/login');
        }
        return $this->render('register', ['messages' => ['Passwords are not the same']]);
    }

    public function changePassword()
    {
        if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])) {
            header('LOCATION: http://localhost:8080/login');
            exit();
        }
        $userRepository = new UserRepository();

        if (!$this->isPost()) {
            return $this->render('profile', ['messages' => ['Post']]);
        }

        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmPassword"];

        if ($password === $confirmPassword && $password != "" && $password != null) {
            $password = password_hash($password, PASSWORD_DEFAULT);
            session_start();
            $user = $userRepository->getUser($_SESSION['user']);
            $userRepository->changePassword($user, $password);
            return $this->render('profile', ['messages' => ['Post']]);
        }
        else {
            return $this->render('profile', ['messages' => ['Passwords are not the same']]);
        }
    }

}