<?php

/*************************************************************************
 ***  Systém pro TME/TH2E - TMEP                                        ***
 ***  (c) Michal Ševčík 2007-2017 - multi@tricker.cz                    ***
 *************************************************************************/

/*
 * VLOZENI SOUBORU
 */

require "./config.php";         // skript s nastavenim
//require "./scripts/db.php";        // skript s databazi
require "./scripts/fce.php";       // skript s nekolika funkcemi

{

//JAZYK A JEDNOTKA
require_once "scripts/variableCheck.php";


    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-70474721-2"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-70474721-2');
        </script>

        <title><?php echo $lang['titulekstranky']; ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/css.css" type="text/css">
        <meta NAME="description" CONTENT="<?php echo $lang['popisstranky']; ?>">
        <?php if($obnoveniStranky != 0 and is_numeric($obnoveniStranky)) {
            echo '    <meta http-equiv="refresh" content="' . $obnoveniStranky . '">';
        } ?>
        <meta NAME="author" CONTENT="Tomáš Krupička (https://tomaskrupicka.cz), Michal Ševčík (http://multi.tricker.cz)">
        <meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
        <script src="scripts/js/jquery.tools.ui.timer.colorbox.tmep.highcharts.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                // po urcitem case AJAXove nacteni hodnot
                $.timer(60000, function () {
                    $.get('scripts/ajax/aktualne.php<?php echo "?ja={$l}&je={$u}"; ?>', function (data) {
                        $('.ajaxrefresh').html(data);
                    });
                });

                // jQuery UI - datepicker
                $("#den").datepicker($.datepicker.regional["<?php echo $l;  ?>"]);
                $.datepicker.setDefaults({dateFormat: "yy-mm-dd", maxDate: -1, minDate: new Date(2019,05,1), changeMonth: true, changeYear: true});
            });
            var loadingImage = '<p><img src="./images/loading.gif"></p>';

            function loadTab(tab) {
                if ($("#" + tab).html() == "") {
                    $("#" + tab).html(loadingImage);
                    $.get("scripts/tabs/" + tab + ".php<?php echo "?ja={$l}&je={$u}"; ?>", function (data) {
                        $("#" + tab).html(data);
                    });
                }
            }
        </script>

    </head>

    <body>

    <?php

    echo "<div class='roztahovak-modry'>
        <div class='hlavicka container'>
        <div id='nadpis'><h1>{$lang['hlavninadpis']}</h1></div>";

echo "<div id='menu'>
      <nav>
        <ul>
          " . menuJazyky($jazyky, $l) . "
          " . menuJednotky($jednotky, $u) . "
        </ul>
      </nav>
    </div>";

    echo "</div>
      </div>";

    // Tři sloupce
    require_once "./scripts/head.php";

    ?>

    <div id='hlavni' class="container">

        <?php

        // Záložky
        echo "<div id=\"oblastzalozek\">
  <ul class=\"tabs\">
    <li><a href=\"#aktualne\">{$lang['aktualne']}</a></li>
    <li><a href=\"#dlouhodoby\" onclick=\"loadTab('dlouhodoby-vyvoj');\">{$lang['dlouhodobyvyvoj']}</a></li>
    <li><a href=\"#statistiky\" onclick=\"loadTab('statistiky');\">{$lang['statistikytab']}</a></li>
    <li><a href=\"#rekordy\" onclick=\"loadTab('rekordy');\">{$lang['rekordytab']}</a></li>
    <li><a href=\"#historie\">{$lang['historie']}</a></li>
  </ul>

  <div class=\"panely\">";
        echo "<div id=\"aktualneTab\">";
        require "scripts/tabs/aktualne.php";
        echo "</div>";
        echo "<div id=\"dlouhodoby-vyvoj\"></div>";
        echo "<div id=\"statistiky\"></div>";
        echo "<div id=\"rekordy\"></div>";
        echo "<div id=\"historieTab\">";
        require "scripts/tabs/historie.php";
        echo "</div>";
        echo "</div>
  </div>


  </div>";

        // Patička
        echo "<div class='roztahovak-modry'>
          <div class='paticka container'><p>{$lang['paticka']}</p></div>
        </div>";

        ?>

    </body>
    </html>
    <?php
} // konec pokud si stranku prohlizi uzivatel a nevola ji teplomer