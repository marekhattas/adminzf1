<?php

class Cms_Container
{
    protected $_nullCounter = 0;
    protected $_value = array();
    protected $_orderUpdated = false;


    public function add($name,$value,$order = null) {
        $this->_value[$name] = $value;
        $this->_order[$name] = $order;
        if($order == null){
            $this->_nullCounter++;
            $this->_order[$name] = $this->_nullCounter;
        }
        return $this;
    }

    public function remove($name) {
        unset($this->_value[$name]);
        unset($this->_order[$name]);
        return $this;
    }

    public function get($name) {
        if(!array_key_exists($name,$this->_value)){
            throw new Cms_Exception("Empty value for name: ".$name." in container ".get_class($this));
        }
        return $this->_value[$name];
    }

    public function exists($name) {
        return array_key_exists($name,$this->_value);
    }

    public function getOrder($name){
        return $this->_order[$name];
    }
    public function getAll() {

        asort($this->_order);

        $items = array();
        foreach($this->_order as $key => $order) {
            //echo $key.' - '.$order."\n";
            $items[] = $this->_value[$key];
        }
    	return $items;
    }




    /**
     * Count of elements/subforms that are iterable
     *
     * @return int
     */
    public function count()
    {
        return count($this->_order);
    }

    public function __toString()
    {
        $data = $this->getAll();
        $text = '';
        if(count($data)>0){
            foreach($data as $val){
                $text .= $val;
            }
        }
        return $text;
    }


}


