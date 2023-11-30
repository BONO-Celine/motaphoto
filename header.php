<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    
    <?php wp_body_open(); ?>



<body>
    <nav id="site-navigation" class="main-navigation">
    	<?php
    	wp_nav_menu(
    		array(
    			'theme_location' => 'main-menu',
    			'menu_id'     => 'primary-menu',
    		)
    	);
    	?>
    </nav>