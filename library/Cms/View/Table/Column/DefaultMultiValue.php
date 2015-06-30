<?php
class Cms_View_Table_Column_DefaultMultiValue extends  Cms_View_Table_Column_Abstract
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

    public function setData($dbData){
        $this->_dbData = $dbData;
        if(isset($dbData->id)){
            $this->_id = $dbData->id;
        }
        $names = $this->getName();
        $values = array();
        if (is_array($names)) {
            foreach ($names as $name) {
                if(!isset($dbData->$name)){
                    $dbData->$name = null;
                }
                $values[$name] = $dbData->$name;
            }
        } else {
            if(!isset($dbData->$names)){
                $dbData->$names = null;
            }
            $values[$names] = $dbData->$names;
        }
        
        $this->setValue($values);
        return $this;
    }
    
    public function getValue(){
        if(!$this->escape){
            return $this->_value;
        }
        else{
            $values = array();
            foreach ($this->_value as $value) {
                $values[] = strip_tags($value);
            }
            return $values;
        }
    }

    public function render(){
        $values = $this->getValue();
        $html = '<td>';
        
        if(count($values)>1){
            $html .= '<ul>';
            foreach ($values as $val) {
                $html .= '<li>';
                if(empty($val)){
                    $val = ' - ';
                }
                $html .= $val;
                $html .= '</li>';
            }
            $html .= '</ul>';
        } else {
            $val = $values[0];
            if(empty($val)){
                $val = ' - ';
            }
            $html .= $val;
        }

        return $html;
    }
}
