<?php

require_once dirname(__FILE__) . "/../fce.php";
require_once dirname(__FILE__) . "/../variableCheck.php";

//vypocty
$minteplotadnes=$xml->input->sensor[3]->value;
$maxteplotadnes=$xml->input->sensor[2]->value;
$minteplotatyden=$xml->input->sensor[5]->value;
$maxteplotatyden=$xml->input->sensor[4]->value;
         
//psani a kresleni
echo "<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'>" . mb_strtoupper($lang['graf24hodin'],'UTF-8') . "</span></td>
          </tr>
      </table>";

    echo "<div class='graf' id='graf-24-hodin'>";
    require "./scripts/grafy/aktualne/24-hodin.php";
    echo "</div>
    
    <div class='aktualneMensi" . barvaRameckuTeploty($minteplotadnes) . "'>
            <div class='aktualneOdskok'>
              {$lang['mindnes']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($minteplotadnes, $u, 1) . "</font>
            </div>
          </div>
          
          <div class='aktualneMensi vpravo" . barvaRameckuTeploty($maxteplotadnes) . "'>
            <div class='aktualneOdskok'>
              {$lang['maxdnes']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($maxteplotadnes, $u, 1) . "</font>
            </div>
          </div>";
                   
echo "<table class='tabulkaDnes'>
          <tr>
            <td class='radekDnes'><span class='font25 zelena'><br>" . mb_strtoupper($lang['graf5dni'],'UTF-8') . "</span></td>
          </tr>
      </table>";
                  
    echo "<div class='graf' id='graf-5-dni'>";
    require "./scripts/grafy/aktualne/5-dni.php";
    echo "</div>
    
    <div class='aktualneMensi" . barvaRameckuTeploty($minteplotatyden) . "'>
            <div class='aktualneOdskok'>
              {$lang['mintyden']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($minteplotatyden, $u, 1) . "</font>
            </div>
          </div>
          
          <div class='aktualneMensi vpravo" . barvaRameckuTeploty($maxteplotatyden) . "'>
            <div class='aktualneOdskok'>
              {$lang['maxtyden']}<br>
              <font class='aktuamens'>" . jednotkaTeploty($maxteplotatyden, $u, 1) . "</font>
            </div>
          </div>";   
    