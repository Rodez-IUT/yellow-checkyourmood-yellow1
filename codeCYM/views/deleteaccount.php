<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link href="/CheckYourMood/codeCYM/third-party/bootstrap/css/bootstrap.css" rel="stylesheet"/>
    <link href="/CheckYourMood/codeCYM/CSS/deleteaccount.css" rel="stylesheet"/>
    <link rel="apple-touch-icon" sizes="180x180" href="/CheckYourMood/codeCYM/assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/CheckYourMood/codeCYM/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/CheckYourMood/codeCYM/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/CheckYourMood/codeCYM/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <script src="/CheckYourMood/codeCYM/JS/header-component.js" defer></script>
    <title>Modification du profil</title>
</head>
<body>
    <?php
        spl_autoload_extensions(".php");
        spl_autoload_register();
    ?>
    <header-component></header-component>
    <div class="container">
        <div class="row main">
            <h1 class="enRouge" style="text-align: center">Suppression du compte</h1>
            <div class="d-flex flex-row align-items-center justify-content-center">
                <form method="get" action="#">
                    <input type="submit" class="button" value="Retour">
                    <input hidden name="action" value="index">
                    <input hidden name='controller' value='accounts'>
                </form>
                <form method="get" action="#">
                    <input type="submit" class="buttonD" name="delete" value="Confirmer">
                    <input hidden name="action" value="deleteAccount">
                    <input hidden name='controller' value='accounts'>
                </form>
            </div>
        </div>
        <div clas="row">

        </div>
    </div>
</body>
</html>