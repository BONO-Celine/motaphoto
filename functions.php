<?php 
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'theme_enqueue_scripts' );
add_action( 'init', 'register_my_menus' );

/* *************************************************************** */
/*         ENREGISTRER LES EMPLACEMENTS DE MENU                    */
/* *************************************************************** */
function register_my_menus() {
    register_nav_menus(
      array(
        'main-menu' => __( 'Menu Principal' ),
        'footer-menu'  => __( 'Menu du pied de page' ),
      )
    );
  }
/* *************************************************************** */
/* Déclaration des scripts et styles */
/* *************************************************************** */
function theme_enqueue_styles() {
   // Déclaration du fichier style.css à la racine du thème
    wp_enqueue_style( 'theme_style', get_stylesheet_uri() );
}

function theme_enqueue_scripts() {
  // Activation de la version de jQuery qui est incluse dans WordPress
  wp_enqueue_script('jquery');
  // Déclaration du js principal
  wp_enqueue_script( 'theme_script', get_template_directory_uri() . '/js/script.js', array( 'jquery' ), '1.0', true);
  // Déclaration du js de la lightbox
  wp_enqueue_script( 'lightbox', get_template_directory_uri() . '/js/lightbox.js', array( 'jquery', 'theme_script' ), '1.0', true);
  // Localisation des données de la lightbox
  localize_lightbox_data();
}

// ***************************************************************************
// Création de tailles d'images personalisées
//**************************************************************************** */

//active les images mises en avant dans le thème
add_theme_support( 'post thumbnails' ) ; 

// Définir la taille des images mises en avant
set_post_thumbnail_size( 563 );

// Définir d'autres tailles d'images
add_image_size( 'card-size', 564, 495, true );
add_image_size( 'header-size', 1440, 962, true );
add_image_size( 'single-photo-thumbnail-size', 81, 71, true );

/* ******************************************************* */
/*        FORCER L'ACTIVATION D'ACF POUR LES ADMINS        */
/* ******************************************************* */

//* Contrôle si Advanced Custom Field est actif sur le site
if ( ! function_exists( 'get_field' ) ) {
	
	// Variable pour URL de la page Extension
	function page_plugin_redirect() {
		return get_bloginfo('url') . '/wp-admin/plugins.php';
	} 
	
	// Notice dans le back-office au moment de la désactivation
	add_action('admin_notices','gn_warning_admin_missing_acf');
	function gn_warning_admin_missing_acf() {
	   $output = '<div id="my-custom-warning" class="error fade">';
	   $output .= sprintf('<p><strong>Attention</strong>, ce site ne fonctionne pas sans l\'extension <strong>Advanced Custom Fields</strong>. Merci <a href="%s">d\'activer l\'extension</a>.</p>', page_plugin_redirect());
	   $output .= '</div>';
	   echo $output;
	 }
	 
	// Notice dans le front qui masque tout le contenu et affiche le lien pour ce connecter
	add_action( 'template_redirect', 'gn_template_redirect_warning_missing_acf', 0 );
	function gn_template_redirect_warning_missing_acf() {
		wp_die( sprintf( 'Ce site ne fonctionne pas sans l\'extension <strong>Advanced Custom Fields</strong>. Merci <a href="%s">d\'activer l\'extension</a>.', page_plugin_redirect() ) );
	}
}


/* **************************************************************** */
/* ************ Filtres ************************************/
/******************************************************************************* */

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
      'posts_per_page' => 12,
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


   // Effectuer la requête WP_Query
   $query = new WP_Query($args);

   // Charger le template des cards avec les résultats filtrés
   if ($query->have_posts()) :
      while ($query->have_posts()) : $query->the_post();
         get_template_part('templates-parts/card');
      endwhile;
      //wp_reset_postdata();
   else :
      echo 'Aucune photo ne correspond à votre recherche.';
   endif;
   wp_reset_postdata();
   die(); // Arrêter l'exécution après avoir renvoyé les résultats
}

/* *************************************************************** */
/* ***********************Bouton charger plus********************* */
/* *************************************************************** */

function load_more_posts() {
  $args = array(
      'post_type' => 'photos',
      'posts_per_page' => 12,
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

/* ********************************************************************* */
// LIGHTBOX tableau des posts pour utilisation diaporama
/* *************************************************************************/

// Fonction qui récupère des données d'images pour les afficher dans la lightbox.
function localize_lightbox_data() {
// Configuration des arguments pour récupérer tous les articles de type 'photos'.
  $args = array(
      'post_type' => 'photos',
      'posts_per_page' => -1,
  );
// Récupération des articles en utilisant les arguments définis.
  $posts = get_posts($args);
// Initialisation d'un tableau pour stocker les données localisées.
  $localized_data = array();
  // Parcourir chaque article pour extraire les informations nécessaires.
    foreach ($posts as $post) {
      // Récupérer l'URL de l'image en taille complète.
      $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'full');
      // Récupérer la référence de l'image en utilisant le champ personnalisé 'reference'.
      $reference = get_field('reference', $post->ID);
      // Récupérer le terme de la taxonomie "catégorie" de l'image.
      $categorie = get_the_terms($post->ID, 'categorie-photo')[0]->name;
      // Ajouter les données extraites au tableau des données localisées.
      $localized_data[] = array(
          'imageUrl' => $thumbnail_url,
          'imageRef' => $reference,
          'imageCategorie' => $categorie,
      );
  }
// Localiser le script 'lightbox' et passer les données localisées à la variable JavaScript 'lightboxData'.
  wp_localize_script('lightbox', 'lightboxData', $localized_data);
}

/******************************************************************************* */
