<?php

// formular pro den
echo "<form method='GET' action='https://tomaskrupicka.cz/meteostanice-liberec-pilinkov/#historie'>
          <fieldset>
          <legend>{$lang['zobrazitden']}</legend>
          <input type='hidden' name='ja' value='{$_GET['ja']}'>
          <input type='hidden' name='je' value='{$_GET['je']}'>
          <input type='hidden' name='typ' value='0'>
          <p>
            <label for='den'>{$lang['den']}:</label> <input type='text' name='den' id='den' value='{$_GET['den']}'>
            <input type='submit' class='submit' name='odeslani' value='{$lang['zobrazit']}'>
          </p>
          </fieldset>
      </form>";

// odesilame a chceme zobrazit den
if(isset($_GET['odeslani']) && $_GET['typ'] == 0)
{
        echo "<table class='tabulkaVHlavicce'>
              <tr>
                <td class='radekDnes'><span class='font25 zelena'>" . formatDnu($_GET['den']) . "</span></td>
              </tr>
              </table>";

        // Grafy
        echo "<div class='graf' id='graf-historie'>";
        require './scripts/grafy/historie.php';
        echo "</div>";

$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT max(temperature) as maxteplota, min(temperature) as minteplota 
                                    FROM history_cron 
                                    WHERE date_time LIKE '{$_GET['den']}%'";                                    
$result = MySQLi_query($conn, $sql);
mysqli_close($conn);
if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}
else
{
$t = MySQLi_fetch_assoc($result);
echo    "<div class='aktualneMensi" . barvaRameckuTeploty($t['minteplota']) . "'>
            <div class='aktualneOdskok'>
              {$lang['nejnizsiteplota']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($t['minteplota'], $u, 1) . "</font>
            </div>
          </div>
          
          <div class='aktualneMensi vpravo" . barvaRameckuTeploty($t['maxteplota']) . "'>
            <div class='aktualneOdskok'>
              {$lang['nejvyssiteplota']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($t['maxteplota'], $u, 1) . "</font>
            </div>
          </div>";
}
}