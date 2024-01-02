/************************************************/
   /* CODE LIGHTBOX */
/* **********************************************/
var $ = jQuery;
var currentIndex = 0;
var postsData = [];

function removeDuplicates(array) {
    return array.filter((value, index, self) => {
        return self.findIndex(v => v.imageUrl === value.imageUrl) === index;
    });
}

function initializeLightboxData() {
    postsData = removeDuplicates(lightboxData || []);
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

    lightbox.css("display", "flex"); // Changer diplay:none en flex pour afficher la lightbox
}

// Fonction pour fermer la lightbox
function closeLightbox() {
    var lightbox = $("#lightbox");
    lightbox.css("display", "none");
}

// Exécuter la fonction lorsque le document est prêt
$(document).ready(function () {
    initializeLightboxData();

    // Sélectionner tous les boutons avec la classe "icon-full-screen__button"
    var fullscreenButtons = $(".icon-full-screen__button");

    // Ajouter un écouteur d'événements à chaque bouton
    fullscreenButtons.on("click", function () {
        // Obtenir les données nécessaires à partir des attributs de données ou d'autres attributs du bouton
        var imageUrl = $(this).data("image-url");
        var imageRef = $(this).data("image-ref");
        var imageCategorie = $(this).data("image-categorie");

        // Réinitialiser le tableau avant de l'actualiser avec les nouvelles données
        initializeLightboxData();

        // Appeler la fonction openLightbox avec les données récupérées
        openLightbox(imageUrl, imageRef, imageCategorie);
        $("#lightbox").removeClass("animate-zoom-out");
        $("#lightbox").addClass("animate-zoom-in");
    });
    console.log(postsData); // Vérification des données
    // Fermer la lightbox au clic sur la croix de fermeture
    $(".close-lightbox").on("click", function () {
        //closeLightbox();
        $("#lightbox").removeClass("animate-zoom-in");
        $("#lightbox").addClass("animate-zoom-out");
        setTimeout(closeLightbox, 500);
    });
    
    // Événements clic sur les flèches
    //$("#lightbox-left-navigation").on("click", function () {
    $("#lightbox-right-navigation").on("click", function () {
        if (currentIndex > 0) {
            currentIndex--;
        } else {
            currentIndex = postsData.length - 1;
        }
        openLightbox(
            postsData[currentIndex].imageUrl,
            postsData[currentIndex].imageRef,
            postsData[currentIndex].imageCategorie
        );
    });

    //$("#lightbox-right-navigation").on("click", function () {
    $("#lightbox-left-navigation").on("click", function () {
        if (currentIndex < postsData.length - 1) {
            currentIndex++;
        } else {
            currentIndex = 0;
        }
        openLightbox(
            postsData[currentIndex].imageUrl,
            postsData[currentIndex].imageRef,
            postsData[currentIndex].imageCategorie
        );
    });
});