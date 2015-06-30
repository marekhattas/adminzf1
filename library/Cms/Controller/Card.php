<?php

class Cms_Controller_Card extends Cms_Controller_Admin
{

    public function init()
    {

        parent::init();

        $moduleName = ucfirst($this->_getParam('module'));
        $configObjName = $moduleName . '_Config_Options';
        $config = new $configObjName;
        $options = $config->getOptions();
        $cards = $options[$this->_getParam('controller')]['cards'];
        //@fixme do not use array
        $this->cardName = $cards[0];
        $this->card = new $this->cardName;
        $this->card->setParams($this->_getAllParams());
        $this->card->setRequest($this->getRequest());

        $this->view->currentController = $this->_getParam('controller');
        $this->view->params = $this->_getAllParams();
        $this->view->card = $this->card;

        //ajax call me?
        //AJAX detection
        if($this->getRequest()->isXmlHttpRequest() or $this->_getParam('noLayout',null) == 'true'){

        }
        else{


            //main menu - active items
            $this->menuItems = $config->getMenuItems();
            $this->view->parentInMenu = null;
            if(count($this->menuItems)>0){
                foreach($this->menuItems as $item){
                    if(isset($item['sub']) and count($item['sub'])>0){
                        foreach($item['sub'] as $subItem){
                            if($this->_getParam('controller') == $subItem['controller']){
                                $this->view->parentInMenu = $item['name'];
                                break;
                            }
                        }
                        break;
                    }
                }
            }


            $this->view->metaTitle = $this->getTitle();

        }

    }


    public function getTitle(){
        $id = $this->_getParam('id');
        if($id != null){
            $row = $this->card->getModel()->getRow($id);
            if($row != null){
                $name = $row->id;
                if(!empty($row->name)){
                    $name = $row->name;
                }
                return $name;
            }
        }
        return $this->card->getTitle();
    }

    public function accessForbidden($card){
          throw new Cms_Exception(Cms_T::_('cms_access_forbidden_for_this_section').': '.$card->getCardName().', '.$card->getParam('action'));
    }

    public function tryAccess(){
        $action = $this->getParam('action');
        $allowed = $this->card->isAllowed($action,array('id'=>$this->_getParam('id',null),'parentId'=>$this->_getParam('parentId',null)));
        if(!$allowed){
            $this->accessForbidden($this->card);
        }
        return true;
    }

    public function __call($method,$arg){
        if(substr($method,-6) == 'Action'){
            $this->tryAccess();
            $action = $this->getParam('action');
            $this->view->data = $this->card->$method();
            if(isset($this->view->data->redirect)){
                $this->redirect($this->view->data->link);
            }
            $this->renderScript($action.'.phtml');
        }
    }

    public function dndsortAction(){
        $this->tryAccess();
        $action = $this->getParam('action');
        $method = $action.'Action';
        $this->view->data = $this->card->$method();
        if(isset($this->view->data->redirect)){
            $this->redirect($this->view->data->link);
        }
        $this->getHelper('viewRenderer')->setNoRender(true);
    }

    public function pluploadAction(){
        $this->tryAccess();
        $action = $this->getParam('action');
        $method = $action.'Action';
        $this->view->data = $this->card->$method();
        if(isset($this->view->data->redirect)){
            $this->redirect($this->view->data->link);
        }
        $this->getHelper('viewRenderer')->setNoRender(true);
    }

    public function redirect(array $link){
        if(empty($link['ajax'])){
            $link['ajax'] = false;
        }
        if(empty($link['reset'])){
            $link['reset'] = false;
        }
        if(empty($link['router'])){
            $link['router'] = 'admin';
        }

        $url = $this->view->url($link['url'],$link['router'],$link['reset']);

        if($link['ajax'] == true){
            header('Location: '.$url.'');
        }
        elseif(isset($link['windowReset'])){
            echo 'windowReset: '.$url;
        }
        elseif(isset($link['windowNew'])){
            echo 'windowNew: '.$url;
        }
        else{
            echo 'redirect: '.$url;
        }

        $this->getHelper('layout')->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender(true);
        exit;
        return true;
    }


    /*
    public function __call($methodName, $args)
    {

        if ('Action' == substr($methodName, -6)) {
            //$this->card->setParams($this->_getAllParams());
            $this->renderScript('index.phtml');
        }else{
            return parent::__call($methodName, $args);
        }
    }
     */






    public function cardAction(){
        //$this->_helper->layout->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);
    }


    public function redirector(){
        //return $this->getHelper('Redirector');
    }
}

