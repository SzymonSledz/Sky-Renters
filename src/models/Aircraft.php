<?php

class Aircraft
{
    //TODO registration number - airplane recognition

    //TODO array
    private $id;
    private $category;
    private $make;
    private $model;
    //TODO array
    private $licenseTypes = [];
    private $yearOfProduction;
    private $details = [];
    private $image = [];
    private $landingGear;


    public function __construct($category, $make, $model, $licenseTypes, $yearOfProduction, $image, $landingGear)
    {
        $this->category = $category;
        $this->make = $make;
        $this->model = $model;
        $this->licenseTypes[] = $licenseTypes;
        $this->yearOfProduction = $yearOfProduction;
        $this->image[] = $image;
        $this->landingGear = $landingGear;
    }


    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getMake()
    {
        return $this->make;
    }

    public function setMake($make)
    {
        $this->make = $make;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function getLicenseTypes()
    {
        return $this->licenseTypes;
    }

    public function setLicenseTypes($licenseType)
    {
        $this->licenseType = $licenseType;
    }

    public function getYearOfProduction()
    {
        return $this->yearOfProduction;
    }

    public function setYearOfProduction($yearOfProduction)
    {
        $this->yearOfProduction = $yearOfProduction;
    }

    public function getImage($number)
    {

        $r = [];
        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($this->image));
        foreach($it as $v) {
            $r[] = $v;
        }
        $r=array_unique(array_values($r));

        if ($r[$number] == NULL) return "no-image-available.jpg";
        else return strval($r[$number]);
    }

    public function setImage($image, $number)
    {
        $this->image[$number] = $image;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function printLicenseTypes() {
        $r = [];
        $it = new RecursiveIteratorIterator(new RecursiveArrayIterator($this->licenseTypes));
        foreach($it as $v) {
            $r[] = $v;
        }
        $r=array_unique(array_values($r));

        foreach ($r as $res) {
            echo $res. " ";
        }
    }

    public function printCategory() {

    }

    public function getLandingGear()
    {
        return $this->landingGear;
    }

    public function setLandingGear($landingGear): void
    {
        $this->landingGear = $landingGear;
    }

    public function getDetails(): array
    {
        return $this->details;
    }

    public function setDetails(array $details): void
    {
        $this->details = $details;
    }



}