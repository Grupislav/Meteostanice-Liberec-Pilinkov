<?php

//přeložení fáze měsíce
function FazeMesice($cislo)
{
switch($cislo){
case 1: return "nov"; break;
case 2: return "dorusta"; break;
case 3: return "prvnictvrt"; break;
case 4: return "dorustamesic"; break;
case 5: return "uplnek"; break;
case 6: return "couva"; break;
case 7: return "poslednictvrt"; break;
case 8: return "ubyva"; break;
default: return "chyba";
}
}

//přeložení směru větru
function SmerVetru($cislo)
{
switch($cislo){
case 0: return "S &#8595"; break;
case 45: return "SV &#8601"; break;
case 90: return "V &#8592"; break;
case 135: return "JV &#8598"; break;
case 180: return "J &#8593"; break;
case 225: return "JZ &#8599"; break;
case 270: return "Z &#8594"; break;
case 315: return "SZ &#8600"; break;
default: return "chyba";
}
}

//přeložení typu počasí
function Pocasi($cislo)
{
switch($cislo){
case 1: return "jasno"; break;
case 2: return "skorojasno"; break;
case 3: return "polojasno"; break;
case 4: return "zatazeno"; break;
case 5: return "prehanky"; break;
case 6: return "dest"; break;
default: return "chyba";
}
}

/**
 * formatData() vrací datum a čas
 * @param $datum
 * @return string
 */

function formatData($datum)
{

    if(substr($datum, 8, 1) == 0)
    {
        $den = substr($datum, 9, 1);
    }
    else
    {
        $den = substr($datum, 8, 2);
    }
    if(substr($datum, 5, 1) == 0)
    {
        $mesic = substr($datum, 6, 1);
    }
    else
    {
        $mesic = substr($datum, 5, 2);
    }

    return $den . "." . $mesic . "." . substr($datum, 0, 4) . " " . substr($datum, 11, 2) . ":" . substr($datum, 14, 2);
}

/**
 * formatDnu() vrací datum
 * @param $datum
 * @return string
 */

function formatDnu($datum)
{
    if(substr($datum, 8, 1) == 0)
    {
        $den = substr($datum, 9, 1);
    }
    else
    {
        $den = substr($datum, 8, 2);
    }
    if(substr($datum, 5, 1) == 0)
    {
        $mesic = substr($datum, 6, 1);
    }
    else
    {
        $mesic = substr($datum, 5, 2);
    }

    return $den . ". " . $mesic . ". " . substr($datum, 0, 4);
}

/**
 * fahrenheit();
 * @param $teplota
 * @return float
 */
function fahrenheit($teplota)
{
    return round((1.8 * $teplota) + 32, 1);
}

/**
 * kelvin();
 * @param $teplota
 * @return float
 */
function kelvin($teplota)
{
    return round($teplota + 273.15, 1);
}

/**
 * rankine();
 * @param $teplota
 * @return float
 */
function rankine($teplota)
{
    return round(($teplota + 273.15) * (9 / 5), 1);
}

/**
 * delisle();
 * @param $teplota
 * @return float
 */
function delisle($teplota)
{
    return round((100 - $teplota) * (3 / 2), 1);
}

/**
 * newton();
 * @param $teplota
 * @return float
 */
function newton($teplota)
{
    return round($teplota * (33 / 100), 1);
}

/**
 * reaumur();
 * @param $teplota
 * @return float
 */
function reaumur($teplota)
{
    return round($teplota * (4 / 5), 1);
}

/**
 * romer();
 * @param $teplota
 * @return float
 */
function romer($teplota)
{
    return round($teplota * (21 / 40) + 7.5, 1);
}

/**
 * kolik();
 * @param string $co
 * @param string $kde
 * @param string $podminky
 * @return int

function kolik($co, $kde, $podminky = "")
{ 
    $conn = $GLOBALS["DBC"];
    $sql = "SELECT COUNT($co) AS pocet FROM $kde $podminky";
    $k = MySQLi_query($conn, $sql);
    $k = MySQLi_fetch_assoc($k);

    return $k['pocet'];
}

/**
 * jednotkaTeploty();
 * @param int|string $teplota
 * @param string $jednotka
 * @param int $znak
 * @return string
 */

function jednotkaTeploty($teplota = "", $jednotka = "C", $znak = 0)
{
    // Cerpano z: http://en.wikipedia.org/wiki/Temperature_conversion_formulas

    // namerena teplota... nic se nedeje
    if($teplota == "" && $teplota != 0)
    {
        $teplota = "-";
    }
    if($jednotka == "C" AND $znak == 0)
    {
        return $teplota;
    }
    elseif($jednotka == "C" AND $znak == 1)
    {
        return $teplota . " &deg;C";
    }
    elseif($jednotka == "F" AND $znak == 0)
    {
        return fahrenheit($teplota);
    }
    elseif($jednotka == "F" AND $znak == 1)
    {
        return fahrenheit($teplota) . " &deg;F";
    }
    elseif($jednotka == "K" AND $znak == 0)
    {
        return kelvin($teplota);
    }
    elseif($jednotka == "K" AND $znak == 1)
    {
        return kelvin($teplota) . " &deg;K";
    }
    elseif($jednotka == "R" AND $znak == 0)
    {
        return rankine($teplota);
    }
    elseif($jednotka == "R" AND $znak == 1)
    {
        return rankine($teplota) . " &deg;R";
    }
    elseif($jednotka == "D" AND $znak == 0)
    {
        return delisle($teplota);
    }
    elseif($jednotka == "D" AND $znak == 1)
    {
        return delisle($teplota) . " &deg;De";
    }
    elseif($jednotka == "N" AND $znak == 0)
    {
        return newton($teplota);
    }
    elseif($jednotka == "N" AND $znak == 1)
    {
        return newton($teplota) . " &deg;N";
    }
    elseif($jednotka == "Re" AND $znak == 0)
    {
        return reaumur($teplota);
    }
    elseif($jednotka == "Re" AND $znak == 1)
    {
        return reaumur($teplota) . " &deg;Ré";
    }
    elseif($jednotka == "Ro" AND $znak == 0)
    {
        return romer($teplota);
    }
    elseif($jednotka == "Ro" AND $znak == 1)
    {
        return romer($teplota) . " &deg;Ro";
    }
    else
    {
        return;
    }
}

/**
 * jeVikend() - podle date urci typ dne
 * @param date $datum
 * @return int
 */

function jeVikend($datum)
{
    $denVTydnu = date("N", mktime(0, 0, 0, substr($datum, 5, 2), substr($datum, 8, 2), substr($datum, 0, 4)));
    if($denVTydnu == 6 OR $denVTydnu == 7)
    {
        return 1;
    }
    else
    {
        return 0;
    }
}

/**
 * rosnyBod();
 * @param float $teplota
 * @param float $vlhkost
 * @return float
 */

/*function rosnyBod($teplota, $vlhkost)
{
    // Temperature    Range      Tn (°C)         m
    // Above water    0 – 50°C    243.12     17.62
    // Above ice     -40 – 0°C    272.62     22.46

    if(is_numeric($teplota) AND is_numeric($vlhkost) AND $teplota != 0 AND $vlhkost != 0)
    {

        if($teplota > 0)
        {
            return round(243.12 * ((log($vlhkost / 100) + ((17.62 * $teplota) / (243.12 + $teplota))) / (17.62 - log($vlhkost / 100) - ((17.62 * $teplota) / (243.12 + $teplota)))), 1);
        }
        else
        {
            return round(272.62 * ((log($vlhkost / 100) + ((22.46 * $teplota) / (272.62 + $teplota))) / (22.46 - log($vlhkost / 100) - ((22.46 * $teplota) / (272.62 + $teplota)))), 1);
        }

    }
    else
    {
        return "null";
    }
}*/

/**
 * Funkce vrátí datetime z MySQL naformátované do tvaru,
 * který je v vystup-XML.php
 *
 * @param datetime $datetime
 * @return string
 */

function datetimeToPapouch($datetime)
{
    return substr($datetime, 5, 2) . "/" . substr($datetime, 8, 2) . "/" . substr($datetime, 0, 4) . " " . substr($datetime, 11, 2) . ":" . substr($datetime, 14, 2) . ":" . substr($datetime, 17, 2);
}

/**
 * Vrátí CSS třídu pro obarvení rámečku podle hodnoty.
 *
 * @param $teplota
 * @return string
 */

function barvaRameckuTeploty($teplota)
{
    $trida = " teplota-30";

    $skoky = [-30, -25, -20, -15, -10, -5, 0, 5, 10, 15, 20, 25, 30, 35];

    foreach($skoky as $skok)
    {
        if($teplota >= $skok)
        {
            $trida = " teplota" . (string)$skok;
        }
    }

    return $trida;
}

function barvaRameckuOsvit($osvit)
{
    $trida = " osvitneni";

    $skoky = [0, 100, 250, 500];

    foreach($skoky as $skok)
    {
        if($osvit > $skok)
        {
            $trida = " osvit" . (string)$skok;
        }
    }

    return $trida;
}

function barvaRameckuVlhkost($vlhkost)
{
    $trida = " vlhkost0";

    $skoky = [20, 30, 40, 50, 60, 70, 80, 90];

    foreach($skoky as $skok)
    {
        if($vlhkost > $skok)
        {
            $trida = " vlhkost" . (string)$skok;
        }
    }

    return $trida;
}

function barvaRameckuSrazky($srazky)
{
    $trida = " srazkynejsou";

    $skoky = [0, 3, 6, 10, 15, 20, 25, 30, 35, 40, 45];

    foreach($skoky as $skok)
    {
        if($srazky > $skok)
        {
            $trida = " srazky" . (string)$skok;
        }
    }

    return $trida;
}

function barvaRameckuTlak($tlak)
{
    $trida = " tlak-990";

    $skoky = [990, 1000, 1010, 1020, 1030];

    foreach($skoky as $skok)
    {
        if($tlak > $skok)
        {
            $trida = " tlak" . (string)$skok;
        }
    }

    return $trida;
}

function barvaRameckuVitr($vitr)
{
    $trida = " vitr0";

    $skoky = [2, 4, 8, 12, 16, 22];

    foreach($skoky as $skok)
    {
        if($vitr > $skok)
        {
            $trida = " vitr" . (string)$skok;
        }
    }

    return $trida;
}

function barvaRameckuAktualizovano($akt)
{
if (time()-strtotime($akt) < 3600) return("aktualneAktualizovano");
else return("aktualneNeaktualizovano");
}

function textAktualizovano($akt)
{
if (time()-strtotime($akt) < 3600) return("online");
else return("offline");
}

/**
 * @param $jazyky
 * @param $vybranyJazyk
 * @return string
 */

function menuJazyky($jazyky, $vybranyJazyk)
{
    $menu = "<li><a href='#'>" . strtoupper($vybranyJazyk) . "</a>";
    $menu .= "<ul class='jazyk'>";

    foreach($jazyky as $jazyk)
    {

        if($jazyk != $vybranyJazyk)
        {
            $menu .= "<li><a href='https://tomaskrupicka.cz/meteostanice-liberec-pilinkov/?ja={$jazyk}&amp;je={$_GET['je']}'>" . strtoupper($jazyk) . "</a></li>";
        }

    }

    $menu .= "</ul></li>";

    return $menu;
}

/**
 * @param $jednotky
 * @param $vybranaJednotka
 * @return string
 */

function menuJednotky($jednotky, $vybranaJednotka)
{
    $menu = "<li><a href='#' title='{$jednotky[$vybranaJednotka]}'>{$jednotky[$vybranaJednotka]}</a>";
    $menu .= "<ul class='teplota'>";

    foreach($jednotky as $index => $jednotka)
    {

        if($index != $vybranaJednotka)
        {
            $menu .= "<li><a href='https://tomaskrupicka.cz/meteostanice-liberec-pilinkov/?je={$index}&amp;ja={$_GET['ja']}' title='{$jednotka}'>{$jednotka}</a></li>";
        }

    }

    $menu .= "</ul></li>";

    return $menu;
}

function curl_get_file_contents($URL)
{
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents) return $contents;
            else return FALSE;
 }
 
function get_geolocation($url) 
{
        $cURL = curl_init();

        curl_setopt($cURL, CURLOPT_URL, $url);
        curl_setopt($cURL, CURLOPT_HTTPGET, true);
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));
        return curl_exec($cURL);
}