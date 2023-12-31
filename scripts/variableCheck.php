<?php

// Prvotni INIT
if(!isset($_GET['je']))
{
    $_GET['je'] = $l;
}
if(!isset($_GET['ja']))
{
    $_GET['ja'] = $u;
}

$jazyky = [
    "cz" => "cz"/*,
    "sk" => "sk",
    "en" => "en",
    "de" => "de",
    "ru" => "ru",
    "pl" => "pl",
    "fr" => "fr",
    "fi" => "fi",
    "sv" => "sv",
    "pt" => "pt",
    "es" => "es"*/
];

$jednotky = [
    "C" => "Celsius",
    "F" => "Fahrenheit",
    "K" => "Kelvin",
    "R" => "Rankine",
    "D" => "Delisle",
    "N" => "Newton",
    "Re" => "Reaumur",
    "Ro" => "Romer"
];

// jazyk
if(isset($_GET['ja']) AND isset($jazyky[$_GET['ja']]))
{
   $l = $jazyky[$_GET['ja']];
}
else
{
   $_GET['ja'] = $l;
}

require_once dirname(__FILE__) . "/language/" . $l . ".php";       // skript s jazykovou mutaci

// jednotka
if(isset($_GET['je']) AND isset($jednotky[$_GET['je']]))
{
  $u = $_GET['je'];
}
else
{
  $_GET['je'] = $u;
}
