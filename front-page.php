<?php get_header(); ?>

<main>

<!-- ********************************************** HEADER image aléatoire ******************************************* -->
<div class="hero-header">
    <img class="hero-header__title" src="<?php echo get_template_directory_uri(); ?>/assets/images/titre-header.svg" alt="Titre du header : PHOTOGRAPHE EVENT">
    <?php  // Configuration de la requête pour obtenir un tableau des images de tous les posts
        $query_images_args = array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => 'inherit',
            'posts_per_page' => - 1,
        );
        // Création d'une nouvelle requête WordPress pour récupérer les images
        $query_images = new WP_Query( $query_images_args );
        // Initialisation d'un tableau pour stocker les informations sur les images
        $images = array();
        // Parcourt chaque image obtenue de la requête et stocke les informations
        foreach ( $query_images->posts as $image ) {
            $images[] = wp_get_attachment_image_src($image->ID, "header-size");
        }
        // Sélectionne un index d'image aléatoire
        $aleatoire = rand(0,  count($images) - 1);
 // Récupére l'attribut alt de l'image actuelle
 $alt_text = get_post_meta($query_images->posts[$aleatoire]->ID, '_wp_attachment_image_alt', true);
 // Affiche l'image aléatoire avec la classe "hero-header__img", l'attribut et la source
 echo '<img class="hero-header__img" alt="' . esc_attr($alt_text) . '" src="'. $images[$aleatoire][0] . '">';

    ?>
</div>
<!-- ---------------------------------------------------------------------------------- -->

<div class="container">

    <!-- ----------------------------------------------------- -->
    <!-- --------Listes déroulantes personnalisées ------------ -->
    <!-- ----------------------------------------------------- -->
    <div class="filters-container">

        <div class="selects-taxonomies">

            <!-- liste deroulante Catégories -->
            <div class="custom-dropdown" id="select-categories">
                <!-- Bouton déclencheur de la liste déroulante -->
                <button class="dropdown-button" id="mainDropdownButtonCategories">CATÉGORIES</button>
                <!-- Contenu de la liste déroulante -->
                <div class="dropdown-content">
                    <!-- Bouton initial sans valeur utilisé comme titre -->
                    <button class="dropdown-item dropdown-item--title-colors" data-value="">Catégories</button>
                    <!-- Récupére tous les termes de la taxonomie 'categorie-photo'-->
                    <?php $fields = get_terms(array('taxonomy' => 'categorie-photo')); ?>
                    <!-- Vérifie s'il y a des termes (catégories) disponibles -->
                    <?php if ($fields): ?>
                        <!-- Pour chaque terme (catégorie) obtenu -->
                        <?php foreach ($fields as $value): ?>
                            <!-- Vérifie si le terme (catégorie) a un slug et un nom définis  -->
                            <?php if ($value && isset($value->slug) && isset($value->name)): ?>
                                <!-- Affiche un bouton représentant chaque catégorie et stocke dans data-value le slug et le nom correspondant -->
                                <button class="dropdown-item" data-value="<?= $value->slug ?>"><?= $value->name ?></button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

    <!-- ----------------------------------------------------- -->
            <!-- liste deroulante Formats -->
            <div class="custom-dropdown" id="select-formats">
                <button class="dropdown-button" id="mainDropdownButtonFormats">FORMATS</button>
                <div class="dropdown-content">
                    <button class="dropdown-item dropdown-item--title-colors" data-value="">Formats</button>
                    <?php $fields = get_terms(array('taxonomy' => 'format')); ?>
                    <?php if ($fields): ?>
                        <?php foreach ($fields as $value): ?>
                            <?php if ($value && isset($value->slug) && isset($value->name)): ?>
                                <button class="dropdown-item" data-value="<?= $value->slug ?>"><?= $value->name ?></button>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    <!-- ----------------------------------------------------- -->
        <!-- liste deroulante date -->

        <div class="custom-dropdown" id="select-by-date">
            <button class="dropdown-button" id="mainDropdownButtonDate">TRIER PAR</button>
                <div class="dropdown-content">
                    <button class="dropdown-item dropdown-item--title-colors" data-value="">Trier par</button>
                    <button class="dropdown-item" data-value="desc">Plus récents</button>
                    <button class="dropdown-item" data-value="asc">Plus anciens</button>
                </div>
        </div>
</div>
<!-- ----------------------------------------------------- -->
<!-- --------- Liste des cards ----------- -->
<!-- ----------------------------------------------------- -->
<div class="grid-container" id="post-container">
    <?php
    //Paramètres de la requête pour obtenir les articles de type 'photos'
    $args = array(
        'post_type' => 'photos',
        'posts_per_page' => 12,
        'ignore_sticky_posts' => 1,
    );
    // Nouvelle requête WordPress avec les paramètres définis
    $query = new WP_Query($args);
        // Vérifie si des articles ont été trouvés
        if ($query->have_posts()) :
        // Boucle à travers les articles trouvés
        while ($query->have_posts()) : $query->the_post();
    
        // Appel du code de la card déplacé dans le sous template "card"
        get_template_part( 'templates-parts/card' ); 
        endwhile;
        // Réinitialiser les données de la requête après la boucle
        wp_reset_postdata();
        else :
        // Afficher un message s'il n'y a aucun article trouvé
        echo 'Aucun article trouvé.';
        endif;
    ?>
        </div><!-- Fin de la grille -->


<!-- -------------------- -->

        <div class="load-more-btn-container">
        <button id="load-more-btn" class="large-buttons">Charger plus</button>
        <p id="load-more-message"></p>
        </div>


<!-- -------------------- -->



</div>

</main>

<?php get_footer(); ?>