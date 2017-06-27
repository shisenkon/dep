<?php

/**
 * @package          Deplit Form Post
 * @version          0.1
 * @author             Shibkov Konstantin
 * @link             http://deplit.ru
 * @copyright          Copyright (c) 2017, Shibkov Konstantin  aka sendel (shibkov.k@gmail.com)
 * @license          GNU General Public License version 2 or later;
 */
class grao_form_post
{
   
   public $arr_config = array();
   
   // Version
   private $version = '0.1';
   
   public function __construct($post)
   {
      $this->arr_config = $post;
      foreach($this->arr_config as $key => $value) {
         if($value == '') {
            $this->arr_config[$key] = "0";
         }
      }
   }
   
}

?>