<div id="contact-popup" class="overlay">
	<div class="popup">
	<img class="popup__header" src="<?php echo get_template_directory_uri(); ?>/assets/images/contact-header.svg" 
	alt="image d'entête du formulaire de contact">
	<!-- Shortcode Contact Form 7 pour afficher le formulaire -->               
	<?php echo do_shortcode( '[contact-form-7 id="6c00aaa" title="Formulaire de contact"]' ); ?>
	</div>
</div>