<?php

// INIT
require dirname(__FILE__) . "/../../init.php";
require_once dirname(__FILE__) . "/../../fce.php";

//Nejdriv naplnime nadpisy a realny srazky
$conn = mysqli_connect($dbServer,$dbUzivatel,$dbHeslo,$dbDb);
if (!$conn) {echo "Nejaky problem s DB: " . $conn;}
$sql = "SELECT date_time, MONTH( date_time ) as mesic, MAX( temperature ) AS maximum, MIN( temperature ) AS minimum
        FROM history_cron
        GROUP BY YEAR( date_time ) , MONTH( date_time ) , DAY( date_time )";

$result = MySQLi_query($conn, $sql);
mysqli_close($conn);
if (mysqli_num_rows($result) <= 0) {echo "Nemame data!";}
else
{
$letnidny=0;$tropickedny=0;$tropickenoci=0;$mrazovedny=0;$ledovedny=0;$arktickedny=0;
//projet vsechny dny
while($t = MySQLi_fetch_assoc($result))
{
//zmenil se mesic, zapsat napocitany hodnoty a vynulovat
if($t['mesic']!=$mesicpredtim && $mesicpredtim!="")
    {
    $labels[] = substr($datumpredtim,0,7);
    // sup do pole
    $ydata[] = $letnidny;
    $ydata2[] = $tropickedny;
    $ydata3[] = $tropickenoci;
    $ydata4[] = $mrazovedny;
    $ydata5[] = $ledovedny;
    $ydata6[] = $arktickedny;
        
    $letnidny=0;$tropickedny=0;$tropickenoci=0;$mrazovedny=0;$ledovedny=0;$arktickedny=0;
    } 

if($t['maximum']>=25) $letnidny++;
if($t['maximum']>=30) $tropickedny++;
if($t['minimum']>=20) $tropickenoci++;
if($t['minimum']<0) $mrazovedny++;
if($t['maximum']<0) $ledovedny++;
if($t['maximum']<-10) $arktickedny++;  
     
$mesicpredtim=$t['mesic'];
$datumpredtim=$t['date_time'];             
}

// jeste soupneme aktualni mesic
    $labels[] = substr($datumpredtim,0,7);
    // sup do pole
    $ydata[] = $letnidny;
    $ydata2[] = $tropickedny;
    $ydata3[] = $tropickenoci;
    $ydata4[] = $mrazovedny;
    $ydata5[] = $ledovedny;
    $ydata6[] = $arktickedny;

$letnidny=0;$tropickedny=0;$tropickenoci=0;$mrazovedny=0;$ledovedny=0;$arktickedny=0;    
}
?>

<script type="text/javascript">
    $(function () {
        var chart;
        $(document).ready(function () {
            chart = new Highcharts.Chart({
                chart: {renderTo: 'graf-stat-chardny', zoomType: 'x', backgroundColor: '#ffffff', borderRadius: 0},
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
                            return this.value;
                        },
                        style: {color: '#c4423f'}
                    },
                    opposite: false
                }],
                tooltip: {
                    formatter: function () {
                        var unit = {
                            '<?php echo $lang['letnidny'] ?>': ' <?php echo $lang['letnichdnu'] ?>',
                            '<?php echo $lang['tropickedny'] ?>': ' <?php echo $lang['tropickychdnu'] ?>',
                            '<?php echo $lang['tropickenoci'] ?>': ' <?php echo $lang['tropickychnoci'] ?>',
                            '<?php echo $lang['mrazovedny'] ?>': ' <?php echo $lang['mrazovychdnu'] ?>',
                            '<?php echo $lang['ledovedny'] ?>': ' <?php echo $lang['ledovychdnu'] ?>',
                            '<?php echo $lang['arktickedny'] ?>': ' <?php echo $lang['arktickychdnu'] ?>'
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
                    name: '<?php echo $lang['letnidny'] ?>',
                    type: 'column',
                    color: '#ff6600',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata); ?>],
                    marker: {enabled: false}
                }, {
                    name: '<?php echo $lang['tropickedny'] ?>',
                    type: 'column',
                    color: '#ff3300',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata2); ?>],
                    marker: {enabled: false}

                }, {
                    name: '<?php echo $lang['tropickenoci'] ?>',
                    type: 'column',
                    color: '#ff944d',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata3); ?>],
                    marker: {enabled: false},
                    visible: false                    
                }, {
                    name: '<?php echo $lang['mrazovedny'] ?>',
                    type: 'column',
                    color: '#83b2e3',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata4); ?>],
                    marker: {enabled: false}
                }, {
                    name: '<?php echo $lang['ledovedny'] ?>',
                    type: 'column',
                    color: '#4c8aca',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata5); ?>],
                    marker: {enabled: false}
                }, {
                    name: '<?php echo $lang['arktickedny'] ?>',
                    type: 'column',
                    color: '#3573b1',
                    yAxis: 0,
                    data: [<?php echo implode(", ", $ydata6); ?>],
                    marker: {enabled: false},                   
                }]
            });

            $(".tabs > li").click(function () {
                chart.reflow();
            });

        });

    });
</script>