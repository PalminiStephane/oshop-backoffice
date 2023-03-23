# L'authentification, comment on fait CONCRÊTEMENT ?

Mettons des mots sur notre schéma de tout à l'heure :grin:

## Décomposons en étapes

1. On l'a dit tout à l'heure, il faut bien que l'user puisse saisir son email et son mot de passe quelque part.
2. Les données de ce formulaire seront ensuite envoyées en POST coté PHP.
3. PHP va se charger de vérifier si l'email/mdp correspond à une entrée dans notre base de données.
4. Si c'est OK, on stocke l'objet `AppUser` dans une session PHP (`$_SESSION['connectedUser']` par exemple)
5. Sinon, on affiche un message d'erreur.

## Les ajouts nécessaires

Il va falloir ajouter quelques petites choses pour gérer cette authentification !

- compléter le modèle AppUser ! (inspirez-vous du modèle Brand par exemple)
- un nouveau contrôleur : SecurityController.php qui contiendra une méthode `login()` et une méthode `show_form()` pour afficher notre formulaire de login.
- une nouvelle vue : `security/login.tpl.php` qui contiendra le formulaire de connexion
- deux nouvelles routes : /login en GET (formulaire) et /login en POST (vérification du mail/mdp)
- et pourquoi pas `logout.tpl.php` ? :grin: vous pouvez essayer en Bonus !
