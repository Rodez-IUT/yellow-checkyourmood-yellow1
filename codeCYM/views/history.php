<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link href="/CheckYourMood/codeCYM/third-party/bootstrap/css/bootstrap.css" rel="stylesheet"/>
        <link href="/CheckYourMood/codeCYM/CSS/history.css" rel="stylesheet"/>
        <link rel="stylesheet" href="/CheckYourMood/codeCYM/third-party/fontawesome-free-6.2.0-web/css/all.css">
        <link rel="apple-touch-icon" sizes="180x180" href="/CheckYourMood/codeCYM/assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/CheckYourMood/codeCYM/assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/CheckYourMood/codeCYM/assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/CheckYourMood/codeCYM/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
        <script src="/CheckYourMood/codeCYM/JS/history.js"></script>
        <script src="/CheckYourMood/codeCYM/third-party/JQuery/jquery-3.6.1.js"></script>
        <title>Historique</title>
        <script src="/CheckYourMood/codeCYM/JS/header-component.js" defer></script>
    </head>
    <body>
    <?php
        spl_autoload_extensions(".php");
        spl_autoload_register();
    ?>
    <header-component></header-component>
    <div class="first-container">
        <?php  
            if(isset($_GET['page']) && !empty($_GET['page'])){
                $currentPage = (int) strip_tags($_GET['page']);
            } else {
                $currentPage = 0;
            }
            echo "<h1 class='title'>Historique des humeurs</h1>";
            echo "<div class='first-container'>";
                echo "<table class='table table-striped'>";
                    echo "<tr>
                            <form>
                                <input hidden name='action' value='historyVal'>
                                <input hidden name='controller' value='stats'>
                                <th>
                                    Humeur
                                </th>
                                <th>Emoji associé</th>
                                <th>
                                    Date/Heure
                                </th>
                            </form>
                          <tr>";		
                    $i = 1;
                    while( $ligne = $historyValue->fetch() ) { 
                            $date1 = $ligne->Humeur_TimeConst;
                            $timeStamp1 = strtotime($date1);
                            $finalDate = $timeStamp1 + 86400;
                            $finalDate1 = date('Y-m-d H:i:s', $finalDate);

                            $actualDate = date_default_timezone_get(); 
                            $actual = date('Y-m-d H:i:s', time());
                            $actualTimeStamp = strtotime($actual);
                            $actualFinalTimeStanp = date('Y-m-d H:i:s', $actualTimeStamp);

                            $dayBefore = $timeStamp1 - 86400;
                            $minDate = date('Y-m-d H:i:s', $dayBefore);
                            echo "<tr>
                                    <td class='Sscreen-Libelle'>".htmlspecialchars($ligne->Humeur_Libelle)."<br><form class='Property-Sscreen' action='#' method='post'><button name='pop' value='$i' id='$i' type='submit' class='param'><i class='fa-solid fa-gear'></i></button></form>
                                        <div class='popuptext' id='myPopup$i'>
                                            <div class='cross-button'><form action='#' method='post'><button type='submit' class='xMark'><i class='fa-solid fa-xmark'></i></button></form></div>
                                            <div class='desc-title'>Description :</div>
                                            <textarea class='description' disabled>".htmlspecialchars($ligne->Humeur_Description)."</textarea>
                                            <div class='delimiter-Row'></div>
                                            <div>Nouvelle Description :<br></div>
                                            <div class='buttons'>
                                                <form action='#' method='post' class='form-desc'>
                                                    <input hidden name='action' value='update'>
                                                    <input hidden name='controller' value='stats'>
                                                    <input hidden name='time' value='$ligne->Humeur_Time'>
                                                    <input hidden name='libelle' value='$ligne->Humeur_Libelle'>
                                                    <textarea name='desc' class='textarea' value='$ligne->Humeur_Description'>$ligne->Humeur_Description</textarea>";
                                                    if ($actualFinalTimeStanp <= $finalDate1) {
                                                        echo "<label>Nouvelle Date : (Max -24H) </label>
                                                            <input class='time' type='datetime-local' name='change-time' min='$minDate' max='$ligne->Humeur_TimeConst' value='$ligne->Humeur_Time'>";
                                                    } else {
                                                        echo "<label>Date non modifiable <br>(humeur créée il y a trop longtemps):</label>
                                                            <input hidden name='change-time' value='$ligne->Humeur_Time'>
                                                            <input type='text' value='$ligne->Humeur_Time' disabled>";
                                                    }
                                                    echo "<button type='submit' name='del-humeur' val='$i' class='update'>
                                                        Valider
                                                    </button>
                                                </form>
                                                <form action='#' method='post' class='form-del'>
                                                    <input hidden name='action' value='deleteHumeur'>
                                                    <input hidden name='controller' value='stats'>
                                                    <input hidden name='time' value='$ligne->Humeur_Time'>
                                                    <input hidden name='libelle' value='$ligne->Humeur_Libelle'>
                                                    <button type='submit' name='del-humeur' value='$i' class='trash'>
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                    <td class='Sscreen-Emoji'>".htmlspecialchars($ligne->Humeur_Emoji)."</td>
                                    <td class='Sscreen-Time'>".htmlspecialchars($ligne->Humeur_Time)."</td>";
                                    
                            echo "</tr>";
                        /*}*/
                        $i++;										
                    }
                echo "</table>" ;
            echo "</div>";	
            if(isset($_POST['pop']) && $_POST['pop'] != "") {
                if ($_POST['pop'] == 0) {
                    $val = $_POST['pop'];
                    echo "<script>removePopup($val);</script>";
                    $_POST['pop'] = "";
                } else {
                    $val = $_POST['pop'];
                    echo "<script>showPopup($val);</script>";
                }
            }
            $pages = $allRow / 15;
            $valAcomparer = $pages % 15;
            if ($pages > $valAcomparer && $pages >= 0) {
                $pages++;
            }
            echo "<nav>";
                echo "<ul class='pagination'>";
                    if ($currentPage > 1) {
                        echo "<li class='page-item'>"; 
                            ?>
                            <a class="page-button" href="./?action=historyVal&controller=stats&page=1"><i class="fa-solid fa-angles-left"></i></a>
                            <?php
                        echo "</li>";
                        echo "<li class='page-item'>"; 
                            ?>
                            <a class="page-button" href="./?action=historyVal&controller=stats&page=<?php echo $currentPage - 1?>"><i class="fa-solid fa-chevron-left"></i></a>
                            <?php
                        echo "</li>";
                    }
                    for ($compteur = 1; $compteur <= $pages; $compteur++) { 
                        if ($compteur >= $currentPage - 2 && $compteur <= $currentPage + 2) {
                            ?>
                            <li class="page-item <?= ($currentPage == $compteur) ? "active" : "" ?>">
                                <a class="page-button" href="./?action=historyVal&controller=stats&page=<?php echo $compteur?>"><?php echo $compteur ?></a>
                            </li>
                            <?php 
                        }
                    } 
                    if ($compteur - 1 > $currentPage && $pages > 0) {
                        echo "<li class='page-item'>"; 
                            ?>
                            <a class="page-button" href="./?action=historyVal&controller=stats&page=<?php echo $currentPage + 1?>"><i class="fa-solid fa-chevron-right"></i></a>
                            <?php
                        echo "</li>";
                        echo "<li class='page-item'>"; 
                            ?>
                            <a class="page-button" href="./?action=historyVal&controller=stats&page=<?php echo (int) $pages ?>"><i class="fa-solid fa-angles-right"></i></a>
                            <?php
                        echo "</li>";
                    }
                echo "</ul>";
            echo "</nav>";
            ?>
        </div>
    </body>
</html>