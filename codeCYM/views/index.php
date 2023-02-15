<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/CheckYourMood/codeCYM/CSS/menu.css">
        <link rel="stylesheet" href="/CheckYourMood/codeCYM/third-party/bootstrap/css/bootstrap.css">
        <link rel="apple-touch-icon" sizes="180x180" href="/CheckYourMood/codeCYM/assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/CheckYourMood/codeCYM/assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/CheckYourMood/codeCYM/assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/CheckYourMood/codeCYM/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <script src="/CheckYourMood/codeCYM/JS/header-component.js" defer></script>     
        <title>Accueil</title>
    </head>
    <body>
        <?php
        spl_autoload_extensions(".php");
        spl_autoload_register();
        ?>
        <header-component></header-component>  
        <div class="container ">
            <div class="row text-block">
                <div class="col-sm-12">
                    <h1>Check Your Mood</h1>
                </div>
                <div class="col-sm-12">
                    <p>
                        Check Your Mood est une application vous permettant
                        à tout moment d'indiquer vos émotions à différents
                        moments de la journée, consulter ces dernières
                        pour suivre l’évolution de votre humeur.
                        Les émotions se basent sur <a class="liensTravaux" href="https://www.pnas.org/doi/10.1073/pnas.1702247114" target="_blank">
                        l’étude de Alan S. Cowenet Dacher Keltner</a>
                        , répertoriant un total de 27 émotions.
                    </p>
                </div>
                <div class="col-sm-12 texteInscription">
                    <p>
                        Pour commencer à enregistrer vos émotions 
                        <?php 
                            if(isset($_SESSION['UserID']) && $_SESSION['UserID'] != null) echo "Cliquez ici : <a class='liensTravaux' href='?action=index&controller=humeurs'>Ajouter une humeur</a>";
                            else echo "<a class='liensTravaux' href='?action=index&controller=register'>inscrivez-vous ou connectez-vous</a>"; 
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>