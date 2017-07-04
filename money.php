<?php
$percent = 0.085;
$years = 1;
$money_start = 1000;
$money_finish_cap = $money_start;
$money_finish_wocap = $money_start;
$money_perc = 0;
for ($i = 1; $i <= $years; $i++) {
    $money_finish_cap = $money_finish_cap*(1+$percent);
    $money_perc = $money_perc + $money_start*$percent;
}
echo ("Положим " . $money_start . "р под ". $percent*100 . "% на " . $years . " лет с капитализацией ежегодно и получим " .  $money_finish_cap. "р.<br>Положим " . $money_start . "р под ". $percent*100 . "% на " . $years . " лет с выплатой процентов в конце срока и получим" . ($money_perc+$money_start) . "р. <br>");
?>