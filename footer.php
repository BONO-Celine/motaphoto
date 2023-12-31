	<footer id="colophon" class="site-footer">
		<div class="site-info">

        <?php
 if ( has_nav_menu( 'footer-menu' ) ) : ?>
 <?php 
 wp_nav_menu ( array (
 'theme_location' => 'footer-menu' ,
 'menu_class' => 'menu-footer', // classe CSS pour customiser mon menu
 ) ); ?>
 <?php endif;
 ?>

		</div><!-- .site-info -->

    <!-- Appel du code de la modale déplacé dans le sous template "modal" -->
    <?php get_template_part( 'templates-parts/modal-contact' ); ?>
    <?php get_template_part( 'templates-parts/lightbox' ); ?>
    <!-- ********************************************* -->

	</footer><!-- #colophon -->
</div><!-- #page -->


<?php wp_footer(); ?>
</body>
</html>
