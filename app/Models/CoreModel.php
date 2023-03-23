<?php

namespace App\Models;

// Classe mère de tous les Models
// On centralise ici toutes les propriétés et méthodes utiles pour TOUS les Models

// CoreModel est une classe abstraite (abstract), ça veut dire qu'on ne pourra pas l'instancier !
abstract class CoreModel
{
    /**
     * @var int
     */
    protected $id;
    /**
     * @var string
     */
    protected $created_at;
    /**
     * @var string
     */
    protected $updated_at;


    /**
     * Get the value of id
     *
     * @return  int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the value of created_at
     *
     * @return  string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * Get the value of updated_at
     *
     * @return  string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }

    // une méthode abstract : on ne l'implémente pas !
    // on définit juste sa "signature", comme visible ci-dessous
    // le principe ? ces méthodes-là devront OBLIGATOIREMENT être implémentées par les 
    // classes qui vont hériter du CoreModel

    // R de CRUD (Read)
    public abstract static function find($id);
    public abstract static function findAll();

    public function save()
    {
        // si le modèle courant a un id supérieur à 0, c'est qu'il a déjà été enregistré en base
        // donc on veut faire un update
        if ($this->getId() > 0) {
            return $this->update();
        }
        // sinon, c'est que le modèle n'a jamais été enregistré, donc on veut le créer en base
        else {
            return $this->insert();
        }
    }

    // C de CRUD (Create)
    public abstract function insert();

    // U de CRUD (Update)
    public abstract function update();

    // D de CRUD (Delete)
    //public abstract function delete();
}
