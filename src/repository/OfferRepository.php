<?php

require_once 'Repository.php';
require_once 'AddressRepository.php';
require_once 'AircraftRepository.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/Offer.php';
require_once __DIR__.'/../models/Aircraft.php';

class OfferRepository extends Repository
{
    public function getOffer(int $id): ?Offer
    {
        //TODO registation
        $addressRepository = new AddressRepository();
        $aircraftRepository = new AircraftRepository();

        $offers = $this->getAllOffers();
        $aircraft = $aircraftRepository->getAircraftByOfferID($id);
        $addresses = $addressRepository->getAllAddresses();

        $offers_array = $this->getOffersArray($offers,$aircraft,$addresses);

        return $offers_array[0];
    }

    public function getOffers(): array
   {
       $addressRepository = new AddressRepository();
       $aircraftRepository = new AircraftRepository();

       $offers = $this->getAllOffers();
       $aircrafts = $aircraftRepository->getAllAircrafts();
       $addresses = $addressRepository->getAllAddresses();

       return $this->getOffersArray($offers,$aircrafts,$addresses);
   }

    public function getUserOffers(User $user) {
        $addressRepository = new AddressRepository();
        $aircraftRepository = new AircraftRepository();

        $userId = $user->getId();
        $stmt = $this->database->connect()->prepare('
            SELECT * FROM offers WHERE created_by = :id
        ');
        $stmt->bindParam(':id', $userId, PDO::PARAM_STR);
        $stmt->execute();
        $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $aircrafts = $aircraftRepository->getAllAircrafts();
        $addresses = $addressRepository->getAllAddresses();

        return $this->getOffersArray($offers,$aircrafts,$addresses);
    }

    public function addOffer (Offer $offer, User $user) {
        $date = new DateTime();
        $addressRepository = new AddressRepository();
        $aircraftRepository = new AircraftRepository();
        $pdo = $this->database->connect();

        try {
            $pdo->beginTransaction();

            $aircraftId = $aircraftRepository->insertAirplane($pdo,$offer);
            $addressId = $addressRepository->insertAddress($pdo, $offer->getAddress());
            $offerId = $this->insertOffer($pdo,$offer,$aircraftId,$date, $user->getId(), $addressId);

            $pdo->commit();
        } catch (\PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    private function insertOffer ($pdo, $offer, $aircraftId, $date, $user_id, $addressId) {
        $stmt = $pdo->prepare('
            INSERT INTO offers (title, description, price, created_at, id_aircraft, created_by, id_address)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            ');

        $stmt->execute([
            $offer->getTitle(),
            $offer->getDescription(),
            $offer->getPrice(),
            $date->format('Y-m-d'),
            $aircraftId,
            $user_id,
            $addressId
        ]);
        return $pdo->lastInsertID('offers_id_seq');
    }

    private function getOffersArray ($offers, $aircrafts, $addresses) {
        $result = [];
        foreach ($offers as $offer) {
            foreach ($aircrafts as $aircraft) {
                foreach ($addresses as $address) {
                    if ($aircraft->getId() == $offer['id_aircraft'] &&
                        $address->getId() === $offer['id_address']) {

                        $offerResult = new Offer(
                            $offer['title'],
                            $offer['description'],
                            $offer['price'],
                            $aircraft,
                            $address
                        );
                        $offerResult->setId($offer['id']);
                        $result[] = $offerResult;
                    }
                }
            }
        }
        return $result;
    }

    private function getAllOffers () {
        $stmt = $this->database->connect()->prepare('
           SELECT * FROM offers;
        ');
        $stmt->execute();
        return $offers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDatabaseOfferInfo($id) {
        $stmt = $this->database->connect()->prepare('
           SELECT DISTINCT offer.id_aircraft, aircraft.id_aircraft_details, offer.id_address 
           FROM offers offer, aircrafts aircraft, addresses address
           WHERE offer.id = :id AND aircraft.id = offer.id_aircraft;
        ');
        $stmt->bindParam(':id', $id, PDO::PARAM_STR);
        $stmt->execute();
        return  $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteOffer($aircraftId, $aircraftDetailsId, $addressId) {
        $addressRepository = new AddressRepository();
        $pdo = $this->database->connect();
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare('
           DELETE FROM aircrafts WHERE id = :id
        ');
            $stmt->bindParam(':id', $aircraftId, PDO::PARAM_STR);
            $stmt->execute();
            $stmt2 = $pdo->prepare('
           DELETE FROM aircraft_details WHERE id = :id
        ');
            $stmt2->bindParam(':id', $aircraftDetailsId, PDO::PARAM_STR);
            $stmt2->execute();

            $addressRepository->deleteAddress($pdo, $addressId);

            $pdo->commit();
        } catch (\PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

}