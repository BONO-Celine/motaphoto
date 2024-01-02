<?php get_header(); ?>

<main>

<!-- ********************************************** HEADER image aléatoire ******************************************* -->
<div class="hero-header">
    <img class="hero-header__title" src="<?php echo get_template_directory_uri(); ?>/assets/images/titre-header.svg" alt="Titre du header : PHOTOGRAPHE EVENT">
    <?php
        $query_images_args = array(
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'post_status'    => 'inherit',
            'posts_per_page' => - 1,
        );
        
        $query_images = new WP_Query( $query_images_args );
        
        $images = array();
        foreach ( $query_images->posts as $image ) {
            $images[] = wp_get_attachment_image_src($image->ID, "header-size");
        }

        $aleatoire = rand(0,  count($images) - 1);
       // echo '<img class="hero-header__img" alt="test" src="'. $images[$aleatoire][0] . '">';


 // Récupérer l'attribut alt de l'image actuelle
 $alt_text = get_post_meta($query_images->posts[$aleatoire]->ID, '_wp_attachment_image_alt', true);

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
                <button class="dropdown-button" id="mainDropdownButtonCategories">CATÉGORIES</button>
                <div class="dropdown-content">
                    <button class="dropdown-item dropdown-item--title-colors" data-value="">Catégories</button>
                    <?php $fields = get_terms(array('taxonomy' => 'categorie-photo')); ?>
                    <?php if ($fields): ?>
                        <?php foreach ($fields as $value): ?>
                            <?php if ($value && isset($value->slug) && isset($value->name)): ?>
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
            $args = array(
                'post_type' => 'photos',
                'posts_per_page' => 12,
                'ignore_sticky_posts' => 1,
            );

            $query = new WP_Query($args);

            if ($query->have_posts()) :
                while ($query->have_posts()) : $query->the_post();
                    ?>

     <!-- Appel du code de la card déplacé dans le sous template "card" -->
     <?php get_template_part( 'templates-parts/card' ); ?>

                    <?php
                endwhile;
                wp_reset_postdata();
            else :
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