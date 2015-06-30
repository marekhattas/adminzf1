<?php
class Blog_Card_ProductHome extends Cms_CardTree
{

    protected $_actions = array('index'=>true,'edit'=>true,'delete'=>true,'new'=>true);
    public $filePath = '/files/gallery';

    public function init(){

        $this->setTitle(text("Products"));
        $this->_options['defaultCard'] = true;
        $this->setModel("Blog_Model_Texts");

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
        $elm = $obj->createElement('profile_file_name','Profile photo',$this->getZca()->view,$this->filePath,$oldFileName);
        $form->addElement($elm);
        
        $elm = new Zend_Form_Element_Textarea('text',array('label'=>text('Text')));
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
            $data['profile_file_name'] = $upload->uploadImage(1024,800);

        }
        else{
            unset($data['profile_file_name']);
        }

        return parent::update($data,$where);
    }

    
}