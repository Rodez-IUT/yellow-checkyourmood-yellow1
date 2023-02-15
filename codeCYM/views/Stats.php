<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <link href="/CheckYourMood/codeCYM/third-party/bootstrap/css/bootstrap.css" rel="stylesheet"/>
        <link href="/CheckYourMood/codeCYM/CSS/stats.css" rel="stylesheet"/>
        <link rel="apple-touch-icon" sizes="180x180" href="/CheckYourMood/codeCYM/assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/CheckYourMood/codeCYM/assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/CheckYourMood/codeCYM/assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/CheckYourMood/codeCYM/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
        <title>Statistiques</title>
        <script src="/CheckYourMood/codeCYM/third-party/JQuery/jquery-3.6.1.js"></script>
        <script src="/CheckYourMood/codeCYM/JS/header-component.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="/JS/humeurs.js"></script>
        <script src="/CheckYourMood/codeCYM/JS/stats.js" defer></script>
    </head>
    <body>
    <?php
        spl_autoload_extensions(".php");
        spl_autoload_register();
    ?>
    <header-component></header-component>
   
    <table>
        <tr class="title">
            <td class="top-float-part">
                <form class="top-float-part" action="#" method="get">
                    <input hidden id="action" name="action" value="optionSelected">
                    <input hidden name="controller" value="stats">
                    <div class="date-selector">
                        <input Type="text" placeholder="Date de début" name="startDate" id="startDate" value="<?php 
                            if (isset($startDate)) {
                                echo $startDate;
                            }
                        ?>">
                    </div>
                    <div class="date-selector">
                        <input Type="text" placeholder="Date de fin" name="endDate" id="endDate" value="<?php 
                            if (isset($endDate)) {
                                echo $endDate;
                            }
                        ?>">
                    </div>
                    <select name="humeurs">
                        <option>TOUS</option>
                        <?php 
                            foreach ($listeHumeurs as $row) {
                                if (isset($humeurs)) {  
                                    if ($humeurs == $row) {
                                        echo "<option selected>".$row."</option>";
                                    } else {
                                        echo "<option>".$row."</option>";
                                    }
                                } else { 
                                    echo "<option>".$row."</option>";
                                }
                            }
                        ?>
                    </select>
                    <div class="date-selector">
                        <input type="submit" class="btn bouton">
                    </div>
                </form>
            </td>
            <td class="top-const-part const">
                <h1>All Time</h1>
            </td>
        </tr>
        <tr class="second-part">
            <td class="mid-float-part">
          <?php  
          if ($humeurs != "TOUS" && $Exist) { 
                    if ($endDate == "" || $startDate == "") {
                        echo "<h2>Veuillez saisir une date de début et une date de fin.</h2>";
                    } else if (!($startDate < $endDate)) {
                        echo "<h2>Veuillez saisir une date de début antèrieur à la date de fin.</h2>";
                    } else { ?>
                        <div class="chart-container" style="position: relative;">
                            <canvas id="myLineChart" class='donu-line-Chart'></canvas>
                        </div>
            <?php   } 
                } else if (!$isThere) { 
                    if ($startDate != "" && $endDate != "") {
                        echo "<h2>Aucune humeur n'a été saisie entre le $startDate et le $endDate</h2>";
                    } else {
                        echo '<h2>Veuillez sélectionner une date de début et de fin';
                    }
                } else { 
                    echo "<h3>Humeurs saisies entre le $startDate et le $endDate </h3>";
                    ?>
                    <div class="chart-container" style="position: relative;">
                        <canvas id="myChart3" class='donu-line-Chart'></canvas>
                    </div>
          <?php } ?>
                    <div>
                        <?php
                        echo "<h3>";
                            if (isset($humeurs) && $humeurs != "TOUS") {
                                echo "L'humeur $humeurs a été saisie $nombreSaisiesHumeurSelectionnee fois sur un total de $allRow saisie";
                            if ($allRow > 1 ) echo 's'; 
                            if ($allRow == 0) {
                                $allRow = 0; 
                            } else {
                                $allRow = round($nombreSaisiesHumeurSelectionnee * 100 / $allRow, 2);
                            }
                            echo " d'humeur toutes confondues ce qui représente " . $allRow . "% des humeurs saisies";
                        } else {
                            echo 'Merci de sélectionner une humeur';
                        }
                        echo "</h3>";
                        ?>
                    </div>
                <?php
                    $countRow = $valueByDate1->rowCount();
                ?>
                <script>
                    const ctx1 = document.getElementById('myLineChart');
                    new Chart(ctx1, {
                        type: 'line',
                        data: {
                            labels: <?php 
                                        $i = 0;
                                        while ($row = $valueByDate1->fetch()) {
                                            if($i == 0) {
                                                echo "[";
                                            }
                                            echo "\"$row->Date\",";
                                            if ($i == $countRow - 1) {
                                                echo "]";
                                            }
                                            $i++;
                                        }
                                    ?>,
                            datasets: [{
                                label: <?php echo "'$humeurs'" ?>,
                                data: <?php 
                                            $i = 0;
                                            while ($row2 = $valueByDate2->fetch()) {
                                                if($i == 0) {
                                                    echo "[";
                                                }
                                                echo "\"$row2->nombreHumeur\",";
                                                if ($i == $countRow - 1) {
                                                    echo "]";
                                                }
                                                $i++;
                                            }
                                        ?>,
                                borderWidth: 1,
                                borderColor: 'rgb(0, 0, 0)',
                                backgroundColor: [
                                    '#00ff7f',
                                    '#dc143c',
                                    '#00bfff',
                                    '#0000ff',
                                    '#8b008b',
                                    '#b03060',
                                    '#ff0000',
                                    '#ffd700',
                                    '#ff00ff',
                                    '#1e90ff',
                                    '#eee8aa',
                                    '#00ffff',
                                    '#b0e0e6',
                                    '#ff1493',
                                    '#ee82ee',
                                    '#ffb6c1',
                                    '#00008b',
                                    '#556b2f',
                                    '#0000ff',
                                    '#8b4513',
                                    '#483d8b',
                                    '#3cb371',
                                    '#b8860b',
                                    '#7fff00',
                                    '#8a2be2',
                                    '#ff7f50',
                                    '#008b8b',
                                    '#9acd32',
                                    '#00bfff',
                            ],
                            tension: 0.1
                            }]
                        },
                        options: {
                            indexAxis: 'x',
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0
                                    }
                                }
                            }
                        },
                    });
                </script>
            </td>
            <td class="mid-const-part const">
                <?php
                    if ($MaxHumeur == "Vous n'avez saisi aucune humeur") {
                        echo "<h1>🤔</h1>";
                        echo "<h1>$MaxHumeur</h1>";
                    } else {
                        $ligne = $MaxHumeur->fetch();
                        $stockerSmiley = $ligne->Humeur_Emoji;
                        $stocker = $ligne->compteur;
                        $stockerLib = $ligne->Humeur_Libelle;
                        echo "<div class='smiley'>$stockerSmiley</div>";
                        echo "<h1> Voici l'humeur prédominante chez vous \"<span style='color:red'>".$stockerLib."</span>\".<br> Vous l'avez utilisée <span style='color:red'>$stocker</span> fois.</h1>";
                    }
                ?>
            </td>
        </tr>
        <tr class="third-part">
            <td class="bot-float-part">
                <?php 
                    if (!isset($emojiUsed)) {
                        echo "<p>Sélectionner une date début/fin et une humeur</p>";
                    } else {
                        echo $emojiUsed;
                    }
                ?>   
            </td>
            <td class="bot-const-part const">
                <h2>Toutes les humeurs saisies :</h2>
                <div class="chart-container" style="position: relative;">
                    <canvas id="myChart4"></canvas>
                </div>
                <?php
                    $countRow = $allValue1->rowCount();
                ?>
                <script>
                    const ctx2 = document.getElementById('myChart4');

                    new Chart(ctx2, {
                        type: 'doughnut',
                        data: {
                            labels: <?php 
                                        $i = 0;
                                        while ($row = $allValue1->fetch()) {
                                            if($i == 0) {
                                                echo "[";
                                            }
                                            echo "\"$row->Humeur_Libelle\",";
                                            if ($i == $countRow - 1) {
                                                echo "]";
                                            }
                                            $i++;
                                        }
                                    ?>,
                            datasets: [{
                                data: <?php 
                                            $i = 0;
                                            while ($row = $allValue2->fetch()) {
                                                if($i == 0) {
                                                    echo "[";
                                                }
                                                echo "\"$row->compteur\",";
                                                if ($i == $countRow - 1) {
                                                    echo "]";
                                                }
                                                $i++;
                                            }
                                        ?>,
                                borderWidth: 0.75,
                                backgroundColor: [
                                    '#00ff7f',
                                    '#dc143c',
                                    '#00bfff',
                                    '#0000ff',
                                    '#8b008b',
                                    '#b03060',
                                    '#ff0000',
                                    '#ffd700',
                                    '#ff00ff',
                                    '#1e90ff',
                                    '#eee8aa',
                                    '#00ffff',
                                    '#b0e0e6',
                                    '#ff1493',
                                    '#ee82ee',
                                    '#ffb6c1',
                                    '#00008b',
                                    '#556b2f',
                                    '#0000ff',
                                    '#8b4513',
                                    '#483d8b',
                                    '#3cb371',
                                    '#b8860b',
                                    '#7fff00',
                                    '#8a2be2',
                                    '#ff7f50',
                                    '#008b8b',
                                    '#9acd32',
                                    '#00bfff',
                                ],
                            }]
                        },
                    });
                </script>
            </td>
        </tr>
        <?php
            $countRow = $AllValueBetweenTwoDate1->rowCount();
        ?>
        <script>
            const ctx3 = document.getElementById('myChart3');

            new Chart(ctx3, {
                type: 'doughnut',
                data: {
                    labels: <?php 
                                $i = 0;
                                while ($row = $AllValueBetweenTwoDate1->fetch()) {
                                    if($i == 0) {
                                        echo "[";
                                    }
                                    echo "\"$row->Humeur_Libelle\",";
                                    if ($i == $countRow - 1) {
                                        echo "]";
                                    }
                                    $i++;
                                }
                            ?>,
                    datasets: [{
                        data: <?php 
                                    $i = 0;
                                    while ($row = $AllValueBetweenTwoDate2->fetch()) {
                                        if($i == 0) {
                                            echo "[";
                                        }
                                        echo "\"$row->compteur\",";
                                        if ($i == $countRow - 1) {
                                            echo "]";
                                        }
                                        $i++;
                                    }
                                ?>,
                        borderWidth: 0.75,
                        backgroundColor: [
                            '#00ff7f',
                            '#dc143c',
                            '#00bfff',
                            '#0000ff',
                            '#8b008b',
                            '#b03060',
                            '#ff0000',
                            '#ffd700',
                            '#ff00ff',
                            '#1e90ff',
                            '#eee8aa',
                            '#00ffff',
                            '#b0e0e6',
                            '#ff1493',
                            '#ee82ee',
                            '#ffb6c1',
                            '#00008b',
                            '#556b2f',
                            '#0000ff',
                            '#8b4513',
                            '#483d8b',
                            '#3cb371',
                            '#b8860b',
                            '#7fff00',
                            '#8a2be2',
                            '#ff7f50',
                            '#008b8b',
                            '#9acd32',
                            '#00bfff',
                        ],
                    }]
                },
            });
        </script>
        <tr class="low">
            <td class="top-const-part low-part">
                <h1>All Time</h1>
            </td>
        </tr>
        <tr class="low">
            <td class="mid-const-part low-part">
                <?php
                    if ($MaxHumeur2 == "Vous n'avez saisi aucune humeur") {
                        echo "<h1>🤔</h1>";
                        echo "<h1>$MaxHumeur2</h1>";
                    } else {
                        $line = $MaxHumeur2->fetch();
                        $stockerSmiley = $line->Humeur_Emoji;
                        $stocker = $line->compteur;
                        $stockerLib = $line->Humeur_Libelle;
                        echo "<div class='smiley'>$stockerSmiley</div>";
                        echo "<h1> Voici l'humeur prédominante chez vous \"<span style='color:red'>".$stockerLib."</span>\".<br> Vous l'avez utilisée <span style='color:red'>$stocker</span> fois.</h1>";
                    }
                ?>
            </td>
        </tr>
        <tr>
            <td class="bot-const-part low-part">
                <div class="chart-container" style="position: relative;">
                    <canvas id="myChart2" class='low-part-don'></canvas>
                </div>
                <?php
                    $countRow = $allValue3->rowCount();
                ?>
                <script>
                    const ctx4 = document.getElementById('myChart2');

                    new Chart(ctx4, {
                        type: 'doughnut',
                        data: {
                            labels: <?php 
                                        $i = 0;
                                        while ($row = $allValue3->fetch()) {
                                            if($i == 0) {
                                                echo "[";
                                            }
                                            echo "\"$row->Humeur_Libelle\",";
                                            if ($i == $countRow - 1) {
                                                echo "]";
                                            }
                                            $i++;
                                        }
                                    ?>,
                            datasets: [{
                                data: <?php 
                                            $i = 0;
                                            while ($row = $allValue4->fetch()) {
                                                if($i == 0) {
                                                    echo "[";
                                                }
                                                echo "\"$row->compteur\",";
                                                if ($i == $countRow - 1) {
                                                    echo "]";
                                                }
                                                $i++;
                                            }
                                        ?>,
                                borderWidth: 0.75,
                                backgroundColor: [
                                    '#00ff7f',
                                    '#dc143c',
                                    '#00bfff',
                                    '#0000ff',
                                    '#8b008b',
                                    '#b03060',
                                    '#ff0000',
                                    '#ffd700',
                                    '#ff00ff',
                                    '#1e90ff',
                                    '#eee8aa',
                                    '#00ffff',
                                    '#b0e0e6',
                                    '#ff1493',
                                    '#ee82ee',
                                    '#ffb6c1',
                                    '#00008b',
                                    '#556b2f',
                                    '#0000ff',
                                    '#8b4513',
                                    '#483d8b',
                                    '#3cb371',
                                    '#b8860b',
                                    '#7fff00',
                                    '#8a2be2',
                                    '#ff7f50',
                                    '#008b8b',
                                    '#9acd32',
                                    '#00bfff',
                                ],
                            }]
                        },
                    });
                </script>
            </td>
        </tr>
    </table>
    </body>
</html>