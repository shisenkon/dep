<?php
// include lib file
include('deplit_pano.inc.php');

$arr_pano_sizes = array(
	'3 3 all 4000 5400',
	'4 3 all 4400 5900',
	'4 4 all 4900 6600',
	'5 3 all 4800 6400',
	'5 4 all 5300 7100',
	'5 5 all 5900 7900',
	'6 3 all 5200 7000',
	'6 4 all 5900 7900',
	'6 5 all 6400 8600',
	'6 6 all 7200 9600',
	'7 3 all 5600 7500',
	'7 4 all 6300 8400',
	'7 5 all 7000 9400',
	'7 6 all 7700 10300',
	'7 7 all 8400 11200',
	'8 3 all 6100 8200',
	'8 4 all 7000 9400',
	'8 5 all 7700 10300',
	'8 6 all 8700 11600',
	'8 7 all 9400 12600',
	'8 8 all 10900 14600',
	); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Deplit</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
   </head>
  <body>
<div class="row title">
	<div class="col-xs-6"><img src="img/logo.png" alt="Лого Deplit" width="250px"></div>
	<div class="col-xs-6 text-center font-weight-bold"><h2>Декоративные панно</h2><h3>www.deplit.ru</h3>
</div></div>
<div class="row sub_title">
	<div class="col-xs-12 text-center font-weight-bold"><h1>Декоративные панно серии FIX</h1></div>
	<div class="col-xs-12 text-right">Прайс-лист на продукцию компании «Деплит»<br>Цены указаны на 01.06.2017</div>
	<div class="col-xs-12"><img src="img/example.jpg" alt="" height="213" width="300" style="float:right;margin: 10px 0	10px 10px;"><p>Наборное панно из дерева состоящее из плитки Деплит Лофт, 3D Лофт (Line/Дуб/Бук) обрамленные дубовой рамой. Композицию для панно можно составить самому или воспользоваться уже готовыми вариантами. Серия FIX отличается тем, что рисунок панно после сборки фиксированный, в отличии от серии MAGNETIC, где можно разбирать и собирать панно в различных конфигурациях.</p></div>
</div>
<table class="table table-sm">
<thead>
	<tr>
		<th>№</th>
		<th>Схема</th>
		<th>Название / Артикул</th>
		<th>Описание</th>
		<th>Розничная цена</th>
	</tr>
</thead>
<tbody>
<?php
try {
$form = new Panno_price($arr_pano_sizes,'FIX');
$form->render_table();
}
catch(Exception $e) {
	die($e->getMessage());
}

?>
<tr>
		<td colspan="6" class="footer">
		<div><img src="img/manager.png" alt=""></div>
				<p class="name">Константин Шибков</p>
		<p class="tel">8(965)936-37-62</p>
		<p class="email">deplit@deplit.ru</p>
		<p class="email">www.deplit.ru</p>
		</td>

	</tr>
</tbody>
</table>

<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="../js/bootstrap.min.js"></script>
</body>
  </html>