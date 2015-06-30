<?php
class Cms_View_Table_Column_Default extends  Cms_View_Table_Column_Abstract
{

    public $escape = true;
    public $link = false;
    public $url = array();
    public $card = null;
    public $action = null;

    public function getAction(){
        return $this->action;
    }

    public function isAllowed(){
        $action = $this->getAction();
        if($action !=null and $this->card!=null){
            return $this->card->isAllowed($action,$this->card->getParams());
        }
        return true;
    }

    public function getValue(){
        if(!$this->escape){
            return $this->_value;
        }
        else{
            return strip_tags($this->_value);
        }
    }

    public function render(){
        $val = $this->getValue();
        if(empty($val)){
            $val = ' - ';
        }

        return '<td>'.$val.'</td>';
    }
}
