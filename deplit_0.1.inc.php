<?php
/**
 * @package 			Deplit Modern Calculator
 * @version 			0.1
 * @author 				Shibkov Konstantin
 * @link 				http://deplit.ru
 * @copyright 			Copyright (c) 2017, Shibkov Konstantin  aka sendel (shibkov.k@gmail.com)
 * @license 			GNU General Public License version 2 or later;
 */

 class Deplit_calc
{
	// тип плитки
	protected $deplit_type;
	// высота площади
	protected $surface_height;
	// высота длина
	protected $surface_lenght;
	// зерно для расчета
	public $seed; //для вывода зерна в любом месте
	private $seed_arr = array();
	// тип поверхности (форма)
	protected $surface_type;
	// есть ли плинтус (форма)
	protected $plintus100;
	protected $seed_fix;
	protected $seed_fix_check;

	// дополнительное значение высота для треугольника
	protected $surface_triangle_H;

	// дополнительное значение отступа вершины для треугольника
	protected $surface_triangle_L;
	// Google Analytics ID
	protected $analytics_id;

	// Version
	private $version = '0.1';

	// Seed lenght
	private $seed_lenght;
	private $plitka_thick_first_lastline = "";

	private $text_render_horiz = "";
	private $text_render = "";
	public $render_manual = "";
	private $stop_i;
	private $error_txt = array(); //сюда складываем сообщения ошибок
	private $manual_montage = array();


	//забиваем размеры плиток, в массивах даны длины, толщины по порядку 15, 20, 25
	private $plitka40 = array(); 		//длины плиток шириной 40мм
	private $plitka60 = array();		//длины плиток шириной 60мм
	private $plitka100 = array();		//длины плиток шириной 100мм
	private $plitka_thickness = array(15, 20, 25); 	//тощины плиток, соответсвуют длинам плиток
	private $plitka_width = array(); 	//ширина плиток
	private $plitka_count = array(0, 0, 0, 0, 0, 0, 0, 0, 0); 	//количество плиток
	private $i_plitka = 0;

	/**
	 * Contructor function
	 *
	 * @param string $deplit_type default:modern
	 * @param string $surface_height
	 * @param string $surface_width
	 * @param string $surface_type default:rectangle
	 * @return string
	 */
	public function __construct($config)
	{
		if (isset($config['deplit_type'])) {
			$this->deplit_type = $config['deplit_type'];
		} else {$this->surface_type = "modern";}
		if (isset($config['surface_height'])) {
			$this->surface_height = $config['surface_height'];
		}
		if (isset($config['surface_lenght'])) {
			$this->surface_lenght = $config['surface_lenght'];
		}
		if (isset($config['surface_type'])) {
			$this->surface_type = $config['surface_type'];
		} else {$this->surface_type = "rectangle";}
		if (isset($config['plintus100'])) {
			$this->plintus100 = $config['plintus100'];
		} else {$this->plintus100 = "off";}
		if (isset($config['seed_fix'])) {
			$this->seed_fix = trim($config['seed_fix']);
		} else {$this->seed_fix = "off";}
		if (isset($config['seed_fix_check'])) {
			$this->seed_fix_check = $config['seed_fix_check'];
		} else {$this->seed_fix_check = "off";}
		$this->stop_i = 0;
		if ($this->seed_fix_check == "on") {
			$this->seed_lenght = strlen($this->seed_fix);
		} else{$this->seed_lenght=mt_rand(15,20);}
		if ($this->deplit_type == "loft"){
			$this->plitka40 = array(100, 100, 100); 		//длины плиток шириной 100мм
			$this->plitka60 = array(100, 100, 100);		//длины плиток шириной 100мм
			$this->plitka100 = array(100, 100, 100);		//длины плиток шириной 100мм
			$this->plitka_width = array(100, 100, 100); 	//ширина плиток
			}
		elseif ($this->deplit_type == "modern"){
			$this->plitka40 = array(150, 250, 350); 		//длины плиток шириной 40мм
			$this->plitka60 = array(200, 300, 350);		//длины плиток шириной 60мм
			$this->plitka100 = array(200, 300, 350);		//длины плиток шириной 100мм
			$this->plitka_width = array(40, 60, 100); 	//ширина плиток
		}
		}


	public function generate_seed()
	{
		do {
			$this->seed = "";
		$x = 1;
		$last_seed_n = -1;
		$seed_count_component = array(0,0,0);
		//генерируем случайную строку из (012)
		do 	{
			$rand_seed_n = mt_rand(0, 1);
			if ($last_seed_n != $rand_seed_n) {
				if ($x % 4 == 0) {$rand_seed_n = 2;}
				$this->seed .= $last_seed_n = $rand_seed_n; //добавляем число к строке зерна
				//if ((($seed_count_component[0]+$seed_count_component[1]) > $seed_count_component[2]*2) || $x == $this->seed_lenght-1)
				//	{$x++;}
				$x++;
			}
			} while ($x<=$this->seed_lenght);

		//проверяем правильность введеного определенного набора значений
	if ($this->seed_fix_check == "on") {
		//echo "Проверяется строка: ".$this->seed_fix."<br>";
			//Проверка длины строки
		/*	if (strlen($this->seed_fix) <> $this->seed_lenght) {

				$this->error_txt[] = "Длина строки набора не равна ".$this->seed_lenght." символам!";}*/
				//Проверка на верные символы (разрешены только 0 1 2)
			if (!(preg_match("/^([012]*)$/", $this->seed_fix))) {
				$this->error_txt[] = "В строке должны быть только символы 0 1 2!";}
			if (count($this->error_txt) == 0) {
				$this->seed = $this->seed_fix;
			} else {return false;}}

		//разбиваем строку на массив
		$this->seed_arr = str_split($this->seed);
		//проверяем одинаковость первого и посл элемента
	$this->stop_i++;}
		while ($this->seed_arr[0]==end($this->seed_arr) & $this->stop_i < $this->seed_lenght*2);
		//print_r($this->seed);
		if ($this->stop_i < $this->seed_lenght) return true;

	}

	private function check_end_of_seed_arr(){
		if ($this->i_plitka >= $this->seed_lenght) {$this->i_plitka=0;}
	}
	public function fill_horizontal_line($size, $is_plintus100)
	{

		$trail_lenght = $this->surface_lenght;
		$plitka_this_lenght = 0;
		$plitka_this_thickness = 0;
		$manual_montage_horiz_line = "";
		$this->text_render_horiz = "";
		$this->text_render_horiz .="<div class=\"deplit_row fadein\">";
		//проверка на сопадение высоты первых плиток
		 while ($trail_lenght > 0){
			//echo $trail_lenght;
		 	$this->check_end_of_seed_arr();
			$plitka_this_thickness = $this->plitka_thickness[$this->seed_arr[$this->i_plitka]];

				//проверка на повторяемость толщины первых элементов
			if ($trail_lenght==$this->surface_lenght)
			{
				if ($this->plitka_thick_first_lastline == $plitka_this_thickness)
						{$this->i_plitka++;
						$this->check_end_of_seed_arr();
						$plitka_this_thickness = $this->plitka_thickness[$this->seed_arr[$this->i_plitka]];
					}
		 	$this->plitka_thick_first_lastline = $plitka_this_thickness; }


			//echo $this->seed_arr[$i];
			if ($size == 40)
				{$plitka_this_lenght = $this->plitka40[$this->seed_arr[$this->i_plitka]];
				$trail_lenght -= $plitka_this_lenght;
				if ($plitka_this_lenght == $this->plitka40[0]) $this->plitka_count[0]++; // +1 к счетчику плитки
				if ($plitka_this_lenght == $this->plitka40[1]) $this->plitka_count[1]++;
				if ($plitka_this_lenght == $this->plitka40[2]) $this->plitka_count[2]++;
				}
			if ($size == 60)
				{$plitka_this_lenght = $this->plitka60[$this->seed_arr[$this->i_plitka]];
				$trail_lenght -= $plitka_this_lenght;
				if ($plitka_this_lenght == $this->plitka60[0]) $this->plitka_count[3]++;
				if ($plitka_this_lenght == $this->plitka60[1]) $this->plitka_count[4]++;
				if ($plitka_this_lenght == $this->plitka60[2]) $this->plitka_count[5]++;
				}
			if ($size == 100)
				{
					//обработка правила нижнего плинтуса
					if ($is_plintus100 == 1)
					{
						$plitka_this_lenght = $this->plitka100[2];
						$trail_lenght -= $plitka_this_lenght;
						$this->plitka_count[8]++;
						$plitka_this_thickness = $this->plitka_thickness[2];
					}
					else
					{
						$plitka_this_lenght = $this->plitka100[$this->seed_arr[$this->i_plitka]];
						$trail_lenght -= $plitka_this_lenght;
						if ($plitka_this_thickness == $this->plitka_thickness[0]) $this->plitka_count[6]++;
						if ($plitka_this_thickness == $this->plitka_thickness[1]) $this->plitka_count[7]++;
						if ($plitka_this_thickness == $this->plitka_thickness[2]) $this->plitka_count[8]++;
					}
				}

				//код для сборки

				//вывод визуализированной раскладки
				$this->text_render_horiz .= "<div class=\"pl".$size."-".$plitka_this_lenght."-".$plitka_this_thickness." pl_wide".$size." deplit_cell";

				if ($trail_lenght < 0)
					{$this->text_render_horiz .= " pl_last_horiz";}
				else
					{$this->text_render_horiz .=  " pl_len".$plitka_this_lenght;}
				$this->text_render_horiz .= "\"";
				if ($trail_lenght < 0) {$this->text_render_horiz .= " id=".$trail_lenght." style=\"width:".($plitka_this_lenght + $trail_lenght)."px;\"";}
				$this->text_render_horiz .= ">";
		//		$this->text_render_horiz .= $size."х".$plitka_this_lenght."x".$plitka_this_thickness;
				$this->text_render_horiz .= "</div>";

				$manual_montage_horiz_line .= $plitka_this_thickness." ";

			if ($is_plintus100 == 0) {$this->i_plitka++;}
		}
		$this->text_render_horiz .= "</div>";
		array_unshift($this->manual_montage, $size."=".$manual_montage_horiz_line);
	}

	public function fill_vertical_line()
	{
		if (!($this->generate_seed())) {print_r($this->error_txt); return false;}
		$trail_height = $this->surface_height;
		$i=2; //начинаем по умолчанию с плитки 100мм шириной
		$plintus100_render = "";
		//проверка и заполнение плинтуса 100мм
		if ($this->plintus100 == "on"){
			$trail_height -= $this->plitka_width[2];
			$this->fill_horizontal_line($this->plitka_width[2], 1);
			$plintus100_render = $this->text_render.$this->text_render_horiz;
			$i=0; //если есть плинтус начинаем с плитки 40мм
			}
		//Заполнение по вертикале горизонтальномы полосами
		while ($trail_height > 0) {
			if ($i == 3) {$i=0;}
			$trail_height -= $this->plitka_width[$i];
			$this->fill_horizontal_line($this->plitka_width[$i], 0);
			$this->text_render = $this->text_render_horiz.$this->text_render;
			$i++;
			}
			$this->text_render = $this->text_render.$plintus100_render;

			$s_surface = round(($this->surface_lenght*$this->surface_height)/10000)/100;
			$this->text_render = "<div class=\"deplit_show_lenght\">" . $this->surface_lenght."x".$this->surface_height . "мм. (" . $s_surface . " м2)</div><div class=\"deplit_block\" style=\"width:" . ($this->surface_lenght+8) . "px;position:relative;margin: 40px auto 20px auto;border: 4px solid;\"><div class=\"deplit_surface\" style=\"width:" . ($this->surface_lenght+8) . "px;top:0;right:0;\">" . $this->text_render . "</div></div>";
//вывод инструкции по сборке
$this->render_manual .="</div><div class=\"spoiler-wrapper\"><div class=\"spoiler-head\"><a href=\"javascript:void(0);\" class=\"btn btn-primary btn-block btn-success btn-lg\">Показать/Скрыть инструкцию и кол-во плиток</a></div><div class=\"spoiler-body row plitka-count\"><div class=\"col-md-12\">";

//вывод инструкции к монтажу
foreach ($this->manual_montage as $value)
			{
			$this->render_manual .= "\n".$value."<br>";
			}

			//вывод количества плиток

		if ($this->deplit_type == "loft"){
$this->render_manual .="</div><div class=\"row\" style=\"margin-top:20px;\"><div class=\"col-md-4 col-xs-12\"<br><br>плитка 100х100х15 - ".$this->plitka_count[6]."шт.";
			$this->render_manual .="<br> плитка 100х100х20 - ".$this->plitka_count[7]."шт.";
			$this->render_manual .="<br> плитка 100х100х25 - ".$this->plitka_count[8]."шт.</div>";
			}
		elseif ($this->deplit_type == "modern"){
			$this->render_manual .= "</div><div class=\"row\" style=\"margin-top:20px;\"><div class=\"col-md-4 col-xs-12\"> плитка 40х150х15 - ".$this->plitka_count[0]."шт.";
			$this->render_manual .= "<br>плитка 40х250х20 - ".$this->plitka_count[1]."шт.";
			$this->render_manual .= "<br> плитка 40х350х25 - ".$this->plitka_count[2]."шт.</div>";
			$this->render_manual .= "<div class=\"col-md-4 col-xs-12\"> плитка 60х200х15 - ".$this->plitka_count[3]."шт.";
			$this->render_manual .= "<br> плитка 60х300х20 - ".$this->plitka_count[4]."шт.";
			$this->render_manual .= "<br> плитка 60х350х25 - ".$this->plitka_count[5]."шт.</div>";
			$this->render_manual .= "<div class=\"col-md-4 col-xs-12\"> плитка 100х200х15 - ".$this->plitka_count[6]."шт.";
			$this->render_manual .= "<br> плитка 100х300х20 - ".$this->plitka_count[7]."шт.";
			$this->render_manual .= "<br> плитка 100х350х25 - ".$this->plitka_count[8]."шт.</div>";
		}
			$all_deplit = 0;
			foreach ($this->plitka_count as $value)
			{
			$all_deplit += $value;
			}

			$this->render_manual .= "<div class=\"col-md-12\"><b>всего плиток - ".$all_deplit."шт.</b></div></div></div></div>";



			echo ($this->text_render);


	}
}
?>