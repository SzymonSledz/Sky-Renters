<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/Aircraft.php';
require_once __DIR__.'/../models/Offer.php';
require_once 'LicenseRepository.php';

class AircraftRepository extends Repository
{
    public function getAllAircrafts() {
        $licenseRepository = new LicenseRepository();
        $result = [];
        $stmt = $this->database->connect()->prepare('
           SELECT DISTINCT a.id AS id, a.year_of_production AS yearOfProduction,
                  am.make AS make, amod.model AS model
                , ac.name AS ac_cat_name, alg.landing_gear AS landing_gear,
                ad.hangared AS hangared, ad.load AS load, ad.seats AS seats
           FROM aircrafts a, aircraft_categories ac, aircraft_details ad,
            aircraft_has_license_types ahlt, license_types lt, aircrafts_models amod, aircrafts_makes am,
            aircrafts_landing_gears alg
           WHERE a.id_aircraft_category = ac.id AND a.id_aircraft_details = ad.id AND
                 ahlt.id_aircraft = a.id AND ahlt.id_license_type = lt.id AND am.id = ad.id_make
                AND amod.id = ad.id_model AND ad.id_landing_gear = alg.id;
        ');
        $stmt->execute();
        $aircrafts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($aircrafts as $aircraft) {
            $aircraftLicenseTypes[] = $licenseRepository->getAllAircraftLicenseTypes($aircraft['id']);
            $aircraftsImages = $this->getAircraftImages($aircraft['id']);

            $aircraftResult = new Aircraft($aircraft['ac_cat_name'], $aircraft['make'], $aircraft['model'], $aircraftLicenseTypes, $aircraft['year_of_production'], $aircraftsImages, $aircraft['landing_gear']);
            $aircraftResult->setId($aircraft['id']);

            $details = $this->getAircraftDetails([
                'load' => $aircraft['load'],
                'seats' => $aircraft['seats'],
                'hangared' => $aircraft['hangared']
            ]);
            $aircraftResult->setDetails($details);
            $result[] = $aircraftResult;
        }
        return $result;
    }

    public function getAircraftByOfferID($id) {
        $licenseRepository = new LicenseRepository();
        $result = [];
        $stmt = $this->database->connect()->prepare('
           SELECT DISTINCT a.id AS id, a.year_of_production AS year_of_production,
                  am.make AS make, amod.model AS model
                , ac.name AS ac_cat_name, alg.landing_gear AS landing_gear,
                ad.hangared AS hangared, ad.load AS load, ad.seats AS seats
           FROM offers o, aircrafts a, aircraft_categories ac, aircraft_details ad,
            aircraft_has_license_types ahlt, license_types lt, aircrafts_models amod, aircrafts_makes am,
            aircrafts_landing_gears alg 
           WHERE o.id = :id AND o.id_aircraft = a.id AND a.id_aircraft_category = ac.id AND a.id_aircraft_details = ad.id AND
                 ahlt.id_aircraft = a.id AND ahlt.id_license_type = lt.id AND am.id = ad.id_make
                AND amod.id = ad.id_model AND ad.id_landing_gear = alg.id;
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $aircrafts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($aircrafts as $aircraft) {
            $aircraftLicenseTypes[] = $licenseRepository->getAllAircraftLicenseTypes($aircraft['id']);
            $aircraftsImages = $this->getAircraftImages($aircraft['id']);

            $aircraftResult = new Aircraft($aircraft['ac_cat_name'], $aircraft['make'], $aircraft['model'], $aircraftLicenseTypes, $aircraft['year_of_production'], $aircraftsImages, $aircraft['landing_gear']);
            $aircraftResult->setId($aircraft['id']);
            $details = $this->getAircraftDetails([
                'load' => $aircraft['load'],
                'seats' => $aircraft['seats'],
                'hangared' => $aircraft['hangared']
            ]);
            $aircraftResult->setDetails($details);
            $result[] = $aircraftResult;
        }
        return $result;
    }

    public function getAircraftImages($id): array
    {
        $stmt = $this->database->connect()->prepare('
           SELECT image FROM aircraft_images WHERE id_aircraft = :id;
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $aircraft_images = $stmt->fetchALL(PDO::FETCH_NUM);

        return $aircraft_images;
    }

    public function getAllAircraftCategories() {
        $stmt = $this->database->connect()->prepare('
           SELECT * FROM aircraft_categories;
        ');
        $stmt->execute();
        $aircraft_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $aircraft_categories;
    }

    public function getAircraftCategoryId($category) {
        $stmt = $this->database->connect()->prepare('
           SELECT id FROM aircraft_categories WHERE name = :category;
        ');
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->execute();
        $aircraft_categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $aircraft_categories;
    }

    public function getAllAircraftLandingGears() {
        $stmt = $this->database->connect()->prepare('
           SELECT * FROM aircrafts_landing_gears;
        ');
        $stmt->execute();
        $aircraft_landing_gears = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $aircraft_landing_gears;
    }

    public function getAircraftDetails($details) {
        $result = [];
        if ($details['load'] != NULL) {
            $result['load'] = $details['load'];
        }
        if ($details['seats'] != NULL) {
            $result['seats'] = $details['seats'];
        }
        if ($details['hangared'] != NULL) {
            $result['hangared'] = $details['hangared'];
        }
        return $result;
    }

    public function insertAirplane($pdo, $offer) {
        $licenseRepository = new LicenseRepository();
        $categoryId = $this->getAircraftCategoryId($offer->getAircraft()->getCategory());

        $detailsId = $this->insertAircraftDetails($pdo, $offer);

        $stmt = $pdo->prepare('
            INSERT INTO aircrafts (year_of_production, id_aircraft_category, id_aircraft_details)
            VALUES (?, ?, ?)
            ');
        $stmt->execute([
            $offer->getAircraft()->getYearOfProduction(),
            $categoryId[0]['id'],
            $detailsId
        ]);

        $aircraftId = $pdo->lastInsertID('aircrafts_id_seq');

        $this->insertAircraftImages($pdo, $offer, $aircraftId);
        $licenseRepository->insertAircraftLicenseTypes($pdo, $aircraftId, $offer->getAircraft()->getLicenseTypes());

        return $aircraftId;
    }

    private function insertAircraftImages($pdo,Offer $offer, $idAircraft) {
        for ($i = 0; $i < 5; $i++)
        if (!$offer->getAircraft()->getImage($i) == NULL && $offer->getAircraft()->getImage($i) != "no-image-available.jpg") {
            $stmt = $pdo->prepare('
            INSERT INTO aircraft_images (image, id_aircraft)
            VALUES (?, ?)
            ');
            $stmt->execute([
                $offer->getAircraft()->getImage($i),
                $idAircraft
            ]);
        }
    }

    private function insertAircraftDetails($pdo, $offer) {
        $makeId = $this->insertAircraftMake($pdo, $offer->getAircraft()->getMake());
        $modelId = $this->insertAircraftModel($pdo, $offer->getAircraft()->getModel());
        $landingGearId = $this->insertAircraftLandingGear($pdo, $offer->getAircraft()->getLandingGear());

        $stmt = $pdo->prepare('
            SELECT id FROM aircraft_details WHERE id_make = :make AND id_model = :model 
                                                AND id_landing_gear = :landingGear
            ');
        $stmt->bindParam(':make', $makeId, PDO::PARAM_STR);
        $stmt->bindParam(":model", $modelId, PDO::PARAM_STR);
        $stmt->bindParam(':landingGear',$landingGearId, PDO::PARAM_STR);
        $stmt->execute();
        $resultDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultDetails[0]['id']==NULL) {
            $stmt = $pdo->prepare('
            INSERT INTO aircraft_details (id_make, id_model, id_landing_gear)
            VALUES (?, ?, ?)
            ');

            $stmt->execute([
                $makeId,
                $modelId,
                $landingGearId
            ]);
            return $pdo->lastInsertID('aircraft_details_id_seq');
        }
        else {
            return $resultDetails[0]['id'];
        }
    }

    private function insertAircraftMake($pdo, $make) {
        $stmt = $pdo->prepare('
            SELECT id FROM aircrafts_makes WHERE make =:make
            ');
        $stmt->bindParam(':make', $make, PDO::PARAM_STR);
        $stmt->execute();
        $resultMake = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultMake[0]['id']==NULL) {
            $stmt = $pdo->prepare('
            INSERT INTO aircrafts_makes (make)
            VALUES (?)
            ');

            $stmt->execute([
                $make
            ]);
            return $pdo->lastInsertID('aircrafts_make_id_seq');
        }
        else {
            return $resultMake[0]['id'];
        }
    }

    private function insertAircraftModel($pdo, $model) {
        $stmt = $pdo->prepare('
            SELECT id FROM aircrafts_models WHERE model =:model
            ');
        $stmt->bindParam(':model', $model, PDO::PARAM_STR);
        $stmt->execute();
        $resultModel = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultModel[0]['id']==NULL) {
            $stmt = $pdo->prepare('
            INSERT INTO aircrafts_models (model)
            VALUES (?)
            ');

            $stmt->execute([
                $model
            ]);
            return $pdo->lastInsertID('aircrafts_models_id_seq');
        }
        else {
            return $resultModel[0]['id'];
        }
    }

    private function insertAircraftLandingGear($pdo, $landingGear) {
        $stmt = $pdo->prepare('
            SELECT id FROM aircrafts_landing_gears WHERE landing_gear =:landingGear
            ');
        $stmt->bindParam(':landingGear', $landingGear, PDO::PARAM_STR);
        $stmt->execute();
        $resultLandingGear = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultLandingGear[0]['id']==NULL) {
            $stmt = $pdo->prepare('
            INSERT INTO aircrafts_landing_gears (id, landing_gear)
            VALUES (?)
            ');

            $stmt->execute([
                $landingGear
            ]);
            return $pdo->lastInsertID('landing_gear_id_seq');
        }
        else {
            return $resultLandingGear[0]['id'];
        }
    }

}