/************************************************/
   /* CODE LIGHTBOX */
/* **********************************************/
var $ = jQuery; // Assigne la bibliothèque jQuery à la variable $
var currentIndex = 0; // Initialise l'index courant pour le suivi de la position actuelle dans les données
var postsData = []; // Initialise un tableau pour stocker les données des images sans doublons

function removeDuplicates(array) {
    //filtre le tableau en ne conservant que la première occurrence de chaque élément pour la propriété imageUrl. 
    return array.filter((value, index, self) => { 
        return self.findIndex(v => v.imageUrl === value.imageUrl) === index;
    });
}

// Fonction pour initialiser les données de la lightbox en supprimant les doublons
function initializeLightboxData() {
    postsData = removeDuplicates(lightboxData || []); // Utilise la fonction removeDuplicates pour éliminer les doublons des données
}

// Fonction pour ouvrir la lightbox avec l'image correspondante
function openLightbox(imageUrl, imageRef, imageCategorie) {
    var lightbox = $("#lightbox");
    var lightboxImage = $("#lightbox-image");
    var lightboxRef = $("#lightbox__ref");
    var lightboxCategorie = $("#lightbox__categorie");

    lightboxImage.attr("src", imageUrl);
    lightboxRef.text(imageRef);
    lightboxCategorie.text(imageCategorie);

    lightbox.css("display", "flex"); // Change le style pour afficher la lightbox en utilisant Flexbox
}

// Fonction pour fermer la lightbox
function closeLightbox() {
    var lightbox = $("#lightbox");
    lightbox.css("display", "none"); // Change le style pour cacher la lightbox
}

// Exécute la fonction lorsque le document est prêt
$(document).ready(function () {
    initializeLightboxData(); // Initialise les données de la lightbox lors du chargement du document

    // Sélectionne tous les boutons avec la classe "icon-full-screen__button"
    var fullscreenButtons = $(".icon-full-screen__button");

    // Ajoute un écouteur d'événements à chaque bouton
    fullscreenButtons.on("click", function () {
        // Obtient les données nécessaires à partir des attributs de données du bouton
        var imageUrl = $(this).data("image-url");
        var imageRef = $(this).data("image-ref");
        var imageCategorie = $(this).data("image-categorie");

        // Réinitialise le tableau avant de l'actualiser avec les nouvelles données
        initializeLightboxData();

        // Appelle la fonction openLightbox avec les données récupérées
        openLightbox(imageUrl, imageRef, imageCategorie);
        // Anime l'ouverture de la lightbox en ajoutant et supprimant des classes
        $("#lightbox").removeClass("animate-zoom-out");
        $("#lightbox").addClass("animate-zoom-in");
    });

    //console.log(postsData); // Vérification des données

    // Ferme la lightbox au clic sur la croix de fermeture
    $(".close-lightbox").on("click", function () {
        // Ajoute et supprime des classes pour animer la fermeture de la lightbox
        $("#lightbox").removeClass("animate-zoom-in"); 
        $("#lightbox").addClass("animate-zoom-out"); 
        setTimeout(closeLightbox, 500); //Exécute la fonction de fermeture après un délai pour laisser l'animation se terminer
    });
    
    // Événements clic sur les flèches
    $("#lightbox-right-navigation").on("click", function () {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = postsData.length - 1;
        }
        // Ouvre la lightbox avec les données de l'image suivante
        openLightbox(
            postsData[currentIndex].imageUrl,
            postsData[currentIndex].imageRef,
            postsData[currentIndex].imageCategorie
        );
    });

    $("#lightbox-left-navigation").on("click", function () {
        if (currentIndex < postsData.length - 1) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        // Ouvre la lightbox avec les données de l'image précédente
        openLightbox(
            postsData[currentIndex].imageUrl,
            postsData[currentIndex].imageRef,
            postsData[currentIndex].imageCategorie
        );
    });
});