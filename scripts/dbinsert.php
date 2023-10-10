<?php
//connect
$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {mysqli_close($conn); exit("Nejaky problem s DB!");}

//prvni datum (dnes)
$date = date("Y-m-d");

//posledni den a cas
$sql = "SELECT max(date_time) as end_date FROM history_cron";
$result = MySQLi_query($conn, $sql);
if (mysqli_num_rows($result) <= 0) {mysqli_close($conn); exit("Nemame data!");}
$t = MySQLi_fetch_assoc($result);
$end_date = $t['end_date'];

//cyklus projizdejici cele dny
while (strtotime($date) >= strtotime(substr($end_date,0,10)))
{
// Ziskani XML s dnesnimi hodnotami
require_once dirname(__FILE__) . "/fce.php";       // skript s nekolika funkcemi
$xmlString = curl_get_file_contents("http://api.meteo-pocasi.cz/api.xml?action=get-meteo-history&period=day&period_date=" . $date . "&client=xml&id=00004c8SfUq5hdYFumackwf6NBJ5JC0iPfTG0QifuZlcCJs75Sj");
$xml = simplexml_load_string($xmlString) or die("Error: Cannot create object");

//parse
foreach($xml->meteo->children() as $node)
{
if ($node['value'] <= $end_date) {break 2;}
$sql = "INSERT INTO history_cron (date_time, humidity, pressure_QNH, exposure, temperature, wind_speed, wind_direction, dew_point, precipitation, temperature_apparent, wind_gust)
VALUES ('" .
     $node['value'] . "'," .  
     $node->humidity['value'] . "," . 
     $node->pressure_QNH['value'] . "," .
     $node->exposure['value'] . "," .
     $node->temperature['value'] . "," .
     $node->wind_speed['value'] . "," .
     $node->wind_direction['value'] . "," .
     $node->dew_point['value'] . "," .
     $node->precipitation['value'] . "," .
     $node->temperature_apparent['value'] . "," .               
     $node->wind_gust['value'] . ")";                                        

//insert do db
mysqli_query($conn, $sql);
}
//iterovat den
$date = date ("Y-m-d", strtotime("-1 day", strtotime($date)));
}
mysqli_close($conn);
?>