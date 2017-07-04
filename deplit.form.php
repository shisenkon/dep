<?php
/**
 * @package 			Deplit Form Post
 * @version 			0.1
 * @author 				Shibkov Konstantin
 * @link 				http://deplit.ru
 * @copyright 			Copyright (c) 2017, Shibkov Konstantin  aka sendel (shibkov.k@gmail.com)
 * @license 			GNU General Public License version 2 or later;
 */

 class Deplit_form_post
{
	// тип плитки
	public $deplit_type;
	// высота площади
	public $surface_height;
	// высота длина
	public $surface_lenght;
	// есть ли плинтус (форма)
	public $plintus100;
	// фикированное число сборки
	public $seed_fix;
	// тип поверхности (форма)
	public $surface_type;
	// дополнительное значение высота для треугольника
	public $surface_triangle_H;
	// дополнительное значение отступа вершины для треугольника
	public $surface_triangle_L;
	// Version
	private $version = '0.1';

	/**
	 * Contructor function
	 *
	 * @param string $deplit_type default:modern
	 * @param string $surface_height
	 * @param string $surface_width
	 * @param string $surface_type default:rectangle
	 * @return string
	 */
	public function __construct($post)
	{
		if (isset($post['deplit_type'])) {
			$this->deplit_type = $post['deplit_type'];
		} else {$this->surface_type = "modern";}
		if (isset($post['surface_height'])) {
			$this->surface_height = $post['surface_height'];
		}else {$this->surface_height = 1000;}
		if (isset($post['surface_lenght'])) {
			$this->surface_lenght = $post['surface_lenght'];
		}else {$this->surface_lenght = 1500;}
		if (isset($post['surface_type'])) {
			$this->surface_type = $post['surface_type'];
		} else {$this->surface_type = "rectangle";}
		if (isset($post['plintus100'])) {
			$this->plintus100 = $post['plintus100'];
		} else {$this->plintus100 = "off";}
		if (isset($post['seed_fix_check'])) {
			$this->seed_fix_check = $post['seed_fix_check'];
		} else {$this->seed_fix_check = "off";}
		if (isset($post['seed_fix'])) {
			$this->seed_fix = trim($post['seed_fix']);
		} else {$this->seed_fix = "Случайная";}

	}



}
?>