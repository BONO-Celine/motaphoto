          <footer id="colophon" class="site-footer">

            <div class="site-info">
              <?php
              // Vérifie si un menu de pied de page ('footer-menu') est défini dans le thème
              if ( has_nav_menu( 'footer-menu' ) ) :
              // Affiche le menu de pied de page
              wp_nav_menu ( array (
              'theme_location' => 'footer-menu' ,
              'menu_class' => 'menu-footer', // classe CSS pour personaliser le menu
              ) );
              endif;
              ?>
            </div>

            <!-- Appel du code des modales de contact et de la lightbox déplacées dans les sous templates "modal-contact" et "lightbox"-->
            <?php get_template_part( 'templates-parts/modal-contact' ); ?>
            <?php get_template_part( 'templates-parts/lightbox' ); ?>
            <!-- ********************************************* -->

          </footer><!-- #colophon -->
      </div><!-- #page -->
    <?php wp_footer(); ?>
  </body>
</html>
