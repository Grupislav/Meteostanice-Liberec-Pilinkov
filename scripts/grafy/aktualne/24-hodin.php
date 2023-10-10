<?php

// INIT
require dirname(__FILE__) . "/../../init.php";

// Posledni zaznamy
$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT date_time, temperature, temperature_apparent, humidity, dew_point, precipitation, pressure_QNH, exposure, wind_speed 
                                    FROM history_cron 
                                    WHERE date_time >= NOW() - INTERVAL 1 DAY  
                                    ORDER BY date_time DESC";
$result = MySQLi_query($conn, $sql);
mysqli_close($conn);
if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}
else
{

// budeme brat kazdy Xty zaznam (a), abychom se do grafu rozumne vesli
$a = 5;
$count = 0;

while($t = MySQLi_fetch_assoc($result))
{

    // budeme za tu dobu pocitat prumernou teplotu,
    // abychom meli graf "uhlazenejsi" (vypada to lepe)
    $teplota = $teplota + $t['temperature'];
    $pocteplota = $pocteplota + $t['temperature_apparent'];
    $vlhkost = $vlhkost + $t['humidity'];
    $rosnyBod = $rosnyBod + $t['dew_point'];
    $srazky = $srazky + $t['precipitation'];
    $tlak = $tlak + $t['pressure_QNH'];
    $osvit = $osvit + $t['exposure'];
    $vitr = $vitr + $t['wind_speed'];
    $count++;

    // uz mame dostatek mereni?
    if($a == 5)
    {
        // pridame teplotu do pole
        $ydata[] = round(jednotkaTeploty($teplota / $count, $u, 0), 1);
        $ydata2[] = round(jednotkaTeploty($pocteplota / $count, $u, 0), 1);        
        $ydata3[] = round($vlhkost / $count, 1);
        $ydata4[] = round(jednotkaTeploty($rosnyBod / $count, $u, 0), 1);
        $ydata5[] = $srazky;
        $ydata6[] = round($tlak / $count, 1);        
        $ydata7[] = round($osvit / $count, 1);
        $ydata8[] = round($vitr / $count, 1);
                
        // pridame popisek do pole
        $labels[] = $t['date_time'];

        // vynulujeme
        $teplota = "";
        $vlhkost = "";
        $pocteplota = "";        
        $rosnyBod = "";
        $srazky = "";
        $tlak = "";
        $osvit = "";
        $vitr = "";
        $count = 0;
        $a = 0;
     }
     
    // iterujeme
    $a++;

}

// abychom ziskali spravnou posloupnoust udaju, tak pole obratime
$ydata = array_reverse($ydata);
$ydata2 = array_reverse($ydata2);
$ydata3 = array_reverse($ydata3);
$ydata4 = array_reverse($ydata4);
$ydata5 = array_reverse($ydata5);
$ydata6 = array_reverse($ydata6);
$ydata7 = array_reverse($ydata7);
$ydata8 = array_reverse($ydata8);
$labels = array_reverse($labels);

$mereni = 0;
$plotLines = [];
$latestLabel = "";

foreach($labels as $index => $label)
{
    if((substr($label, 0, 10) != substr($latestLabel, 0, 10)) AND $latestLabel != "")
    {
        $plotLines[] = $mereni;
    }
    $latestLabel = $label;
    $labels[$index] = substr($label, 11, 5);
    $mereni++;
}

$plotLinesOutput = "";
if(count($plotLines) > 0)
{
    $toOutput = [];

    foreach($plotLines AS $position)
    {
        $toOutput[] = "{ color: 'lightgrey', dashStyle: 'solid', value: {$position}, width: 1 }";
    }

    $plotLinesOutput = implode(",", $toOutput);
}

?>
<script type="text/javascript">
    $(function () {
        var chart;
        $(document).ready(function () {
            chart = new Highcharts.Chart({
                chart: {renderTo: 'graf-24-hodin', zoomType: 'x', backgroundColor: '#ffffff', borderRadius: 0},
                credits: {enabled: 0},
                xAxis: {
                    categories: ['<?php echo implode("','", $labels); ?>'],
                    labels: {rotation: -45, align: 'right', step: 10},
                    plotLines: [<?php echo $plotLinesOutput; ?>]
                },
                yAxis: [{
                    labels: {
                        formatter: function () {
                            return this.value + ' <?php echo "$jednotka"; ?>';
                        },
                        style: {color: '#c4423f'}
                    },
                    title: {
                        text: null,
                        style: {color: '#c4423f'}
                    },
                    opposite: false
                }, {
                    title: {
                        text: null,
                        style: {color: '#33cccc'}
                    },
                    labels: {
                        formatter: function () {
                            return this.value + ' %';
                        },
                        style: {color: '#33cccc'},
                    },
                    opposite: true,
                    max: 100,
                    ceiling: 100                                        
                }, {
                    title: {
                        text: null,
                        style: {color: '#0066ff'}
                    },
                    labels: {
                        formatter: function () {
                            return this.value + ' mm';
                        },
                        style: {color: '#0066ff'}
                    },
                    opposite: true
                }, {
                    title: {
                        text: null,
                        style: {color: '#800000'}
                    },
                    labels: {
                        formatter: function () {
                            return this.value + ' hPa';
                        },
                        style: {color: '#800000'}
                    },
                    opposite: true
                }, {
                    title: {
                        text: null,
                        style: {color: '#999900'}
                    },
                    labels: {
                        formatter: function () {
                            return this.value + ' W';
                        },
                        style: {color: '#999900'}
                    },
                    opposite: true
                }, {
                    title: {
                        text: null,
                        style: {color: '#3399ff'}
                    },
                    labels: {
                        formatter: function () {
                            return this.value + ' m/s';
                        },
                        style: {color: '#3399ff'}
                    },
                    opposite: true
                }],
                tooltip: {
                    formatter: function () {
                        var unit = {
                            '<?php echo $lang['teplota'] ?>': '<?php echo "$jednotka"; ?>',
                            '<?php echo $lang['pocteplota'] ?>': '<?php echo "$jednotka"; ?>',                            
                            '<?php echo $lang['vlhkost'] ?>': '%',
                            '<?php echo $lang['rosnybod'] ?>': '<?php echo "$jednotka"; ?>',
                            '<?php echo $lang['srazky'] ?>': ' mm',
                            '<?php echo $lang['tlak'] ?>': ' hPa',
                            '<?php echo $lang['osvit'] ?>': ' W',
                            '<?php echo $lang['vitr'] ?>': ' m/s'                                                                                    
                        }[this.series.name];
                        return '<b>' + this.x + '</b><br /><b>' + this.y + ' ' + unit + '</b>';
                    },
                    crosshairs: true,
                },
                legend: {
                    layout: 'horizontal',
                    align: 'left',
                    x: 6,
                    verticalAlign: 'top',
                    y: -5,
                    floating: true,
                    backgroundColor: '#FFFFFF'
                },
                series: [{
                    name: '<?php echo $lang['teplota'] ?>',
                    type: 'spline',
                    color: '#c4423f',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata); ?>],
                    marker: {enabled: false}
                }, {
                    name: '<?php echo $lang['pocteplota'] ?>',
                    type: 'spline',
                    color: '#990099',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata2); ?>],
                    marker: {enabled: false},
                    visible: false
                }, {
                    name: '<?php echo $lang['vlhkost'] ?>',
                    type: 'spline',
                    color: '#33cccc',
                    yAxis: 1,
                    data: [<?php echo implode(", ", $ydata3); ?>],
                    marker: {enabled: false},
                    visible: false
                }, {
                    name: '<?php echo $lang['rosnybod'] ?>',
                    type: 'spline',
                    color: '#009933',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata4); ?>],
                    marker: {enabled: false},
                    visible: false
                }, {
                    name: '<?php echo $lang['srazky'] ?>',
                    type: 'column',
                    color: '#0066ff',
                    yAxis: 2,
                    data: [<?php echo implode(", ", $ydata5); ?>],
                    marker: {enabled: false}                    
                }, {
                    name: '<?php echo $lang['tlak'] ?>',
                    type: 'spline',
                    color: '#800000',
                    yAxis: 3,
                    data: [<?php echo implode(", ", $ydata6); ?>],
                    marker: {enabled: false},
                    visible: false
                }, {
                    name: '<?php echo $lang['osvit'] ?>',
                    type: 'spline',
                    color: '#e6e600',
                    yAxis: 4,
                    data: [<?php echo implode(", ", $ydata7); ?>],
                    marker: {enabled: false},
                    visible: false
                }, {
                    name: '<?php echo $lang['vitr'] ?>',
                    type: 'spline',
                    color: '#3399ff',
                    yAxis: 5,
                    data: [<?php echo implode(", ", $ydata8); ?>],
                    marker: {enabled: false},
                    visible: false
                }]
            });

            $(".tabs > li").click(function () {
                chart.reflow();
            });

        });

    });
</script>
<?php
}
?>