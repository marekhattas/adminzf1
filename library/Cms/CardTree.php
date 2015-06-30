<?php
class Cms_CardTree extends Cms_Card
{

    protected $_availableChilds;

    public function indexAction() {
        if($this->getOption('treeControllerName') != $this->getParam('controller')){
            $url = array();
            $url['controller'] = $this->getOption('treeControllerName');
            $url['card'] = null;
            $ajax = false;
            $this->redirect($url, $ajax);
        }else{
            return parent::indexAction($render);
        }
    }

    public function editAction() {
        if($this->getOption('treeControllerName') == $this->getParam('controller')){
            //eee
            $row = $this->getModel()->getRow($this->getParam('id'));
            $url = array();
            $url['controller'] = $row->controller;
            $url['card'] = null;
            $ajax = false;
            $this->redirect($url, $ajax);
        }else{
            return parent::editAction($render);
        }
    }
   public function newForm(){
        $form = parent::newForm();

        $elm = new Zend_Form_Element_Text('name',array('label'=>Cms_T::_('cms_name')));
        $elm->setRequired(true);
        $form->addElement($elm);



        $model = new Blog_Model_Tree();
        $childs = $model->getTreeChilds(null,array('onlyDir'=>true));
        $data = $model->treeList($childs);

        $elm = new Zend_Form_Element_Select('tree_id',array('label'=>'Category'));
        $elm->addMultiOption('', 'Main category');
        foreach($data as $item){
            $elm->addMultiOption($item['id'], $item['distance'].$item['row']->name);
        }
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Select('controller',array('label'=>'Content type'));
        $elm->setRequired(true);
        $data = $this->getAvailableChilds();
        foreach($data as $controller){
            if(empty($controller['title'])){
                $card = new $controller['cards'][0];
                $card->init();
                $controller['title'] = $card->getTitle();
            }
            $elm->addMultiOption($controller['name'],$controller['title']);
        }
        $form->addElement($elm);

        $elm = new Cms_Form_Element_Active('active');
        $elm->setLabel('Show on web page');

        $elm->setValue(1);
        $form->addElement($elm);

        return $form;

    }

    public function editForm(){
        $form = parent::editForm();

        $elm = $form->getElement('controller');
        $elm->setDescription(Cms_T::_('cms_if_you_change_this_field_you_must_save_this_form_with_button_save_and_go_back'));
        //$form->removeElement('controller');

        return $form;

    }

   public function getAvailableChilds(){
        if(is_array($this->_availableChilds)){
          return $this->_availableChilds;
        }

        $moduleName = ucfirst($this->getParam('module'));
        $configObjName = $moduleName . '_Config_Options';
        $config = new $configObjName;
        $options = $config->getOptions();

        //searchinfg for a controller whish should be available for new record
        foreach($options as $key=>$option){
            if(isset($option['new']) and $option['new']===true){
                $option['name'] = $key;
                $this->_availableChilds[] = $option;
            }
        }

        return $this->_availableChilds;

    }

    public function getCloseLink(){
        $url = parent::getCloseLink();
        $url['controller'] = $this->getOption('treeControllerName');
        
        return $url;
    }

}