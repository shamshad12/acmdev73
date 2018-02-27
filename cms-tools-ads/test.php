<?php
    ini_set('display_errors','1');
    error_reporting(E_ALL);
    //$data=  file_get_contents('/var/www/html/cms-tools-ads/files/curl.php');\
    $zip_file = '/var/www/html/cms-tools-ads/files/curl.zip';
    $destination_path='/var/www/html/cms-tools-ads/files/';
        if (file_exists($zip_file)) 
        {
           //exec('unzip '.$zip_file.' -d '.$destination_path);
           // unlink($destination_path.'curl.php');

        }
        else {
        }
//echo $data;exit();




?>