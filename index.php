<?php
// include lib file
//include('deplit.inc.php');
include('deplit.form.php');
include('deplit_0.1.inc.php');
try {

$form = new Deplit_form_post($_POST);

}
catch(Exception $e) {
	die($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Deplit</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
   </head>
  <body>


    <h1>Генераторы плиток DEPLIT</h1>
	<div class="center"><div class="btn-group" role="group" aria-label="Калькулятор плитки Deplit">
  <a href="modern.php" type="button" class="btn btn-primary btn-lg">Modern</a>
  <a href="loft.php" type="button" class="btn btn-primary btn-lg">Loft</a>
</div></div>
   <script src="http://code.jquery.com/jquery-latest.js"></script>
    <script src="js/bootstrap.min.js"></script>


  </body>
  </html>