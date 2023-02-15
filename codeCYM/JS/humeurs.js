/**
 * Récupère l'heure locale
 */
function refreshTime() {
    const dateString = new Date().toLocaleTimeString();
    $("#time").text(dateString);
}
/* Lance la récupèration de l'heure toute les 100 millisecondes pour l'actualiser */
setInterval(refreshTime, 100);

/**
 * Récupère le smiley qui correspond à l'humeur saisie par l'utilisateur
 * @param {*} element  l'humeur saisie
 */
function getSmiley(element) {
    var saisie = (element.value || element.options[element.selectedIndex].value); 
    switch ((""+saisie).toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "")) {
        case 'admiration': smiley = "😊"; break;
        case 'adoration': smiley = "🤤"; break;
        case 'appreciation esthetique': smiley = "🖼️"; break;
        case 'amusement': smiley = "🥳"; break;
        case 'colere': smiley = "😠"; break;
        case 'anxiete': smiley = "😰"; break;
        case 'emerveillement': smiley = "🤩"; break;
        case 'malaise': smiley = "😖"; break;
        case 'ennui': smiley = "🥱"; break;
        case 'calme': smiley = "😐"; break;
        case 'confusion': smiley = "😕"; break;
        case 'envie': smiley = "🥵"; break;
        case 'degout': smiley = "🤢"; break;
        case 'douleur empathique': smiley = "💔"; break;
        case 'interet etonne, intrigue': smiley = "🤔"; break;
        case 'excitation': smiley = "🤪"; break;
        case 'peur': smiley = "😨"; break;
        case 'horreur': smiley = "😱"; break;
        case 'interet': smiley = "🧐"; break;
        case 'joie': smiley = "😄"; break;
        case 'nostalgie': smiley = "🎆"; break;
        case 'soulagement': smiley = "😌"; break;
        case 'romance': smiley = "🌹"; break;
        case 'tristesse': smiley = "😥"; break;
        case 'satisfaction': smiley = "👍"; break;
        case 'desir sexuel': smiley = "😏"; break;
        case 'surprise': smiley = "🙀"; break;
        default:
            smiley = "🚫";
    }
    $("#smiley").val(smiley);
}