<?php

//TODO do I need this?
require_once __DIR__.'/Address.php';

class User
{
    private $id;
    private $email;
    private $password;
    private $name;
    private $surname;
    private $Address;
    private $userDetails;
    private $userType;

    public function __construct(int $id, string $email, string $password, string $name, string $surname, string $userType)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
        $this->userType = $userType;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname)
    {
        $this->surname = $surname;
    }

    public function getAddress()
    {
        return $this->Address;
    }

    public function setAddress($Address)
    {
        $this->Address = $Address;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserDetails()
    {
        return $this->userDetails;
    }

    public function setUserDetails($userDetails): void
    {
        $this->userDetails = $userDetails;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }



}