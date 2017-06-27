<?php
// include lib file
//include('deplit.inc.php');
$start = microtime(true);
include('grao.form.php');
include('grao_calc_stair.inc.php');
include('ChromePhp.php');
include('getCSV_grao.php');
try {
   $form = new grao_form_post($_POST);
   Stair::read_prices("csv/price.csv");
   ChromePhp::groupCollapsed('Таблица цен');
   ChromePhp::table(Stair::get_prices());
   ChromePhp::groupEnd('Таблица цен');
   $st_bolz_euro60 = new Stair($form->arr_config, array('stair_type' => 'bolz', 'stair_material' => 'euro60'));
   $st_bolz_fan54 = new Stair($form->arr_config, array('stair_type' => 'bolz', 'stair_material' => 'fan54'));
   $st_bolz_oak50 = new Stair($form->arr_config, array('stair_type' => 'bolz', 'stair_material' => 'oak50'));
   $st_riser_euro60 = new Stair($form->arr_config, array('stair_type' => 'riser', 'stair_material' => 'euro60'));
   $st_riser_fan54 = new Stair($form->arr_config, array('stair_type' => 'riser', 'stair_material' => 'fan54'));
   $st_riser_oak50 = new Stair($form->arr_config, array('stair_type' => 'riser', 'stair_material' => 'oak50'));
} catch(Exception $e) {
   die($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GRAO Calc</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Калькулятор Лестниц</h1>

<div class="row">

    <div class="col-xs-8 col-md-6">
        <form method="post" action="">
            <button class="btn btn-primary btn-block btn-success btn-lg" type="submit">Посчитать</button>
            <h4 data-toggle="collapse" data-target="#demo" role="button"> Размеры базовых ступеней</h4>
            <div class="row collapse in" id="demo">
                <div class="col-xs-2">
                    <label id="basic-addon3" for="step_base_lenght">Длина</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="step_base_lenght" id="step_base_lenght"
                                                    class="form-control" placeholder="Длина базовой ступени"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['step_base_lenght'])) {
                                                       echo($form->arr_config['step_base_lenght']);
                                                    }
                                                    else {
                                                       echo(900);
                                                    }; ?>"><span
                                class="input-group-addon" id="basic-addon2">мм</span></div>
                </div>
                <div class="col-xs-2">
                    <label id="basic-addon3" for="step_base_width">Ширина</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="step_base_width" id="step_base_width"
                                                    class="form-control" placeholder="Длина поверхности"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['step_base_width'])) {
                                                       echo($form->arr_config['step_base_width']);
                                                    }
                                                    else {
                                                       echo(330);
                                                    } ?>">
                        <span class="input-group-addon" id="basic-addon2">мм</span></div>
                </div>
            </div>
            <h4>Количество ступеней</h4>
            <div class="row form-group">
                <div class="col-xs-2">
                    <label id="basic-addon3" for="step_base_quantity">Прямые</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="step_base_quantity" id="step_base_quantity"
                                                    class="form-control" placeholder="Длина поверхности"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['step_base_quantity'])) {
                                                       echo($form->arr_config['step_base_quantity']);
                                                    }
                                                    else {
                                                       echo(14);
                                                    } ?>"><span
                                class="input-group-addon" id="basic-addon2">шт</span></div>
                </div>
                <div class="col-xs-2">
                    <label id="basic-addon3" for="step_frieze_quantity">Фризовые</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="step_frieze_quantity" id="step_frieze_quantity"
                                                    class="form-control" placeholder="Длина поверхности"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['step_frieze_quantity'])) {
                                                       echo($form->arr_config['step_frieze_quantity']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>">
                        <span class="input-group-addon" id="basic-addon2">шт</span></div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-xs-2">
                    <label id="basic-addon3" for="step_runin_quantity">Забежные</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="step_runin_quantity" id="step_runin_quantity"
                                                    class="form-control" placeholder="Длина поверхности"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['step_runin_quantity'])) {
                                                       echo($form->arr_config['step_runin_quantity']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>"><span
                                class="input-group-addon" id="basic-addon2">шт</span></div>
                </div>
                <div class="col-xs-2">
                    <label id="basic-addon3" for="step_halfplatform_quantity">Полу площадки</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="step_halfplatform_quantity"
                                                    id="step_halfplatform_quantity" class="form-control"
                                                    placeholder="Длина поверхности" aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['step_halfplatform_quantity'])) {
                                                       echo($form->arr_config['step_halfplatform_quantity']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>">
                        <span class="input-group-addon" id="basic-addon2">шт</span></div>
                </div>
            </div>

            <h4>Площадки</h4>
            <div class="row form-group">
                <div class="col-xs-2">
                    <label id="basic-addon3" for="step_platform_square">Площадь суммарная</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="step_platform_square" id="step_platform_square"
                                                    class="form-control" placeholder="Длина поверхности"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['step_platform_square'])) {
                                                       echo($form->arr_config['step_platform_square']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>"><span
                                class="input-group-addon" id="basic-addon2">м2</span></div>
                </div>
                <div class="col-xs-2">
                    <label id="basic-addon3" for="step_platform_material">Материал</label>
                </div>
                <div class="col-xs-4">
                    <select class="form-control" id="step_platform_material" name="step_platform_material">
                       <?php if(!(isset($form->arr_config['step_platform_material']))) {
                          $form->arr_config['step_platform_material'] = "Fan54";
                       } ?>
                        <option value="Fan54"<?php if($form->arr_config['step_platform_material'] == "Fan54") echo(" selected"); ?>>
                            Фан54
                        </option>
                        <option value="Fan36"<?php if($form->arr_config['step_platform_material'] == "Fan36") echo(" selected"); ?>>
                            Фан36
                        </option>
                        <option value="Compromat1p"<?php if($form->arr_config['step_platform_material'] == "Compromat1p") echo(" selected"); ?>>
                            Компримат 1п
                        </option>
                        <option value="Compromat3p"<?php if($form->arr_config['step_platform_material'] == "Compromat3p") echo(" selected"); ?>>
                            Компримат 3п
                        </option>
                    </select>
                </div>
            </div>

            <h4>Дополнительные элементы</h4>
            <div class="row form-group">
                <div class="col-xs-2">
                    <label id="basic-addon3" for="pendant_quantity">Подвесы</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="pendant_quantity" id="pendant_quantity"
                                                    class="form-control" placeholder="Подвесы"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['pendant_quantity'])) {
                                                       echo($form->arr_config['pendant_quantity']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>"><span
                                class="input-group-addon" id="basic-addon2">шт</span></div>
                </div>
                <div class="col-xs-2">
                    <label id="basic-addon3" for="boots_lenght">Сапожок у стены</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="boots_lenght" id="boots_lenght"
                                                    class="form-control" placeholder="Сапожок у стены"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['boots_lenght'])) {
                                                       echo($form->arr_config['boots_lenght']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>">
                        <span class="input-group-addon" id="basic-addon2">м</span></div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-xs-2">
                    <label id="basic-addon3" for="supportwood_quantity">Кронштейн дерево</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="supportwood_quantity" id="supportwood_quantity"
                                                    class="form-control" placeholder="Кронштейн дерево"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['supportwood_quantity'])) {
                                                       echo($form->arr_config['supportwood_quantity']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>"><span
                                class="input-group-addon" id="basic-addon2">шт</span></div>
                </div>
                <div class="col-xs-2">
                    <label id="basic-addon3" for="bolzextra_quantity">Доп. больц</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="bolzextra_quantity" id="bolzextra_quantity"
                                                    class="form-control" placeholder="Длина поверхности"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['bolzextra_quantity'])) {
                                                       echo($form->arr_config['bolzextra_quantity']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>">
                        <span class="input-group-addon" id="basic-addon2">шт</span></div>
                </div>
            </div>

            <h4>Ограждение</h4>
            <div class="row form-group">
                <div class="col-xs-2">
                    <label id="basic-addon3" for="fence_lenght_stair">На лестнице</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="fence_lenght_stair" id="fence_lenght_stair"
                                                    class="form-control" placeholder="Ограждение на лестнице"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['fence_lenght_stair'])) {
                                                       echo($form->arr_config['fence_lenght_stair']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>"><span
                                class="input-group-addon" id="basic-addon2">м</span></div>
                </div>
                <div class="col-xs-2">
                    <label id="basic-addon3" for="fence_lenght_balustrade">Балюстрада</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="fence_lenght_balustrade"
                                                    id="fence_lenght_balustrade" class="form-control"
                                                    placeholder="Ограждение на балюстраде"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['fence_lenght_balustrade'])) {
                                                       echo($form->arr_config['fence_lenght_balustrade']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>">
                        <span class="input-group-addon" id="basic-addon2">м</span></div>
                </div>
            </div>

            <h4>Обшивка торца проема</h4>
            <div class="row form-group">
                <div class="col-xs-2">
                    <label id="basic-addon3" for="coverface_lenght">Длина</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="coverface_lenght" id="coverface_lenght"
                                                    class="form-control" placeholder="Длина поверхности"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['coverface_lenght'])) {
                                                       echo($form->arr_config['coverface_lenght']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>"><span
                                class="input-group-addon" id="basic-addon2">м</span></div>
                </div>
                <div class="col-xs-2">
                    <label id="basic-addon3" for="coverface_height">Ширина</label>
                </div>
                <div class="col-xs-4">
                    <div class="input-group"><input type="text" name="coverface_height" id="coverface_height"
                                                    class="form-control" placeholder="Длина поверхности"
                                                    aria-describedby="basic-addon2"
                                                    value="<?php if(isset($form->arr_config['coverface_height'])) {
                                                       echo($form->arr_config['coverface_height']);
                                                    }
                                                    else {
                                                       echo(0);
                                                    } ?>">
                        <span class="input-group-addon" id="basic-addon2">м</span></div>
                </div>
            </div>

        </form>
    </div>
   
    <div class="col-xs-4 col-md-6">
       <?php $arr_prices = Stair::get_prices();
       echo(render_prices($st_bolz_euro60->get_stair_type_literary(),$st_bolz_euro60->get_stair_material_literary(), $st_bolz_euro60->get_cost_material(),$st_bolz_euro60->get_cost_stair(), $st_bolz_euro60->get_consumption(), $arr_prices, '1'));
       echo(render_prices($st_bolz_fan54->get_stair_type_literary(),$st_bolz_fan54->get_stair_material_literary(), $st_bolz_fan54->get_cost_material(),$st_bolz_fan54->get_cost_stair(), $st_bolz_fan54->get_consumption(), $arr_prices, '2'));
       echo(render_prices($st_bolz_oak50->get_stair_type_literary(),$st_bolz_oak50->get_stair_material_literary(), $st_bolz_oak50->get_cost_material(),$st_bolz_oak50->get_cost_stair(), $st_bolz_oak50->get_consumption(), $arr_prices, '3'));
       echo(render_prices($st_riser_euro60->get_stair_type_literary(),$st_riser_euro60->get_stair_material_literary(), $st_riser_euro60->get_cost_material(),$st_riser_euro60->get_cost_stair(), $st_riser_euro60->get_consumption(), $arr_prices, '4'));
       echo(render_prices($st_riser_fan54->get_stair_type_literary(),$st_riser_fan54->get_stair_material_literary(), $st_riser_fan54->get_cost_material(),$st_riser_fan54->get_cost_stair(), $st_riser_fan54->get_consumption(), $arr_prices, '5'));
       echo(render_prices($st_riser_oak50->get_stair_type_literary(),$st_riser_oak50->get_stair_material_literary(), $st_riser_oak50->get_cost_material(),$st_riser_oak50->get_cost_stair(), $st_riser_oak50->get_consumption(), $arr_prices, '6'));
       ?>

     
        
        
        
        
    </div>
</div>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/bootstrap.min.js"></script>

<?php
function render_prices($stair_type_literary,$stair_material_literary, $cost_material, $cost_stair, $materials, $arr_prices, $ii)
{
    
    $str ='<div class="row stair_panel"><div class="col-xs-12"><h3>Лестница '.$stair_type_literary.' (' . $stair_material_literary . ') без ограждений</h3></div><div class="col-xs-6 stair_cost text-center panel-heading"><div>Стоимость материалов:<p>';
$str .= number_format($cost_material, 2, ',', ' ');
$str .='руб.</p></div></div><div class="col-xs-6 stair_cost text-center panel-heading">';
$str .=' <div>Стоимость лестницы:<p>'.number_format($cost_stair, 2, ',', ' ');
   $str .='руб.</p></div></div><div class="col-xs-12" data-toggle="collapse" data-target="#stair'.$ii.'" role="button">Показать подробности</div></div><div class="row collapse" id="stair'.$ii.'"><div class="col-xs-12 ">';
  
       
       $i = 0;
       foreach($materials as $key => $value) {
          if($value != 0) {
             $i++;
             $str .='<div class="row"><div class="col-xs-1">' . $i . '</div><div class="col-xs-5">' . $arr_prices[$key]['descr'] . '</div><div class="col-xs-2">' . $value . '</div><div class="col-xs-2">' . $arr_prices[$key]['price'] . ' руб.</div><div class="col-xs-2">' . $value * $arr_prices[$key]['price'] . ' руб.</div></div>';
          }
       }
       $str.='</div></div>';
return $str;};






$time = microtime(true) - $start;
printf('Скрипт выполнялся %.4F сек.', $time);
?>
</div>
</div>
</body>
</html>








