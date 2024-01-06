<figure class="card">
     <!-- Conteneur pour l'image à la une -->
    <div class="img-container">
    <?php
    // Vérifie si l'article a une image à la une
    if (has_post_thumbnail()) {
        // Affiche cette image avec la taille spécifiée 'card-size'
        the_post_thumbnail('card-size');
    }
    ?>
    </div>
    <!-- Icône représentant un œil avec un lien vers l'article -->
    <div class="icon-eye">
        <a href="<?php echo esc_url(get_permalink()); ?>" class="icon-eye__button">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-eye.svg" alt="icône représentant un œil" />
        </a>
    </div>
    <!-- Icône représentant le mode plein écran -->
    <div class="icon-full-screen">
            <!-- Bouton contenant l'icone et qui stocke des attributs de données pour utilisation dans le JS -->
        <button type="button" class="icon-full-screen__button" id="fullscreen-button<?= get_the_ID(); ?>"
                data-image-url="<?= get_the_post_thumbnail_url(null,'full') ?>"
                data-image-ref="<?= get_field('reference') ?>"
                data-image-categorie="<?= get_the_terms(get_the_ID(), 'categorie-photo')[0]->name; ?>"
        >
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-fullscreen.svg" alt="icône plein écran" />
        </button>
    </div>
    <!-- Légende de la carte avec le titre et la catégorie -->
    <figcaption>
        <span class="card-title"><?php the_title(); ?></span>
        <?php             
        // Récupère les termes de la taxonomie 'categorie-photo' attachés à l'article
        $categories = get_the_terms(get_the_ID(), 'categorie-photo');
        // Vérifie s'il y a des catégories associées
        if (!empty($categories)) {
            // Affiche la première catégorie attachée à l'article
            echo '<span class="card-categorie">' . esc_html($categories[0]->name) . '</span>';
        }
        ?>
    </figcaption>
</figure>