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

try {
	// initialize class with custom parameters
	$deplit = new Deplit_calc(array('deplit_type'=>'loft', 'surface_height'=>$form->surface_height, 'surface_lenght'=>$form->surface_lenght, 'plintus100'=>$form->plintus100, 'seed_fix_check'=>$form->seed_fix_check, 'seed_fix'=>$form->seed_fix));
}catch(Exception $e) {
	die($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Deplit</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
   </head>
  <body>

<div id="deplit_type" style="display: none">loft</div>
<div class="btn-group" style="margin:5px 0 10px 0;">
  <a href="loft.php" type="button" class="btn btn-primary">Плитка Loft</a>
  <a href="modern.php" type="button" class="btn btn-primary">Плитка Modern</a>
</div>
    <h4>Калькулятор плитки Deplit View Loft &alpha;</h4>

<?php include('footer.php'); ?>