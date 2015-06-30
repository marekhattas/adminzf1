<?php
class Admin_Card_Profiles extends Cms_Card
{

    protected $_actions = array('index'=>false,'edit'=>true,'delete'=>false,'new'=>false);

    public function init(){
        $this->setTitle(text("Užívatelia"));
        $this->_options['defaultCard'] = true;
        $this->setModel("Admin_Model_Users");

    }


    public function editForm(){
        $form = parent::editForm();

        //name
        $form->addElement('text', 'name', array(
            'label'      => text('Meno'),
            'filter'      => 'StringTrim',
            'required'   => true
        ));

        //email
        $elm = new Zend_Form_Element_Text('email',array('label'=>text('Email')));
        $elm->setRequired(true)
              ->addValidator('emailAddress', false);
        $form->addElement($elm);


        // Password
        $length=new Zend_Validate_StringLength();
        $length->setMin(5);
        $form->addElement('password', 'password', array(
            'label'      => text('Heslo'),
            'description'=> text('5 Znakov minimálne'),
            'filter'      => 'StringTrim',
            'validators' => array($length),
            'autocomplete' => 'off'
        ));



      return $form;
    }


    public function update($data,$where){
        $auth= new Cms_Auth();
        $id = $auth->getData()->id;
        $where = "id = ".$id;
        return parent::update($data,$where);
    }

    public function indexColumns(){

        $columns = array();
        $columns[] = new Cms_View_Table_Column_DefaultActions('id',array('actions'=>$this->_actions,'options'=>$this->_options,'card'=>$this->getCardName()));
        $columns[] = new Cms_View_Table_Column_Default('name',array('label'=>text('Meno'),'link'=>true,'action'=>'edit','options'=>$this->_options,'card'=>$this->getCardName()));
        $columns[] = new Cms_View_Table_Column_Default('email',array('label'=>text('Email')));
        $columns[] = new Cms_View_Table_Column_Default('active',array('label'=>text('Aktívny')));

        return $columns;
    }




}