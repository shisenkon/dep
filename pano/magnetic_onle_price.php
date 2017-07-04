<?php
// include lib file
include('deplit_pano.inc.php');

$arr_pano_sizes = array(
	'3 3 all 5500 7400',
	'4 3 all 6400 8600',
	'4 4 all 7500 9990',
	'5 3 all 7200 9600',
	'5 4 all 8600 11500',
	'5 5 all 10000 13400',
	'6 3 all 8100 10800',
	'6 4 all 9800 13100',
	'6 5 all 11400 15200',
	'6 6 all 13100 17500',
	'7 3 all 9000 12000',
	'7 4 all 10900 8400',
	'7 5 all 12700 17000',
	'7 6 all 14700 19600',
	'7 7 all 16500 22000',
	'8 3 all 9999 13400',
	'8 4 all 12300 16400',
	'8 5 all 14300 19100',
	'8 6 all 16600 22200',
	'8 7 all 18700 25000',
	'8 8 all 21400 28600',
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
	<div class="col-xs-12 text-center font-weight-bold"><h1>Декоративные панно серии MAGNETIC</h1></div>
	<div class="col-xs-12 text-right">Прайс-лист на продукцию компании «Деплит»<br>Цены указаны на 01.06.2017</div>
	<div class="col-xs-12"><img src="img/example.jpg" alt="" height="213" width="300" style="float:right;margin: 10px 0	10px 10px;"><p>Наборное панно из дерева состоящее из плитки Деплит Лофт, 3D Лофт (Line/Дуб/Бук) обрамленные дубовой рамой. Композицию для панно можно составить самому или воспользоваться уже готовыми вариантами. Каждая плитка крепится на панно с помощью магнитов, что позволяет собирать различные рисунки на панно в любое время.</p></div>
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
$form = new Panno_price($arr_pano_sizes,'MAGNETIC');
$form->render_table();
}
catch(Exception $e) {
	die($e->getMessage());
}

?>
<tr>
    <td colspan="6" class="footer">
        <p class="tel">Контакты</p>
        <p class="name">Шибков Константин</p>
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