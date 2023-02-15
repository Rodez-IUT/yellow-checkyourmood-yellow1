/**
 * met la classe 'show' à la div numéro 'i' pour afficher la popup et 
 * modifie la valeur du bouton associé
 * @param {*} i  le numéro de la div et du bouton
 */
function showPopup(i) {  
    var popup = $("#myPopup"+i);
    popup.addClass("show");
    var input = $("#"+i);
    input.val(0);
}

/**
 * enlève la classe 'show' à la div numéro 'i' pour enlever la popup
 * @param {*} i  le numéro de la div
 */
function removePopup(i) {  
    var popup = $("#myPopup"+i);
    popup.removeClass("show");
}