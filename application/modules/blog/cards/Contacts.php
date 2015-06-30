<?php
class Blog_Card_Contacts extends Cms_CardTree
{

    protected $_actions = array('index'=>true,'edit'=>true,'delete'=>false,'new'=>true);
 public $filePath = '/files/gallery';
    public function init(){

        $this->setTitle(text("Kontakt"));
        $this->_options['defaultCard'] = true;
        $this->setModel("Blog_Model_Contacts");

    }

    public function editForm(){
        $form = parent::editForm();

         //image
        $form->setEnctype('multipart/form-data');
        //image
        $id = $this->getParam('id');
        $row = $this->getModel()->find($id);
        $oldFileName = null;
        if(!empty($row[0])){
            $oldFileName = $row[0]['profile_file_name'];
        }
        $obj = new Cms_Form_Element_Image();
        $elm = $obj->createElement('profile_file_name','obrázok',$this->getZca()->view,$this->filePath,$oldFileName);
        //$form->addElement($elm);

        $elm = new Zend_Form_Element_Text('email', array("label"=> text('Email')));
        $elm->setDescription('na tento email sa bude posielať vyplnený kontaktý formulár');
        $elm->addValidator('emailAddress');
        $elm->setRequired(true);
        $form->addElement($elm);


        $elm = new Zend_Form_Element_Textarea('googlemap', array("label"=> text('Mapa')));
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Textarea('text', array("label"=> text('Text pod nadpisom')));
        $elm->setAttrib('class','tiny normal');
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Text('form_name', array("label"=> text('Nadpis pri formuláry')));
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Textarea('form_text', array("label"=> text('text nad formulárom')));
        $elm->setAttrib('class','tiny normal');
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