
/* Change le type des champs 'password' en champs de type 'text' 
   quand la case 'Afficher le mot de passe' est coché et 
   les remet en champ de type password quand il est décoché */
$("#check").on('click', function() {
    if ($("#check").prop('checked')) {
        $('#oldPassword').attr('type', 'text');
        $('#newPassword').attr('type', 'text');
        $('#confirmPassword').attr('type', 'text');
    } else {
        $('#oldPassword').attr('type', 'password');
        $('#newPassword').attr('type', 'password');
        $('#confirmPassword').attr('type', 'password');
    }
});