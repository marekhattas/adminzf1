<?php
abstract class Cms_View_Table_Column_Abstract extends Zend_View_Helper_Abstract
{

    protected $_id;
    protected $_name;
    protected $_value;
    protected $_label;
    protected $_orderAble = false;
    protected $_dbData;
    protected $_class;

    public function __construct($name, $options =null){
        $this->setName($name);

        if (is_array($options)) {
            foreach ($options as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($this, $method)) {
                    // Setter exists; use it
                    $this->$method($value);
                }
                else{
                    $this->$key = $value;
                }
            }
        }
        elseif(!empty($options)){
            throw new Cms_Exception('Parametr $options must be array or null');
        }
    }
    public function getThClass(){
        return null;
    }

    public function getClass() {
        return $this->_class;
    }
    public function setClass($name){
        $this->_class = $name;
        return $this;
    }

    public function getName() {
        return $this->_name;
    }

    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    public function isOrderAble() {
        return $this->_orderAble;
    }

    public function setOrderAble($orderAble) {
        $this->_orderAble = $orderAble;
        return $this;
    }



    public function getValue() {
        return $this->_value;
    }

    public function setValue($value) {
        $this->_value = $value;
        return $this;
    }

    public function getLabel() {
        return $this->_label;
    }

    public function setLabel($value) {
        $this->_label = $value;
        return $this;
    }

    public function getData(){
        return $this->_dbData;
    }
    public function setData($dbData){
        $this->_dbData = $dbData;
        if(isset($dbData->id)){
            $this->_id = $dbData->id;
        }
        $name = $this->getName();
        if(!isset($dbData->$name)){
            $dbData->$name = null;
        }
        $this->setValue($dbData->$name);
        return $this;
    }

    public function render(){
        try{
            return $this->getValue();
        }
        catch (Exception $e){
            throw new Cms_Exception(Cms_T::_('cms_error_in_rendering'));
        }
    }

    public function __toString()
    {
        return $this->render();
    }

}