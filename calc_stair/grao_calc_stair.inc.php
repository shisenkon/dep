<?php
/**
 * @package          GRAO Stair Calc
 * @version          0.25
 * @author             Shibkov Konstantin
 * @link             http://grao.biz
 * @copyright          Copyright (c) 2017, Shibkov Konstantin  aka sendel (shibkov.k@gmail.com)
 * @license          GNU General Public License version 2 or later;
 * Функции: Считает стоимость: фанера54, Евростандарт 60, Дуб.
 */
// TODO считать Фанера36 на обшивку, Бук, Компримат 1п, Компримат 3п
// TODO сравнение цен из двух файлов CSV1
class Stair
{
   
   // параметры входящие через форму
   protected $step_base_lenght;
   protected $step_base_width;
   protected $step_base_quantity;
   protected $step_frieze_quantity;       //количество фризовых
   protected $step_runin_quantity;         //количество забежных
   protected $step_halfplatform_quantity;   //количество полуплощадок
   protected $step_platform_square;      //площадь площадок
   protected $step_platform_material;      //материал площадок
   protected $pendant_quantity;         //количество подвесов
   protected $boots_lenght;            //длина сапожка
   protected $supportwood_quantity;      //количество кронштейнов дерево
   protected $bolzextra_quantity;         //количество дополнительных больц
   protected $fence_lenght_stair;         //длина ограждений на лестнице
   protected $fence_lenght_balustrade;      //длина ограждений на балюстраде
   protected $coverface_lenght;         //длина обшивки торца
   protected $coverface_height;         //ширина обшивки торца
   // тип лестницы
   protected $stair_type;
   protected $stair_material;
   //вычисляемые значения
   protected $step_all_quantity; //сумма всех ступеней и площадок
   protected $hole_all;
   protected $k_increase_price = 1;
   const K_LENGHT = 950; // длина после которого начинается удорожания по длине
   const K_WIDTH  = 330; // длина после которого начинается удорожания по ширине
   
   protected $cost_material = 0;
   protected $cost_stair    = 0;
   //расходы материалов
   protected $consumption = array();
   
   //техническик параметры
   public $script_time = 0;
   public $error_msg   = '';
   //статические параметры
   static protected $arr_prices = array();
   // Version
   private $version = '0.2';
   /*
    * Contructor function
    */
   /**
    * Stair constructor.
    *
    * @param $config
    * @param $config_excl
    */
   public function __construct($config, $config_excl)
   {
      foreach($config as $key => $value) {
         if(isset($config[$key])) {
            $temp_name = $key;
            $this->$temp_name = $value;
         }
      }
      $this->consumption['plywood18'] = 0;
      $this->consumption['plywood6'] = 0;
      $this->consumption['oak50'] = 0;
      $this->consumption['dsp'] = 0;
      $this->consumption['veneer'] = 0;
      $this->consumption['pva_vintek'] = 0;
      $this->consumption['pva_kleuberit'] = 0;
      $this->consumption['hanker'] = 0;
      $this->consumption['mixer'] = 0;
      $this->consumption['sormat'] = 0;
      $this->consumption['pintle'] = 0;
      $this->consumption['beech'] = 0;
      $this->consumption['compr1p'] = 0; //в м2
      $this->consumption['compr3p'] = 0; //в м2
      $this->consumption['bolz'] = 0;
      $this->consumption['varnish'] = 0;
      $this->consumption['mordant'] = 0;
      $this->consumption['pendant'] = $this->pendant_quantity;
      $this->consumption['boots'] = $this->boots_lenght;
      if(isset($config_excl['stair_type'])) {
         $this->stair_type = $config_excl['stair_type'];
      }
      if(isset($config_excl['stair_material'])) {
         $this->stair_material = $config_excl['stair_material'];
      }
      //подсчет общего количества ступеней
      $this->step_all_quantity = $this->step_base_quantity + $this->step_frieze_quantity + $this->step_runin_quantity + $this->step_halfplatform_quantity;
      $this->k_increase_price();
      //расчет прямых ступеней
      $this->price_base_step($this->step_base_lenght, $this->step_base_width, $this->step_base_quantity, $this->stair_material);
      //расчет фризовых ступеней
      $this->price_frieze_step();
      //расчет забежных ступеней
      $this->price_base_step($this->step_base_lenght, $this->step_base_lenght, $this->step_runin_quantity * 1.6, $this->stair_material); //расчет полуплоащадок
      $this->price_base_step($this->step_base_lenght, $this->step_base_lenght, $this->step_halfplatform_quantity / 1.8, $this->stair_material);
      //расчет подступенков если лестница с подступенками
      if($this->stair_type == 'riser') {
         $this->count_risers_sormats();
      }
      $this->count_bolz();
      //расчет кол-ва отверстий
      $this->count_hole();
      //расчет кол-ва химанкера
      $this->count_hanker_mixer();
      //расчет кол-ва штырей
      $this->count_pintle();
      //материалы на кронштейн
      $this->count_supportwood();
      //материалы на сапоги
      $this->count_boots();
      //материал на площадки
      $this->count_step_platform_square();
      //материал на обшивку
      $this->count_coverface();
      //округляем все полученные величины
      foreach($this->consumption as $i => $value) {
         $this->consumption[$i] = round($value, 2);
      }
      $this->calculate_price();
      ChromePhp::groupCollapsed("$this->stair_type\t$this->stair_material\t$this->step_platform_material\t\tmat:" . number_format($this->cost_material, 2, ',', ' ') . "\tstair:" . number_format($this->cost_stair, 2, ',', ' '));
      ChromePhp::log("Коэффициент цены: \t\t" . $this->k_increase_price);
      foreach($this->consumption as $key => $value) {
         ChromePhp::log(self::$arr_prices[$key]['descr'] . " \t" . $value . " x " . self::$arr_prices[$key]['price'] . " = " . ($value * self::$arr_prices[$key]['price']));
      }
      ChromePhp::groupEnd('Тип и материал');
   }

//коэфициент удорожания по длине и ширне базовой ступени
   protected function k_increase_price()
   {
      if($this->step_base_lenght > self::K_LENGHT) {
         $this->k_increase_price = $this->step_base_lenght / self::K_LENGHT;
      }
      if($this->step_base_width > self::K_WIDTH) {
         $this->k_increase_price = $this->k_increase_price * ($this->step_base_width / self::K_WIDTH);
      }
      $this->k_increase_price = $this->k_increase_price / (sqrt($this->k_increase_price));
   }
   
   protected function price_base_step($step_lenght, $step_width, $step_count, $step_material)
   {
      //считаем стоимость базовых ступеней из Евростндарта
      //Количество фанеры на одну ступень
      if($step_material == 'euro60') {
         //Количество фанеры6 на одну ступень
         $this->consumption['plywood6'] += (2 * 1 / (1520 / ($step_width + 40))) * $step_count;
         //Количество дсп на одну ступень
         $part_of_list_dsp16_horiz_n = floor(1500 / ($step_lenght + 20));//количество рядов заготовок по длине листа
         $part_of_list_dsp16_horiz = ($part_of_list_dsp16_horiz_n) * floor(3500 / ($step_width + 10)); //количество заготовок по длине листа
         $part_of_list_dsp16_vertical_n = floor((1500 - ($part_of_list_dsp16_horiz_n * $step_lenght + 10)) / ($step_width + 10));
         $part_of_list_dsp16_vertical = $part_of_list_dsp16_vertical_n * floor(3500 / ($step_lenght + 10)); //количество заготовок по длине листа
         //Всего заготовок из одного листа ДСП
         $part_of_dsp16_1step = 3 * (1 / ($part_of_list_dsp16_horiz + $part_of_list_dsp16_vertical));
         $this->consumption['dsp'] += $part_of_dsp16_1step * $step_count; //Накапливаем количество ДСП
         //   ChromePhp::log("на прямые ступени нужно будет ".$part_of_dsp16_1step*$step_count." листа ДСП");
         $this->consumption['veneer'] += ($step_lenght + $step_width) * 60 * 2 * 2 * 0.000001 * $step_count;
         $this->consumption['pva_vintek'] += ($step_lenght * $step_width) * 4 * 0.5 * 0.000001 * $step_count;
      }
      elseif($step_material == 'fan54') {
         $this->consumption['plywood18'] += (1 / (1520 / ($step_width + 40))) * $step_count;
         $part_of_list_dsp16_horiz_n = floor(1500 / ($step_lenght + 20));//количество рядов заготовок по длине листа
         $part_of_list_dsp16_horiz = ($part_of_list_dsp16_horiz_n) * floor(1520 / ($step_width + 10)); //количество заготовок по длине листа
         $part_of_list_dsp16_vertical_n = floor((1500 - ($part_of_list_dsp16_horiz_n * $step_lenght + 10)) / ($step_width + 10));
         $part_of_list_dsp16_vertical = $part_of_list_dsp16_vertical_n * floor(1520 / ($step_lenght + 10)); //количество заготовок по длине листа
         //Всего заготовок из одного листа ДСП
         //ChromePhp::log("part_of_list_dsp16_horiz: \t\t" . $part_of_list_dsp16_horiz . ' step_width:' . $step_width . ' step_lenght:' . $step_lenght . ' step_count:' . $step_count);
         $part_of_dsp16_1step = 2 * (1 / ($part_of_list_dsp16_horiz + $part_of_list_dsp16_vertical));
         $this->consumption['plywood18'] += $part_of_dsp16_1step * $step_count; //Накапливаем количество Фанеры18
         $this->consumption['veneer'] += ($step_lenght + $step_width) * 54 * 2 * 2 * 0.000001 * $step_count;
         $this->consumption['pva_vintek'] += ($step_lenght * $step_width) * 3 * 0.5 * 0.000001 * $step_count;
      }
      elseif($step_material == 'oak50') {
         $this->consumption['oak50'] += ($step_lenght * $step_width) * 0.07 * 1.25 * 0.000001 * $step_count;
         $this->consumption['veneer'] += ($step_lenght + $step_width) * 54 * 2 * 2 * 0.000001 * $step_count;
         $this->consumption['pva_kleuberit'] += ($step_lenght * $step_width) * 3 * 0.5 * 0.000001 * $step_count;
      }
      $this->consumption['mordant'] += (($step_lenght * $step_width) * 2 + (60 * 2 * ($step_lenght + $step_width))) * 0.000001 * 0.05 * $step_count;
      $this->consumption['varnish'] += (($step_lenght * $step_width) * 2 + (60 * 2 * ($step_lenght + $step_width))) * 0.000001 * 0.5 * $step_count;
   }
   
   protected function price_frieze_step()
   {
      $frieze_lenght = $this->step_base_lenght;
      $k_add = 1;
      for($i = 0; $i < $this->step_frieze_quantity; $i++) {
         switch($i) {
            case 0:
               $k_add = 1.25;
               break;
            case 1:
               $k_add = 1.15;
               break;
            case 2:
               $k_add = 1.1;
               break;
            default:
               $k_add = 1.1;
         }
//ChromePhp::log("Кол-во фризовых ".$i." k_add:".$k_add);
         $this->price_base_step($this->step_base_lenght * $k_add, $this->step_base_width * $k_add, 1, $this->stair_material);
      }
   }
   
   protected function count_bolz()
   {
      if($this->stair_type == 'bolz') {
         $this->consumption['bolz'] = $this->step_base_quantity + $this->step_frieze_quantity + $this->step_runin_quantity * 2 + $this->step_halfplatform_quantity * 2;
      }
      $this->consumption['bolz'] += $this->bolzextra_quantity;
   }
   
   protected function count_risers_sormats()
   {
      $temp_material = $this->stair_material;
      $this->price_base_step($this->step_base_lenght, 140, $this->step_base_quantity, $this->stair_material, 'fan54');
      $this->price_base_step($this->step_base_lenght, 140, $this->step_frieze_quantity * 1.4, $this->stair_material, 'fan54');
      $this->price_base_step($this->step_base_lenght, 140, $this->step_runin_quantity * 1.6, $this->stair_material, 'fan54');
      $this->consumption['sormat'] += $this->step_base_quantity * 4 + $this->step_frieze_quantity * 6 + $this->step_runin_quantity * 5 + $this->step_halfplatform_quantity * 5 + $this->step_platform_square * 4;
   }
   
   protected function count_hole()
   {
      $this->hole_all = $this->step_base_quantity * 2 + $this->step_frieze_quantity * 2 + $this->step_runin_quantity * 3 + $this->step_halfplatform_quantity * 3 + $this->pendant_quantity + $this->supportwood_quantity * 3;
   }
   
   protected function count_pintle()
   {
      $this->consumption['pintle'] = $this->step_base_quantity * 2 + $this->step_frieze_quantity * 2 + $this->step_runin_quantity * 3 + $this->step_halfplatform_quantity * 3 + $this->supportwood_quantity * 3;
   }
   
   protected function count_hanker_mixer()
   {
      $this->consumption['hanker'] = ceil($this->hole_all / 6);
      $this->consumption['mixer'] = $this->consumption['hanker'] * 2;
   }
   
   protected function count_supportwood()
   {
      $this->price_base_step(800, 300, $this->supportwood_quantity, 'fan54');
   }
   
   protected function count_boots()
   {
      $this->consumption['beech'] += $this->boots_lenght * (0.02 * 0.06 * 2);
   }
   
   protected function count_step_platform_square()
   {
      $this->consumption['veneer'] += ($this->step_platform_square) * 4 * 0.054 * 2;
      switch($this->step_platform_material) {
         case 'Fan54':
            $this->consumption['plywood18'] += 2 * (($this->step_platform_square / (1.5 * 1.5)) * 3);
            $this->consumption['pva_vintek'] += ($this->step_platform_square) * 3 * 0.5;
            break;
         case 'Fan36':
            $this->consumption['plywood18'] += 2 * (($this->step_platform_square / (1.5 * 1.5)) * 2);
            $this->consumption['pva_vintek'] += ($this->step_platform_square) * 2 * 0.5;
            break;
         case 'Compromat1p':
            $this->consumption['plywood18'] += 2 * ($this->step_platform_square / (1.5 * 1.5));
            $this->consumption['compr1p'] += $this->step_platform_square * 1.5;
            $this->consumption['pva_vintek'] += ($this->step_platform_square) * 2 * 0.5;
            break;
         case 'Compromat3p':
            $this->consumption['plywood18'] += 2 * ($this->step_platform_square / (1.5 * 1.5));
            $this->consumption['compr3p'] += $this->step_platform_square * 1.5;
            $this->consumption['pva_vintek'] += ($this->step_platform_square) * 2 * 0.5;
            break;
         default:
            ChromePhp::error("Не удалось определить материал площадки! \n");
            $this->error_msg .= 'Не удалось определить материал площадки!<br>';
      }
   }
   
   protected function count_coverface()
   {
      $this->consumption['plywood18'] += $this->coverface_height * $this->coverface_lenght * 1.25;
      $this->consumption['beech'] += ($this->coverface_height + $this->coverface_lenght) * 2 * 0.02 * 0.06;
   }
   
   static public function read_prices($file)
   {
      try {
         $csv_prices = new CSV_grao($file); //Открываем наш csv
         $get_csv = $csv_prices->getCSV();
         //var_dump($get_csv);
         $prices_format = array();
         foreach($get_csv[1] as $index => $value) {
            //echo("$value, ");
            $prices_format[$value] = array('descr' => $get_csv[0][$index], 'price' => $get_csv[2][$index], 'work' => $get_csv[3][$index]);
         }
         self::$arr_prices = $prices_format;
      } catch(Exception $e) { //Если csv файл не существует, выводим сообщение
         echo "Ошибка: " . $e->getMessage();
      }
   }
   
   //отдаем во вне требуемые переменные
   static public function get_prices() { return self::$arr_prices; }
   
   public function get_consumption() { return $this->consumption; }
   
   public function get_stair_material() { return $this->stair_material; }
   
   public function get_stair_type() { return $this->stair_type; }
   
   public function get_stair_type_literary()
   {
      if($this->stair_type == 'bolz') {
         return 'на больцах';
      }
      elseif($this->stair_type == 'riser')
         return 'с подступенками';
   }
   
   public function get_stair_material_literary()
   {
      if($this->stair_material == 'euro60') {
         return 'Евростандарт 60мм';
      }
      elseif($this->stair_material == 'fan54') {
         return 'Многослойная 54мм';
      }
      elseif($this->stair_material == 'oak50') {
         return 'Дуб ц/м 50мм';
      }
   }
   
   public function get_cost_material() { return $this->cost_material; }
   
   public function get_cost_stair() { return $this->cost_stair; }
   
   protected function calculate_price()
   {
      foreach($this->consumption as $key => $value) {
         $this->cost_material += $value * self::$arr_prices[$key]['price'];
         $this->cost_stair += $value * self::$arr_prices[$key]['price'] * self::$arr_prices[$key]['work'] * $this->k_increase_price;
      }
      $this->cost_material = round($this->cost_material, 2);
      $this->cost_stair = round($this->cost_stair, 2);
   }
}