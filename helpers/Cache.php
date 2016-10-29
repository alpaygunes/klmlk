<?php
/**
 * Created by PhpStorm.
 * User: alpay
 * Date: 03.03.2015
 * Time: 10:47
 */

class Cache {

    public  $zaman_asimi = 600 ; // saniye
    public  $cache_dir;
    public  $file_path;

    function  __construct(){
        $this->cache_dir = configuration::$cache;
        $this->makeFileName();

    }

    function makeFileName($file_name=null){
        if(!$file_name){
            $file_name = md5($_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]).'.html';
            $this->file_path = $this->cache_dir.DS.$file_name;
        }else{
            $this->file_path = $this->cache_dir.DS.md5($file_name);
        }
    }

    function timeOut(){
        //dosya varmı ?
        $olusturma_zamani   =0;
        if(file_exists($this->file_path)){
            $olusturma_zamani = filemtime($this->file_path);
        }
        if(time()- $this->zaman_asimi < $olusturma_zamani ){
            return false;
        }
        return true;
    }

    function writeToCache($cikti){
        $ac = fopen($this->file_path,"w+");
        $a = fwrite($ac,$cikti);
        fclose($ac);
    }

    function readFromCache(){
        readfile($this->file_path);
    }
}