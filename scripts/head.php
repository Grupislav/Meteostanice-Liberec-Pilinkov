<?php

  echo "<div class='roztahovak-vrsek'>
        <div id='tri' class='row'>
        <div class='container'>";

        // Aktualne
        echo "<div class='col-md-3'>
                <div class='sloupekAktualne'>
                  <div class='ajaxrefresh'>";
                    require_once dirname(__FILE__)."/ajax/aktualne.php";
            echo "</div>
                </div>
              </div>";

        // Drive touto dobou
        echo "<div class='col-md-4'>
                <div class='drivetoutodobouted'>";
                  require_once dirname(__FILE__)."/info_a_astro.php";
          echo "</div>
              </div>";

      // Info tabulka
echo "<div class='col-md-4'>
<table width='100%' class='tabulkaVHlavicce'>
    <tr class='radek zelenyRadek'>
      <td colspan='3'>{$lang['rekordy']}</td>
    </tr>
    <tr>
      <td>{$lang['nejvyssiteplota']}</td>
      <td><div class='tooltip'>" . jednotkaTeploty(35.9, $u, 1) . "<span class='tooltiptext'>5. 8. 2022</span></div></td>
    </tr>
    <tr>
      <td>{$lang['nejnizsiteplota']}</td>
      <td><div class='tooltip'>" . jednotkaTeploty(-15.1, $u, 1) . "<span class='tooltiptext'>15. 2. 2021</span></div></td>
    </tr>
    <tr>
      <td>{$lang['nejvyssipocteplota']}</td>
      <td><div class='tooltip'>" . jednotkaTeploty(39.1, $u, 1) . "<span class='tooltiptext'>20. 8. 2023</span></div></td>
    </tr>
    <tr>
      <td>{$lang['nejnizsipocteplota']}</td>
      <td><div class='tooltip'>" . jednotkaTeploty(-19.6, $u, 1) . "<span class='tooltiptext'>10. 2. 2021</span></div></td>
    </tr>
    <tr>
      <td>{$lang['nejvyssirosnybod']}</td>
      <td><div class='tooltip'>" . jednotkaTeploty(24.9, $u, 1) . "<span class='tooltiptext'>21. 8. 2023</span></div></td>
    </tr>
    <tr>
      <td>{$lang['nejnizsirosnybod']}</td>
      <td><div class='tooltip'>" . jednotkaTeploty(-16.4, $u, 1) . "<span class='tooltiptext'>15. 2. 2021</span></div></td>
    </tr>
    <tr>
      <td>{$lang['nejnizsivlhkost']}</td>
      <td><div class='tooltip'>13.9 %<span class='tooltiptext'>8. 4. 2020</span></div></td>
    </tr>              
    <tr>
      <td>{$lang['nejvyssiuhrn']}</td>
      <td><div class='tooltip'>43.2 mm<span class='tooltiptext'>23. 2. 2020</span></div></td>
    </tr>
    <tr>
      <td>{$lang['nejrychlejsivitr']}</td>
      <td><div class='tooltip'>7.3 m/s<span class='tooltiptext'>4. 2. 2023</span></div></td>
    </tr>    
    <tr>
      <td>{$lang['nejprudsinaraz']}</td>
      <td><div class='tooltip'>16.1 m/s<span class='tooltiptext'>21. 10. 2021</span></div></td>
    </tr>
    <tr>
      <td>{$lang['maxosvit']}</td>
      <td><div class='tooltip'>1165.5 W<span class='tooltiptext'>17. 7. 2020</span></div></td>
    </tr>
    <tr>
      <td>{$lang['nejvyssitlak']}</td>
      <td><div class='tooltip'>1046.0 hPa<span class='tooltiptext'>20. 1. 2020</span></div></td>
    </tr>
    <tr>
      <td>{$lang['nejnizsitlak']}</td>
      <td><div class='tooltip'>982.9 hPa<span class='tooltiptext'>13. 12. 2019</span></div></td>
    </tr>
</table>
          </div>
        </div>
      </div>
    </div>";
