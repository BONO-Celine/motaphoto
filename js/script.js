// Crée la variable $
jQuery(function ($) {

  /**********************************************/
  /*        OVERLAY (modale Contact)            */
  /**********************************************/
  $(".menu__contact, .btn-contact").click(function () {
    $(".overlay").removeClass("animate-zoom-out");
    $(".overlay").addClass("open animate-zoom-in");
  });
  
  $(".overlay").click(function (event) {
    if (  //Si Le clic s'est produit en dehors de l'élément popup et l'element overlay a la class open
      !$(event.target).closest(".popup").length &&
      $(".overlay").hasClass("open")
    ) {
      $(".overlay").removeClass("open animate-zoom-in");
      $(".overlay").addClass("animate-zoom-out");
    }
  });
  
  /**********************************************/
  /*        Bouton charger plus                 */
  /**********************************************/
  var page = 2; // Page actuelle, initialisée à 2 car première page déjà chargée
  var loading = false; // Variable pour éviter le chargement simultané de plusieurs pages
  var buttonHidden = false; // Variable pour suivre si le bouton a été masqué
  // Écouteur d'événements sur le bouton "Charger plus"
  $("#load-more-btn").on("click", function () {
    if (!loading) { // Vérifie si le chargement est déjà en cours
      loading = true; // Met à jour la variable pour indiquer que le chargement commence
      // Récupérer les valeurs de filtres actuelles
      const selectedCategory = $("#select-categories .selected-option").data(
      "value"
      );
      const selectedFormat = $("#select-formats .selected-option").data(
        "value"
      );
      const sortType = $("#select-by-date .selected-option").data("value");
      //console.log(sortType);
      // Appel AJAX pour récupérer davantage de posts
      $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
        action: "load_more_posts",
        page: page,
        category: selectedCategory,
        format: selectedFormat,
        sortType: sortType,
        },
        success: function (response) { // Vérifie si la réponse contient plus de posts
        if (response !== "no-more-posts") {
          // Ajoute les nouveaux posts au conteneur existant
          $("#post-container").append(response);
          page++; // Incrémente le numéro de page pour la prochaine requête
        } else {
          // Affiche un message s'il n'y a plus de posts à charger
            $("#load-more-message").text("Il n'y a plus d'autres photos à afficher.");
            if (!buttonHidden) { // Ne masquera le bouton que s'il n'a pas été masqué auparavant
            $("#load-more-btn").hide(); // Cache le bouton s'il n'y a plus de posts
            buttonHidden = true; // Met à jour la variable pour indiquer que le bouton a été masqué
            }
          }
            loading = false; // Met à jour la variable pour indiquer que le chargement est terminé
        },
      });
    }
  });
  
  // Remet à zéro l'etat du bouton load more
  const resetLoadMore = () => {
  page = 2; // Réinitialise le numéro de page
  loading = false; // Réinitialise la variable de chargement
  buttonHidden = false; // Réinitialise la variable de bouton masqué
  $("#load-more-btn").show(); // Affiche le bouton
  $("#load-more-message").text(""); // Efface le message
  }
  
  /* ******************************************************************** */
  /*      Affichage des miniatures des posts suivant/précedent
          au hover sur la fleche correspondante */
  /* ******************************************************************** */
  
    // Fléche precédente :
    /* Si on survole la fléche précédente */
    $(".post-navigation__previous-arrow").hover(
      function () {
        /* Pendant le survol, on change l'opacité pour afficher le container avec la miniature */
        $(".post-navigation__previous-thumbnail").css("opacity", 1);
        /* Au moment ou la souris quitte le survol */
      },
      function () {
        /* On remet l'opacité à 0 pour cacher la div de la miniature */
        $(".post-navigation__previous-thumbnail").css("opacity", 0);
      }
    );
  
    // Fléche suivante :
    /* Si on survole la fléche suivante */
    $(".post-navigation__next-arrow").hover(
      function () {
        /* Pendant le survol, on change l'opacité pour afficher le container avec la miniature */
        $(".post-navigation__next-thumbnail").css("opacity", 1);
        /* Au moment ou la souris quitte le survol */
      },
      function () {
        /* On remet l'opacité à 0 pour cacher la div de la miniature */
        $(".post-navigation__next-thumbnail").css("opacity", 0);
      }
    );
  

/***********************************************/
/*   Dropdowns (listes déroulantes)           */
/**********************************************/

// Ajoute un écouteur d'événements au document pour fermer les listes déroulantes
$(document).on("click", function (event) {
  closeDropdownIfOutside(event, "#select-categories");
  closeDropdownIfOutside(event, "#select-formats");
  closeDropdownIfOutside(event, "#select-by-date");
});

// Écouteur d'événements pour les listes déroulantes
$("#select-categories, #select-formats, #select-by-date").on("click", ".dropdown-item", function () {
    // Récupére les valeurs sélectionnées
    const selectedCategory = $("#select-categories .selected-option").data("value");
    const selectedFormat = $("#select-formats .selected-option").data("value");
    const sortType = $("#select-by-date .selected-option").data("value");

    //console.log(sortType);

    // Effectue une requête AJAX pour récupérer les résultats filtrés
    filterPosts(selectedCategory, selectedFormat, sortType);
});

// Écouteurs d'événements pour les boutons des listes déroulantes
bindDropdownEvents("#mainDropdownButtonCategories", "#select-categories");
bindDropdownEvents("#mainDropdownButtonFormats", "#select-formats");
bindDropdownEvents("#mainDropdownButtonDate", "#select-by-date");

// Fonction pour basculer la visibilité du contenu de la liste déroulante
function toggleDropdown(dropdown) {
    dropdown.toggleClass("active");
}

// Fonction pour gérer la sélection d'une option
function selectOption(button, label, value) {
    const buttonContainer = button.closest(".custom-dropdown");
    buttonContainer.find(".dropdown-button").text(label);
    console.log("Option sélectionnée :", { label, value });

    const items = buttonContainer.find(".dropdown-item");
    items.removeClass("selected-option");
    button.addClass("selected-option");

    toggleDropdown(buttonContainer);
}

// Fonction pour fermer la liste déroulante si le clic est à l'extérieur
  function closeDropdownIfOutside(event, dropdownSelector) {
    if (!$(event.target).closest(dropdownSelector).length) {
        $(dropdownSelector).removeClass("active");
    }
}

// Fonction pour lier les événements aux boutons des listes déroulantes
function bindDropdownEvents(mainButtonSelector, dropdownSelector) {
    const mainDropdownButton = $(mainButtonSelector);
    mainDropdownButton.click(function () {
        toggleDropdown($(this).parent());
    });

    const dropdownItems = $(`${dropdownSelector} .dropdown-item`);
    dropdownItems.click(function () {
        const label = $(this).text();
        const value = $(this).data("value");
        selectOption($(this), label, value);
    });
}

// Fonction pour filtrer les cards en fonction des valeurs sélectionnées
function filterPosts(selectedCategory, selectedFormat, sortType) {
    console.log(sortType);
    $.ajax({
        url: ajaxurl,
        type: "POST",
        data: {
            action: "filter_posts",
            category: selectedCategory,
            format: selectedFormat,
            sortType: sortType,
        },
        success: function (response) {
          // Mise à jour du contenu de la div avec les résultats filtrés
            $("#post-container").html(response);
            resetLoadMore();
        },
        error: function (error) {
            console.error("Erreur AJAX :", error);
        },
    });
}
  
  /*************************************************/
    /* Transformation de l'icone burger et Ouverture 
      et fermeture OVERLAY du menu mobile */
  /*************************************************/
  $(".burger-icon, .menu-mobile-container").click(function () {
    $(".burger-icon").toggleClass("icon-toggle");
    $(".menu-mobile-container").toggleClass("open-menu-mobile");
  });
  
  /***************************************************************/
  /* Supprimer balise lien "a" du dernier élement du menu footer */
  /* *************************************************************/
  $(".no-link").find("a").contents().unwrap();
  
  /**********************************************/
     /* Ajout Réf au formulaire Contact Form 7 */
  /**********************************************/
  $(".btn-contact").on("click", function (e) {
    e.preventDefault(); //permet d'empêcher l'ouverture du formulaire avant le pré-remplissage de la Réf.
    // Récupére la valeur du champ ACF "reference" à partir du post actuel
    var valeurChampACF = $(this).data("reference");
    // Pré-remplit le champ du formulaire Contact Form 7
    $(".__ref-photo").val(valeurChampACF);
    // Ouvre le formulaire Contact Form 7 grace à son ID
    $("#wpcf7-f18-o1").show();
  });
});
  