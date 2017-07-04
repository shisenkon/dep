<?php
$percent = 0.085;
$years = 1000;
$money_start = 1000;
$money_finish = $money_start;
for ($i = 1; $i <= $years; $i++) {
    $money_finish = $money_finish*(1+$percent);
}
echo ($money_finish);
?>