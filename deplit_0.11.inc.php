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
	private $seed;

	private $seed_arr = array();
	// тип поверхности (форма)
	protected $surface_type;
	// есть ли плинтус (форма)
	protected $plintus100;
	protected $seed_fix;
	
	// дополнительное значение высота для треугольника
	protected $surface_triangle_H;

	// дополнительное значение отступа вершины для треугольника
	protected $surface_triangle_L;
	// Google Analytics ID
	protected $analytics_id;

	// Version
	private $version = '0.1';
	
	// Seed lenght
	private $seed_lenght = 15;
	private $plitka_thick_first_lastline;
	
	private $text_visual_horiz = "";
	private $text_visual = "";
	private $stop_i;
	

	//забиваем размеры плиток, в массивах даны длины, толщины по порядку 15, 20, 25
	private $plitka40 = array(150, 250, 350); 		//длины плиток шириной 40мм
	private $plitka60 = array(200, 300, 350);		//длины плиток шириной 60мм
	private $plitka100 = array(200, 300, 350);		//длины плиток шириной 100мм
	private $plitka_thickness = array(15, 20, 25); 	//тощины плиток, соответсвуют длинам плиток
	private $plitka_width = array(40, 60, 100); 	//ширина плиток
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
			$this->seed_fix = $config['seed_fix'];
		} else {$this->seed_fix = "off";}
		$this->stop_i = 0;
		$this->generate_seed();	
	}
	
	public function generate_seed()
	{
		$this->seed = "";	
		$x = 1;
		$last_seed_n = -1;
		
		do 	{
			$rand_seed_n = mt_rand(0, 2);
			if ($last_seed_n != $rand_seed_n) {
				$this->seed .= " ".$rand_seed_n;
				$last_seed_n = $rand_seed_n;
				$x++;
			} 
			} while ($x<=$this->seed_lenght);
			
	//if ($this->seed_fix <> "off") {$this->seed = $this->seed_fix;}
		echo "<div>".$this->seed."</div>";
		//$this->seed = "2 1 0 2 1 2 1 0 1 0 1 0 1 0 1 0 2 0 2 0 1 0 2 0 2 1 0 1 0 1 0 2 1 0 1 2 0 1 0 1 0 1 2 0 2 0 1 0 1 2 1 0 1 0 1 2 0 1 2 0 2 1 0 1 0 2 0 1 0";
		$this->seed_arr = explode(" ", trim($this->seed));
		//проверяем одинаковость первого и посл элемента
		if ($this->seed_arr[0]==end($this->seed_arr) & $this->stop_i < 10) {$this->stop_i++;$this->generate_seed();}
	}
	public function fill_horizontal_line($size, $is_plintus100)
	{
		$trail_lenght = $this->surface_lenght;
		$plitka_this_lenght = 0;
		$plitka_this_thickness = 0;
		$this->text_visual_horiz = "";
		$this->text_visual_horiz .="<div class=\"deplit_row\">";
		 while ($trail_lenght > 0){
			//echo $trail_lenght;
			
			
			if ($this->i_plitka >= $this->seed_lenght) {$this->i_plitka=0;}
			$plitka_this_thickness = $this->plitka_thickness[$this->seed_arr[$this->i_plitka]]; 
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
						if ($plitka_this_lenght == $this->plitka100[0]) $this->plitka_count[6]++;
						if ($plitka_this_lenght == $this->plitka100[1]) $this->plitka_count[7]++;
						if ($plitka_this_lenght == $this->plitka100[2]) $this->plitka_count[8]++;
					}
				}
				
				
				//вывод визуализированной раскладки
				$this->text_visual_horiz .= "<div class=\"pl".$size."-".$plitka_this_lenght."-".$plitka_this_thickness."\"";
				if ($trail_lenght < 0) {$this->text_visual_horiz .= " id=".$trail_lenght." style=\"width:".(($plitka_this_lenght + $trail_lenght)/2)."px;\"";}
				$this->text_visual_horiz .= ">".$size."х".$plitka_this_lenght."x".$plitka_this_thickness."</div>";
				
				

			if ($is_plintus100 == 0) {$this->i_plitka++;}
			
			
		}

		$this->text_visual_horiz .= "</div>";
	//echo ($this->text_visual_horiz);
	}
	
	public function fill_vertical_line()
	{
		$trail_height = $this->surface_height;
		$i=0;
		$plintus100_visual = "";
		
		
		echo "<div class=\"deplit_block\" style=\"width:".($this->surface_lenght/2+8)."px;position:relative;margin: 50px auto 20px auto;border: 4px solid;\">";
		echo "<div class=\"deplit_show_lenght\">".$this->surface_lenght."x".$this->surface_height."мм.</div>";
		echo "<div class=\"deplit_surface\" style=\"width:".($this->surface_lenght/2+8)."px;top:0;right:0;\">";
		//проверка и заполнение плинтуса 100мм
		if ($this->plintus100 == "on"){
			$trail_height -= $this->plitka_width[2];
			$this->fill_horizontal_line($this->plitka_width[2], 1);
			$plintus100_visual = $this->text_visual.$this->text_visual_horiz;
			}
		//Заполнение по вертикале горизонтальномы полосами
		while ($trail_height > 0) {
			if ($i == 3) {$i=0;}
			$trail_height -= $this->plitka_width[$i];
			$this->fill_horizontal_line($this->plitka_width[$i], 0);
			$this->text_visual = $this->text_visual.$this->text_visual_horiz;
			$i++;
			} 
			$this->text_visual = $this->text_visual.$plintus100_visual;
			echo ($this->text_visual);
		echo "</div></div>";	
			echo "<div class=\"row plitka-count\">";
			echo "<div class=\"col-md-4\"> плитка 40х150х15 - ".$this->plitka_count[0]."шт.";
			echo "<br> плитка 40х250х20 - ".$this->plitka_count[1]."шт.";
			echo "<br> плитка 40х350х25 - ".$this->plitka_count[2]."шт.</div>";
			echo "<div class=\"col-md-4\"> плитка 60х200х15 - ".$this->plitka_count[3]."шт.";
			echo "<br> плитка 60х300х20 - ".$this->plitka_count[4]."шт.";
			echo "<br> плитка 60х350х25 - ".$this->plitka_count[5]."шт.</div>";
			echo "<div class=\"col-md-4\"> плитка 100х200х15 - ".$this->plitka_count[6]."шт.";
			echo "<br> плитка 100х300х20 - ".$this->plitka_count[7]."шт.";
			echo "<br> плитка 100х350х25 - ".$this->plitka_count[8]."шт.</div>";
			
			$all_deplit = 0;
			foreach ($this->plitka_count as $value) 
			{
			$all_deplit += $value;
			}
			
			echo "<div class=\"col-md-12\"><b>всего плиток - ".$all_deplit."шт.</b></div></div>";
		
	}
}
?>