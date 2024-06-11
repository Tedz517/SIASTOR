<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2016, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package CodeIgniter
 * @author  EllisLab Dev Team
 * @copyright Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright Copyright (c) 2014 - 2016, British Columbia Institute of Technology (http://bcit.ca/)
 * @license http://opensource.org/licenses/MIT  MIT License
 * @link  https://codeigniter.com
 * @since Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Array Helpers
 *
 * @package   CodeIgniter
 * @subpackage  Helpers
 * @category  Helpers
 * @author    EllisLab Dev Team
 * @link    https://codeigniter.com/user_guide/helpers/array_helper.html
 */

function joinDate($month,$year){
  if($month == '01'){
    $bulan = 'Januari';
  } else if($month == '02'){
    $bulan = 'Februari';
  } else if($month == '03'){
    $bulan = 'Maret';
  } else if($month == '04'){
    $bulan = 'April';
  } else if($month == '05'){
    $bulan = 'Mei';
  } else if($month == '06'){
    $bulan = 'Juni';
  } else if($month == '07'){
    $bulan = 'Juli';
  } else if($month == '08'){
    $bulan = 'Agustus';
  } else if($month == '09'){
    $bulan = 'September';
  } else if($month == '10'){
    $bulan = 'Oktober';
  } else if($month == '11'){
    $bulan = 'Nopember';
  } else if($month == '12'){
    $bulan = 'Desember';
  }

  $join = 'Bergabung pada '.$bulan.', '.$year;

  return $join;
}

function datepicker($date){
  $text = str_replace('/','-',$date);
  $text = date('Y-m-d',strtotime($text));

  return $text;
}

function date_indo($date){
  $text = str_replace('-','/',$date);
  $text = date('d/m/Y',strtotime($text));

  return $text;
}

function buffer($data,$p1,$p2){
  $data = ' '.$data;
  $hasil = '';

  $awal = strpos($data,$p1);

  if($awal != ''){
    $akhir = strpos(strstr($data,$p1),$p2);

    if($akhir != ''){
      $hasil = substr($data,$awal + strlen($p1),$akhir - strlen($p1));
    }
  }

  /*
  0 = Masuk
  1 = Pulang
  4 = Lembur Masuk
  5 = Lembur Keluar
  */

  return $hasil;
}

function datereport($date){
  $text = date('Y-m-d',strtotime($date));

  return $text;
}

function download_send_headers($filename){
  // disable caching
  $now = gmdate("D, d M Y H:i:s");
  header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
  header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
  header("Last-Modified: {$now} GMT");

  // force download  
  header("Content-Type: application/force-download");
  header("Content-Type: application/octet-stream");
  header("Content-Type: application/download");

  // disposition / encoding on response body
  header("Content-Disposition: attachment;filename={$filename}");
  header("Content-Transfer-Encoding: binary");
}

function array2csv($array){
   if (count($array) == 0) {
     return null;
   }

   ob_start();
   $df = fopen("php://output", 'w');
   fputcsv($df, array_keys(reset($array)), ',');

   foreach ($array as $row) {
      fputcsv($df, $row, ',');
   }

   fclose($df);
   return ob_get_clean();
}
