<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
    <?php wp_body_open(); ?>
	<header>
    <nav>
	
<div id="site-navigation" class="main-navigation">


	<div class="main-navigation__logo">

      <a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.svg" 
				alt="Logo Nathalie MOTA"></a>
    </div>

    	<?php
    	wp_nav_menu(
    		array(
    			'theme_location' => 'main-menu',
    			'menu_id'     => 'primary-menu',
    		)
    	);
    	?>

	<!-- Icone burger --> 
	<button class="burger-icon" aria-expanded="true">
                <span class="burger-icon__line"></span>
            </button> 
<!-- ************************ -->
</div>

	<!-- ******* Début overlay ***************** -->
	<div id="menu-mobile-container" class="menu-mobile-container">
<!-- Contenu menu Overlay -->
<!-- Liste des liens du menu --> 
<?php
    	wp_nav_menu(
    		array(
    			'theme_location' => 'main-menu',
    			'menu_id'     => 'primary-menu',
    		)
    	);
    	?>
</div>  
    </nav>
     
<!-- ******* fin overlay ***************** -->
	</header>