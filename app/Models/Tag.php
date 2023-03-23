<?php

namespace App\Models;

use PDO;
use App\Utils\Database;

class Tag extends CoreModel
{
    private $name;

    public static function find($id)
    {
        // se connecter à la BDD
        $pdo = Database::getPDO();

        // écrire notre requête
        $sql = 'SELECT * FROM `tag` WHERE `id` =' . $id;

        // exécuter notre requête
        $pdoStatement = $pdo->query($sql);

        // un seul résultat => fetchObject
        $tag = $pdoStatement->fetchObject('App\Models\Tag');

        // retourner le résultat
        return $tag;
    }

    public static function findAll()
    {
        $pdo = Database::getPDO();
        $sql = 'SELECT * FROM `tag`';
        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Tag');

        return $results;
    }

    public static function findAllByProductId($product_id)
    {
        $pdo = Database::getPDO();

        $sql = "SELECT tag.id, tag.name
        FROM tag
        INNER JOIN product_has_tag AS pht ON pht.tag_id = tag.id
        INNER JOIN product AS pro ON pro.id = pht.product_id
        WHERE pro.id = {$product_id}";

        $pdoStatement = $pdo->query($sql);
        $results = $pdoStatement->fetchAll(PDO::FETCH_CLASS, 'App\Models\Tag');

        return $results;
    }

    public function insert()
    {
        // TODO
    }

    public function update()
    {
        // TODO
    }

    public function delete()
    {
        // TODO
    }


    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}