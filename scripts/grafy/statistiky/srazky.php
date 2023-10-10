<?php

// INIT
require dirname(__FILE__) . "/../../init.php";
require_once dirname(__FILE__) . "/../../fce.php";

//Nejdriv naplnime nadpisy a realny srazky
$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT date_time, SUM( precipitation ) AS srazky, MONTH( date_time ) AS mesic, precipitation_normals.normal19812010, precipitation_normals.normal19611990
        FROM history_cron
        INNER JOIN precipitation_normals ON MONTH( date_time ) = month
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
    $ydata[] = $t['srazky'];
    $ydata2[] = $t['normal19812010'];
    $ydata3[] = $t['normal19611990'];         
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
                chart: {renderTo: 'graf-stat-srazky', zoomType: 'x', backgroundColor: '#ffffff', borderRadius: 0},
                credits: {enabled: 0},
                title: {text: '<?php echo "."; ?>'},
                xAxis: {
                    categories: ['<?php echo implode("','", $labels); ?>'],
                    labels: {rotation: -45, align: 'right'}
                },
                yAxis: [{
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
                    opposite: false
                }],
                tooltip: {
                    formatter: function () {
                        var unit = {
                            '<?php echo $lang['skutecnost'] ?>': ' mm',
                            '<?php echo $lang['normal19812010'] ?>': ' mm',
                            '<?php echo $lang['normal19611990'] ?>': ' mm'
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
                    name: '<?php echo $lang['skutecnost'] ?>',
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