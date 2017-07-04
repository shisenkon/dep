	<form method="post" action=""><div class="row">
		<div class="col-md-2 save_list">
				<label id="basic-addon3" for="surface_lenght" >Длина поверхности</label><div class="input-group"><input type="text" name="surface_lenght"  id="surface_lenght" class="form-control" placeholder="Длина поверхности" aria-describedby="basic-addon2" value="<?php echo ($form->surface_lenght); ?>">
				<span class="input-group-addon" id="basic-addon2">мм</span>

			</div>

				<label id="basic-addon3">Высота поверхности</label><div class="input-group"><input type="text" name="surface_height" id="surface_height" class="form-control" placeholder="Высота поверхности" aria-describedby="basic-addon2" value="<?php echo ($form->surface_height); ?>">
				<span class="input-group-addon" id="basic-addon2">мм</span>
			</div><br>

			<label>
            <input type="checkbox" name="plintus100" id="plintus100" <?php if ($form->plintus100 == "on") echo "checked"; ?>> Нижний плинтус 100мм
          </label>
<br><br>
			<div class="input-group">
				<label>
            <input type="checkbox" name="seed_fix_check" id="seed_fix_check" <?php if ($form->seed_fix_check == "on") echo "checked"; ?>> Заполнение по значению сборки
          </label>
          <div class="input-group" id="seed_fix_div" <?php if ($form->seed_fix_check == "on") echo ('style="display:table"'); else echo ('style="display:none"');?>>
				<input type="text" name="seed_fix" id="seed_fix" class="form-control" placeholder="Вставьте значение сборки" aria-describedby="basic-addon2" value="<?php echo ($form->seed_fix); ?>" >
			</div><br><br>

				<button class="btn btn-primary btn-block btn-success btn-lg" type="submit">Посчитать</button>
				</form><a class="btn btn-primary btn-block btn-success btn-lg" id="change_bg">изменить фон</a>


		</div>

</div>
	<div class="col-md-8 container_render_pl">

<?php
	$deplit->fill_vertical_line(); //выводим вгенерированную плитку
?>

<div id="this_seed" style="display: none"><?php echo $deplit->seed; ?></div>

</div>
<div class="col-md-2 col-xs-12 save_list"><div><a class="btn btn-primary btn-success  btn-block save_param" id="save_param">Сохранить<br>результат</a>
				<a class="btn btn-success btn-block show_param" id="show_param" style="display:none">Показать</a>
				<a class="btn btn-primary btn-danger btn-block del_param" id="del_param">Очистить список</a></div><div class="render_cookie row"></div>
</div>


<?php
	echo $deplit->render_manual; //выводим инструкцию и список плиток
?>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.cookie.js"></script>
	<script src="js/deplit_cookie.js"></script>
	</body>
  </html>