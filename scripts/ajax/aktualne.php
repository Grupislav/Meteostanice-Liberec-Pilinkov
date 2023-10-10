<?php

require_once dirname(__FILE__) . "/../../config.php";      
require_once dirname(__FILE__) . "/../fce.php";       // skript s nekolika funkcemi

// Osetreni vstupu
require_once dirname(__FILE__) . "/../variableCheck.php";

//nahrat data do db
require_once dirname(__FILE__) . "/../dbinsert.php";

// Ziskani XML s aktualnimi hodnotami
$xmlString = curl_get_file_contents("http://api.meteo-pocasi.cz/api.xml?action=get-meteo-data&client=xml&id=00004c8SfUq5hdYFumackwf6NBJ5JC0iPfTG0QifuZlcCJs75Sj");
$xml = simplexml_load_string($xmlString);// Vycteni hodnot

$akteplota=$xml->input->sensor[1]->value;
$aktvlhkost=$xml->input->sensor[6]->value;
$aktrosnybod=$xml->input->sensor[9]->value;
$aktpocteplota=$xml->input->sensor[14]->value;
$akttlak=$xml->input->sensor[7]->value;
$aktosvit=$xml->input->sensor[10]->value;
$aktvitr=$xml->input->sensor[12]->value;
$aktnarazvetru=$xml->input->sensor[13]->value;
$aktsmervetru=$xml->input->sensor[11]->value;
$aktsrazky=$xml->input->sensor[8]->value;
$aktpocasi=$xml->input->sensor[0]->value;
$aktualizovano=$xml->last_communication;

//dlazdice vlevo nahore
//velka teplota + posledni aktualizace
echo "<div class='aktualne jen jen" . barvaRameckuTeploty($akteplota) . "'>
        <div class='aktualneOdskok'>
          {$lang['aktualnipocasi']}<br>
          <font class='aktua jen'>" . jednotkaTeploty($akteplota, $u, 1) . "</font>
          <br>".$lang[Pocasi($aktpocasi)]."
        </div>
      </div>

          <div class='aktualneMensi" . barvaRameckuTeploty($aktpocteplota) . "'>
            <div class='aktualneOdskok'>
              {$lang['pocteplota']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($aktpocteplota, $u, 1) . "</font>
            </div>
          </div>
          
          <div class='aktualneMensi vpravo" . barvaRameckuTeploty($aktrosnybod) . "'>
            <div class='aktualneOdskok'>
              {$lang['rosnybod']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($aktrosnybod, $u, 1) . "</font>
            </div>
          </div>
          
          <div class='aktualneMensi" . barvaRameckuVlhkost($aktvlhkost) . "'>
            <div class='aktualneOdskok'>
              {$lang['vlhkost']}<br>
              <span class='aktuamens'>{$aktvlhkost} %</span>
            </div>
          </div>
          
          <div class='aktualneMensi vpravo" . barvaRameckuSrazky($aktsrazky) . "'>
            <div class='aktualneOdskok'>
              {$lang['srazky']}<br>
              <span class='aktuamens'>{$aktsrazky} mm</span>
            </div>
          </div>

          <div class='aktualneMensi" . barvaRameckuVitr($aktvitr) . "'>
            <div class='aktualneOdskok'>
              {$lang['vitr']}<br>
              <span class='aktuamens'>{$aktvitr} m/s</span>
            </div>
          </div>

          <div class='aktualneMensi aktualneMensiVitr vpravo'>
            <div class='aktualneOdskok'>
              {$lang['smervetru']}<br>
              <font class='aktuamens'>".SmerVetru($aktsmervetru)."</font>
            </div>
          </div>

          <div class='aktualneMensi" . barvaRameckuTlak($akttlak) . "'>
            <div class='aktualneOdskok'>
              {$lang['tlak']}<br>
              <span class='aktuamens'>{$akttlak} hPa</span>
            </div>
          </div>

          <div class='aktualneMensi vpravo" . barvaRameckuOsvit($aktosvit) . "'>
            <div class='aktualneOdskok'>
              {$lang['osvit']}<br>
              <font class='aktuamens" . ($aktosvit < 250 ? "" : "cerna") . "'>{$aktosvit} W</font>
            </div>
          </div>
          
          <div class='" . barvaRameckuAktualizovano($aktualizovano) . "'>
              <font>{$lang['posledniaktualizace']} ".formatData($aktualizovano)." (" . textAktualizovano($aktualizovano) . ")</font>
          </div>";