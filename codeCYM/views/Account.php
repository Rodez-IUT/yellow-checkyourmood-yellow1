<!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link href="/CheckYourMood/codeCYM/third-party/bootstrap/css/bootstrap.css" rel="stylesheet"/>
        <link href="/CheckYourMood/codeCYM/CSS/Account.css" rel="stylesheet"/>
        <link rel="apple-touch-icon" sizes="180x180" href="/CheckYourMood/codeCYM/assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/CheckYourMood/codeCYM/assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/CheckYourMood/codeCYM/assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/CheckYourMood/codeCYM/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
        <script src="/CheckYourMood/codeCYM/JS/header-component.js" defer></script>
        <script src="/CheckYourMood/codeCYM/JS/accounts.js" defer></script>
        <title>Account</title>
    </head>
    <body>
        <?php
            spl_autoload_extensions(".php");
            spl_autoload_register();
        ?>
        <header-component></header-component>
        <div class="main-container">
            <div class='title'>
                <h1>Profil</h1>
            </div>
            <div class="main">  
            <?php
                echo "<div class='Profil-Main'>";
                    echo '<div class="Profil1">';
                        echo '<div class="Email">';
                                echo "<h2><b>Email :</b></h2>";
                                echo "<div></div>";
                                echo "<h2>".$mail."</h2>";
                        echo "</div>";
                        echo '<div class="UserName">';
                            echo "<h2><b>Nom d'utilisateur :</b></h2>";
                            echo "<div></div>";
                            echo "<h2>".$username."</h2>";
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='Profil2'>";
                        echo '<div class="BirthDate">';
                            echo "<h2><b>Date de naissance :</b></h2>";
                            echo "<div></div>";
                            echo "<h2>".$birthDate."</h2>";
                        echo "</div>";
                        echo '<div class="Gender">';
                            echo "<h2><b>Genre :</b></h2>";
                            echo "<h2>$gender</h2>";
                        echo "</div>";
                    echo "</div>"; 
                echo "</div>";
            ?>
            </div>
            <div class='mid-Buttons'>
                <div class='button-Del'>
                    <form method="get" action="#">
                        <input hidden name="action" value="editPassword">
                        <input hidden name="controller" value="accounts">
                        <input class="form-control button" type="submit" value="Modifier le mot de passe"/></input>
                    </form>
                </div> 
                <div class='button-Dec'>
                    <form method="get" action="#">
                        <input hidden name="action" value="editProfile">
                        <input hidden name="controller" value="accounts">
                        <input class="form-control button" type="submit" value="Modifier le profil"/>
                    </form>
                </div>
            </div>
            <div class="bot-Buttons-Container">
                <div class="bot-button-one">
                    <form method="get" action="#">
                        <input hidden name="action" value="deleteAccount">
                        <input hidden name="controller" value="accounts">
                        <input class="buttonD" type="submit" value="Supprimer le compte"/>
                    </form>
                </div>
                <div class="bot-button-two">
                    <form method="get" action="#">
                        <input hidden name="action" value="disconnect">
                        <input hidden name="controller" value="accounts">
                        <input class="buttonD" type="submit" value="Déconnexion"/>
                    </form>
                </div>
            </div>
        </div>
    </body>
    </html>