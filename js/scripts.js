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