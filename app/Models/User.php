<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class User extends CoreModel
{

    private $email;
    private $password;
    private $firstname;
    private $lastname;
    private $role;
    private $status;

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of firstname
     */ 
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set the value of firstname
     *
     * @return  self
     */ 
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of role
     */ 
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set the value of role
     *
     * @return  self
     */ 
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }


    public static function find($id)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `app_user` WHERE `id` = :id';

        // on prépare notre requête
        $pdoStatement = $pdo->prepare($sql);

        // on bind nos params
        $pdoStatement->bindParam(":id", $id);

        // on lance la requete        
        $pdoStatement->execute();
        // on récupère un seul résultat => fetchObject
        $user = $pdoStatement->fetchObject('App\Models\User');

        // retourner le résultat
        return $user;
    }

    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `app_user`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\User');

        return $results;
    }

    /**
     * findByEmail permet de récupérer un utilisateur par son email
     * 
     * @param String $email l'email de l'utilisateur
     * @return AppUser|false retourne un objet AppUser ou false si non trouvé
     * 
     * @link http://google.fr
     * 
     */
    public static function findByEmail($email)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `app_user` WHERE `email` = :email';

        // on prépare notre requête
        $pdoStatement = $pdo->prepare($sql);

        // on bind nos params
        $pdoStatement->bindParam(":email", $email);

        // on lance la requete        
        $pdoStatement->execute();
        // on récupère un seul résultat => fetchObject
        $user = $pdoStatement->fetchObject('App\Models\User');

        // retourner le résultat
        return $user;
    }

    public function insert()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO
        $sql = "
            INSERT INTO `app_user` (email, password, firstname, lastname, role, status)
            VALUES (:email, :password, :firstname, :lastname, :role, :status)
        ";

        // On prépare notre requête SQL
        $pdoStatement = $pdo->prepare($sql);

        // on "bind" nos paramètres
        $pdoStatement->bindParam(':email', $this->email);
        $pdoStatement->bindParam(':password', $this->password);
        $pdoStatement->bindParam(':firstname', $this->firstname);
        $pdoStatement->bindParam(':lastname', $this->lastname);
        $pdoStatement->bindParam(':role', $this->role);
        $pdoStatement->bindParam(':status', $this->status);


        // Execution de la requête préparée avec execute()
        $success = $pdoStatement->execute();

        // Si la requête a fonctionné
        if ($success) {
            // Alors on récupère l'id auto-incrémenté généré par MySQL
            $this->id = $pdo->lastInsertId();

            // On retourne VRAI car l'ajout a parfaitement fonctionné
            return true;
            // => l'interpréteur PHP sort de cette fonction car on a retourné une donnée
        }

        // Si on arrive ici, c'est que quelque chose n'a pas bien fonctionné => FAUX
        return false;
    }

    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            UPDATE `app_user`
            SET
                email = :email,
                password = :password,
                firstname = :firstname,
                lastname = :lastname,
                role = :role,
                status = :status,
                updated_at = NOW()
            WHERE id = :id
        ";

        // On prépare notre requête SQL
        $pdoStatement = $pdo->prepare($sql);

        // on "bind" nos paramètres
        $pdoStatement->bindParam(':email', $this->email);
        $pdoStatement->bindParam(':password', $this->password);
        $pdoStatement->bindParam(':firstname', $this->firstname);
        $pdoStatement->bindParam(':lastname', $this->lastname);
        $pdoStatement->bindParam(':role', $this->role);
        $pdoStatement->bindParam(':status', $this->status);
        // et on oublie pas l'id !
        $pdoStatement->bindParam(':id', $this->id);

        // Execution de la requête préparée de mise à jour
        $success = $pdoStatement->execute();

        // On retourne VRAI, si au moins une ligne modifiée
        return $success;
    }

    public function delete()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // on écrit notre requête SQL
        $sql = "DELETE FROM app_user WHERE id = :id";

        // on prépare cette requête
        $pdoStatement = $pdo->prepare($sql);

        // on bind nos paramètres
        $pdoStatement->bindParam(":id", $this->id);

        // on exécute la requête
        $success = $pdoStatement->execute();

        // on retourne true ou false en fonction de si la requête s'est bien passée ou pas !
        return $success;
    }
}
