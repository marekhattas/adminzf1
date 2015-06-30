<?php
class Blog_Card_ClearTexts extends Cms_CardTree
{

    protected $_actions = array('index'=>true,'edit'=>true,'delete'=>true,'new'=>true);

    public function init(){

        $this->setTitle(text("Čistý text"));
        $this->_options['defaultCard'] = true;
        $this->setModel("Blog_Model_Texts");

    }

    public function newForm(){
        $form = parent::newForm();

        $elm = new Zend_Form_Element_Textarea('text',array('label'=>text('Text')));
        //$elm->setAttrib('class','tiny normal');
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Text('link',array('label'=>text('Odkazuje na:')));
        $elm->setDescription('napr: http://wwww.performia.sk/nieco');
        $form->addElement($elm);

      return $form;
    }

    public function update($data,$where){

        $form = $this->editForm();
        if(!empty($data['profile_file_name'])){

            $options = array();
            $options['model'] = $this->getModel();
            $options['where'] = $where;
            $options['column'] = 'profile_file_name';
            $options['fileName'] = $data['profile_file_name'];
            $options['form'] = $form;

            $upload = new Cms_Upload($options);
            //don't optimize pictures
            $data['profile_file_name'] = $upload->uploadImage(1024,800);

        }
        else{
            unset($data['profile_file_name']);
        }

        return parent::update($data,$where);
    }


}