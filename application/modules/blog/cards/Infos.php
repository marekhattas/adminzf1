<?php
class Blog_Card_Infos extends Cms_Card
{

    protected $_actions = array('index'=>true,'edit'=>true,'delete'=>false,'new'=>false);

    public function init(){

        $this->setTitle(text("Základné informácie o stránke"));
        $this->_options['defaultCard'] = true;
        $this->setModel("Blog_Model_Infos");
        $this->setParam('id', 1);
        $this->setOption('editOnly',true);

    }
    
    public function indexAction($render = true) {
        parent::editAction($render);
    }

    public function editForm(){
        $form = parent::editForm();

        $elm = new Zend_Form_Element_Text('meta_title', array("label"=> text('Názov stránky')));
        $elm->setRequired(true);
        $form->addElement($elm);
      
        $elm = new Zend_Form_Element_Textarea('meta_description', array("label"=> text('Stručný popis stránky')));
        $elm->setAttrib('rows', 3);
        $form->addElement($elm);
      
        $elm = new Zend_Form_Element_Textarea('meta_keywords', array("label"=> text('Klúčové slová')));
        $elm->setAttrib('rows', 3);
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Textarea('statistics', array("label"=> text('Štatistika')));
        $elm->setAttrib('rows', 19);
        $elm->setDescription('Kód, ktorý vám vygeneruje stránka, ktorá vám spravuje štatistiku.');
        $form->addElement($elm);
        

        return $form;
    }



}