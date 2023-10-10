<?php

// INIT
require_once dirname(__FILE__) . "/../../config.php";
require_once dirname(__FILE__) . "/../fce.php";
require_once dirname(__FILE__) . "/../variableCheck.php";

//nejdriv zjistime hodnoty
//nejnizsi teplota tento den
$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT temperature, YEAR( date_time ) AS rok
        FROM history_cron
        WHERE DAY( date_time ) =".date("j")."
        AND MONTH( date_time ) =".date("n")."
        ORDER BY temperature ASC
        LIMIT 1";
$result = MySQLi_query($conn, $sql);
mysqli_close($conn);
if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}
else
{
$t = MySQLi_fetch_assoc($result);
$minteplota=$t['temperature'];
$rokminteplota=$t['rok'];
}

//nejvyssi teplota tento den
$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT temperature, YEAR( date_time ) AS rok
        FROM history_cron
        WHERE DAY( date_time ) =".date("j")."
        AND MONTH( date_time ) =".date("n")."
        ORDER BY temperature DESC
        LIMIT 1";
$result = MySQLi_query($conn, $sql);
mysqli_close($conn);
if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}
else
{
$t = MySQLi_fetch_assoc($result);
$maxteplota=$t['temperature'];
$rokmaxteplota=$t['rok'];
}

//nejvyssi srazky tento den
$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT SUM( precipitation ) AS srazky, YEAR( date_time ) AS rok
        FROM history_cron
        WHERE DAY( date_time ) =".date("j")."
        AND MONTH( date_time ) =".date("n")."
        GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
        ORDER BY srazky DESC
        LIMIT 1";
$result = MySQLi_query($conn, $sql);
mysqli_close($conn);
if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}
else
{
$t = MySQLi_fetch_assoc($result);
$maxsrazky=$t['srazky'];
$rokmaxsrazky=$t['rok'];
} 

echo "<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'>" . mb_strtoupper($lang['rekordydatum'],'UTF-8') . "</span></td>
          </tr>
      </table>
      
      <div class='container'>
      <div class='row' style='width: 98%;'>     
      <div class='col-md-4 trisloupce'>
          <div class='aktualnetretinka" . barvaRameckuTeploty($maxteplota) . "'>
            <div class='aktualneOdskok'>
              {$lang['nejvyssiteplota']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($maxteplota, $u, 1) . " ({$rokmaxteplota})</font>
            </div>
          </div>
      </div>
      <div class='col-md-4 trisloupce'>    
          <div class='aktualnetretinka" . barvaRameckuTeploty($minteplota) . "'>
            <div class='aktualneOdskok'>
              {$lang['nejnizsiteplota']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($minteplota, $u, 1) . " ({$rokminteplota})</font>
            </div>
          </div>
      </div>
      <div class='col-md-4 trisloupce'>   
          <div class='aktualnetretinka" . barvaRameckuSrazky($maxsrazky) . "'>
            <div class='aktualneOdskok'>
              {$lang['nejvyssiuhrn']}<br>
              <span class='aktuamens'>{$maxsrazky} mm ({$rokmaxsrazky})</span>
            </div>
          </div>
      </div>
      </div>
      </div>

      <table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'><br>" . mb_strtoupper($lang['rekordydlednu'],'UTF-8') . "</span></td>
          </tr>
      </table>";  
      
      /////////////////////////////////
        // SLOUPEK 1                
        ////////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['nejteplejsidny']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['den']}</td>
              <td class='radek'>{$lang['teplota']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, MAX( temperature ) AS maxteploty
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
                                                ORDER BY maxteploty DESC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}

        while($t = MySQLi_fetch_assoc($result))
        {
            echo "<tr>
                  <td>" . formatDnu($t['date_time']) . "</td>
                  <td>" . jednotkaTeploty($t['maxteploty'], $u, 1) . "</td>
                </tr>";
        }

        echo "</table>";

        /////////////////////////////////
        // SLOUPEK 2
        ////////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['nejchladnejsidny']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['den']}</td>
              <td class='radek'>{$lang['teplota']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, MIN( temperature ) AS minteploty
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
                                                ORDER BY minteploty ASC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {

            echo "<tr>
                <td>" . formatDnu($t['date_time']) . "</td>
                <td>" . jednotkaTeploty($t['minteploty'], $u, 1) . "</td>
              </tr>";

        }

        echo "</table>";

        /////////////////////////////////
        // SLOUPEK 3
        ////////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['nejnizsimaxima']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['den']}</td>
              <td class='radek'>{$lang['teplota']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, MAX( temperature ) AS maxteploty
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
                                                ORDER BY maxteploty ASC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}

        while($t = MySQLi_fetch_assoc($result))
        {
            echo "<tr>
                  <td>" . formatDnu($t['date_time']) . "</td>
                  <td>" . jednotkaTeploty($t['maxteploty'], $u, 1) . "</td>
                </tr>";
        }

        echo "</table>";

        /////////////////////////////////
        // SLOUPEK 4
        ////////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['nejvyssiminima']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['den']}</td>
              <td class='radek'>{$lang['teplota']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, MIN( temperature ) AS minteploty
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
                                                ORDER BY minteploty DESC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {

            echo "<tr>
                <td>" . formatDnu($t['date_time']) . "</td>
                <td>" . jednotkaTeploty($t['minteploty'], $u, 1) . "</td>
              </tr>";

        }

        echo "</table>";
        
        /////////////////////////////////
        // SLOUPEK 5
        ///////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['pocnejteplejsidny']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['den']}</td>
              <td class='radek'>{$lang['teplota']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, MAX( temperature_apparent ) AS maxpocteploty
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
                                                ORDER BY maxpocteploty DESC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}

        while($t = MySQLi_fetch_assoc($result))
        {
            echo "<tr>
                  <td>" . formatDnu($t['date_time']) . "</td>
                  <td>" . jednotkaTeploty($t['maxpocteploty'], $u, 1) . "</td>
                </tr>";
        }

        echo "</table>";

        /////////////////////////////////
        // SLOUPEK 6
        ////////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['pocnejchladnejsidny']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['den']}</td>
              <td class='radek'>{$lang['teplota']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, MIN( temperature_apparent ) AS minpocteploty
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
                                                ORDER BY minpocteploty ASC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {

            echo "<tr>
                <td>" . formatDnu($t['date_time']) . "</td>
                <td>" . jednotkaTeploty($t['minpocteploty'], $u, 1) . "</td>
              </tr>";

        }

        echo "</table>";
        
         /////////////////////////////////
        // SLOUPEK 7
        ////////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['nejdestivejsidny']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['den']}</td>
              <td class='radek'>{$lang['srazky']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, SUM( precipitation ) AS maxsrazky
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
                                                ORDER BY maxsrazky DESC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}

        while($t = MySQLi_fetch_assoc($result))
        {
            echo "<tr>
                  <td>" . formatDnu($t['date_time']) . "</td>
                  <td>" . $t['maxsrazky'] . " mm</td>
                </tr>";
        }

        echo "</table>";
        
        /////////////////////////////////
        // SLOUPEK 8
        ////////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['nejvetrnejsidny']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['den']}</td>
              <td class='radek'>{$lang['prumvitr']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, AVG( wind_speed ) AS maxvitr
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
                                                ORDER BY maxvitr DESC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}

        while($t = MySQLi_fetch_assoc($result))
        {
            echo "<tr>
                  <td>" . formatDnu($t['date_time']) . "</td>
                  <td>" . round($t['maxvitr'],1) . " m/s</td>
                </tr>";
        }

        echo "</table>";
                        
echo "<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'><br>" . mb_strtoupper($lang['rekordydlemesicu'],'UTF-8') . "</span></td>
          </tr>
      </table>";

        /////////////////////////////////
        // SLOUPEK 1                
        ////////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['nejteplejsimesice']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['mesic']}</td>
              <td class='radek'>{$lang['prumteplota']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, AVG( temperature ) AS prumteploty
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time )
                                                ORDER BY prumteploty DESC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}

        while($t = MySQLi_fetch_assoc($result))
        {
            echo "<tr>
                  <td>" . substr($t['date_time'],0,7) . "</td>
                  <td>" . jednotkaTeploty(round($t['prumteploty'], 1), $u, 1) . "</td>
                </tr>";
        }

        echo "</table>";

        /////////////////////////////////
        // SLOUPEK 2
        ////////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['nejchladnejsimesice']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['mesic']}</td>
              <td class='radek'>{$lang['prumteplota']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, AVG( temperature ) AS prumteploty
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time )
                                                ORDER BY prumteploty ASC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {

            echo "<tr>
                <td>" . substr($t['date_time'],0,7) . "</td>
                <td>" . jednotkaTeploty(round($t['prumteploty'], 1), $u, 1) . "</td>
              </tr>";

        }

        echo "</table>";

        /////////////////////////////////
        // SLOUPEK 3
        ////////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['nejdestivejsimesice']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['mesic']}</td>
              <td class='radek'>{$lang['srazky']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, SUM( precipitation ) AS srazky
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time )
                                                ORDER BY srazky DESC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}

        while($t = MySQLi_fetch_assoc($result))
        {
            echo "<tr>
                  <td>" . substr($t['date_time'],0,7) . "</td>
                  <td>" . $t['srazky'] . " mm</td>
                </tr>";
        }

        echo "</table>";

        /////////////////////////////////
        // SLOUPEK 4
        ////////////////////////////////

        echo "<table class='rekordyctvrtina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang['nejsussimesice']}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['den']}</td>
              <td class='radek'>{$lang['srazky']}</td>
            </tr>";

        // nacteme
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT date_time, SUM( precipitation ) AS srazky
                                                FROM history_cron
                                                GROUP BY YEAR( date_time ) , MONTH( date_time )
                                                ORDER BY srazky ASC
                                                LIMIT 10";
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {

            echo "<tr>
                <td>" . substr($t['date_time'],0,7) . "</td>
                <td>" . $t['srazky'] . " mm</td>
              </tr>";

        }

        echo "</table>";
        
        /////////////////////////////////
        // Mìsíce
        ////////////////////////////////
        $i=1;
        
        while($i<=12)
        {
        $aktmesic="mesic".$i;
        
        echo "<table class='rekordytretina'>
            <tr class='zelenyRadek'>
              <td colspan='2' class='radek'>{$lang[$aktmesic]}</td>
            </tr>
            <tr class='modryRadek'>
              <td class='radek'>{$lang['velicina']}</td>
              <td class='radek'>{$lang['datum']}</td>
            </tr>";
            
        // nejvyšší teplota            

        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT MAX( temperature ) AS maxteplota, date_time
                FROM history_cron
                WHERE MONTH( date_time ) =".$i."
                GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
                ORDER BY maxteplota DESC
                LIMIT 1";
                
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {
        echo "<tr>
        <td>{$lang['maxteplota']}</td>
        <td>" . jednotkaTeploty($t['maxteplota'], $u, 1) . " (".formatDnu($t['date_time']).")</td>
        </tr>";
        }

        // nejnižší teplota
                    
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT MIN( temperature ) AS minteplota, date_time
                FROM history_cron
                WHERE MONTH( date_time ) =".$i."
                GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
                ORDER BY minteplota ASC
                LIMIT 1";
                
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {
        echo "<tr>
        <td>{$lang['minteplota']}</td>
        <td>" . jednotkaTeploty($t['minteplota'], $u, 1) . " (".formatDnu($t['date_time']).")</td>
        </tr>";
        }

        // nevyššší denní srážky
                    
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT SUM( precipitation ) AS maxdennisrazky, date_time
                FROM history_cron
                WHERE MONTH( date_time ) =".$i."
                GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )
                ORDER BY maxdennisrazky DESC
                LIMIT 1";
                
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {
        echo "<tr>
        <td>{$lang['nejvyssidennisrazky']}</td>
        <td>" . $t['maxdennisrazky'] . " mm (".formatDnu($t['date_time']).")</td>
        </tr>";
        }
        
        // nevyšší prùm. teplota
                    
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT YEAR( date_time ) AS rok, AVG( temperature ) AS maxprumteplota
                FROM history_cron
                WHERE MONTH( date_time ) =".$i."
                GROUP BY YEAR( date_time ) , MONTH( date_time )
                ORDER BY maxprumteplota DESC
                LIMIT 1";
                
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {
        echo "<tr>
        <td>{$lang['nejvyssiprumteplota']}</td>
        <td>" . jednotkaTeploty(round($t['maxprumteplota'], 1), $u, 1) . " (".$t['rok'].")</td>
        </tr>";
        }
        
        // nejnižší prùm. teplota
                    
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT YEAR( date_time ) AS rok, AVG( temperature ) AS minprumteplota
                FROM history_cron
                WHERE MONTH( date_time ) =".$i."
                GROUP BY YEAR( date_time ) , MONTH( date_time )
                ORDER BY minprumteplota ASC
                LIMIT 1";
                
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {
        echo "<tr>
        <td>{$lang['nejnizsiprumteplota']}</td>
        <td>" . jednotkaTeploty(round($t['minprumteplota'], 1), $u, 1) . " (".$t['rok'].")</td>
        </tr>";
        }
        
        // nejvyšší srážky mìsíènì
                    
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT YEAR( date_time ) AS rok, SUM( precipitation ) AS messrazky
                FROM history_cron
                WHERE MONTH( date_time ) =".$i."
                GROUP BY YEAR( date_time ) , MONTH( date_time )
                ORDER BY messrazky DESC
                LIMIT 1";
                
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {
        echo "<tr>
        <td>{$lang['nejvyssimesicnisrazky']}</td>
        <td>" . $t['messrazky'] . " mm (".$t['rok'].")</td>
        </tr>";
        } 
        
        // nejnižší srážky mìsíènì
                    
        $conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
        if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
        $sql = "SELECT YEAR( date_time ) AS rok, SUM( precipitation ) AS messrazky
                FROM history_cron
                WHERE MONTH( date_time ) =".$i."
                GROUP BY YEAR( date_time ) , MONTH( date_time )
                ORDER BY messrazky ASC
                LIMIT 1";
                
        $result = MySQLi_query($conn, $sql);
        mysqli_close($conn);
        if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}        

        while($t = MySQLi_fetch_assoc($result))
        {
        echo "<tr>
        <td>{$lang['nejnizsimesicnisrazky']}</td>
        <td>" . $t['messrazky'] . " mm (".$t['rok'].")</td>
        </tr>";
        }                                 
        
        echo "</table>";
        $i++;
        }      
/*echo "<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'><br>" . mb_strtoupper($lang['absolutnirekordy'],'UTF-8') . "</span></td>
          </tr>
      </table>";*/
