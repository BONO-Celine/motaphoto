                    <figure class="card">
                      <div class="img-container">
                        <?php
                        if (has_post_thumbnail()) {
                            the_post_thumbnail('card-size');
                        }
                        ?>
                        </div>
                        <div class="icon-eye">
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="icon-eye__button">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-eye.svg" alt="icône représentant un œil" />
        </a>
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
                        <figcaption>
                            <span class="card-title"><?php the_title(); ?></span>
                            <?php
                            
                        // On Récupère les termes de la taxonomie qui sont attachées au post grace à la fonction get_the_terms()
                        $categories = get_the_terms(get_the_ID(), 'categorie-photo');
                        //

                            if (!empty($categories)) {
                                echo '<span class="card-categorie">' . esc_html($categories[0]->name) . '</span>';
                            }
                            ?>
                        </figcaption>
                    </figure>