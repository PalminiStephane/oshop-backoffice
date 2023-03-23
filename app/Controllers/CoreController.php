<?php

namespace App\Controllers;

class CoreController
{

    /**
     * le constructeur de CoreController sera exécuté systématiquement
     * sur toutes nos pages !
     * 
     * ça veut dire que le code qu'on met là dedans est exécuté sur toutes les pages,
     * AVANT l'affichage de la page !
     */
    public function __construct()
    {
        // ce dump est visible sur TOUTES les pages
        //dump('coucou !');

        // ici, c'est pile poil le bon endroit pour faire notre vérification de sécurité !

        // pour récupérér le nom de la route que veut visiter l'user, on a besoin de $match
        global $match;

        // quelle page veut visiter l'user ?
        $routeName = $match['name'];

        // le tableau dans lequel on stocke les droits d'accès
        // On définit la liste des permissions pour les routes nécessitant une connexion utilisateur
        $acl = [
            // 'security-login' => [], => pas besoin, la route est libre d'accès
            'main-home' => ['admin', 'catalog-manager'],
            'appuser-list' => ['admin'],
            'appuser-add' => ['admin'],
            'appuser-addpost' => ['admin'],
            'category-add' => ['admin', 'catalog-manager'],
            'category-addpost' => ['admin', 'catalog-manager'],
            'category-list' => ['admin', 'catalog-manager'],
            'category-update' => ['admin', 'catalog-manager'],
            'category-updatepost' => ['admin', 'catalog-manager'],
            'category-delete' => ['admin', 'catalog-manager'],
            'category-order' => ['admin', 'catalog-manager'],
            'category-orderpost' => ['admin', 'catalog-manager'],
            'product-add' => ['admin', 'catalog-manager'],
            'product-addpost' => ['admin', 'catalog-manager'],
            'product-list' => ['admin', 'catalog-manager'],
            'product-update' => ['admin', 'catalog-manager'],
            'product-updatepost' => ['admin', 'catalog-manager'],
            'product-delete' => ['admin', 'catalog-manager'],
            // etc.
        ];

        // est-ce que l'user a le rôle qui l'autorise à accéder à cette page ?
        // pour ça, on va regarder dans un tableau si le rôle de l'user est autorisé à vister la page

        // on vérifie si la route demandée existe dans le tableau d'ACL
        // array_key_exists vérifie l'existance d'une clé dans un tableau associatif
        if(array_key_exists($routeName, $acl)) {
            // la route est bien dans le tableau d'ACL, ça veut dire qu'elle est "sécurisée"

            // on récupère les rôles autorisés à accéder à cette route
            $allowedRoles = $acl[$routeName];

            // on vérifie si le role de l'user lui permet d'y accéder !
            $this->checkAuthorization($allowedRoles);
        }

        // si la route n'existe pas dans le tableau, RAS, on laisse l'user y accéder
        // donc on fait rien !

        // SECURITÉ ANTI-CSRF

        // tableau pour stocker les routes sur lesquelles on va GÉNÉRER un token CSRF
        $csrfTokenToCreate = [
            'appuser-add', // on génère le token sur la route en GET
            'category-add',
            'category-order',
            'category-list' // on génère un token sur la liste des catégorie pour ajout sur les liens de suppression !
            // TODO : rajouter les autres routes à protéger
        ];

        // tableau pour stocker les routes sur lesquelles on va VÉRIFIER le token CSRF
        $csrfTokenToCheck = [
            'appuser-addpost', // on vérifie le token sur la route en POST
            'category-addpost',
            'category-orderpost',
            'category-delete' // Pour la suppression, on vérifie le token en GET
            // TODO : rajouter les autres routes à protéger
        ];

        // est-ce que la route actuelle fait partie des routes pour lesquelles on doit générer un token
        if(in_array($routeName, $csrfTokenToCreate)) {
            // la route demandée par le visiteur fait bien partie des routes pour lesquelles on doit générer un token

            // générer le token anti-CSRF
            $token = bin2hex(random_bytes(32));
            // et le stocker !
            $_SESSION['token'] = $token;
        }

        // est-ce que la route actuelle nécessite une vérification du token anti-CSRF ?
        if(in_array($routeName, $csrfTokenToCheck)) {
            // la route demandée nécessite une vérif du token 

            // on récupère le token reçu
            $receivedToken = $_POST['token'] ?? $_GET['token'] ?? '';

            // si le token est vide, on essaye de le récupèrer en GET !
            // if($receivedToken === '') {
            //     $receivedToken = $_GET['token'] ?? '';
            // }

            // on récupère le token stocké
            $storedToken = $_SESSION['token'] ?? null;

            // on compare le token reçu dans le form avec celui stocké
            if($receivedToken === $storedToken) {
                // s'ils sont identiques, on supprime le token stocké
                unset($_SESSION['token']);

                // on autorise la soumission du form !
                // on a rien à faire !
            } else {
                // le token soumis ne correspond pas au token stocké
                // on redirige vers une erreur 403

                $controller = new ErrorController();
                $controller->err403();
                // on arrête le script, pour que la ressource demandée ne s'affiche pas
                exit;
            }      
        }

    }

    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewData Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewData = [])
    {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;

        // Comme $viewData est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewData['currentPage'] = $viewName;

        // définir l'url absolue pour nos assets
        $viewData['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewData['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewData, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewData);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewData est disponible dans chaque fichier de vue
        require_once __DIR__ . '/../views/layout/header.tpl.php';
        require_once __DIR__ . '/../views/' . $viewName . '.tpl.php';
        require_once __DIR__ . '/../views/layout/footer.tpl.php';
    }

    public function checkAuthorization($authorizedRoles = [])
    {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;

        // est-ce que l'utilisateur est connecté ?
        if(isset($_SESSION['userObject'])) {
            // si oui ...
            // on récupère l'utilisateur connecté (on a l'info en session !)
            $user = $_SESSION['userObject'];
            // on récupère son rôle (est-ce qu'il est admin, catalog-manager, ou autre)
            $role = $user->getRole();
            // est-ce que son rôle fait partie des rôles autorisés à accéder à la ressource actuelle ?
            if(in_array($role, $authorizedRoles)) {
                // si le rôle de l'user fait partie du tableau $authorizedRoles
                // alors on retourne true
                return true;
            } else {
                // sinon, l'utilisateur n'a pas le droit d'accéder à la ressource demandée
                // on envoie le code HTTP 403 Forbidden
                // on lui affiche une page d'erreur 403                
                $controller = new ErrorController();
                $controller->err403();
                // on arrête le script, pour que la ressource demandée ne s'affiche pas
                exit;
            }
        } else {
            // s'il n'est pas connecté

            // alors on le redirige vers la page de connexion
            header("Location: " . $router->generate('security-login'));
            exit;
        }
            
        
    }
}
