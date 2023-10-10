<?php

// INIT
require dirname(__FILE__) . "/../../init.php";
require_once dirname(__FILE__) . "/../../fce.php";

//Nejdriv naplnime nadpisy a realny srazky
$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT date_time, AVG( temperature ) AS prumteplota, MONTH( date_time ) AS mesic, temperature_normals.normal19812010, temperature_normals.normal19611990
        FROM history_cron
        INNER JOIN temperature_normals ON MONTH( date_time ) = month
        GROUP BY YEAR( date_time ) , mesic
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
    $ydata[] = jednotkaTeploty(round($t['prumteplota'], 1), $u, 0);
    $ydata2[] = jednotkaTeploty($t['normal19812010'], $u, 0);
    $ydata3[] = jednotkaTeploty($t['normal19611990'], $u, 0);         
}

// abychom ziskali spravnou posloupnoust udaju, tak pole obratime
$ydata = array_reverse($ydata);
$ydata2 = array_reverse($ydata2);
$ydata3 = array_reverse($ydata3);
$labels = array_reverse($labels);
}
?>

<script type="text/javascript">
    $(function () {
        var chart;
        $(document).ready(function () {
            chart = new Highcharts.Chart({
                chart: {renderTo: 'graf-stat-teplota', zoomType: 'x', backgroundColor: '#ffffff', borderRadius: 0},
                credits: {enabled: 0},
                title: {text: '<?php echo "."; ?>'},
                xAxis: {
                    categories: ['<?php echo implode("','", $labels); ?>'],
                    labels: {rotation: -45, align: 'right'}
                },
                yAxis: [{
                    title: {
                        text: null,
                        style: {color: '#c4423f'}
                    },
                    labels: {
                        formatter: function () {
                            return this.value + ' <?php echo "$jednotka"; ?>';
                        },
                        style: {color: '#c4423f'}
                    },
                    opposite: false
                }],
                tooltip: {
                    formatter: function () {
                        var unit = {
                            '<?php echo $lang['prumteplota'] ?>': ' <?php echo "$jednotka"; ?>',
                            '<?php echo $lang['normal19812010'] ?>': ' <?php echo "$jednotka"; ?>',
                            '<?php echo $lang['normal19611990'] ?>': ' <?php echo "$jednotka"; ?>'
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
                    name: '<?php echo $lang['prumteplota'] ?>',
                    type: 'column',
                    color: '#c01212',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata); ?>],
                    marker: {enabled: false}
                }, {
                    name: '<?php echo $lang['normal19812010'] ?>',
                    type: 'column',
                    color: '#ebb91f',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata2); ?>],
                    marker: {enabled: false}

                }, {
                    name: '<?php echo $lang['normal19611990'] ?>',
                    type: 'column',
                    color: '#1260c0',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata3); ?>],
                    marker: {enabled: false}
                }]
            });

            $(".tabs > li").click(function () {
                chart.reflow();
            });

        });

    });
</script>