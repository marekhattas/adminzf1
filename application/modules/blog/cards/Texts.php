<?php
class Blog_Card_Texts extends Cms_CardTree
{

    protected $_actions = array('index'=>true,'edit'=>true,'delete'=>true,'new'=>true);
    public $filePath = '/files/gallery';

    public function init(){

        $this->setTitle(text("Udalosti"));
        $this->_options['defaultCard'] = true;
        $this->setModel("Blog_Model_Texts");

    }

    public function editForm(){
        $form = parent::editForm();

        $elm = new Zend_Form_Element_Textarea('text_short',array('label'=>text('Krátky popis')));
        $elm->setDescription('Využijete pri článkoch');
        $elm->setAttrib('rows', '2');
        //$form->addElement($elm);

        $elm = new Zend_Form_Element_Textarea('text',array('label'=>text('Text')));
        $elm->setAttrib('class','tiny normal');
        $form->addElement($elm);

      return $form;
    }

}