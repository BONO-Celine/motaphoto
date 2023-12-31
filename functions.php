<?php 
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
}