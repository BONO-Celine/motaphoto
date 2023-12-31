<?php
get_header(); ?>

	<main id="primary" class="site-main">

<div class="post">
    <div class="post-top-container">
        <div class="post-left-container">
        <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
                <h2 class="post-left-container__title"><?php the_title(); ?></h2>
        <?php endwhile; ?>
        <?php endif; ?>

                <div class="post-left-container__fields">
                <?php
                // Contrôler si ACF est actif
                if ( !function_exists('get_field') ) return;
                ?>
                    <ul>
                        <li>Référence : <?php the_field('reference'); ?></li>
                        <!-- "strip_tags" supprime le lien pour afficher seulement le texte du terme de taxonomie  
                        On affiche les termes gràce à la fonction get_the_term_list()-->
                        <li>Catégorie : <?php echo strip_tags(get_the_term_list($post->ID, 'categorie-photo')); ?></li>
                        <li>Format : <?php echo strip_tags(get_the_term_list($post->ID, 'format')); ?></li>
                        <li>Type : <?php the_field('type'); ?></li>
                        <li>Année : <?php the_field('annee'); ?></li>

                    </ul>
                </div>

        </div>

        <div class="post-right-container">

            <div class="post__image">
            <?php the_post_thumbnail(); ?>
            </div>
            <div class="icon-full-screen">
                            <button
                            type="button"
                            class="icon-full-screen__button"
                            id="fullscreen-button<?= get_the_ID(); ?>"
                            data-image-url="<?= get_the_post_thumbnail_url(null,'full') ?>"
                            data-image-ref="<?= get_field('reference') ?>"
                            data-image-categorie="<?= get_the_terms(get_the_ID(), 'categorie-photo')[0]->name; ?>"
                            >
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-fullscreen.svg" alt="icône plein écran" />
                            </button>

                        </div>
        </div>
    </div>

    <div class="post-center-container">
            <div class="post__contact">
                <div class="post-contact-content">
                <p> Cette photo vous intéresse ? </p>
                <!--<button class="large-buttons btn-contact">Contact</button>-->
                <button class="large-buttons btn-contact" data-reference="<?php the_field('reference'); ?>">Contact</button>
                </div>
            </div>

            <div class="post__navigation">

                <div class="post-navigation__previous-thumbnail">
                <?php echo get_the_post_thumbnail( get_previous_post(), 'single-photo-thumbnail-size' ); ?> <!-- miniature post précedent -->
                </div>
                <div class="post-navigation__next-thumbnail">
                <?php echo get_the_post_thumbnail( get_next_post(), 'single-photo-thumbnail-size' ); ?> <!-- miniature post suivant -->
                </div>
        
                <div class="post-navigation__arrows">
                    <div class="post-navigation__previous-arrow">
                    <?php previous_post_link(' %link', '&#10229;' ); ?> <!-- fléche post précedent -->
                    </div>
                    <div class="post-navigation__next-arrow">
                    <?php next_post_link(' %link', '&#10230;' ); ?> <!-- fléche post suivant -->
                    </div>
                </div>

            </div>

    </div>

    <div class="bottom-container">
    <h3>VOUS AIMEREZ AUSSI</h3>

     <div class= "post-suggestions">

<!-- --------- Liste des cards ----------- -->
<div class="grid-container" id="post-container">
    <?php
    // Récupérer les termes de la taxonomie pour le post actuel
    //$current_post_
    $categories = get_the_terms(get_the_ID(), 'categorie-photo');
    //$current_term_slug = (!empty($current_post_categories)) ? $current_post_categories[0]->slug : '';

    // Récupérer l'ID du post actuellement ouvert
    $current_post_id = get_the_ID();

    $args = array(
        'post_type' => 'photos',
        'posts_per_page' => 2,
        'ignore_sticky_posts' => 1,
        'orderby' => 'rand',
        'post__not_in' => array($current_post_id),
        'tax_query' => array(
            array(
                'taxonomy' => 'categorie-photo',
                'field' => 'slug',
               // 'terms' => $current_term_slug,
               'terms' =>  (!empty($categories)) ? $categories[0]->slug : '',
            ),
        ),
    );

    $query = new WP_Query($args);

    // Variable pour suivre le nombre de cartes affichées
    $card_count = 0;

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();

            // Vérifier si le post actuel est différent du post affiché
            if ($current_post_id != get_the_ID()) {
                ?>


     <!-- Appel du code de la card déplacé dans le sous template "card" -->
     <?php get_template_part( 'templates-parts/card' ); ?>



                <?php
                // Incrémenter le nombre de cartes affichées
                $card_count++;
            }

        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>Désolé, mais il n\'y a aucune autre photo à afficher dans cette catégorie.</p>';
    endif;

    // Vérifier si aucune carte n'a été affichée

    ?>
</div><!-- Fin de la grille -->
    <!-- ********************************************* -->



     </div>
     <a href='<?php echo esc_url( home_url() ); ?>'>
    <button class="large-buttons" id="all-photos">Toutes les photos</button>
    </a> 
    </div>

</div>



	</main><!-- #main -->

<?php get_footer(); ?>
