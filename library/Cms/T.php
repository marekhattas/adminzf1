<?php

class Cms_T
{   
    public static function _($string,$lang = null){
        
        $translations = Zend_Registry::get('Zend_Translate_ini');
        if($translations->getAdapter()->isTranslated($string, false, $lang)){
            $string = $translations->_($string,$lang);
        } else {
            $string = $translations->_($string,'en');
        }
        return $string;
    }   
}
