<?php

class Address
{
    private $country;
    private $state;
    private $city;
    private $street;
    private $number;
    private $zipCode;
    private $localNumber;
    private $id;

    public function __construct($country, $state, $city, $street, $number, $localNumber, $zipCode)
    {
        $this->country = $country;
        $this->state = $state;
        $this->city = $city;
        $this->street = $street;
        $this->number = $number;
        $this->zipCode = $zipCode;
        $this->localNumber = $localNumber;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setStreet($street)
    {
        $this->street = $street;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function getZipCode()
    {
        return $this->zipCode;
    }

    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

    public function getLocalNumber()
    {
        return $this->localNumber;
    }

    public function setLocalNumber($localNumber)
    {
        $this->localNumber = $localNumber;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state): void
    {
        $this->state = $state;
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