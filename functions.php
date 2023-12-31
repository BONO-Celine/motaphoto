<?php 
/*
add_action( 'after_setup_theme', 'register_my_menu' );*/
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );
add_action( 'init', 'register_my_menus' );

function register_my_menus() {
    register_nav_menus(
      array(
        'main-menu' => __( 'Menu Principal' ),
        'footer-menu'  => __( 'Menu du pied de page' ),
      )
    );
  }


function theme_enqueue_styles() {
    wp_enqueue_style( 'theme_style', get_stylesheet_uri() );
}
function theme_enqueue_scripts() {
  // Activation de la version de jQuery qui est incluse dans WordPress
  wp_enqueue_script('jquery');
  // Déclaration du js principal
  wp_enqueue_script( 'theme_script', get_template_directory_uri() . '/js/script.js', array( 'jquery' ), '1.0', true);
    // Déclaration du js de la lightbox
    //wp_enqueue_script( 'lightbox', get_template_directory_uri() . '/js/lightbox.js', array( 'jquery' ), '1.0', true);
    // Déclaration du js de la lightbox, dépendant de 'jquery' et 'theme_script'
  wp_enqueue_script( 'lightbox', get_template_directory_uri() . '/js/lightbox.js', array( 'jquery', 'theme_script' ), '1.0', true);
      // Localisation des données
      localize_lightbox_data();

    // Passer data à script.js avec wp_localize_script (lightbox)
    wp_localize_script('lightbox', 'post_navigation_data', array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce'    => wp_create_nonce('post_navigation_nonce')
    ));

//Ajax tests

//***************** */
/*wp_enqueue_script( 'script',  get_stylesheet_directory_uri() . '/js/script.js', array('jquery') );
wp_localize_script( 'script', 'adminAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));*/

}


// test ajax
add_action( 'wp_localize_script', 'theme_enqueue_scripts' );

/******************************************************************************* */


// ***************************************************************************
// tailles d'images personalisées
//**************************************************************************** */

//active les images mises en avant dans le thème
add_theme_support( 'post thumbnails' ) ; 

// Définir la taille des images mises en avant
set_post_thumbnail_size( 563 );

// Définir d'autres tailles d'images
add_image_size( 'card-size', 564, 495, true );
add_image_size( 'header-size', 1440, 962, true );
add_image_size( 'single-photo-thumbnail-size', 81, 71, true );
//add_image_size( 'single-photo-size', 563, 640 );




/* *************************************************************** */
/* ***********************Bouton charger plus********************* */
/* *************************************************************** */

function load_more_posts() {
  $args = array(
      'post_type' => 'photos',
      'posts_per_page' => 2,
      'ignore_sticky_posts' => 1,
      'paged' => $_POST['page'],
  );

  // Ajouter la taxonomie "categorie-photo" si elle est sélectionnée
  if (!empty($_POST['category'])) {
      $args['tax_query'][] = array(
          'taxonomy' => 'categorie-photo',
          'field' => 'slug',
          'terms' => $_POST['category'],
      );
  }

  // Ajouter la taxonomie "format" si elle est sélectionnée
  if (!empty($_POST['format'])) {
      $args['tax_query'][] = array(
          'taxonomy' => 'format',
          'field' => 'slug',
          'terms' => $_POST['format'],
      );
  }

  // Ajouter le tri par date si spécifié
  /*if (!empty($_POST['sortType'])) {
      if ($_POST['sortType'] === 'asc') {
          //$args['orderby'] = 'meta_value_date';
          $args['orderby'] = 'date';
          $args['meta_key'] = 'annee'; // Nom du champ ACF date
          $args['order'] = 'ASC';
      } elseif ($_POST['sortType'] === 'desc') {
          //$args['orderby'] = 'meta_value_date';
          $args['orderby'] = 'date';
          $args['meta_key'] = 'annee';
          $args['order'] = 'DESC';
      }
  }*/

    // Ajouter le tri par date si spécifié
    if (!empty($_POST['sortType'])) {
      if ($_POST['sortType'] === 'asc') {
          $args['orderby'] = 'meta_value';
          $args['meta_key'] = 'annee';
          $args['meta_type'] = 'DATE';
          $args['order'] = 'ASC';
      } elseif ($_POST['sortType'] === 'desc') {
          $args['orderby'] = 'meta_value';
          $args['meta_key'] = 'annee';
          $args['meta_type'] = 'DATE';
          $args['order'] = 'DESC';
      }
  }

  $query = new WP_Query($args);

  if ($query->have_posts()) :
      while ($query->have_posts()) : $query->the_post();
          // code pour afficher chaque carte
          get_template_part('templates-parts/card');
      endwhile;
      //wp_reset_postdata();
  else :
      echo 'no-more-posts';
  endif;
  wp_reset_postdata();
  die();
}

add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');

function add_ajax_url_to_front() {
  ?>
  <script>
      var ajaxurl = '<?php echo site_url('/wp-admin/admin-ajax.php'); ?>';
  </script>
  <?php
}

add_action('wp_head', 'add_ajax_url_to_front');


/* **************************************************************** */
/* ************ Filtres ************************************/

add_action('wp_ajax_filter_posts', 'filter_posts');
add_action('wp_ajax_nopriv_filter_posts', 'filter_posts');

function filter_posts() {
   // Récupérer les valeurs filtrées de la requête AJAX
   $selectedCategory = isset($_POST['category']) ? $_POST['category'] : '';
   $selectedFormat = isset($_POST['format']) ? $_POST['format'] : '';
   $sortType = isset($_POST['sortType']) ? $_POST['sortType'] : '';

   // Construire les arguments de la requête WP_Query en fonction des filtres
   $args = array(
      'post_type' => 'photos',
      'posts_per_page' => 2,
      'ignore_sticky_posts' => 1,
   );

   // Ajouter la taxonomie "categorie-photo" si elle est sélectionnée
   if (!empty($selectedCategory)) {
      $args['tax_query'][] = array(
         'taxonomy' => 'categorie-photo',
         'field' => 'slug',
         'terms' => $selectedCategory,
      );
   }

   // Ajouter la taxonomie "format" si elle est sélectionnée
   if (!empty($selectedFormat)) {
      $args['tax_query'][] = array(
         'taxonomy' => 'format',
         'field' => 'slug',
         'terms' => $selectedFormat,
      );
   }

// Ajouter le tri par date si spécifié
/*if ($sortType === 'asc') {
  //$args['orderby'] = 'meta_value_date'; // Utiliser 'meta_value_date' pour trier par date
  $args['orderby'] = 'date';
  $args['meta_key'] = 'annee'; //nom du champ ACF
  $args['order'] = 'ASC';
} elseif ($sortType === 'desc') {
  //$args['orderby'] = 'meta_value_date';
  $args['orderby'] = 'date';
  $args['meta_key'] = 'annee';
  $args['order'] = 'DESC';
}*/

// Ajouter le tri par date si spécifié
if ($sortType === 'asc') {
  $args['orderby'] = 'meta_value';
  $args['meta_key'] = 'annee';
  $args['meta_type'] = 'DATE';
  $args['order'] = 'ASC';
} elseif ($sortType === 'desc') {
  $args['orderby'] = 'meta_value';
  $args['meta_key'] = 'annee';
  $args['meta_type'] = 'DATE';
  $args['order'] = 'DESC';
}




   // Effectuer la requête WP_Query
   $query = new WP_Query($args);

   // Charger le template des cards avec les résultats filtrés
   if ($query->have_posts()) :
      while ($query->have_posts()) : $query->the_post();
         get_template_part('templates-parts/card');
      endwhile;
      //wp_reset_postdata();
   else :
      echo 'Aucun article trouvé.';
   endif;
   wp_reset_postdata();
   die(); // Arrêter l'exécution après avoir renvoyé les résultats
}


/* ********************************************************************* */

// LIGHTBOX tableau des posts pour SUIVANT / PRECEDENT

/* *************************************************************************/


function localize_lightbox_data() {
  $args = array(
      'post_type' => 'photos',
      'posts_per_page' => -1,
  );

  $posts = get_posts($args);

  $localized_data = array();

  foreach ($posts as $post) {
      $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'full');
      $reference = get_field('reference', $post->ID);
      $categorie = get_the_terms($post->ID, 'categorie-photo')[0]->name;

      $localized_data[] = array(
          'imageUrl' => $thumbnail_url,
          'imageRef' => $reference,
          'imageCategorie' => $categorie,
      );
  }

  wp_localize_script('lightbox', 'lightboxData', $localized_data);
}
