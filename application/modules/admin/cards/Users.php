<?php
class Admin_Card_Users extends Cms_Card
{

    protected $_actions = array('index'=>true,'edit'=>true,'delete'=>true,'new'=>true);

    public function init(){
        $this->setTitle(text("Užívatelia"));
        $this->_options['defaultCard'] = false;
        $this->setModel("Admin_Model_Users");

    }

    public function newForm(){
        $form = parent::newForm();

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
        $form->getElement('password')->setRequired(true);


        $elm = new Cms_Form_Element_Active('active');
        $form->addElement($elm);


        $elm = new Zend_Form_Element_MultiCheckbox('groups',array('label'=>text('Skupiny')));
        $model = new Admin_Model_Groups();
        $data = $model->fetchAll();
        foreach($data as $row){
            $elm->addMultiOption($row->id,' '.$row->name);
        }
        $form->addElement($elm);
        
        return $form;
    }

    public function editForm(){
        $form = parent::editForm();
        $form->getElement('password')->setRequired(false);

        $userId = $this->getParam('id');
        $elm = $form->getElement('groups');
        $model = new Admin_Model_UsersGroups();
        $data = $model->fetchAll('user_id = "'.$userId.'"');
        $a=array();
        foreach($data as $row){
            $a[] = $row->group_id;
        }
        $elm->setValue($a);
        $form->addElement($elm);


      return $form;
    }


    public function insert($data){
        $groups = $data['groups'];
        unset($data['groups']);
        $userId = parent::insert($data);
        $model = new Admin_Model_UsersGroups();
        foreach($groups as $group_id){
            $model->insert(array('user_id'=>$userId,'group_id'=>$group_id));
        }
        return $userId;
    }
    public function update($data,$where){
        $groups = $data['groups'];
        unset($data['groups']);
        $userId = $this->getParam('id');
        $model = new Admin_Model_UsersGroups();
        $model->delete('user_id = "'.$userId.'"');
        foreach($groups as $group_id){
            $model->insert(array('user_id'=>$userId,'group_id'=>$group_id));
        }
        return parent::update($data,$where);
    }

    public function indexColumns(){

        $columns = array();
        $columns[] = new Cms_View_Table_Column_DefaultActions('id',array('actions'=>$this->_actions,'options'=>$this->_options,'card'=>$this->getCardName()));
        $columns[] = new Cms_View_Table_Column_Default('name',array('label'=>text('Meno'),'link'=>true,'action'=>'edit','options'=>$this->_options,'card'=>$this->getCardName()));
        $columns[] = new Cms_View_Table_Column_Default('email',array('label'=>text('Email')));
        $columns[] = new Cms_View_Table_Column_YesNo('active',array('label'=>text('Aktívny')));

        return $columns;
    }




}