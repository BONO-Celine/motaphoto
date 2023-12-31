jQuery(function ($) {

    /**********************************************
     * OVERLAY (modale Contact)
     **********************************************/
    $(".menu__contact, .btn-contact").click(function () {
      $(".overlay").toggleClass("open animate-zoom-in"); //« toggle » permet de permuter la classe "open". Elle est supprimée si elle existe sinon elle est ajoutée
    });
  
  
    $(".overlay").click(function (event) {
      if (
        !$(event.target).closest(".popup").length &&
        $(".overlay").hasClass("open")
      ) {
        //Si Le clic s'est produit en dehors de l'élément popup et l'element overlay a la class open
        $(".overlay").removeClass("open animate-zoom-in");
      }
    });
});  


  /**********************************************
   * Card
   **********************************************/
  $(".hover").mouseleave(function () {
    $(this).removeClass("hover");
  });

  /* ******************************************************************** */
/* Affichage des miniatures des posts suivant/précedent le post photo */
 //Affichage de la miniature au hover sur la fleche correspondante
/* ******************************************************************** */

  // Fléche precédente :
  /* Si on survole la fléche précedente */
  $(".post-navigation__previous-arrow").hover(
    function () {
      /* Pendant le survol, on change la couleur du fond */
      $(".post-navigation__previous-thumbnail").css("opacity", 1);
      /* Au moment ou la souris quitte le survol */
    },
    function () {
      /* On remet l'opacité vide (de base) */
      $(".post-navigation__previous-thumbnail").css("opacity", 0);
    }
  );

  // Fléche suivante :
  /* Si on survole la fléche suivante */
  $(".post-navigation__next-arrow").hover(
    function () {
      /* Pendant le survol, on change la couleur du fond */
      $(".post-navigation__next-thumbnail").css("opacity", 1);
      /* Au moment ou la souris quitte le survol */
    },
    function () {
      /* On remet l'opacité vide (de base) */
      $(".post-navigation__next-thumbnail").css("opacity", 0);
    }
  );

    /**********************************************
   * Supprimer balise lien "a" du dernier élement du menu footer
   **********************************************/
    $(".no-link").find("a").contents().unwrap();

    /**********************************************
     *Ajout Réf formulaire contact 
     **********************************************/
    $(".btn-contact").on("click", function (e) {
      e.preventDefault();
  
      // Récupérez la valeur du champ ACF à partir du post actuel
      var valeurChampACF = $(this).data("reference"); // Remplacez 'acf-field' par la classe, l'ID ou tout autre sélecteur approprié pour cibler le champ ACF
  
      // Pré-remplissez le champ du formulaire Contact Form 7
      $(".__ref-photo").val(valeurChampACF);
  
      // Ouvrez le formulaire Contact Form 7 (ajoutez l'ID du formulaire)
      $("#wpcf7-f18-o1").show();
    });

  