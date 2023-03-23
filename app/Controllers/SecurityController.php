<?php

namespace App\Controllers;

use App\Models\Brand;
use App\Models\AppUser;

class SecurityController extends CoreController
{
    public function login()
    {
        $this->show('security/login');
    }

    public function loginPost()
    {
        //dd($_POST);

        // jusqu'à maintenant, on faisait comme ça
        //$email = $_POST['email'] ?? '';
        //$password = $_POST['password'] ?? '';

        // on récupère les données avec filter_input, ce coup-ci !
        // https://www.php.net/manual/en/function.filter-input.php
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        // FILTER_VALIDATE_EMAIL permet de valider / vérifier que l'adresse email
        // est correcte, si elle l'est filter_input renvoit l'adresse
        // si elle n'est pas correcte, filter_input renvoit false
        $password = filter_input(INPUT_POST, 'password');

        // on créé un tableau pour stocker nos erreurs
        $errorList = [];

        // si l'email est incorrect ou vide, filter_input renvoit false
        if($email == false) {
            // afficher un message d'erreur
            $errorList[] = "L'adresse email saisie est vide ou malformée.";
        }

        // est-ce que le password est vide ?
        if(empty($password)) {
            $errorList[] = "Merci de renseigner le champ \"mot de passe\"";
        }

        // on tente de récupérer l'user correspondant à l'email
        $user = AppUser::findByEmail($email);

        // on vérifie si l'utilisateur existe
        if($user) {
            // on vérifie si le mot de passe est correct
            // password_verify nous permet de comparer le mdp saisi par l'user dans le form avec le hash stocké en BDD !
            // https://www.php.net/manual/en/function.password-verify.php

            if(password_verify($password, $user->getPassword())) {
                //die("OK !");

                //session_start();
                // on va plutôt démarrer les sessions dans index.php
                // comme ça, on est tranquilles dans toute notre app !

                // on "connecte" l'utilisateur -> on enregistre ses infos en session !
                $_SESSION['userId'] = $user->getId();
                $_SESSION['userObject'] = $user;

                //dd($_SESSION);

                // une fois les données enregistrées en session, on peut rediriger l'utilisateur
                header("Location: /");
                exit;
            } else {
                // cas ou le mdp est incorrect
                //! on ne précise JAMAIS si l'email est correct ou pas, on reste "vague"
                $errorList[] = "Email ou password invalide.";
            }
        } else {
            // cas ou l'email est incorrect
            $errorList[] = "Email ou password invalide.";
        }

        
        // si on arrive ici, c'est que le login n'a pas fonctionné
        // donc on réaffiche notre formulaire en lui envoyant les erreurs !
        $this->show('security/login', [
            'errorList' => $errorList
        ]);
    }

    public function logout()
    {
        // on ferme la session de l'utilisateur
        session_destroy();

        // on pourrait aussi juste supprimer les variables de sessions userId et userObject
        //unset($_SESSION['userId']);
        //unset($_SESSION['userObject']);

        // on redirige vers la page d'accueil
        header("Location: /");
        exit;
    }
}