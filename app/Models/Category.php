<?php

namespace App\Models;

use App\Utils\Database;
use PDO;

class Category extends CoreModel
{

    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $subtitle;
    /**
     * @var string
     */
    private $picture;
    /**
     * @var int
     */
    private $home_order;

    /**
     * Get the value of name
     *
     * @return  string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @param  string  $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get the value of subtitle
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set the value of subtitle
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    /**
     * Get the value of picture
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set the value of picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * Get the value of home_order
     */
    public function getHomeOrder()
    {
        return $this->home_order;
    }

    /**
     * Set the value of home_order
     */
    public function setHomeOrder($home_order)
    {
        $this->home_order = $home_order;
    }

    /**
     * Méthode permettant de récupérer un enregistrement de la table Category en fonction d'un id donné
     *
     * @param int $categoryId ID de la catégorie
     * @return Category
     */
    public static function find($categoryId)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `category` WHERE `id` =' . $categoryId;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $category = $pdoStatement->fetchObject('App\Models\Category');

        // retourner le résultat
        return $category;
    }

    /**
     * Méthode permettant de récupérer tous les enregistrements de la table category
     * * Pour définir que cette méthode est STATIQUE (c'est à dire une méthode utile au niveau de la classe)
     * * on ajoute le mot-clé "static" !
     *
     * @return Category[]
     */
    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `category`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

        return $results;
    }

    /**
     * Récupérer les 5 catégories mises en avant sur la home
     *
     * @return Category[]
     */
    public static function findAllHomepage()
    {
        $pdo = Database::getPDO();
        $sql = '
            SELECT *
            FROM category
            WHERE home_order > 0
            ORDER BY home_order ASC
        ';
        $pdoStatement = $pdo->query($sql);
        $categories = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

        return $categories;
    }

    public static function findFirstFive()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `category` ORDER BY id LIMIT 5';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Category');

        return $results;
    }

    /**
     * Méthode permettant d'ajouter un enregistrement dans la table category
     * L'objet courant doit contenir toutes les données à ajouter : 1 propriété => 1 colonne dans la table
     *
     * @return bool
     */
    public function insert()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête INSERT INTO
        $sql = "
            INSERT INTO `category` (name, subtitle, picture)
            VALUES (:name, :subtitle, :picture)
        ";

        // On prépare notre requête SQL
        $pdoStatement = $pdo->prepare($sql);

        // on "bind" nos paramètres
        $pdoStatement->bindParam(':name', $this->name);
        $pdoStatement->bindParam(':subtitle', $this->subtitle);
        $pdoStatement->bindParam(':picture', $this->picture);


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

    /**
     * Méthode permettant de mettre à jour un enregistrement dans la table category
     * L'objet courant doit contenir l'id, et toutes les données à ajouter : 1 propriété => 1 colonne dans la table
     *
     * @return bool
     */
    public function update()
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // Ecriture de la requête UPDATE
        $sql = "
            UPDATE `category`
            SET
                name = :name,
                subtitle = :subtitle,
                picture = :picture,
                updated_at = NOW()
            WHERE id = :id
        ";

        // On prépare notre requête SQL
        $pdoStatement = $pdo->prepare($sql);

        // on "bind" nos paramètres
        $pdoStatement->bindParam(':name', $this->name);
        $pdoStatement->bindParam(':subtitle', $this->subtitle);
        $pdoStatement->bindParam(':picture', $this->picture);
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
        $sql = "DELETE FROM category WHERE id = :id";

        // on prépare cette requête
        $pdoStatement = $pdo->prepare($sql);

        // on bind nos paramètres
        $pdoStatement->bindParam(":id", $this->id);

        // on exécute la requête
        $success = $pdoStatement->execute();

        // on retourne true ou false en fonction de si la requête s'est bien passée ou pas !
        return $success;
    }

    public static function defineHomeOrder($emplacements)
    {
        // Récupération de l'objet PDO représentant la connexion à la DB
        $pdo = Database::getPDO();

        // on reset tous les home_order
        $resetSQL = "UPDATE `category` 
        SET `home_order` = 0;";

        // on lance la requête SQL
        $pdoStatement = $pdo->query($resetSQL);

        // on boucle sur le tableau d'emplacements
        for($i=0; $i<5; $i++) {
            // dans notre tableau, home_order correspond à l'index du tableau + 1
            $home_order = $i + 1;

            // on récupère l'ID de la catégorie stocké dans le tableau
            $category_id = $emplacements[$i];

            // on prepare notre requête SQL
            $updateSQL = "UPDATE `category` 
            SET `home_order` = :homeorder
            WHERE `id` = :id;";

            // on prépare cette requête
            $pdoStatement = $pdo->prepare($updateSQL);

            // on bind nos paramètres
            $pdoStatement->bindParam(":homeorder", $home_order);
            $pdoStatement->bindParam(":id", $category_id);

            // on exécute la requête
            $pdoStatement->execute();
        } 
    }
}
