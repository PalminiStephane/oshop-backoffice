    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= $router->generate('main-home') ?>">oShop</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <!-- connecté -->
                <?php if (isset($_SESSION['userObject'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $viewName === 'main/home' ? 'active' : '' ?>" href="<?= $router->generate('main-home') ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $viewName === 'category/category_list' ? 'active' : '' ?>" href="<?= $router->generate('category-list') ?>">Catégories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $viewName === 'product/product_list' ? 'active' : '' ?>" href="<?= $router->generate('product-list') ?>">Produits</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Types</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Marques</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Tags</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $viewName === 'category/category_home-order' ? 'active' : '' ?>" href="<?= $router->generate('category-order') ?>">Sélection Accueil</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-danger" href="<?= $router->generate('security-logout') ?>">Déconnexion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link">
                            <?= $_SESSION['userObject']->getEmail(); ?>
                        </a>
                    </li>
                <?php else : ?>
                    <!-- déconnecté -->
                    <li class="nav-item">
                        <a class="nav-link btn btn-sm btn-success" href="<?= $router->generate('security-login') ?>">Connexion</a>
                    </li>
                <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>