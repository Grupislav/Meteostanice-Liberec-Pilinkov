<?php

//////////////////////////////////////////////////////////////////////////
//// VLOZENI SOUBORU
//////////////////////////////////////////////////////////////////////////

//require_once dirname(__FILE__) . "/../../config.php"; // skript s nastavenim
//require_once dirname(__FILE__) . "/../db.php";        // skript s databazi
require_once dirname(__FILE__)."/fce.php";       // skript s nekolika funkcemi

// Ziskani dat s aktualnimi hodnotami z moje-meteo a z IP Geolocation
$xmlString = curl_get_file_contents("http://api.meteo-pocasi.cz/api.xml?action=get-meteo-data&client=xml&id=00004c8SfUq5hdYFumackwf6NBJ5JC0iPfTG0QifuZlcCJs75Sj");
$xml = simplexml_load_string($xmlString);

$ipgeolocationurl = "https://api.ipgeolocation.io/astronomy?apiKey=35fa55a9bef84a859ba97ed0f34b0f2f&lat=50.7290444&long=15.0367186";
$location = get_geolocation($ipgeolocationurl);
$json = json_decode($location, true);

// Osetreni vstupu
require_once dirname(__FILE__) . "/variableCheck.php";

// Stranku vkladame a nevolame AJAXem? Neposleme hlavicku, kdyz existuje v index.php deklarovana hodnota.
/*if(!isset($dopocitat))
{
    header('Content-type: text/html; charset=UTF-8');
} */

 
echo      "<table class='tabulkaVHlavicce'>
            <tr class='radek zelenyRadek'>
              <td colspan='2'>{$lang['info']}</td>
            </tr>
            <tr>
              <td align='right'>{$lang['umisteni']}:</td>
              <td>{$lang['pilinkov']}</td>
            </tr>
            <tr>
              <td align='right'>{$lang['nadmvyska']}:</td>
              <td>430 m n.m.</td>
            </tr>
            <tr>
              <td align='right'>{$lang['merenood']}:</td>
              <td>1. 5. 2019</td>
            </tr>
            <tr class='radek zelenyRadekStredovy'>
              <td colspan='2'>{$lang['astronomie']}</td>
            </tr>
            <tr>
              <td align='right'>{$lang['vychodslunce']}:</td>
              <td>".$xml->variable->sunrise."</td>
            </tr>
            <tr>
              <td align='right'>{$lang['zapadslunce']}:</td>
              <td>".$xml->variable->sunset."</td>
            </tr>
            <tr>
              <td align='right'>{$lang['delkadne']}:</td>
              <td>".date_format(date_create($json['day_length']),"H:i")."</td>
            </tr>            
            <tr>
              <td align='right'>{$lang['slunecnipoledne']}:</td>
              <td>".$json['solar_noon']."</td>
            </tr>            
            <tr>
              <td align='right'>{$lang['vzdalenostslunce']}:</td>
              <td><div class='tooltip'>".number_format(round($json['sun_distance']/1000),0,"."," ")." tis. km<span class='tooltiptext'>{$lang['strednivzdalenostslunce']}</span></div></td>
            </tr>            
            <tr>
              <td align='right'>{$lang['fazemesice']}:</td>
              <td>".$lang[FazeMesice($xml->variable->moonphase)]."</td>
            </tr>
            <tr>
              <td align='right'>{$lang['vychodmesice']}:</td>
              <td>".$json['moonrise']."</td>
            </tr>
            <tr>
              <td align='right'>{$lang['zapadmesice']}:</td>
              <td>".$json['moonset']."</td>
            </tr>
            <tr>
              <td align='right'>{$lang['vzdalenostmesice']}:</td>
              <td><div class='tooltip'>".number_format(round($json['moon_distance']/1000),0,"."," ")." tis. km<span class='tooltiptext'>{$lang['strednivzdalenostmesice']}</span></div></td>
            </tr>                                                                                    
          </table>";
          
// posledni dny do pole
/*$dny2 = [];
for($a = 1; $a < 6; $a++)
{
    $dny2[] = date("Y-m-d H:i", mktime(date("H"), date("i"), date("s"), date("m"), date("d") - $a, date("Y")));
}

// projdeme pole, pro kazdy den a podobnou dobu nalezneme teplotu a vypiseme
for($a = 0; $a < count($dny2); $a++)
{
    $dotaz = MySQLi_query($GLOBALS["DBC"], "SELECT kdy, teplota, vlhkost
                            FROM tme 
                            WHERE kdy >= CAST('" . substr($dny2[$a], 0, 15) . "0' AS datetime)
                                  AND kdy <= CAST('" . substr($dny2[$a], 0, 15) . "9' AS datetime)
                            LIMIT 1");
    $hod = MySQLi_fetch_assoc($dotaz);

    echo "<tr>
              <td>" . formatDnu($dny2[$a]) . "</td>
              <td><abbr title='" . substr($hod['kdy'], 11, 5) . "'>" . jednotkaTeploty($hod['teplota'], $u, 1) . "</abbr></td>";
    if($vlhkomer == 1)
    {
        echo "<td>" . ($hod['vlhkost'] != 0 ? "{$hod['vlhkost']}%" : "") . "</td>";
    }
    echo "</tr>";
}*/
