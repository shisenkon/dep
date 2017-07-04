<?php

/**
 * @package          GRAO Stair Calc
 * @version          0.1
 * @author             Shibkov Konstantin
 * @link             http://grao.biz
 * @copyright          Copyright (c) 2017, Shibkov Konstantin  aka sendel (shibkov.k@gmail.com)
 * @license          GNU General Public License version 2 or later;
 */
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
   protected $k_lenght         = 950; // длина после которого начинается удорожания по длине
   protected $k_width          = 330; // длина после которого начинается удорожания по ширине
   //стоимость материалов для деталей
   protected $bolz_cost = 278.4;
   //расходы материалов
   protected $consumption_bolz       = 0;
   protected $consumption_plywood18  = 0;
   protected $consumption_plywood6   = 0;
   protected $consumption_oak        = 0;
   protected $consumption_dsp        = 0;
   protected $consumption_veneer     = 0;
   protected $consumption_pva_vintek = 0;
   protected $consumption_hanker     = 0;
   protected $consumption_mixer      = 0;
   protected $consumption_sormat     = 0;
   protected $consumption_pintle     = 0;
   protected $consumption_beech      = 0;
   protected $consumption_compr1p    = 0; //в м2
   protected $consumption_compr3p    = 0; //в м2
   //техническик параметры
   public $script_time = 0;
   public $error_msg   = '';
   // Version
   private $version = '0.1';
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
      $this->price_base_step($this->step_base_lenght, $this->step_base_width, $this->step_base_quantity);
      //расчет фризовых ступеней
      $this->price_frieze_step();
      //расчет забежных ступеней
      $this->price_base_step($this->step_base_lenght, $this->step_base_lenght, $this->step_runin_quantity * 1.6);
      //расчет полуплоащадок
      $this->price_base_step($this->step_base_lenght, $this->step_base_lenght, $this->step_halfplatform_quantity / 1.8);
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
      ChromePhp::groupCollapsed("Тип и материал: $this->stair_type-$this->stair_material-$this->step_platform_material");
      ChromePhp::log("Коэффициент цены: \t\t" . $this->k_increase_price);
      ChromePhp::group("Дерево:");
      ChromePhp::log("Всего л. фанеры6: \t" . $this->consumption_plywood6);
      ChromePhp::log("Всего л. фанеры18: \t" . $this->consumption_plywood18);
      ChromePhp::log("Всего л. ДСП16: \t" . $this->consumption_dsp);
      ChromePhp::log("Всего м3 дуба: \t\t" . $this->consumption_oak);
      ChromePhp::log("Всего бука., м3: \t $this->consumption_beech\n");
      ChromePhp::log("Всего м2 паркет 1п: \t\t" . $this->consumption_compr1p);
      ChromePhp::log("Всего м2 паркет 3п: \t\t $this->consumption_compr3p\n");
      ChromePhp::log("Всего шпона, м2: \t" . $this->consumption_veneer);
      ChromePhp::groupEnd('Дерево:');
      ChromePhp::log("Всего ПВА, кг: \t\t" . $this->consumption_pva_vintek);
      ChromePhp::log("Всего больц, шт: \t" . $this->consumption_bolz);
      ChromePhp::log("Всего отв., шт: \t" . $this->hole_all);
      ChromePhp::log("Всего хима., шт: \t" . $this->consumption_hanker);
      ChromePhp::log("Всего микс., шт: \t" . $this->consumption_mixer);
      ChromePhp::log("Всего сорм., шт: \t $this->consumption_sormat");
      ChromePhp::log("Всего штырей., шт: \t $this->consumption_pintle");
      ChromePhp::groupEnd('Тип и материал');
   }

//коэфициент удорожания по длине и ширне базовой ступени
   protected function k_increase_price()
   {
      if($this->step_base_lenght > $this->k_lenght) {
         $this->k_increase_price = $this->step_base_lenght / $this->k_lenght;
      }
      if($this->step_base_width > $this->k_width) {
         $this->k_increase_price = $this->k_increase_price * ($this->step_base_width / $this->k_width);
      }
      $this->k_increase_price = $this->k_increase_price / (sqrt($this->k_increase_price));
   }
   
   protected function price_base_step($step_lenght, $step_width, $step_count)
   {
      //считаем стоимость базовых ступеней из Евростндарта
      //Количество фанеры на одну ступень
      if($this->stair_material == 'euro60') {
         //Количество фанеры6 на одну ступень
         $this->consumption_plywood6 += (2 * 1 / (1520 / ($step_width + 40))) * $step_count;
         //Количество дсп на одну ступень
         $part_of_list_dsp16_horiz_n = floor(1500 / ($step_lenght + 20));//количество рядов заготовок по длине листа
         $part_of_list_dsp16_horiz = ($part_of_list_dsp16_horiz_n) * floor(3500 / ($step_width + 10)); //количество заготовок по длине листа
         $part_of_list_dsp16_vertical_n = floor((1500 - ($part_of_list_dsp16_horiz_n * $step_lenght + 10)) / ($step_width + 10));
         $part_of_list_dsp16_vertical = $part_of_list_dsp16_vertical_n * floor(3500 / ($step_lenght + 10)); //количество заготовок по длине листа
         //Всего заготовок из одного листа ДСП
         $part_of_dsp16_1step = 3 * (1 / ($part_of_list_dsp16_horiz + $part_of_list_dsp16_vertical));
         $this->consumption_dsp += $part_of_dsp16_1step * $step_count; //Накапливаем количество ДСП
         //   ChromePhp::log("на прямые ступени нужно будет ".$part_of_dsp16_1step*$step_count." листа ДСП");
         $this->consumption_veneer += ($step_lenght + $step_width) * 60 * 2 * 2 * 0.000001 * $step_count;
         $this->consumption_pva_vintek += ($step_lenght * $step_width) * 4 * 0.5 * 0.000001 * $step_count;
      }
      elseif($this->stair_material == 'fan54') {
         $this->consumption_plywood18 += (1 / (1520 / ($step_width + 40))) * $step_count;
         $part_of_list_dsp16_horiz_n = floor(1500 / ($step_lenght + 20));//количество рядов заготовок по длине листа
         $part_of_list_dsp16_horiz = ($part_of_list_dsp16_horiz_n) * floor(1520 / ($step_width + 10)); //количество заготовок по длине листа
         $part_of_list_dsp16_vertical_n = floor((1500 - ($part_of_list_dsp16_horiz_n * $step_lenght + 10)) / ($step_width + 10));
         $part_of_list_dsp16_vertical = $part_of_list_dsp16_vertical_n * floor(1520 / ($step_lenght + 10)); //количество заготовок по длине листа
         //Всего заготовок из одного листа ДСП
         //ChromePhp::log("part_of_list_dsp16_horiz: \t\t" . $part_of_list_dsp16_horiz . ' step_width:' . $step_width . ' step_lenght:' . $step_lenght . ' step_count:' . $step_count);
         $part_of_dsp16_1step = 2 * (1 / ($part_of_list_dsp16_horiz + $part_of_list_dsp16_vertical));
         $this->consumption_plywood18 += $part_of_dsp16_1step * $step_count; //Накапливаем количество Фанеры18
         $this->consumption_veneer += ($step_lenght + $step_width) * 54 * 2 * 2 * 0.000001 * $step_count;
         $this->consumption_pva_vintek += ($step_lenght * $step_width) * 3 * 0.5 * 0.000001 * $step_count;
      }
      elseif($this->stair_material == 'oak') {
         $this->consumption_oak += ($step_lenght * $step_width) * 0.07 * 2 * 0.000001 * $step_count;
         $this->consumption_veneer += ($step_lenght + $step_width) * 54 * 2 * 2 * 0.000001 * $step_count;
         $this->consumption_pva_vintek += ($step_lenght * $step_width) * 3 * 0.5 * 0.000001 * $step_count;
      }
   }
   
   protected function price_frieze_step()
   {
      $frieze_lenght = $this->step_base_lenght;
      $k_add = 1;
      for($i = 0; $i < $this->step_frieze_quantity; $i++) {
         switch($i) {
            case 0:
               $k_add = 1.35;
               break;
            case 1:
               $k_add = 1.25;
               break;
            case 2:
               $k_add = 1.2;
               break;
            default:
               $k_add = 1.1;
         }
//ChromePhp::log("Кол-во фризовых ".$i." k_add:".$k_add);
         $this->price_base_step($this->step_base_lenght, $this->step_base_width * $k_add, $k_add);
      }
   }
   
   protected function count_bolz()
   {
      if($this->stair_type == 'bolz') {
         $this->consumption_bolz = $this->step_base_quantity + $this->step_frieze_quantity + $this->step_runin_quantity * 2 + $this->step_halfplatform_quantity * 2;
      }
      $this->consumption_bolz += $this->bolzextra_quantity;
   }
   
   protected function count_risers_sormats()
   {
      $temp_material = $this->stair_material;
      if($temp_material == "euro60") {
         $this->stair_material = 'fan54';
      }
      $this->price_base_step($this->step_base_lenght, 140, $this->step_base_quantity);
      $this->price_base_step($this->step_base_lenght, 140, $this->step_frieze_quantity * 1.4);
      $this->price_base_step($this->step_base_lenght, 140, $this->step_runin_quantity * 1.6);
      $this->stair_material = $temp_material;
      $this->consumption_sormat += $this->step_base_quantity * 4 + $this->step_frieze_quantity * 6 + $this->step_runin_quantity * 5 + $this->step_halfplatform_quantity * 5 + $this->step_platform_square * 4;
   }
   
   protected function count_hole()
   {
      $this->hole_all = $this->step_base_quantity * 2 + $this->step_frieze_quantity * 2 + $this->step_runin_quantity * 3 + $this->step_halfplatform_quantity * 3 + $this->pendant_quantity + $this->supportwood_quantity * 3;
   }
   
   protected function count_pintle()
   {
      $this->consumption_pintle = $this->step_base_quantity * 2 + $this->step_frieze_quantity * 2 + $this->step_runin_quantity * 3 + $this->step_halfplatform_quantity * 3 + $this->supportwood_quantity * 3;
   }
   
   protected function count_hanker_mixer()
   {
      $this->consumption_hanker = ceil($this->hole_all / 6);
      $this->consumption_mixer = $this->consumption_hanker * 2;
   }
   
   protected function count_supportwood()
   {
      $temp_material = $this->stair_material;
      $this->stair_material = 'fan54';
      $this->price_base_step(800, 300, $this->supportwood_quantity);
      $this->stair_material = $temp_material;
   }
   
   protected function count_boots()
   {
      $this->consumption_beech += $this->boots_lenght * (0.02 * 0.06 * 2);
   }
   
   protected function count_step_platform_square()
   {
      $this->consumption_veneer += ($this->step_platform_square) * 4 * 0.054 * 2;
      switch($this->step_platform_material) {
         case 'Fan54':
            $this->consumption_plywood18 += 2 * (($this->step_platform_square / (1.5 * 1.5)) * 3);
            $this->consumption_pva_vintek += ($this->step_platform_square) * 3 * 0.5;
            break;
         case 'Fan36':
            $this->consumption_plywood18 += 2 * (($this->step_platform_square / (1.5 * 1.5)) * 2);
            $this->consumption_pva_vintek += ($this->step_platform_square) * 2 * 0.5;
            break;
         case 'Compromat1p':
            $this->consumption_plywood18 += 2 * ($this->step_platform_square / (1.5 * 1.5));
            $this->consumption_compr1p += $this->step_platform_square * 1.5;
            $this->consumption_pva_vintek += ($this->step_platform_square) * 2 * 0.5;
            break;
         case 'Compromat3p':
            $this->consumption_plywood18 += 2 * ($this->step_platform_square / (1.5 * 1.5));
            $this->consumption_compr3p += $this->step_platform_square * 1.5;
            $this->consumption_pva_vintek += ($this->step_platform_square) * 2 * 0.5;
            break;
         default:
            ChromePhp::error("Не удалось определить материал площадки! \n");
            $this->error_msg .= 'Не удалось определить материал площадки!<br>';
      }
   }
   
   protected function count_coverface()
   {
      $this->consumption_plywood18 += $this->coverface_height * $this->coverface_lenght * 1.25;
      $this->consumption_beech += ($this->coverface_height + $this->coverface_lenght) * 2 * 0.02 * 0.06;
   }
   
   protected function calculate_price()
   {
      // TODO расчет стоимости материалов, отдельно в массиве
   }
}

?>