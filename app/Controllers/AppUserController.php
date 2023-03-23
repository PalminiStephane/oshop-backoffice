<?php

namespace App\Controllers;

use App\Models\AppUser;

class AppUserController extends CoreController
{
    public function list()
    {
        // seuls les admins ont le droit de lister les users
        //$this->checkAuthorization(['admin']);

        // pour récupérer la liste des users, on utilise AppUser::findAll()
        $users = AppUser::findAll();

        // on envoie les données à la vue
        $this->show('appuser/appuser_list', [
            'users' => $users
        ]);
    }

    public function add()
    {
        // seuls les admins ont le droit de créer des users
        //$this->checkAuthorization(['admin']);

        // on créé un user vide à envoyer à notre form
        $user = new AppUser();

        $this->show('appuser/appuser_form', [
            'user' => $user
        ]);
    }

    public function addPost()
    {
        // seuls les admins ont le droit de créer des users
        //$this->checkAuthorization(['admin']);

        //dd($_POST);

        $firstname = $_POST['firstname'] ?? '';
        $lastname = $_POST['lastname'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $status = $_POST['status'] ?? '';
        $role = $_POST['role'] ?? '';

        // VALIDATION DES DONNÉES
        // on créé un tableau pour stocker les erreurs éventuelles
        $errorList = [];

        // TODO : à améliorer !

        // est-ce que l'adresse email est correcte ?
        if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorList[] = "L'adresse email est mal-formée !";
        }

        // est-ce que le mot de passe respecte la complexité demandée
        if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_])[\w\W]{8,}$/', $password)) {
            $errorList[] = "Le mot de passe doit contenir 8 caractères, au moins une lettre en minuscule, au moins une lettre en majuscule, au moins un chiffre et au moins un caractère spécial parmi ['_', '-', '|', '%', '&', '*', '=', '@', '$']";
        }

        // le prénom doit contenir au moins 1 caractères
        if( mb_strlen($firstname, 'utf-8') < 1 ) {
            $errorList[] = "Le prénom doit contenir au moins 1 caractère !";
        }
        // le nom doit contenir au moins 1 caractères
        if( mb_strlen($lastname, 'utf-8') < 1 ) {
            $errorList[] = "Le nom doit contenir au moins 1 caractère !";
        }

        // on doit vérifier si l'adresse email n'est pas déjà prise !
        // findByEmail renvoit false si l'email n'est pas déjà utilisé 
        if (AppUser::findByEmail($email)) {
            $errorList[] = "L'adresse email saisie existe déjà.";
        }

        // on vérifie si les deux mots de passe sont identiques
        if ($password !== $password_confirm) {
            $errorList[] = "Les deux mots de passe ne sont pas identiques.";
        }


        // on vérifie si on a rencontré une erreur
        if(empty($errorList)) {
            // si le tableau errorList est vide, ça veut dire qu'il n'y a pas d'erreur
            // donc on peut ajouter à la DB !

            // on créé un nouvel objet User
            $user = new AppUser();

            // on hash le mot de passe de l'user !
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // on alimente cet objet (on remplit ses propriétés)
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setEmail($email);
            $user->setPassword($hash);
            $user->setRole($role);
            $user->setStatus($status);

            // on dit à cet objet de s'insérer dans la base !
            $success = $user->save();

            if($success) {
                // redirection vers la liste des catégories
                header('Location: /user/list');
                exit;
            } else {
                // ça n'a pas fonctionné, on met un message d'erreur.
                $errorList[] = "Erreur lors de l'ajout.";
            }

        }

        // si on arrive là, c'est qu'il y a eu une erreur
        // on réaffiche le formulaire, mais pré-rempli avec les (mauvaises) données saisies

        // on créé un nouvel objet User
        $user = new AppUser();

        // on alimente cet objet (on remplit ses propriétés)
        // avec les données potentiellement erronées
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setRole($role);
        $user->setStatus($status);

        // on affiche à nouveau de form d'ajout, mais avec les erreurs & les données erronées
        $this->show('appuser/appuser_form', [
            'user' => $user,
            'errorList' => $errorList
        ]);
    }
}