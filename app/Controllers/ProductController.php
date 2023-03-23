<?php

// on range ici tout le CRUD sur les produits

namespace App\Controllers;

use App\Models\Tag;
use App\Models\Product;
use App\Controllers\CoreController;

class ProductController extends CoreController
{
    public function list()
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        // je récupère tous mes produits
        $products = Product::findAll();

        // et je les envoie à la vue !
        $this->show('product/product_list', [
            'products' => $products
        ]);
    }

    public function add()
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        // on créé un produit vide pour éviter d'avoir une erreur 
        // (à cause du fait qu'on a un seul template pour ajout/modif !)
        $product = new Product();

        // on récupère TOUS les tags, pour afficher les checkboxes
        $tags = Tag::findAll();

        // étant donné que c'est un nouveau produit, il n'est pas encore associé à des tags !
        $selectedTagsID = [];

        $this->show('product/product_form', [
            'product' => $product,
            'tags' => $tags,
            'selectedTags' => $selectedTagsID
        ]);
    }

    public function addPost()
    {
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        //dd($_POST);

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $picture = $_POST['picture'] ?? '';
        $status = $_POST['status'] ?? '';
        $price = $_POST['price'] ?? '';
        $brand_id = $_POST['brand_id'] ?? '';
        $type_id = $_POST['type_id'] ?? '';
        $category_id = $_POST['category_id'] ?? '';

        $tags = $_POST['tags'] ?? [];

        // on créé un nouvel objet Product
        $product = new Product();

        // on alimente cet objet (on remplit ses propriétés)
        $product->setName($name);
        $product->setPicture($picture);
        $product->setDescription($description);
        $product->setBrandId($brand_id);
        $product->setCategoryId($category_id);
        $product->setTypeId($type_id);
        $product->setPrice($price);
        $product->setStatus($status);

        // on dit à cet objet de s'insérer dans la base !
        // insert() renvoit true si l'ajout a fonctionné, false sinon
        $success = $product->insert();

        // on ajoute les tags sélectionnées au produit
        foreach($tags as $id => $value) {
            $product->addTag($id);
        }

        if($success) {
            // redirection vers la liste des catégories
            header('Location: /product/list');
            exit;
        } else {
            // ça n'a pas fonctionné, on met un message d'erreur.
            die("Erreur lors de l'ajout.");
        }
    }

    public function update($id)
    {
        // le product backlog nous dit "seuls les catalog-manager & admins peuvent modifier un produit
        // $this->checkAuthorization(['catalog-manager', 'admin']);

        // on récupère le produit à modifier
        // pour pré-remplir le form !
        $product = Product::find($id);

        // on récupère TOUS les tags, pour afficher les checkboxes
        $tags = Tag::findAll();

        // on récupère les tags associés à ce produit
        $selectedTags = Tag::findAllByProductId($id);
        
        // on boucle sur les selectedTags pour reconstruire un tableau contenant uniquement leurs IDs
        $selectedTagsID = [];
        foreach($selectedTags as $tag) {
            $selectedTagsID[] = $tag->getId();
        }

        $this->show('product/product_form', [
            'product' => $product,
            'tags' => $tags,
            'selectedTags' => $selectedTagsID
        ]);
    }

    public function updatePost($id)
    {
        // on doit aussi vérifier la route en post
        // $this->checkAuthorization(['catalog-manager', 'admin']);

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $picture = $_POST['picture'] ?? '';
        $status = $_POST['status'] ?? '';
        $price = $_POST['price'] ?? '';
        $brand_id = $_POST['brand_id'] ?? '';
        $type_id = $_POST['type_id'] ?? '';
        $category_id = $_POST['category_id'] ?? '';

        $tags = $_POST['tags'] ?? [];

        //dd($tags);

        // on récupère l'objet Product à modifier
        $product = Product::find($id);

        // on alimente cet objet (on remplit ses propriétés)
        $product->setName($name);
        $product->setPicture($picture);
        $product->setDescription($description);
        $product->setBrandId($brand_id);
        $product->setCategoryId($category_id);
        $product->setTypeId($type_id);
        $product->setPrice($price);
        $product->setStatus($status);

        // on dit à cet objet de s'insérer dans la base !
        // insert() renvoit true si l'ajout a fonctionné, false sinon
        $success = $product->update();

        // on supprime TOUS les tags déjà associés à ce produit
        $product->removeAllTags();

        // on ajoute les tags sélectionnées au produit
        foreach($tags as $id => $value) {
            $product->addTag($id);
        }

        if($success) {
            // redirection vers la liste des catégories
            header('Location: /product/list');
            exit;
        } else {
            // ça n'a pas fonctionné, on met un message d'erreur.
            die("Erreur lors de l'ajout.");
        }
    }
}