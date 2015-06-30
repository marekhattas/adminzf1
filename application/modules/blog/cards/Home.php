<?php
class Blog_Card_Home extends Cms_CardTree
{

    protected $_actions = array('index'=>true,'edit'=>true,'delete'=>false,'new'=>false);

    public function init(){

        $this->setTitle(text("Úvod"));
        $this->_options['defaultCard'] = true;
        $this->setModel("Blog_Model_Texts");

    }

    public function editForm(){
        $form = parent::editForm();
/*
        $elm = new Zend_Form_Element_Textarea('text',array('label'=>text('Horný banner')));
        $elm->setAttrib('class','tiny normal');
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Textarea('text_short',array('label'=>text('Text na úvode')));
        $elm->setAttrib('class','tiny normal');
        $form->addElement($elm);
 
 */
      return $form;
    }

}