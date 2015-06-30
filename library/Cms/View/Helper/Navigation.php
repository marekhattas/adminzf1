<?php
class Cms_View_Helper_Navigation extends Cms_View_Helper_Abstract{

    public function navigation(){


        $html = '<ul class="breadcrumb">';

    //navigation
    if(!empty($this->navigation)){
        foreach($this->navigation as $item){
            if(isset($item['link'])){
                $html .= '<li><a href="'.$item['link'].'">'.$item['name'].'</a> <span class="divider">/</span></li>';
            }else{
                $html .= '<li>'.$item['name'].' <span class="divider">/</span></li>';
            }
        }
    }
    $html .= '
        <li class="active">'.$this->title.'</li></ul>

      ';


        return $html;
    }
}