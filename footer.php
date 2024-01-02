	<footer id="colophon" class="site-footer">
		<div class="site-info">

        <?php
 if ( has_nav_menu( 'footer-menu' ) ) : ?>
 <?php 
 wp_nav_menu ( array (
 'theme_location' => 'footer-menu' ,
 'menu_class' => 'menu-footer', // classe CSS pour customiser le menu
 ) ); ?>
 <?php endif;
 ?>

		</div><!-- .site-info -->

    <!-- Appel du code de la modale de contact et de la lightbox déplacé dans les sous templates "modal-contact" et "lightbox"-->
    <?php get_template_part( 'templates-parts/modal-contact' ); ?>
    <?php get_template_part( 'templates-parts/lightbox' ); ?>
    <!-- ********************************************* -->

	</footer><!-- #colophon -->
</div><!-- #page -->


<?php wp_footer(); ?>
</body>
</html>
