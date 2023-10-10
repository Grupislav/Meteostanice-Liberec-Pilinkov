<?php

// INIT
require dirname(__FILE__) . "/../../init.php";
require_once dirname(__FILE__) . "/../../fce.php";

// Posledni zaznamy
$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT date_time, AVG(temperature_apparent) AS pocteplota, AVG(dew_point) AS rosnybod, AVG(humidity) AS vlhkost, AVG(wind_speed) AS vitr, AVG(pressure_QNH) AS tlak, AVG(exposure) AS osvit
        FROM history_cron
        GROUP BY year(date_time) , month(date_time) , day(date_time) 
        ORDER BY date_time DESC 
        LIMIT 30";
$result = MySQLi_query($conn, $sql);
mysqli_close($conn);
if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}
else
{

while($t = MySQLi_fetch_assoc($result))
{
    // popisek
    $labels[] = formatDnu(substr($t['date_time'],0,10));
    // sup do pole
    $ydata[] = jednotkaTeploty(round($t['pocteplota'],1), $u, 0);
    $ydata2[] = round($t['vlhkost'],1);
    $ydata3[] = round($t['rosnybod'],1);
    $ydata4[] = round($t['tlak'],1);
    $ydata5[] = round($t['osvit'],1);
    $ydata6[] = round($t['vitr'],1);    
}

// abychom ziskali spravnou posloupnoust udaju, tak pole obratime
$ydata = array_reverse($ydata);
$ydata2 = array_reverse($ydata2);
$ydata3 = array_reverse($ydata3);
$ydata4 = array_reverse($ydata4);
$ydata5 = array_reverse($ydata5);
$ydata6 = array_reverse($ydata6);
$labels = array_reverse($labels);
}
?>

<script type="text/javascript">
    $(function () {
        var chart;
        $(document).ready(function () {
            chart = new Highcharts.Chart({
                chart: {renderTo: 'graf-ostatni-30-dni', zoomType: 'x', backgroundColor: '#ffffff', borderRadius: 0},
                credits: {enabled: 0},
                title: {text: '<?php echo "."; ?>'},
                xAxis: {
                    categories: ['<?php echo implode("','", $labels); ?>'],
                    labels: {rotation: -45, align: 'right'}
                },
                yAxis: [{
                    labels: {
                        formatter: function () {
                            return this.value + ' <?php echo "$jednotka"; ?>';
                        },
                        style: {color: '#990099'}
                    },
                    title: {
                        text: null,
                        style: {color: '#990099'}
                    },
                    opposite: true
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
                        style: {color: '#800000'}
                    },
                    labels: {
                        formatter: function () {
                            return this.value + ' hPa';
                        },
                        style: {color: '#800000'}
                    },
                    opposite: false
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
                            '<?php echo $lang['pocteplota'] ?>': '<?php echo "$jednotka"; ?>',                            
                            '<?php echo $lang['vlhkost'] ?>': '%',
                            '<?php echo $lang['rosnybod'] ?>': '<?php echo "$jednotka"; ?>',
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
                    name: '<?php echo $lang['pocteplota'] ?>',
                    type: 'spline',
                    color: '#990099',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata); ?>],
                    marker: {enabled: false},
                    visible: false                    
                }, {
                    name: '<?php echo $lang['vlhkost'] ?>',
                    type: 'spline',
                    color: '#33cccc',
                    yAxis: 1,
                    data: [<?php echo implode(", ", $ydata2); ?>],
                    marker: {enabled: false},
                    visible: false
                }, {
                    name: '<?php echo $lang['rosnybod'] ?>',
                    type: 'spline',
                    color: '#009933',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata3); ?>],
                    marker: {enabled: false},
                    visible: false                    
                }, {
                    name: '<?php echo $lang['tlak'] ?>',
                    type: 'spline',
                    color: '#800000',
                    yAxis: 2,
                    data: [<?php echo implode(", ", $ydata4); ?>],
                    marker: {enabled: false},
                }, {
                    name: '<?php echo $lang['osvit'] ?>',
                    type: 'spline',
                    color: '#e6e600',
                    yAxis: 3,
                    data: [<?php echo implode(", ", $ydata5); ?>],
                    marker: {enabled: false},
                }, {
                    name: '<?php echo $lang['vitr'] ?>',
                    type: 'spline',
                    color: '#3399ff',
                    yAxis: 4,
                    data: [<?php echo implode(", ", $ydata6); ?>],
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