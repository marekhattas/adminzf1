<?php
class Blog_Card_Form extends Cms_CardTree
{

    protected $_actions = array('index'=>true,'edit'=>true,'delete'=>true,'new'=>true);

    public function init(){

        $this->setTitle(text("Objednávka"));
        $this->_options['defaultCard'] = true;
        $this->setModel("Blog_Model_Texts");

    }

    public function editForm(){
        $form = parent::editForm();

        $elm = new Zend_Form_Element_Textarea('text',array('label'=>text('Text')));
        $elm->setAttrib('class','tiny normal');
        //$form->addElement($elm);


      return $form;
    }


}