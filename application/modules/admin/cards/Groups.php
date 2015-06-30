<?php
class Admin_Card_Groups extends Cms_Card
{

    protected $_actions = array('index'=>true,'edit'=>true,'delete'=>true,'new'=>true,'acl'=>true);

    public function init(){

        $this->setTitle(text("Skupiny"));
        $this->_options['defaultCard'] = false;
        $this->setModel("Admin_Model_Groups");

    }

    public function aclAction(){
        $groupId = $this->getParam('groupId');
        $resource = $this->getParam('resource');
        if(!empty($groupId) and !empty($resource)){
            $model = new Admin_Model_Acl();
            $select = $model->select();
            $select = $select->where('group_id = "'.$groupId.'" and resource="'.$resource.'" ');
            $row = $model->fetchRow($select);
            if($row != null){
                $row->delete();
                echo "0";
                exit;
            }else{
                $model->insert(array('group_id'=>$groupId,'resource'=>$resource));
                echo "1";
                exit;
            }
        }
        echo "empty params";
        exit;
    }


    public function editAction($render = true){
        $container = $this->getDefaultTemplate();
        $container->add('editJS',$this->show('Admin_View_GroupEdit',array('id'=>$this->getParam('id'))),1002);
        $this->setDefaultTemplate($container);
        parent::editAction($render);
    }

    public function newForm(){
        $form = parent::newForm();

        //name
        $form->addElement('text', 'name', array(
            'label'      => text('Meno'),
            'filter'      => 'StringTrim',
            'required'   => true
        ));

        return $form;
    }

    public function editForm(){
        $form = parent::editForm();

        $acl = new Cms_Acl();
        $data = $acl->getResourceList();

        $table = '<table width="100%" class="cms-dataTable cms-panel content" style="border-bottom:1px solid #cccccc">';        
        if(!$acl->isSuperRole($this->getParam('id'))){
            foreach($data as $key=>$row){
                if (!preg_match("/::/", $key)){
                    $row = '<b>'.$row.'</b>';
                }else{
                    $row = ' - - '.$row;
                }
                $button = '<div class="fakeButton" style="margin:2px;" title="'.$key.'">'.text('nepovolený').'</div>';
                if($acl->isAllowed($this->getParam('id'),$key)){
                    $button = '<div class="fakeButton active" style="margin:2px;" title="'.$key.'">'.text('povolený').'</div>';
                }
                $table .='<tr>
                          <td></td>
                          <td style="padding-top:10px;">'.$row.'</td>
                          <td>'.$button.'</td>
                          </tr>';
            }
        }else{
            $table .='<tr>
                      <td></td>
                      <td style="padding-top:10px;">'.text('Prístup k celému systému povolený.').'</td>
                      </tr>';

        }
            
        $table .= "</table>";
        $elm = new  Zend_Form_Element_Text('rule',array('label'=>text('Oprávnenia')));
        $deco = $elm->getDecorator('description');
        $deco->setEscape(false);
        $elm->setDescription($table);
        $elm->setAttrib('style','display:none');
        $form->addElement($elm);

        return $form;
    }
    
    public function indexColumns(){

        $columns = array();
        $columns[] = new Cms_View_Table_Column_DefaultActions('id',array('actions'=>$this->_actions,'options'=>$this->_options,'card'=>$this->getCardName()));
        $columns[] = new Cms_View_Table_Column_Default('name',array('label'=>text('Názov'),'link'=>true,'action'=>'edit','options'=>$this->_options,'card'=>$this->getCardName()));

        return $columns;
    }


}