<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="/CheckYourMood/codeCYM/third-party/bootstrap/css/bootstrap.css" rel="stylesheet"/>
    <link href="/CheckYourMood/codeCYM/CSS/editpassword.css" rel="stylesheet"/>
    <link rel="apple-touch-icon" sizes="180x180" href="/CheckYourMood/codeCYM/assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/CheckYourMood/codeCYM/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/CheckYourMood/codeCYM/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/CheckYourMood/codeCYM/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <script src="/CheckYourMood/codeCYM/JS/header-component.js" defer></script>
    <script src="/CheckYourMood/codeCYM/third-party/JQuery/jquery-3.6.1.js"></script>
    <script src="/CheckYourMood/codeCYM/JS/accounts.js" defer></script>
    <title>Modification du mot de passe</title>
</head>
<body>
    <?php
        spl_autoload_extensions(".php");
        spl_autoload_register();
    ?>
    <header-component></header-component>
    <div class="container">
        <div class="row">
            <form class="main" method="post">
                <?php
                    if($update) {
                        if($testOldPassword == false) {
                            echo '<input class="form-control enRouge" type=password id="oldPassword" name="oldPassword" placeholder="Vous devez mettre votre ancien mot de passe" value = "';
                            if (isset($_POST["oldPassword"]) && $_POST["oldPassword"] != "") {
                                echo $_POST["oldPassword"];
                            }
                            echo '"></input>';
                            if (isset($_POST["oldPassword"]) && $_POST["oldPassword"] != "") echo '<span class = "texteRouge">Renseignez votre mot de passe actuel</span>';
                        } else {
                            echo '<input class="form-control" type=password id="oldPassword" name="oldPassword" placeholder="Ancien mot de passe" value="';
                            if($resetPwd != 1) {
                                echo $oldPassword;
                            }
                            echo '"></input>';
                        }
                        if($testNewPassword == false || $testOldPasswordNotSameAsNew == false) {
                            echo '<input class="form-control enRouge" type=password id="newPassword" name="newPassword" placeholder="Mettez un nouveau mot de passe" value = "';
                            if (isset($_POST["newPassword"]) && $_POST["newPassword"] != "") {
                                echo $_POST["newPassword"];
                            }
                            echo '"></input>';
                            echo '<input class="form-control enRouge" type=password id="confirmPassword" name="confirmPassword" placeholder="Confirmez votre nouveau mot de passe" value = "';
                            if (isset($_POST["confirmPassword"]) && $_POST["confirmPassword"] != "") {
                                echo $_POST["confirmPassword"];
                            }
                            echo '"></input>';
                            if ($testNewPassword == false && $testOldPassword == true && isset($_POST["newPassword"]) && $_POST["newPassword"] != "" && isset($_POST["confirmPassword"]) && $_POST["confirmPassword"] != "" && isset($_POST["oldPassword"]) && $_POST["oldPassword"] != "") echo '<span class = "texteRouge">Les mots de passe ne sont pas identiques</span>';
                            if ($testOldPasswordNotSameAsNew == false && $testNewPassword == true && $testOldPassword == true) echo '<span class = "texteRouge">Votre nouveau mot de passe doit être différent de l\'actuel</span>';
                        } else {
                            echo '<input class="form-control" type=password id="newPassword" name="newPassword" placeholder="Nouveau mot de passe" value ="';
                            if ($resetPwd != 1 && isset($_POST["newPassword"]) && $_POST["newPassword"] != "") {
                                echo $_POST["newPassword"];
                            }
                            echo '"></input>';
                            echo '<input class="form-control" type=password id="confirmPassword" name="confirmPassword" placeholder="Confirmez votre mot de passe" value ="';
                            if ($resetPwd != 1 && isset($_POST["confirmPassword"]) && $_POST["confirmPassword"] != "") {
                                echo $_POST["confirmPassword"];
                            }
                            echo '"></input>';
                        }
                    } else {
                        echo '<input class="form-control" type=password id="oldPassword" name="oldPassword" placeholder="Ancien mot de passe"></input>';
                        echo '<input class="form-control" type=password id="newPassword" name="newPassword" placeholder="Nouveau mot de passe"></input>';
                        echo '<input class="form-control" type=password id="confirmPassword" name="confirmPassword" placeholder="Confirmez votre mot de passe"></input>';
                    }
                    echo '<div class="checkbox">
                            <input id="check" type="checkbox" name="check"> Afficher les Mots de passe
                        </div>';
                    echo '<input class="button" name="envoyer" type="submit" value="Confirmer"></input>';
                    echo '<p class="enVert">'.$message.'</p>';
                ?>
            </form>
        </div>
        <div clas="row">
            <form method="get" action="#">
                <input type="submit" class="button" value="Retour">
                <input hidden name="action" value="index">
                <input hidden name='controller' value='accounts'>
            </form>
        </div>
    </div>
</body>
</html>