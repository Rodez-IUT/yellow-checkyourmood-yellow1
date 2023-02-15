let register = document.querySelectorAll('.left');
let connection = document.querySelectorAll('.right');
let shifterElements = document.querySelectorAll('.shifter');
let login = document.getElementById('login');
let valuesSE = [];
let registerTab = [];
let connectionTab = [];
let loginError = document.getElementById('loginError');
let registerError = document.getElementById('registerError');
let action = document.getElementById('action');

// met les 2 div qui correspondent au "bouton" 's'inscrire' dans un tableau
register.forEach((element) => {
    registerTab.push(element);
});

// met les 2 div qui correspondent au "bouton" 'se connecter' dans un tableau
connection.forEach((element) => {
    connectionTab.push(element);
});

// quand on clique sur le "bouton" 's'inscrire', appel la fonction 'registerSelected()'
$(".left").on('click', function() { (registerSelected()) });
// quand on clique sur le "bouton" 'se connecter', appel la fonction 'loginSelected()'
$(".right").on('click', function() { (loginSelected()) });

// choisit quel formulaire réafficher en fonction de l'erreur de l'utilisateur
if (loginError.className.match('error')) {
    loginSelected();
} else if (registerError.className.match('error')) {
    registerSelected();
}

/* Change le type des champs 'password' en champs de type 'text' 
   quand la case 'Afficher le mot de passe' est coché et 
   les remet en champ de type password quand elle est décoché */
$("#check").on('click', function() {
    if ($("#check").prop('checked')) {
        $('#password').attr('type', 'text');
        $('#confirmPassword').attr('type', 'text');
    } else {
        $('#password').attr('type', 'password');
        $('#confirmPassword').attr('type', 'password');
    }
});


/**
 * Permet de changer le mode de connexion à inscription
 */
function registerSelected() {
    registerTab.forEach((elementRegister) => {
        /* Vérifie pour chaque div 'register' (celle pour la version pc, tablettes et celle pour la version téléphone)
           si la div a la classe 'selection' si c'est le cas ne fait rien */
        if (!(elementRegister.className.match('selection'))) {
            /* sinon ajoute la classe 'selection' à la div 'register' et la retire à la div 'connection' */
            $(".left").addClass('selection');
            $(".right").removeClass('selection');
            /* change la valeur de 'action' pour que le bouton valider du formulaire appel la bonne fonction du controller */
            action.value = "register";
            /* change la valeur de 'login' pour que l'utilisateur ne puisse pas se connecter depuis l'inscription */
            login.value = 0;
            /* remet toutes les valeurs déjà saisie dans les champs et réaffiche les champs qui n'étaient pas affiché 
               en leur enlevant la classe 'display-none' */
            shifterElements.forEach((element) => {
                if (valuesSE[0] != null) {
                    element.value = valuesSE[0];
                    valuesSE.shift();
                }
                element.classList.remove('display-none');
                element.setAttribute('required', true);
            });
        }
    });
}

/**
 * Permet de changer le mode de inscription à connexion
 */
function loginSelected() {
    connectionTab.forEach((elementConnection) => {
        /* Vérifie pour chaque div 'register' (celle pour la version pc, tablettes et celle pour la version téléphone)
           si la div a la classe 'selection' si c'est le cas ne fait rien */
        if (!(elementConnection.className.match('selection'))) {
            /* sinon ajoute la classe 'selection' à la div 'connection' et la retire à la div 'register' */
            $(".left").removeClass('selection');
            $(".right").addClass('selection');
            /* change la valeur de 'action' pour que le bouton valider du formulaire appel la bonne fonction du controller */
            action.value = "login";
            /* change la valeur de 'login' pour que l'utilisateur puisse se connecter */
            login.value = 1;
            /* met toutes les valeurs déjà saisie dans un tableau et met des valeurs null dans les champs
               ajoute aussi la classe 'display-none' aux champs qui ne sont pas nécessaire à la connexion */
            shifterElements.forEach((element) => {
                valuesSE.push(element.value);
                element.value = '';
                element.classList.add('display-none');
                element.removeAttribute('required');
            });
        }
    });
}

// vérifie si les différents champs sont remplis ou non quand l'utilisateur déselectionne le champ
$("#username").on('blur', function() { (champValide($("#username"), "")) });
$("#email").on('blur', function() { (champValide($("#email"), "")) });
$("#birthDate").on('blur', function() { 
    if (!champValide($("#birthDate"), "")) {
        // si le champ est vide le remet en type 'text'
        $("#birthDate").attr('type', 'text');
    }
});
$("#gender").on('blur', function() { (champValide($("#gender"), "Choisissez votre genre")) });
$("#password").on('blur', function() { (champValide($("#password"), "")) });
$("#confirmPassword").on('blur', function() { (champValide($("#confirmPassword"), "")) });

/**
 * Vérifie si un champ contient une certaine valeur, 
 * sert plus spécifiquement à vérifier si un champ est vide ou si il contient sa valeur par défaut
 * @param {*} champ  le champ à vérifier
 * @param {*} value  la valeur par défaut à vérifier (vide si il n'y a pas de valeur particulière par défaut)
 * @returns true si le champ est rempli, sinon false si il est vide ou qu'il a une valeur par défaut
 */
function champValide(champ, value) {
    if (champ.val() == value) {
      // Le champ est vide, donc il est invalide, il passe en rouge
      champ.addClass('input-error');
      return false;
    } else {
      // Le champ n'est pas vide, donc il est valide, il reste/repasse en vert et renvoi
      champ.removeClass('input-error');
      return true;
    }
}

/* quand on passe sur le champ 'Date de naissance' ou que l'on soumet le formulaire, 
   transforme le champ en champ de type date  */
$("#birthDate").on('focus', function() { toDate() });
$("#form").on('submit', function() { toDate() });

/**
 * Transforme le champ 'Date de naissance' en champ de type date
 */
function toDate() {
    $("#birthDate").attr('type', 'date');
}

