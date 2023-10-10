<?php

// INIT
require_once dirname(__FILE__) . "/../../config.php";
require_once dirname(__FILE__) . "/../fce.php";
require_once dirname(__FILE__) . "/../variableCheck.php";

//psani a kresleni
echo "<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'>" . mb_strtoupper($lang['grafstatistikysrazky'],'UTF-8') . "</span></td>
          </tr>
      </table>

<div class='graf' id='graf-stat-srazky'>";
require dirname(__FILE__) . '/../grafy/statistiky/srazky.php';
echo "</div>

<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'>" . mb_strtoupper($lang['grafstatistikyteplota'],'UTF-8') . "</span></td>
          </tr>
      </table>

<div class='graf' id='graf-stat-teplota'>";
require dirname(__FILE__) . '/../grafy/statistiky/teplota.php';
echo "</div>";

echo $lang['statistikyzdroj'];

echo "<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'>" . mb_strtoupper($lang['grafcharakteristickedny'],'UTF-8') . "</span></td>
          </tr>
      </table>

<div class='graf' id='graf-stat-chardny'>";
require dirname(__FILE__) . '/../grafy/statistiky/chardny.php';
echo "</div>";
echo "<div style='text-align:left'><span style='background-color:#ff6600'>Letní den:</span> den, kdy teplota dosáhla alespoň 25 °C.<br>
      <span style='background-color:#ff3300'>Tropický den:</span> den, kdy teplota dosáhla alespoň 30 °C.<br>
      <span style='background-color:#ff944d'>Tropická noc:</span> noc, kdy teplota neklesla pod 20 °C (dosud nezaznamenána).<br>
      <span style='background-color:#83b2e3'> Mrazový den:</span> den, kdy teplota klesla pod bod mrazu.<br>
      <span style='background-color:#4c8aca'> Ledový den:</span> den, kdy teplota nevzrostla nad bod mrazu.<br>
      <span style='background-color:#3573b1'>Arktický den:</span> den, kdy teplota nevzrostla nad -10 °C.</div>";
