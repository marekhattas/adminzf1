<?php
class Cms_View_Helper_TimelineGraph extends Cms_View_Helper_Abstract{

 public function timelineGraph($hours){

        if(!is_numeric($hours) && $hours != null){
            throw new Cms_Exception('Invalid parameter $hours: parameter is not numeric');
        }
        
        $html = '';
        $text = '';
        $day = '';
        $days = 0;
                
        if($hours === null){
            
            $day = 'ASAP';
            $text = 'ASAP';
            $class = 'orange';
            
        } else {
            
            $days = $hours / 24;
            if($hours >= 0){
                $days = floor($days);
                $end = Cms_T::_('cms_remaining');
            } else {
                $days = ceil($days)+0;
                $end = Cms_T::_('cms_overdue');
            }
            
            

            if($days >= 7){
                $class = 'green';
            }
            elseif($days >= 4){
                $class = 'yellow';
            }
            elseif($hours >= 0){
                $class = 'orange';
            }
            else{
                $class = 'red';
            }
            
            
            if(abs($days) < 2){
                if(abs($hours) == 1){
                    $day = Cms_T::_('cms_hour');
                } else {
                    $day = Cms_T::_('cms_hours');
                }
                $text = abs($hours).' '.$day;
            }else{
                $day = Cms_T::_('cms_days');
                $text = abs($days).' '.$day;
            }


            $text = $text.' '.$end;
            
            if($days < -20){
                $days = -20;
                $day = '<-20';
            }elseif($days > 20){
                $days = 20;
                $day = '>20';
            }else{
                $day = $days;
            }
            
        }
        
        
        $p = 100/40;
        $left = $p*($days+20);

        

        $html = '<td class="overdue-parent '.$class.'">'
                . '<div class="overdue">
                    <div class="overdue-day" style="left:'.$left.'%">'.$day.'</div>
                    <div class="overdue-y"></div>
                    <div class="overdue-x"></div>
                   </div>
                   <div class="overdue-info">'.$text.'</div>
                </td>';
        return $html;

    }

}