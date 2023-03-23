        <a href="<?= $router->generate('product-list') ?>" class="btn btn-success float-end">Retour</a>
        <h2>Ajouter un produit</h2>
        
        <form action="" method="POST" class="mt-5">
            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nom du produit" value="<?= $product->getName() ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea id="description" name="description" rows="5" cols="33"><?= $product->getDescription() ?></textarea>
                <small id="descriptionHelpBlock" class="form-text text-muted">
                    Sera affiché sur la page d'accueil comme bouton devant l'image
                </small>
            </div>
            <div class="mb-3">
                <label for="picture" class="form-label">Image</label>
                <input type="text" class="form-control" id="picture" name="picture" placeholder="image jpg, gif, svg, png" aria-describedby="pictureHelpBlock" value="<?= $product->getPicture() ?>">
                <small id="pictureHelpBlock" class="form-text text-muted">
                    URL relative d'une image (jpg, gif, svg ou png) fournie sur <a href="https://benoclock.github.io/S06-images/" target="_blank">cette page</a>
                </small>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Prix</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="10" value="<?= $product->getPrice() ?>"> €
            </div>

            <div class="mb-3">
                <label for="status">Status du produit</label>
                <select name="status" id="status">
                    <option value="1" <?= $product->getStatus() == "1" ? 'selected' : '' ?>>Disponible</option>
                    <option value="2"<?= $product->getStatus() == "2" ? 'selected' : '' ?>>Indisponible</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="brand_id" class="form-label">Marque</label>
                <input type="text" class="form-control" id="brand_id" name="brand_id" placeholder="1" value="<?= $product->getBrandId() ?>">
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Catégorie</label>
                <input type="text" class="form-control" id="category_id" name="category_id" placeholder="1" value="<?= $product->getCategoryId() ?>">
            </div>
            <div class="mb-3">
                <label for="type_id" class="form-label">Type de produit</label>
                <input type="text" class="form-control" id="type_id" name="type_id" placeholder="1" value="<?= $product->getTypeId() ?>">
            </div>


            <h3>Tags :</h3>

            <?php foreach($tags as $tag) : ?>
                <div>
                    <input type="checkbox" name="tags[<?= $tag->getId() ?>]" id="tag<?= $tag->getId() ?>" <?= in_array($tag->getId(), $selectedTags, true) ? 'checked' : '' ?>>
                    <label for="tag<?= $tag->getId() ?>" class="form-label"><?= $tag->getName() ?></label>
                </div>
            <?php endforeach; ?>


            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary mt-5">Valider</button>
            </div>
        </form>