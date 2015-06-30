<?php
class Cms_Form_Element_Active extends Zend_Form_Element_Radio{

    public function init(){
        if($this->getLabel() == null){
            $this->setLabel(Cms_T::_('cms_status'));            
        }
        $this->setRequired(true);
        $this->addMultiOption('1',Cms_T::_('cms_active'));
        $this->addMultiOption('0',Cms_T::_('cms_inactive'));        
    }
}
?>
