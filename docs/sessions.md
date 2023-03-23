# Comment stocker l'auth d'un User ?

On vient de le voir, il va bien falloir qu'on stocke qu'un utilisateur est connecté sur notre site, on ne va pas lui demander de se reconnecter avant d'accéder à chaque page ! Comment peut-on faire cela ?

Un cookie ? Le LocalStorage ? Toutes ces techniques permettent de stocker des données sur le navigateur, et donc potentiellement le visiteur peut altérer ces données (on ne peut décidémment par leur faire confiance, à ces utilisateurs :triumph:). Du coup, on fait comment ?

> On utilise les **sessions** !

La doc est [ici](https://www.php.net/manual/fr/session.examples.basic.php) !

Les sessions vont nous permettre de **stocker des données coté serveur et d'y accéder depuis toutes les pages PHP de notre site**. Chaque visiteur aura **sa propre session**, avec ses propres données stockées dedans. Elles ne sont pas accessibles par les autres utilisateurs, ni directement modifiables par l'utilisateur vu que stockées coté serveur.

## Comment ça marche ?

Les sessions PHP sont stockées coté serveur, comment fait PHP pour savoir à quel utilisateur ces données correspondent ?

PHP utilise un **numéro unique, appelé l'identifiant de session, ou PHPSESSID**. Cet identifiant sera transmis au navigateur du visiteur en utilisant un cookie, que le navigateur renverra à chaque nouvelle requête.

Mais tout ça, c'est le fonctionnement interne de PHP, et nous n'avons pas besoin de nous en préoccuper :grin:

## Comment on les utilise ?

Il faut d'abord indiquer à PHP que l'on veut **commencer à utiliser les sessions**, et plus précisément démarrer une session pour notre visiteur. Pour cela, on utilise la fonction `session_start()`.

```php
<?php

// on démarre la session du visiteur
session_start();

// maintenant on peut utiliser cette session !
```

Les sessions fonctionnent comme un tableau associatif, on va y associer des clés à des variables, objets ou même simplement des chaînes de caractères, comme dans l'exemple suivant.
On utilise la variable superglobale `$_SESSION` pour accéder à ce grand tableau associatif.

### Enregistrer une variable

Un premier exemple : stockons la chaîne `"Hello world"` !

```php
<?php

// on démarre la session
session_start();

// on stocke une chaîne
$_SESSION['hello'] = "Hello world";
// 'hello' est la clé, "Hello world" ce qu'on associe à cette clé
```

### Accéder à une variable

Sur une autre page, on peut maintenant accéder à cette chaîne comme si l'on voulait accéder à une information stockée dans un tableau associatif :

```php
<?php

// on démarre la session
session_start();

// on affiche notre chaîne
var_dump($_SESSION['hello']);

// essayons d'accéder à une clé non définie
var_dump($_SESSION['test']);
// -> ceci déclenchera une erreur :(
// pour éviter cela, on peut utiliser ... isset() !

// exemple : 
if(isset($_SESSION['test'])) {
    var_dump($_SESSION['test']);
} else {
    print "<p>La variable de session 'test' n'est pas définie :(</p>";
}

// on peut aussi regarder tout le contenu de $_SESSION : 
var_dump($_SESSION);
// on voit bien que c'est un tableau associatif ;)
```

**Rappel : une session est personnelle, valable uniquement pour un visiteur**. Essayons d'ouvrir cette même page en navigation privée pour démontrer ce comportement. En regardant les cookies définis, on pourra voir que nos deux instances de navigateur n'ont pas le même PHPSESSID.

### Supprimer une variable

On peut retirer/supprimer une variable de session en utilisant `unset($_SESSION['cle'])`. Attention, il ne faut pas oublier de mettre la clé, sinon les sessions ne fonctionneront plus.

```php
<?php

// on démarre les sessions
session_start();

// on retire notre variable de session 'hello'
unset($_SESSION['hello']);
```

### Terminer la session de l'utilisateur

Si notre visiteur ne charge plus de pages de notre site, au bout d'un certain temps (le timeout), la session sera automatiquement détruite. On peut aussi le faire manuellement (par exemple, au clic sur un bouton déconnexion) :

```php
<?php

// on ferme la session de l'utilisateur
session_destroy();

```
