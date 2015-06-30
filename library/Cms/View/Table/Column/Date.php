<?php
class Cms_View_Table_Column_Date extends  Cms_View_Table_Column_Default
{

  	protected $_enableHtml = true;

    public function render(){
        $date = $this->getValue();
        $d = new Cms_Date();
        $value = $d->dateFromDb($date);
        return '<td data-order="'.$date.'">'.strtr($value,array(' '=>'&nbsp;')).'</td>';
    }
}
