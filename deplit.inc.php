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
	
	// дополнительное значение высота для треугольника
	protected $surface_triangle_H;

	// дополнительное значение отступа вершины для треугольника
	protected $surface_triangle_L;
	// Google Analytics ID
	protected $analytics_id;

	// Version
	private $version = '0.1';
	
	// Seed lenght
	private $seed_lenght = 19;
	

	//забиваем размеры плиток, в массивах даны длины, толщины по порядку 15, 20, 25
	private $plitka40 = array(150, 250, 350); 		//длины плиток шириной 40мм
	private $plitka60 = array(200, 300, 350);		//длины плиток шириной 60мм
	private $plitka100 = array(200, 300, 350);		//длины плиток шириной 100мм
	private $plitka_thickness = array(15, 20, 25); 	//тощины плиток, соответсвуют длинам плиток
	private $plitka_width = array(40, 60, 100); 	//ширина плиток
	private $plitka_count = array(0, 0, 0, 0, 0, 0, 0, 0, 0); 	//количество плиток
	private $i_plitka = 0;
	
	private $seed_arr_fix40 	= array("2 0 1 0 2 1 2 0 1 2 0 1 0 2 1 2 0 2 0","0 1 0 2 1 0 2 1 0 2 1 2 0 2 1 0 2 1 2","1 0 1 2 0 1 0 2 1 0 2 1 2 1 0 2 0 1 0");
	private $seed_arr_fix60 	= array("0 1 2 1 0 2 0 1 0 2 0 2 1 0 1 2 0 1 2","2 1 0 2 1 0 2 1 0 1 2 0 1 2 0 1 2 0 1","2 0 1 2 0 1 2 1 0 1 0 2 1 2 0 1 0 2 0");
	private $seed_arr_fix100 	= array("1 2 0 2 1 0 2 1 0 1 0 1 2 0 2 0 1 0 2","0 2 0 1 0 2 1 0 2 1 0 1 2 0 1 2 0 1 2","1 2 0 1 2 1 0 2 1 2 0 1 0 1 2 1 0 2 0");
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
	}
	
	public function generate_seed($id, $width)
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
		//$this->seed = "2 1 0 2 1 2 1 0 1 0 1 0 1 0 1 0 2 0 2 0 1 0 2 0 2 1 0 1 0 1 0 2 1 0 1 2 0 1 0 1 0 1 2 0 2 0 1 0 1 2 1 0 1 0 1 2 0 1 2 0 2 1 0 1 0 2 0 1 0";
		if ($width == 40) {$this->seed = $this->seed_arr_fix40[$id];}
		if ($width == 60) {$this->seed = $this->seed_arr_fix60[$id];}
		if ($width == 100) {$this->seed = $this->seed_arr_fix100[$id];}
		$this->seed_arr = explode(" ", trim($this->seed));
		if ($this->seed_arr[0]==end($this->seed_arr)) {echo "||ПОВТОРЯЕТСЯ||";}

	}
	public function fill_horizontal_line($size)
	{
		$trail_lenght = $this->surface_lenght;
		$deplits_in_row = 0;
		$plitka_this_lenght = 0;
		$this->i_plitka=0;
		echo "<div class=\"deplit_row\">";
		 while ($trail_lenght > 0){
			//echo $trail_lenght;
			if ($this->i_plitka >= $this->seed_lenght) {$this->i_plitka=0;}
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
				{$plitka_this_lenght = $this->plitka100[$this->seed_arr[$this->i_plitka]];
				$trail_lenght -= $plitka_this_lenght;
				if ($plitka_this_lenght == $this->plitka100[0]) $this->plitka_count[6]++;
				if ($plitka_this_lenght == $this->plitka100[1]) $this->plitka_count[7]++;
				if ($plitka_this_lenght == $this->plitka100[2]) $this->plitka_count[8]++;
				}
				//вывод визуализированной раскладки
				echo "<div class=\"pl".$size."-".$plitka_this_lenght."-".$this->plitka_thickness[$this->seed_arr[$this->i_plitka]]."\"";
				if ($trail_lenght < 0) {echo " id=".$trail_lenght." style=\"width:".(($plitka_this_lenght + $trail_lenght)/2)."px;\"";}
				echo ">"./*$size."х".$plitka_this_lenght."x".$this->plitka_thickness[$this->seed_arr[$this->i_plitka]].*/" </div>";
				

			$this->i_plitka++;
			$deplits_in_row++;
			
		}
		
		//if (fmod($this->seed_lenght, $deplits_in_row))
		echo "</div>";
	}
	
	public function fill_vertical_line()
	{
		$trail_height = $this->surface_height;
		$i=0;
		$i_seed=0;

		echo "<div class=\"deplit_block\" style=\"width:".($this->surface_lenght/2)."px;position:relative;\">";
		echo "<div class=\"deplit_surface\" style=\"width:".($this->surface_lenght/2)."px;border: 4px double black;;top:0;right:0;\">";
		do {
			if ($i == 3) {$i=0;$i_seed++;}
			if ($i_seed == 3) {$i_seed=0;}
			$trail_height -= $this->plitka_width[$i];
			//echo ($i."-".$i_seed."-". $this->plitka_width[$i]);
			$this->generate_seed($i_seed, $this->plitka_width[$i]);
			
			
			$this->fill_horizontal_line($this->plitka_width[$i]);
			$i++;
			
			
			} while ($trail_height > 0);
		echo "</div></div>";	
			echo "<br> плитка 40х150х15 - ".$this->plitka_count[0]."шт.";
			echo "<br> плитка 40х250х20 - ".$this->plitka_count[1]."шт.";
			echo "<br> плитка 40х350х25 - ".$this->plitka_count[2]."шт.";
			echo "<br> плитка 60х200х15 - ".$this->plitka_count[3]."шт.";
			echo "<br> плитка 60х300х20 - ".$this->plitka_count[4]."шт.";
			echo "<br> плитка 60х350х25 - ".$this->plitka_count[5]."шт.";
			echo "<br> плитка 100х200х15 - ".$this->plitka_count[6]."шт.";
			echo "<br> плитка 100х300х20 - ".$this->plitka_count[7]."шт.";
			echo "<br> плитка 100х350х25 - ".$this->plitka_count[8]."шт.";
			
			$all_deplit = 0;
			foreach ($this->plitka_count as $value) 
			{
			$all_deplit += $value;
			}
			echo "<br> всего плиток - ".$all_deplit."шт.";
		
	}
}
?>