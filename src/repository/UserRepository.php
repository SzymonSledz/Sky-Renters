<?php

require_once 'Repository.php';
require_once __DIR__.'/../models/User.php';
require_once __DIR__.'/../models/UserDetails.php';

class UserRepository extends Repository
{
    public function getUser(string $email): ?User
    {
        $stmt = $this->database->connect()->prepare('
            SELECT u.*, ud.image, ud.description, ud.id AS uid, ut.type AS user_type FROM public.users AS u, public.user_details AS ud, user_types AS ut
            WHERE u.email = :email AND u.id_user_details = ud.id AND u.id_user_type = ut.id
        ');
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user == false) {
            //TODO return exception
            return null;
        }

        $userDetails = new UserDetails();
        $userDetails->setImage($user['image']);
        $userDetails->setDescription($user['description']);
        $userDetails->setId($user['uid']);

        $resultUser = new User(
        $user['id'],
        $user['email'],
        $user['password'],
        $user['name'],
        $user['surname'],
        $user['user_type']
    );
        $resultUser->setUserDetails($userDetails);

        return $resultUser;
    }

    public function addUser(User $user) {
        $pdo = $this->database->connect();

        $userDetails = new UserDetails();
        $userDetails->setImage("public/img/no-image-available.jpg");
        $userDetails->setDescription(null);

        try {
            $pdo->beginTransaction();

            $userDetailsId = $this->insertUserDetails($pdo, $userDetails);
            $this->insertUser($pdo, $user, $userDetailsId);

                $pdo->commit();
        } catch (\PDOException $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    private function insertUser($pdo, $user, $userDetailsId) {
        $stmt = $pdo->prepare('
            INSERT INTO users (name, surname, email, password, id_user_details)
            VALUES (?, ?, ?, ?, ?)
            ');

        $stmt->execute([
            $user->getName(),
            $user->getSurname(),
            $user->getEmail(),
            $user->getPassword(),
            $userDetailsId
        ]);
    }

    private function insertUserDetails($pdo,UserDetails $userDetails) {
        $stmt = $pdo->prepare('
            INSERT INTO user_details (image, description)
            VALUES (?, ?)
            ');

        $stmt->execute([
            $userDetails->getImage(),
            $userDetails->getDescription()
        ]);
        return $pdo->lastInsertID('user_details_id_seq');
    }

    public function updateUserProfile($user, UserDetails $userDetails) {
        if ($userDetails->getImage() != null) {
            $stmt = $this->database->connect()->prepare('
            UPDATE user_details SET image = :image, description = :description WHERE id = :id
        ');
            $stmt->bindParam(':id', $user->getUserDetails()->getId(), PDO::PARAM_STR);
            $stmt->bindValue(':image', $userDetails->getImage(), PDO::PARAM_STR);
            $stmt->bindValue(':description', $userDetails->getDescription(), PDO::PARAM_STR);
            $stmt->execute();
        }
        else {
            $stmt = $this->database->connect()->prepare('
            UPDATE user_details SET description = :description WHERE id = :id
        ');
            $stmt->bindParam(':id', $user->getUserDetails()->getId(), PDO::PARAM_STR);
            $stmt->bindValue(':description', $userDetails->getDescription(), PDO::PARAM_STR);
            $stmt->execute();
        }
    }

    public function changePassword($user, $password) {
        $stmt = $this->database->connect()->prepare('
            UPDATE users SET password = :password WHERE id = :id
        ');
        $stmt->bindParam(':id', $user->getId(), PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
    }

}