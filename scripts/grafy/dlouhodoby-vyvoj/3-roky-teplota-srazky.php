<?php

// INIT
require dirname(__FILE__) . "/../../init.php";
require_once dirname(__FILE__) . "/../../fce.php";

// Posledni zaznamy
$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT date_time, AVG(temperature) AS prumer, MIN(temperature) AS nejnizsi, MAX(temperature) AS nejvyssi, SUM(precipitation) AS srazky
        FROM history_cron
        GROUP BY year(date_time) , month(date_time) 
        ORDER BY date_time DESC 
        LIMIT 36";
$result = MySQLi_query($conn, $sql);
mysqli_close($conn);
if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}
else
{

while($t = MySQLi_fetch_assoc($result))
{
    // popisek
    $labels[] = substr($t['date_time'],0,7);
    // sup do pole
    $ydata[] = jednotkaTeploty($t['nejvyssi'], $u, 0);
    $ydata2[] = jednotkaTeploty($t['nejnizsi'], $u, 0);
    $ydata3[] = jednotkaTeploty(round($t['prumer'], 1), $u, 0);
    $ydata4[] = $t['srazky'];    
}

// abychom ziskali spravnou posloupnoust udaju, tak pole obratime
$ydata = array_reverse($ydata);
$ydata2 = array_reverse($ydata2);
$ydata3 = array_reverse($ydata3);
$ydata4 = array_reverse($ydata4);
$labels = array_reverse($labels);
}
?>

<script type="text/javascript">
    $(function () {
        var chart;
        $(document).ready(function () {
            chart = new Highcharts.Chart({
                chart: {renderTo: 'graf-teplota-3-roky', zoomType: 'x', backgroundColor: '#ffffff', borderRadius: 0},
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
                        style: {color: '#0066ff'}
                    },
                    labels: {
                        formatter: function () {
                            return this.value + ' mm';
                        },
                        style: {color: '#0066ff'}
                    },
                    opposite: true
                }],
                tooltip: {
                    formatter: function () {
                        var unit = {
                            '<?php echo $lang['max'] ?>': ' <?php echo "$jednotka"; ?>',
                            '<?php echo $lang['avg'] ?>': ' <?php echo "$jednotka"; ?>',
                            '<?php echo $lang['min'] ?>': ' <?php echo "$jednotka"; ?>',
                            '<?php echo $lang['srazky'] ?>': ' mm'
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
                    name: '<?php echo $lang['max'] ?>',
                    type: 'spline',
                    color: '#c01212',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata); ?>],
                    marker: {enabled: false}
                }, {
                    name: '<?php echo $lang['avg'] ?>',
                    type: 'spline',
                    color: '#ebb91f',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata3); ?>],
                    marker: {enabled: false}

                }, {
                    name: '<?php echo $lang['min'] ?>',
                    type: 'spline',
                    color: '#1260c0',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata2); ?>],
                    marker: {enabled: false}
                }, {
                    name: '<?php echo $lang['srazky'] ?>',
                    type: 'column',
                    color: '#0066ff',
                    yAxis: 1,
                    data: [<?php echo implode(", ", $ydata4); ?>],
                    marker: {enabled: false}                    
                }]
            });

            $(".tabs > li").click(function () {
                chart.reflow();
            });

        });

    });
</script>