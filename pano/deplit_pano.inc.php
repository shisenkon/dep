<?php
/**
 * @package 			Deplit Pano Price Generator
 * @version 			0.1
 * @author 				Shibkov Konstantin
 * @link 				http://deplit.ru
 * @copyright 			Copyright (c) 2017, Shibkov Konstantin  aka sendel (shibkov.k@gmail.com)
 * @license 			GNU General Public License version 2 or later;
 */

 class Panno_price
{
	// тип плитки
	protected $arr_sizes;
	protected $render_html = '';
	protected $pano_type;
	//массив с префиксами для файлов
	protected $arr_pano_types = array(
    "FIX" => "",
    "MAGNETIC" => "",
);
		//массив с префиксами для артикулов
	protected $arr_pano_art = array(
    "FIX" => "Ф",
    "MAGNETIC" => "М",
);
	public function __construct(array $arr_sizess, $pano_type = 'FIX')
	{
	//	if (isset($config['type_pano'])) {
	//		$this->surface_height = $config['type_pano'];
	//	}
		if (isset($arr_sizess)) {
			$this->arr_sizes = $arr_sizess;
		}
		if (isset($pano_type)) {
			$this->pano_type = $pano_type;
		}
		$this->generate_table();
	}

	protected function generate_table()
	{
		foreach ($this->arr_sizes as $key => $value) {
			//$this->render_html .= $value;
			$pieces = explode(" ", $value);
			$this->render_html .= '<tr>
		<th scope="row">'.($key+1).'</th>

		<td><img src="img/'.$this->arr_pano_types[$this->pano_type].'t-'.$pieces[0].'-'.$pieces[1].'.png" alt=""></td>
				<td class="text-center"><h5>Панно<br>
				Deplit&nbsp;'.$this->pano_type.'<br>
				'.$pieces[0].'х'.$pieces[1].'</h5>
				<div class="t_artikul"><div><img src="img/natural.png" height="10px" width="10px">		&nbsp;&nbsp;П'.$this->arr_pano_art[$this->pano_type].'010'.$pieces[0].'0'.$pieces[1].'</div>
				<div><img src="img/venge.png" height="10px" width="10px">		&nbsp;&nbsp;П'.$this->arr_pano_art[$this->pano_type].'020'.$pieces[0].'0'.$pieces[1].'</div>
				<div><img src="img/walnut.png" height="10px" width="10px">		&nbsp;&nbsp;П'.$this->arr_pano_art[$this->pano_type].'030'.$pieces[0].'0'.$pieces[1].'</div>
				<div><img src="img/cherry.png" height="10px" width="10px">		&nbsp;&nbsp;П'.$this->arr_pano_art[$this->pano_type].'040'.$pieces[0].'0'.$pieces[1].'</div>
				<div><img src="img/oak.png" height="10px" width="10px">			&nbsp;&nbsp;П'.$this->arr_pano_art[$this->pano_type].'050'.$pieces[0].'0'.$pieces[1].'</div></div>
		</td>
				<td class="t_description"><strong>Размер панно:</strong><br>
				'.($pieces[0]*100+102).'х'.($pieces[1]*100+102).'мм<br>
				<strong>Размеры плиток в панно:</strong> 100х100мм<br>
				<strong>Кол-во плиток в панно:</strong> '.$pieces[0]*$pieces[1].'шт<br>
				<strong>Ширина рамки:</strong> 50мм<br>
				<strong>Цвета рамки:</strong><br>
				<img src="img/natural.png" height="10px" width="10px"> натуральный<br>
				<img src="img/venge.png" height="10px" width="10px"> венге<br>
				<img src="img/walnut.png" height="10px" width="10px"> орех<br>
				<img src="img/cherry.png" height="10px" width="10px"> вишня<br>
				<img src="img/oak.png" height="10px" width="10px"> беленый дуб<br>
		</td>
				<td class="price">'.number_format($pieces[4],0,',',' ').'&#8381;</td>
	</tr>
	<tr>
		<td colspan="6" class="text-center examples">';
		$filename = 'img/'.$pieces[0].'-'.$pieces[1].'-1.jpg';
		if (file_exists($filename)) {$this->render_html .= 'Примеры раскладки элементов в панно '.$pieces[0].'x'.$pieces[1].':<br><img src="'.$filename.'" alt="" height="200px" width="200px">';}
		$filename = 'img/'.$pieces[0].'-'.$pieces[1].'-2.jpg';
		if (file_exists($filename)) {$this->render_html .= '<img src="'.$filename.'" alt="" height="200px" width="200px">';}
		$filename = 'img/'.$pieces[0].'-'.$pieces[1].'-3.jpg';
		if (file_exists($filename)) {$this->render_html .= '<img src="'.$filename.'" alt="" height="200px" width="200px">';}
		$this->render_html .= '</td>
	</tr>';
		}
	}

	public function render_table()
	{
		echo($this->render_html);

	}



}
?>