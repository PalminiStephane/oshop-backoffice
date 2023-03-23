<a href="<?= $router->generate('appuser-list') ?>" class="btn btn-success float-end">Retour</a>
        <h2>Ajouter un utilisateur</h2>
        
        <form action="" method="POST" class="mt-5">

            <?php
                // Pour afficher les messages d'erreurs éventuels.
                include __DIR__ . '/../partials/errors.tpl.php';
            ?>

            <div class="mb-3">
                <label for="firstname" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Prénom du nouvel utilisateur" value="<?= $user->getFirstname() ?>">
            </div>
            <div class="mb-3">
                <label for="lastname" class="form-label">Nom</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Nom du nouvel utilisateur" value="<?= $user->getLastname() ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="E-mail de l'utilisateur" value="<?= $user->getEmail() ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" value="">
            </div>
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Confirmez le mot de passe</label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Mot de passe" value="">
            </div>
            <div class="mb-3">
                <label for="status">Status du Nouvel Utilisateur</label>
                <select name="status" id="status">
                    <?php if ($user->getStatus() == 1) : ?>
                        <option value="1" selected>Actif</option>
                        <option value="2">Compte désactivé</option>
                    <?php else : ?>
                        <option value="1">Actif</option>
                        <option value="2" selected>Compte désactivé</option>  
                    <?php endif; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="role">Rôle du nouvel utilisateur</label>
                <select name="role" id="role">
                    <?php if ($user->getRole() == 'admin') : ?>
                        <option value="admin" selected>Administrateur</option>
                        <option value="catalog-manager">Gestionnaire du catalogue</option>
                    <?php else : ?>
                        <option value="admin">Administrateur</option>
                        <option value="catalog-manager" selected>Gestionnaire du catalogue</option>
                    <?php endif; ?>
                </select>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary mt-5">Valider</button>
            </div>

            <?php
                // Pour inclure le token anti-CSRF sur les pages qui le nécessitent !
                include __DIR__ . '/../partials/csrf.tpl.php';
            ?>
        </form>