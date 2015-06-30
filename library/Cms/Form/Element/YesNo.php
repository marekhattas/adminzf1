<?php
class Cms_Form_Element_YesNo extends Zend_Form_Element_Radio{

    public function init(){
        $this->setRequired(true);
        $this->addMultiOption('1',Cms_T::_('cms_yes'));
        $this->addMultiOption('0',Cms_T::_('cms_no'));
    }
}
?>
