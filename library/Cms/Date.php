<?php


class Cms_Date {
    
    private static $months = array(
        'en'=>array('00'=>'00','01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec'),
        'sk'=>array('00'=>'00','01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'Máj','06'=>'Jún','07'=>'Júl','08'=>'Aug','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Dec'),
        'cs'=>array('00'=>'00','01'=>'Led','02'=>'Úno','03'=>'Bře','04'=>'Dub','05'=>'Kvě','06'=>'Čvn','07'=>'Čvc','08'=>'Srp','09'=>'Zář','10'=>'Říj','11'=>'Lis','12'=>'Pro'),
        'de'=>array('00'=>'00','01'=>'Jan','02'=>'Feb','03'=>'Mär','04'=>'Apr','05'=>'Mai','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Okt','11'=>'Nov','12'=>'Dez')
    );
    
    
    
    static function dateToDb($date){
        
        if(strlen($date)>11){
            return preg_replace_callback("/([0-9]{1,2})\/(.+)\/([0-9]{4}) ([0-9]{1,2}:([0-9]{1,2}):([0-9]{1,2}))/i", 
                    function ($m) {
                        $lang = Zend_Registry::get('Language');
                        return $m[3]."-".array_search($m[2], self::$months[$lang['code']])."-".$m[1]." ".$m[4];
                    },
                    $date);
        }
        else{
            return preg_replace_callback("/([0-9]{1,2})\/([a-zA-Z]{3})\/([0-9]{4})/i", 
                    function ($m) {
                        $lang = Zend_Registry::get('Language');
                        return $m[3]."-".array_search($m[2], self::$months[$lang['code']])."-".$m[1];
                    },
                    $date);
        }
    }

    static function dateFromDb($date){
        if(strlen($date)>10){
            return preg_replace_callback("/([0-9]{4})\-([0-9]{1,2})\-([0-9]{1,2}) ([0-9]{1,2}:([0-9]{1,2}):([0-9]{1,2}))/i", 
                    static function ($m) { 
                        $lang = Zend_Registry::get('Language'); 
                        return $m[3]."/".self::$months[$lang['code']][$m[2]]."/".$m[1]." ".$m[4];
                    },
                    $date);
        }
        else{
            return preg_replace_callback("/([0-9]{4})\-([0-9]{1,2})\-([0-9]{1,2})/i", 
                    static function ($m) {
                        $lang = Zend_Registry::get('Language');
                        return $m[3]."/".self::$months[$lang['code']][$m[2]]."/".$m[1];
                    },
                    $date);
        }
    }

    static function hourDiff($date1,$date2 = null){
        if($date2 == null){
            $date2 = date('Y-m-d H:i:s');
        }
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $hours = ($datetime1->getTimestamp() - $datetime2->getTimestamp()) / 3600;
        if($hours > 0){
            $hours = floor($hours);
        } else {
            $hours = ceil($hours)+0;
        }
        return $hours;

    }
    static function dayDiff($date1,$date2 = null){
        if($date2 == null){
            $date2 = date('Y-m-d H:i:s');
        }
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);

        return $datetime2->diff($datetime1)->format('%R%a');

    }
    static function diff($date1,$date2 = null){
        if($date2 == null){
            $date2 = date('Y-m-d H:i:s');
        }
        $datetime1 = new DateTime($date1);
        $datetime2 = new DateTime($date2);
        $i = $datetime2->diff($datetime1);

        if($i->format('%y') == 1){
            $data = array('format'=>'year','val'=>$i->format('%y'),'text'=>Cms_T::_('cms_year'),'sign'=>$i->format('%R%'));
        }elseif($i->format('%y') > 1){
            $data = array('format'=>'years','val'=>$i->format('%y'),'text'=>Cms_T::_('cms_year'),'sign'=>$i->format('%R%'));
        }elseif($i->format('%m') == 1){
            $data = array('format'=>'month','val'=>$i->format('%m'),'text'=>Cms_T::_('cms_month'),'sign'=>$i->format('%R%'));
        }elseif($i->format('%m') > 1){
            $data = array('format'=>'months','val'=>$i->format('%m'),'text'=>Cms_T::_('cms_months'),'sign'=>$i->format('%R%'));
        }elseif($i->format('%d') == 1){
            $data = array('format'=>'day','val'=>$i->format('%d'),'text'=>Cms_T::_('cms_day'),'sign'=>$i->format('%R%'));
        }elseif($i->format('%d') > 1){
            $data = array('format'=>'days','val'=>$i->format('%d'),'text'=>Cms_T::_('cms_days'),'sign'=>$i->format('%R%'));
        }elseif($i->format('%h') == 1){
            $data = array('format'=>'hour','val'=>$i->format('%h'),'text'=>Cms_T::_('cms_hour'),'sign'=>$i->format('%R%'));
        }elseif($i->format('%h') > 1){
            $data = array('format'=>'hours','val'=>$i->format('%h'),'text'=>Cms_T::_('cms_hours'),'sign'=>$i->format('%R%'));
        }elseif($i->format('%i') == 1){
            $data = array('format'=>'minute','val'=>$i->format('%i'),'text'=>Cms_T::_('cms_minute'),'sign'=>$i->format('%R%'));
        }elseif($i->format('%i') > 1){
            $data = array('format'=>'minutes','val'=>$i->format('%i'),'text'=>Cms_T::_('cms_minutes'),'sign'=>$i->format('%R%'));
        }elseif($i->format('%s') <= 1){
            $data = array('format'=>'second','val'=>$i->format('%s'),'text'=>Cms_T::_('cms_second'),'sign'=>$i->format('%R%'));
        }elseif($i->format('%s') > 1){
            $data = array('format'=>'seconds','val'=>$i->format('%s'),'text'=>Cms_T::_('cms_seconds'),'sign'=>$i->format('%R%'));
        }
        return $data;
    }
}