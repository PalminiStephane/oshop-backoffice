<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\CoreModel;

// Si j'ai besoin du Model Category
// use App\Models\Category;

class MainController extends CoreController
{
    /**
     * Méthode s'occupant de la page d'accueil
     *
     * @return void
     */
    public function home()
    {
        // seuls les admins et les catalog-manager peuvent accéder à la page d'accueil
        // $this->checkAuthorization(['admin', 'catalog-manager']);

        $categories = Category::findFirstFive();
        $products = Product::findFirstFive();

        // pour tester qu'on a bien les infos de l'user
        //dump($_SESSION);

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show('main/home', [
            'categories' => $categories,
            'products' => $products
        ]);
    }
}
