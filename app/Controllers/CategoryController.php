<?php

// on range ici tout le CRUD sur les catégories

namespace App\Controllers;

use App\Models\Category;
use App\Controllers\CoreController;

class CategoryController extends CoreController
{
    /**
     * list() gère le R (read) du CRUD
     */
    public function list()
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        // jusqu'à maintenant, pour récupérer des données, on commençait par instancier notre modèle
        //$categoryModel = new Category();
        // et ensuite, on pouvait utiliser findAll() pour récupérer les données
        //$categories = $categoryModel->findAll();

        // maintenant, on va utiliser une méthode STATIQUE
        // * Une méthode statique peut être appelée sans avoir besoin d'instancier la classe dans laquelle elle est définie !
        // * Les méthodes statiques sont des méthodes utiles au niveau de la Classe, et pas au niveau de l'objet 
        $categories = Category::findAll();

        // on prépare notre $viewData
        $viewData = [
            'categories' => $categories
        ];

        // on envoie les données à la vue !
        $this->show('category/category_list', $viewData);

        // ou on peut directement envoyer les données, en une seule ligne :
        // $this->show('category/category_list', [
        //     'categories' => $categories
        // ]);
    }

    /**
     * add() gère le C (create) du CRUD
     * affiche le formulaire d'ajout
     */
    public function add()
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        // on créé une catégorie vide
        $category = new Category();

        // on affiche notre formulaire d'ajout
        // on lui envoie une catégorie vide, qu'on créé à la volée avec new Category
        // on est obligé de faire ça, parce que dans notre vue on essaye d'accéder à $category->getName(), $category->getSubtitle()
        $this->show('category/category_add', [
            'category' => $category 
        ]);
    }

    /**
     * réceptionne les données envoyées par le form affiché sur la route category-add
     */
    public function addPost()
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        //dump($_POST);

        // on utilise l'opérateur de coalescence nulle 
        // https://www.php.net/manual/en/migration70.new-features.php
        // cet opérateur nous permet de mettre la valeur de $_POST['name'] dans $name s'il est défini, sinon ''
        $name = $_POST['name'] ?? '';
        $subtitle = $_POST['subtitle'] ?? '';
        $picture = $_POST['picture'] ?? '';

        // on pourrait aussi faire comme ça :
        // if(isset($_POST['picture'])) {
        //     $picture = $_POST['picture'];
        // } else {
        //     $picture = '';
        // }


        // VALIDATION DES DONNÉES

        // on créé un tableau pour stocker les erreurs éventuelles
        $errorList = [];

        // TODO : remplacer strlen par mb_strlen("utf-8") pour résoudre le bug des accents !
        // voir ici : https://www.askingbox.com/question/php-strlen-wrong-result-for-diacritics-accents-and-unicode-characters

        // le nom doit contenir au moins 3 caractères
        if( strlen($name) < 3 ) {
            // ERREUR !
            //die("le nom doit contenir au moins 3 caractères !");
            $errorList[] = "Le nom doit contenir au moins 3 caractères !";
        }

        // le sous-titre doit contenir au moins 5 caractères
        if( strlen($subtitle) < 5 ) {
            // ERREUR !
            //die("le sous-titre doit contenir au moins 5 caractères !");
            $errorList[] = "Le sous-titre doit contenir au moins 5 caractères !";
        }

        //dump(substr($picture, 0, 8));

        // l'image doit commencer par http:// ou https://
        if( substr($picture, 0, 7) !== "http://" && substr($picture, 0, 8) !== "https://") {
            // ERREUR !
            //die("l'URL de l'image doit commencer par http:// ou https:// !");
            $errorList[] = "L'URL de l'image doit commencer par http:// ou https:// !";
        }

        // on vérifie si on a rencontré une erreur
        if(empty($errorList)) {
            // si le tableau errorList est vide, ça veut dire qu'il n'y a pas d'erreur
            // donc on peut ajouter à la DB !

            // on créé un nouvel objet Category
            $category = new Category();

            // on alimente cet objet (on remplit ses propriétés)
            $category->setName($name);
            $category->setSubtitle($subtitle);
            $category->setPicture($picture);

            // on dit à cet objet de s'insérer dans la base !
            // insert() renvoit true si l'ajout a fonctionné, false sinon
            //$success = $category->insert();
            $success = $category->save();

            if($success) {
                // redirection vers la liste des catégories
                header('Location: /category/list');
                exit;
            } else {
                // ça n'a pas fonctionné, on met un message d'erreur.
                $errorList[] = "Erreur lors de l'ajout.";
            }

        }

        // si on arrive là, c'est qu'il y a eu une erreur
        // on réaffiche le formulaire, mais pré-rempli avec les (mauvaises) données saisies

        // on créé un nouvel objet Category
        $category = new Category();

        // on alimente cet objet (on remplit ses propriétés)
        // avec les données potentiellement erronées
        $category->setName($name);
        $category->setSubtitle($subtitle);
        $category->setPicture($picture);

        // on affiche à nouveau de form d'ajout, mais avec les erreurs & les données erronées
        $this->show('category/category_add', [
            'category' => $category,
            'errorList' => $errorList
        ]);
        
    }

    /**
     * affiche le form de modification
     * (et le pré-rempli avec les données de la catégorie à modifier)
     */
    public function update($id)
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        $category = Category::find($id);

        //dump($category);
        // si $category = false
        // alors -> 404

        $this->show('category/category_update', [
            'category' => $category
        ]);
    }

    /**
     * réceptionne les données envoyées par le form de modification
     */
    public function updatePost($id)
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        //dump($_POST);

        // on utilise l'opérateur de coalescence nulle 
        // https://www.php.net/manual/en/migration70.new-features.php
        // cet opérateur nous permet de mettre la valeur de $_POST['name'] dans $name s'il est défini, sinon ''
        $name = $_POST['name'] ?? '';
        $subtitle = $_POST['subtitle'] ?? '';
        $picture = $_POST['picture'] ?? '';

        // VALIDATION DES DONNÉES

        // on créé un tableau pour stocker les erreurs éventuelles
        $errorList = [];

        // mb_strlen est à utiliser à la place de strlen, pour compter correctement les accents !
        //dd(mb_strlen($name, "utf-8"));

        // le nom doit contenir au moins 3 caractères
        if( mb_strlen($name, "utf-8") < 3 ) {
            // ERREUR !
            //die("le nom doit contenir au moins 3 caractères !");
            $errorList[] = "Le nom doit contenir au moins 3 caractères !";
        }

        // le sous-titre doit contenir au moins 5 caractères
        if( mb_strlen($subtitle, "utf-8") < 5 ) {
            // ERREUR !
            //die("le sous-titre doit contenir au moins 5 caractères !");
            $errorList[] = "Le sous-titre doit contenir au moins 5 caractères !";
        }

        // l'image doit commencer par http:// ou https://
        if( substr($picture, 0, 7) !== "http://" && substr($picture, 0, 8) !== "https://") {
            // ERREUR !
            //die("l'URL de l'image doit commencer par http:// ou https:// !");
            $errorList[] = "L'URL de l'image doit commencer par http:// ou https:// !";
        }

        // on vérifie si on a rencontré une erreur
        if(empty($errorList)) {
            // si le tableau errorList est vide, ça veut dire qu'il n'y a pas d'erreur
            // donc on peut effectuer les modifications en DB !

            // on récupère l'objet Category à modifier
            $category = Category::find($id);

            // on alimente cet objet (on remplit ses propriétés)
            $category->setName($name);
            $category->setSubtitle($subtitle);
            $category->setPicture($picture);

            // on dit à cet objet de se mettre à jour dans la base !
            // update() renvoit true si la modification a fonctionné, false sinon
            //$success = $category->update();
            $success = $category->save();

            if($success) {
                // redirection vers la liste des catégories
                header('Location: /category/list');
                // on s'assure de quitter le script PHP
                // ça vient de la doc PHP !
                exit;
            } else {
                // ça n'a pas fonctionné, on met un message d'erreur.
                $errorList[] = "Erreur lors de la modification.";
            }

        }

        // si on arrive là, c'est qu'il y a eu une erreur
        // on réaffiche le formulaire, mais pré-rempli avec les (mauvaises) données saisies

        // on créé un nouvel objet Category
        // là c'est pas si on créé une nouvelle catégorie au lieu de 
        // récupérer la catégorie à modifier, vu que le $category en question sert juste à ré-afficher les erreurs
        $category = new Category();

        // on alimente cet objet (on remplit ses propriétés)
        // avec les données potentiellement erronées
        $category->setName($name);
        $category->setSubtitle($subtitle);
        $category->setPicture($picture);

        // on affiche à nouveau de form d'ajout, mais avec les erreurs & les données erronées
        $this->show('category/category_update', [
            'category' => $category,
            'errorList' => $errorList
        ]);
    }

    public function delete($id)
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);
        
        // on récupère la catégorie à supprimer
        $category = Category::find($id);

        // on supprime la catégorie
        $success = $category->delete();

        // si la suppression a fonctionné
        if($success) {
            // on redirige vers la liste des catégories
            header("Location: /category/list");
            exit;
        }

        // sinon on pourrait afficher un message d'erreur !
        die("Erreur lors de la suppression.");
    }


    public function order()
    {
        // on récupère toutes les catégories
        $categories = Category::findAll();

        // on affiche le form
        $this->show('category/category_home-order', [
            'categories' => $categories
        ]);
    }

    public function orderPost()
    {
        //dd($_POST);

        // on vérifie si le tableau existe
        if(isset($_POST['emplacement'])) {
            
            // on envoie le tableau d'emplacements à notre modèle Category
            Category::defineHomeOrder($_POST['emplacement']);
        }

        header("Location: /category/order");
        exit;
    }
}