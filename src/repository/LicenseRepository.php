<?php

require_once 'Repository.php';

class LicenseRepository extends Repository
{
    public function getAllLicenseTypes() {
        $stmt = $this->database->connect()->prepare('
           SELECT * FROM license_types;
        ');
        $stmt->execute();
        $license_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $license_types;
    }

    public function getAllAircraftHasLicenseTypes() {
        $stmt = $this->database->connect()->prepare('
           SELECT * FROM aircraft_has_license_types;
        ');
        $stmt->execute();
        $aircrafts_license_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $aircrafts_license_types;
    }

    public function getAllAircraftLicenseTypes ($id) {
        $res = [];
        $stmt = $this->database->connect()->prepare('
           SELECT lt.name FROM aircraft_has_license_types ahlt, license_types lt WHERE ahlt.id_aircraft = :id AND lt.id = ahlt.id_license_type;
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $aircrafts_license_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($aircrafts_license_types as $aircrafts_license_type) {
            $res[] = array_values($aircrafts_license_types);
        }

        $res[] = array_unique($res);
        return $res;
    }



    public function insertAircraftLicenseTypes($pdo, $aircraftId, $licenseTypes) {
        $licenseIds = $this->getUserCheckedLicenseTypesIdsPDO($pdo, $licenseTypes[0]);

        foreach ($licenseIds as $licenseId) {
            $stmt = $pdo->prepare('
            INSERT INTO aircraft_has_license_types (id_aircraft, id_license_type) 
            VALUES (?, ?)
            ');

            $stmt->execute([
                $aircraftId,
                intval($licenseId)
            ]);
        }
    }

    private function getUserCheckedLicenseTypesIdsPDO ($pdo, $licenseTypes) {
        $result = [];

        foreach ($licenseTypes as $licenseType) {
            $stmt = $pdo->prepare('
                SELECT id FROM license_types WHERE name = :name
                ');
            $stmt->bindParam(':name', $licenseType, PDO::PARAM_STR);
            $stmt->execute();
            $qres = $stmt->fetch(PDO::FETCH_ASSOC);
            $result[] = $qres['id'];
        }

        return $result;
    }

}