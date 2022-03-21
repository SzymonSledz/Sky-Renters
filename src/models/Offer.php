<?php

require_once __DIR__.'/Address.php';

//TODO flight rules options

class Offer
{
    private $title;
    private $description;
    private $price;
    //TODO Address
    private $Address;
    private $Aircraft;
    private $id;

    public function __construct($title, $description, $price, $Aircraft,Address $Address)
    {
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->Aircraft = $Aircraft;
        $this->Address = $Address;
    }


    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getAircraft()
    {
        return $this->Aircraft;
    }

    public function setAircraft($Aircraft)
    {
        $this->Aircraft = $Aircraft;
    }

    public function getAddressString()
    {
        $address = $this->Address->getCountry() . " " . $this->Address->getState() . " " . $this->Address->getCity() ." ". $this->Address->getStreet() ." ". $this->Address->getNumber() ." ". $this->Address->getZipCode();
        return $address;
    }

    public function getAddress() {
        return $this->Address;
    }

    public function setAddress($Address): void
    {
        $this->Address = $Address;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }



}