        <a href="<?= $router->generate('product-list') ?>" class="btn btn-success float-end">Retour</a>
        <h2>Ajouter un produit</h2>
        
        <form action="<?= $router->generate('product-addpost') ?>" method="POST" class="mt-5">
            
            <?php
            // Pour afficher les messages d'erreurs éventuel
            include __DIR__ . '/../partials/errors.tpl.php';
            ?>

            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nom du produit" >
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" rows="5" cols="33">Description du produit</textarea>
                <small id="descriptionHelpBlock" class="form-text text-muted">
                    Sera affiché sur la page d'accueil comme bouton devant l'image
                </small>
            </div>
            <div class="mb-3">
                <label for="picture" class="form-label">Image</label>
                <input type="text" class="form-control" id="picture" name="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock">
                <small id="pictureHelpBlock" class="form-text text-muted">
                    URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
                </small>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Prix</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="10"> €
            </div>

            <div class="mb-3">
                <label for="status">Status du produit</label>
                <select name="status" id="status">
                    <option value="1">Disponible</option>
                    <option value="2">Indisponible</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="brand_id" class="form-label">Marque</label>
                <input type="text" class="form-control" id="brand_id" name="brand_id" placeholder="1">
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Catégorie</label>
                <input type="text" class="form-control" id="category_id" name="category_id" placeholder="1">
            </div>
            <div class="mb-3">
                <label for="type_id" class="form-label">Type de produit</label>
                <input type="text" class="form-control" id="type_id" name="type_id" placeholder="1">
            </div>




            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary mt-5">Valider</button>
            </div>
        </form>