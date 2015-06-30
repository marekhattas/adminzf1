<?php

class Cms_TextSafe {

    /*
    http://stackoverflow.com/questions/2103797/url-friendly-username-in-php
    $user = 'Alix Axel';
    echo url($user); // alix-axel

    $user = 'Álix Ãxel';
    echo url($user); // alix-axel

    $user = 'Álix----_Ãxel!?!?';
    echo url($user); // alix-axel
    */

    function url($string){
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }

    public function getText($string){
        return $this->url($string);
    }

    /*
    public function getText($text){
        $text=trim($text);

        $trans=array("á"=>"a","ä"=>"a","č"=>"c","ď"=>"d","é"=>"e","ě"=>"e","ë"=>"e",
                 "í"=>"i","ľ"=>"l","ĺ"=>"l","ň"=>"n","ô"=>"o","ó"=>"o","ö"=>"o",
                 "ŕ"=>"r","ř"=>"r","š"=>"s","ť"=>"t","ú"=>"u","ů"=>"u","ü"=>"u",
                 "ý"=>"y","ž"=>"z",
                 "Á"=>"a","Ä"=>"a","Č"=>"c","Ď"=>"d","É"=>"e","Ě"=>"e","Ë"=>"e",
                 "Í"=>"i","Ľ"=>"l","Ĺ"=>"l","Ň"=>"n","Ô"=>"o","Ó"=>"o","Ö"=>"o",
                 "Ŕ"=>"r","Ř"=>"r","Š"=>"s","Ť"=>"t","Ú"=>"u","Ů"=>"u","Ü"=>"u",
                 "Ý"=>"y","Ž"=>"z",
                 "-----"=>"-","----"=>"-","---"=>"-","--"=>"-",
                 " "=>"-","?"=>"-","."=>"-",","=>"-","!"=>"-","\\"=>"-","/"=>"-",
                 "*"=>"-","$"=>"-","ß"=>"-","<"=>"-",">"=>"-","#"=>"-","&"=>"-","™"=>"-",
                 "@"=>"-","{"=>"-","}"=>"-","–"=>"-");
        $text= StrTr($text,$trans);
        $text=mb_strtolower($text);

        //ked by nahodou mi nieco uslo
        $text = preg_replace('/[^A-Za-z0-9-]/', ' ', $text);
        $text = preg_replace('/ +/', ' ', $text);
        $text = trim($text);
        $text = str_replace(' ', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        if(mb_substr($text,-1)=="-"){
            $text=mb_substr($text,0,-1);
        }
        return $text;
    }
     */

}