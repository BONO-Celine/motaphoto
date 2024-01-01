// S'assure que le document est entièrement chargé et crée la variable $
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
  
    /**********************************************
     * Card
     **********************************************/
    $(".hover").mouseleave(function () {
      $(this).removeClass("hover");
    });
  
  
    /**********************************************
     * Bouton charger plus
     **********************************************/
    var page = 2; // Page actuelle, initialisée à 2 car première page déjà chargée
    var loading = false; // Variable pour éviter le chargement simultané de plusieurs pages
    var buttonHidden = false; // Variable pour suivre si le bouton a été masqué
  
    $("#load-more-btn").on("click", function () {
      if (!loading) {
        loading = true;
  
        // Récupérer les valeurs de filtre actuelles
        const selectedCategory = $("#select-categories .selected-option").data(
          "value"
        );
        const selectedFormat = $("#select-formats .selected-option").data(
          "value"
        );
        const sortType = $("#select-by-date .selected-option").data("value");
        console.log(sortType);
  
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
          success: function (response) {
            if (response !== "no-more-posts") {
              $("#post-container").append(response);
              page++;
            } else {
              $("#load-more-message").text(
                "Il n'y a plus d'autres photos à afficher."
              );
              // Ne masquer le bouton que s'il n'a pas été masqué auparavant
              if (!buttonHidden) {
                $("#load-more-btn").hide(); // Cacher le bouton s'il n'y a plus de posts
                buttonHidden = true; // Mettre à jour la variable pour indiquer que le bouton a été masqué
              }
            }
            loading = false;
          },
        });
      }
    });
  
    // Remet à zéro l'etat du bouton load more
    const resetLoadMore = () => {
      page = 2;
      loading = false; 
      buttonHidden = false;
      $("#load-more-btn").show();
      $("#load-more-message").text("");
    }
  
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
     * Dropdowns (listes déroulantes)
     **********************************************/
  // Ajouter un écouteur d'événements au document
  $(document).on("click", function (event) {
    // Vérifier si le clic est à l'intérieur de la liste déroulante "Catégories"
    if (!$(event.target).closest("#select-categories").length) {
      // Fermer la liste déroulante "Catégories" si elle est ouverte
      $("#select-categories").removeClass("active");
    }
  
    // Vérifier si le clic est à l'intérieur de la liste déroulante "Formats"
    if (!$(event.target).closest("#select-formats").length) {
      // Fermer la liste déroulante "Formats" si elle est ouverte
      $("#select-formats").removeClass("active");
    }
  
     // Vérifier si le clic est à l'intérieur de la liste déroulante "Trier par"
     if (!$(event.target).closest("#select-by-date").length) {
      // Fermer la liste déroulante "Trier par" si elle est ouverte
      $("#select-by-date").removeClass("active");
    }
  });
  
  
  
  
  
    // Écouteur d'événements pour les listes déroulantes
    $("#select-categories, #select-formats").on(
      "click",
      ".dropdown-item",
      function () {
        // Récupérer les valeurs sélectionnées
        const selectedCategory = $("#select-categories .selected-option").data(
          "value"
        );
        const selectedFormat = $("#select-formats .selected-option").data(
          "value"
        );

        const sortType = $("#select-by-date .selected-option").data("value");
          console.log(sortType);
        // Effectuer une requête AJAX pour récupérer les résultats filtrés
        $.ajax({
          url: ajaxurl, // Utiliser la variable ajaxurl définie par WordPress
          type: "POST",
          data: {
            action: "filter_posts", // Action defini dans fichier functions.php
            category: selectedCategory,
            format: selectedFormat,
            sortType: sortType,
          },
          success: function (response) {
            // Mise à jour du contenu de la balise div avec les résultats filtrés
            $("#post-container").html(response);
            resetLoadMore();
          },
          error: function (error) {
            console.error("Erreur AJAX:", error);
          },
        });
      }
    );
  
    // Écouteur d'événements pour la liste déroulante "Catégories"
    const mainDropdownButtonCategories = $("#mainDropdownButtonCategories");
    mainDropdownButtonCategories.click(function () {
      toggleDropdown($(this).parent());
    });
  
    // Écouteurs d'événements pour les options de la liste déroulante "Catégories"
    const dropdownItemsCategories = $("#select-categories .dropdown-item");
    dropdownItemsCategories.click(function (event) {
      const label = $(this).text();
      const value = $(this).data("value");
      selectOption($(this), label, value);
    });
  
    // Écouteur d'événements pour la liste déroulante "Formats"
    const mainDropdownButtonFormats = $("#mainDropdownButtonFormats");
    mainDropdownButtonFormats.click(function () {
      toggleDropdown($(this).parent());
    });
  
    // Écouteurs d'événements pour les options de la liste déroulante "Formats"
    const dropdownItemsFormats = $("#select-formats .dropdown-item");
    dropdownItemsFormats.click(function (event) {
      const label = $(this).text();
      const value = $(this).data("value");
      selectOption($(this), label, value);
    });
  
    // Écouteur d'événements pour la liste déroulante "Trier par"
    const mainDropdownButtonDate = $("#mainDropdownButtonDate");
    mainDropdownButtonDate.click(function () {
      toggleDropdown($(this).parent());
    });
  
    // Écouteurs d'événements pour les options de la liste déroulante "Trier par"
    const dropdownItemsdate = $("#select-by-date .dropdown-item");
    dropdownItemsdate.click(function (event) {
      const label = $(this).text();
      const value = $(this).data("value");
      selectOption($(this), label, value);
  
      // Appeler la fonction de filtrage sans spécifier le type de tri ici
      filterPosts();
    });
  
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
  
    // Fonction pour filtrer les articles en fonction des valeurs sélectionnées
    function filterPosts() {
      const selectedCategory = $("#select-categories .selected-option").data(
        "value"
      );
      const selectedFormat = $("#select-formats .selected-option").data("value");
      const sortType = $("#select-by-date .selected-option").data("value");
  
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
          $("#post-container").html(response);
          resetLoadMore();
        },
        error: function (error) {
          console.error("Erreur AJAX:", error);
        },
      });
    }
  
    /**********************************************
     * MENU BURGER et Ouverture et fermeture OVERLAY
     **********************************************/
    $(".burger-icon, .menu-mobile-container").click(function () {
      $(".burger-icon").toggleClass("icon-toggle");
      $(".menu-mobile-container").toggleClass("open-menu-mobile");
    });
  
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
  });
  