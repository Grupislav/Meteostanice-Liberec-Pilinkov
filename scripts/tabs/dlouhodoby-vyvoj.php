<?php

// INIT
require_once dirname(__FILE__) . "/../../config.php";
require_once dirname(__FILE__) . "/../fce.php";
require_once dirname(__FILE__) . "/../variableCheck.php";

//zjistime hodnoty
//mesicne

$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT max(temperature) as maxteplotamesic, min(temperature) as minteplotamesic 
        FROM `history_cron` 
        WHERE month(date_time)=".date("m")." and year(date_time)=".date("Y")."
        GROUP BY year(date_time), month(date_time)";
$result = MySQLi_query($conn, $sql);
mysqli_close($conn);
if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}
else
{
$t = MySQLi_fetch_assoc($result);

$minteplotamesic=$t['minteplotamesic'];
$maxteplotamesic=$t['maxteplotamesic'];
}

//rocne
$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT max(temperature) as maxteplotarok, min(temperature) as minteplotarok 
        FROM `history_cron` 
        WHERE year(date_time)=".date("Y")." 
        GROUP BY year(date_time)";
$result = MySQLi_query($conn, $sql);
mysqli_close($conn);
if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}
else
{
$t = MySQLi_fetch_assoc($result);

$minteplotarok=$t['minteplotarok'];
$maxteplotarok=$t['maxteplotarok'];
}

//psani a kresleni
echo "<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'>" . mb_strtoupper($lang['graf30dniteplota'],'UTF-8') . "</span></td>
          </tr>
      </table>

<div class='graf' id='graf-teplota-30-dni'>";
require dirname(__FILE__) . '/../grafy/dlouhodoby-vyvoj/30-dni-teplota-srazky.php';
echo "</div>

          <div class='aktualneMensi" . barvaRameckuTeploty($minteplotamesic) . "'>
            <div class='aktualneOdskok'>
              {$lang['minmesic']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($minteplotamesic, $u, 1) . "</font>
            </div>
          </div>
          
          <div class='aktualneMensi vpravo" . barvaRameckuTeploty($maxteplotamesic) . "'>
            <div class='aktualneOdskok'>
              {$lang['maxmesic']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($maxteplotamesic, $u, 1) . "</font>
            </div>
          </div>

<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'><br>" . mb_strtoupper($lang['graf3rokyteplota'],'UTF-8') . "</span></td>
          </tr>
      </table>

<div class='graf' id='graf-teplota-3-roky'>";
require dirname(__FILE__) . '/../grafy/dlouhodoby-vyvoj/3-roky-teplota-srazky.php';
echo "</div>

    <div class='aktualneMensi" . barvaRameckuTeploty($minteplotarok) . "'>
            <div class='aktualneOdskok'>
              {$lang['minrok']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($minteplotarok, $u, 1) . "</font>
            </div>
          </div>
          
          <div class='aktualneMensi vpravo" . barvaRameckuTeploty($maxteplotarok) . "'>
            <div class='aktualneOdskok'>
              {$lang['maxrok']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($maxteplotarok, $u, 1) . "</font>
            </div>
          </div>
                    
<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'><br>" . mb_strtoupper($lang['graf30dniostatni'],'UTF-8') . "</span></td>
          </tr>
      </table>
          
<div class='graf' id='graf-ostatni-30-dni'>";
require dirname(__FILE__) . '/../grafy/dlouhodoby-vyvoj/30-dni-ostatni.php';
echo "</div>
          
<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'>" . mb_strtoupper($lang['graf3rokyostatni'],'UTF-8') . "</span></td>
          </tr>
      </table>

<div class='graf' id='graf-ostatni-3-roky'>";
require dirname(__FILE__) . '/../grafy/dlouhodoby-vyvoj/3-roky-ostatni.php';
echo "</div>";