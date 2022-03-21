<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/Offer.php';

class AddressRepository extends Repository
{
    public function insertAddress ($pdo, $address) {

        $countryId = $this->insertCountry($pdo, $address->getCountry());
        $stateId = $this->insertState($pdo, $address->getState());
        $cityId = $this->insertCity($pdo, $address->getCity());
        $streetId = $this->insertStreet($pdo, $address->getStreet());
        $zipCodeId = $this->insertZipCode($pdo, $address->getZipCode());

        //check if address already in addresses
        $stmt = $pdo->prepare('
            SELECT a.id FROM addresses a, cities ci, countries co, streets str, states sta, zip_codes z 
            WHERE a.id_city=ci.id AND a.id_country = co.id AND a.id_street = str.id AND a.id_state = sta.id AND a.id_zip_code = z.id
            AND ci.id = ? AND co.id = ? AND str.id = ? AND sta.id = ? AND z.id = ? AND a.number = ?
            ');
        $stmt->execute([
            $cityId,
            $countryId,
            $streetId,
            $stateId,
            $zipCodeId,
            $address->getNumber()
        ]);
        $resultAddress = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultAddress[0]['id']==null) {
            //if not add address into addresses
            $stmt1 = $pdo->prepare('
            INSERT INTO addresses (id_country, id_city, id_zip_code, id_street, number, id_state)
            VALUES (?, ?, ?, ?, ?, ?)
            ');

            $stmt1->execute([
                $countryId,
                $cityId,
                $zipCodeId,
                $streetId,
                $address->getNumber(),
                $stateId
            ]);
            return $pdo->lastInsertID('addresses_id_seq');
        }
        else return $resultAddress[0]['id'];

    }
    private function insertCountry ($pdo, $country) {
        $stmt = $pdo->prepare('
            SELECT id FROM countries WHERE name =:country
            ');
        $stmt->bindParam(':country', $country, PDO::PARAM_STR);
        $stmt->execute();
        $resultCountry = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultCountry[0]['id']==NULL) {
            $stmt = $pdo->prepare('
            INSERT INTO countries (name)
            VALUES (?)
            ');

            $stmt->execute([
                $country
            ]);
            return $pdo->lastInsertID('countries_id_seq');
        }
        else {
            return $resultCountry[0]['id'];
        }
    }

    private function insertState ($pdo, $state) {
        $stmt = $pdo->prepare('
            SELECT id FROM states WHERE name =:state
            ');
        $stmt->bindParam(':state', $state, PDO::PARAM_STR);
        $stmt->execute();
        $resultState = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultState[0]['id']==NULL) {
            $stmt = $pdo->prepare('
            INSERT INTO states (name)
            VALUES (?)
            ');

            $stmt->execute([
                $state
            ]);
            return $pdo->lastInsertID('states_id_seq');
        }
        else {
            return $resultState[0]['id'];
        }
    }

    private function insertCity ($pdo, $city) {
        $stmt = $pdo->prepare('
            SELECT id FROM cities WHERE name =:city
            ');
        $stmt->bindParam(':city', $city, PDO::PARAM_STR);
        $stmt->execute();
        $resultCity = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultCity[0]['id']==NULL) {
            $stmt = $pdo->prepare('
            INSERT INTO cities (name)
            VALUES (?)
            ');

            $stmt->execute([
                $city
            ]);
            return $pdo->lastInsertID('cities_id_seq');
        }
        else {
            return $resultCity[0]['id'];
        }
    }

    private function insertStreet ($pdo, $street) {
        $stmt = $pdo->prepare('
            SELECT id FROM streets WHERE name =:street
            ');
        $stmt->bindParam(':street', $street, PDO::PARAM_STR);
        $stmt->execute();
        $resultStreet = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultStreet[0]['id']==NULL) {
            $stmt = $pdo->prepare('
            INSERT INTO streets (name)
            VALUES (?)
            ');

            $stmt->execute([
                $street
            ]);
            return $pdo->lastInsertID('streets_id_seq');
        }
        else {
            return $resultStreet[0]['id'];
        }
    }

    private function insertZipCode ($pdo, $zipCode) {
        $stmt = $pdo->prepare('
            SELECT id FROM zip_codes WHERE code =:zipCode
            ');
        $stmt->bindParam(':zipCode', $zipCode, PDO::PARAM_STR);
        $stmt->execute();
        $resultZipCode = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($resultZipCode[0]['id']==NULL) {
            $stmt = $pdo->prepare('
            INSERT INTO zip_codes (code)
            VALUES (?)
            ');

            $stmt->execute([
                $zipCode
            ]);
            return $pdo->lastInsertID('zip_codes_id_seq');
        }
        else {
            return $resultZipCode[0]['id'];
        }
    }

    public function getAddress($pdo, $id)
    {
        $stmt = $pdo->prepare('
            SELECT a.id FROM addresses a, cities ci, countries co, streets str, states sta, zip_codes z 
            WHERE a.id_city=ci.id AND a.id_country = co.id AND a.id_street = str.id AND a.id_state = sta.id AND a.id_zip_code = z.id
            ');
        $stmt->execute();
        $resultaddress = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($resultaddress[0]['id'] == null) {
            //if not add address into addresses

        }
    }

    public function getAllAddresses() {
        $result = [];
        $stmt2 = $this->database->connect()->prepare('
            SELECT a.id, ci.name AS city_name, co.name AS country_name, str.name AS street_name, sta.name AS state_name, z.code, a.number FROM addresses a, cities ci, countries co, streets str, states sta, zip_codes z 
            WHERE a.id_city=ci.id AND a.id_country = co.id AND a.id_street = str.id AND a.id_state = sta.id AND a.id_zip_code = z.id
            ');
        $stmt2->execute();
        $addresses = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        foreach ($addresses as $address) {
            $addressRes = new Address(
                $address['country_name'],
                $address['state_name'],
                $address['city_name'],
                $address['street_name'],
                $address['number'],
                NULL,
                $address['code']
            );
            $addressRes->setId($address['id']);
            $result[] = $addressRes;
        }
        return $result;
    }

    public function deleteAddress($pdo, $id) {
        $stmt = $pdo->prepare('
            SELECT COUNT(o.id_address)
            FROM offers o
            WHERE o.id_address = :id;
            ');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        $addressesCount = $stmt->fetch(PDO::FETCH_NUM);
        if ($addressesCount[0] == 0) {
            $stmt = $pdo->prepare('
                DELETE FROM addresses WHERE id = :id;
            ');
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
        }
    }

}