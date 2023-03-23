<div class="container">
    <div id="login-row" class="row justify-content-center align-items-center">

        <?php
            // Pour afficher les messages d'erreurs éventuels.
            include __DIR__ . '/../partials/errors.tpl.php';
        ?>

        <div id="login-column" class="col-md-6">
            <div class="box">
                <div class="float">
                    <form class="form" action="" method="POST" novalidate>
                        <div class="form-group">
                            <label for="username">E-mail:</label><br>
                            <input type="email" name="email" id="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="password">Mot de passe :</label><br>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-info btn-md" value="Connexion">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>