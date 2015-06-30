<?php
class Cms_Form_Element_File extends Zend_Form_Element_File{


    private $_oldFileName = null;
    private $_model = null;
    private $_rowId = null;


    public function init() {
        $this->setValueDisabled(true);
        return parent::init();
    }
    //$element->setValueDisabled(true);
    //$element->setDestination(PUBLIC_PATH.$filePath);
    //$element->setMaxFileSize(1024*1024*1.5); //1.5MB


    public function setOldFileName($name){
        $this->_oldFileName = $name;
    }

    public function getOldFileName(){
        if($this->_oldFileName == null and $this->getModel() != null and $this->getRowId()!=null){
            $row = $this->getModel()->getRow($this->getRowId());
            if($row != null){
                $name = $this->getName();
                if($row->$name != null){
                    $filePath = $this->getDestination();
                    if(file_exists($filePath.'/'.$row->$name)){
                        $this->_oldFileName =  $row->$name;
                    }
                }
            }
        }
        return $this->_oldFileName;
    }

    /*
     * return Cms_DbTable
     */
    public function getModel(){
        return $this->_model;
    }

    public function setModel($model){
        $this->_model = $model;
        $this->setDestination(PUBLIC_PATH.'/'.$this->getModel()->_filePath);
        return $this;
    }

    public function getRowId(){
        return $this->_rowId;
    }
    public function setRowId($id){
        $this->_rowId = $id;
        return $this;
    }

    public function loadDefaultDecorators()
    {
        if ($this->loadDefaultDecoratorsIsDisabled()) {
            return $this;
        }

        $decorators = $this->getDecorators();
        if (empty($decorators)) {
            $oldFile = new Cms_Form_Decorator_OldFile();
             $this->addDecorator('File')
                  //->addDecorator('ViewHelper')
                 ->addDecorator('Errors')
                 ->addDecorator($oldFile) //<---- new decorator
                 ->addDecorator('Description', array('tag' => 'p', 'class' => 'description'))
                 ->addDecorator('HtmlTag', array(
                     'tag' => 'dd',
                     'id'  => array('callback' => array(get_class($this), 'resolveElementId'))
                 ))
                 ->addDecorator('Label', array('tag' => 'dt'));
             
        }
        return $this;
    }


    public function isValid($value, $context = null) {
        $name = $this->_name;
        if((empty($_FILES) or empty($_FILES[$name])) and !$this->isRequired()){
            return true;
        }

        return parent::isValid($value, $context);
    }


}