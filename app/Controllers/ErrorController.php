<?php

namespace App\Controllers;

// Classe gérant les erreurs (404, 403)
class ErrorController extends CoreController
{
    public function __construct()
    {
        // ici, on fait rien !

        // ce constructeur sert juste à éviter la vérification de sécu des ACL
        // de toutes façons, on a pas besoin de sécuriser les pages d'erreur !
    }

    /**
     * Méthode gérant l'affichage de la page 404
     *
     * @return void
     */
    public function err404()
    {
        // On envoie le header 404
        //header('HTTP/1.0 404 Not Found');
        // on peut aussi l'envoyer avec http_response_code()
        http_response_code(404);

        // Puis on gère l'affichage
        $this->show('error/err404');
    }

    public function err403()
    {
        // on envoie le code HTTP 403
        http_response_code(403);

        // on affiche la vue erreur 403
        $this->show('error/err403');
    }
}
