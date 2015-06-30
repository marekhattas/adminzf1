<?php

class Admin_IndexController extends Zend_Controller_Action
{
    

    public function init(){  
        $this->auth= new Cms_Auth();
        $this->_redirector = $this->_helper->getHelper('Redirector');
    }    
    
    public function indexAction(){

        if($this->auth->isAuth()){
            header('location: '.$this->view->url(array('action'=>'index','controller'=>'dashboard','module'=>'admin'),'admin',true));
            exit;             
        }

        $l = new Cms_L();
        $this->_helper->layout->setLayout('login');
      
        // login data was sent
        if ($this->getRequest()->isPost()) {
          $this->auth->doAuth($this->_getParam('email'),$this->_getParam('password'));
          if($this->auth->isAuth()){
            //log login
            $log = new Cms_Log();
            $log->insert(array('type'=>'login'));
            header('location: '.$this->view->url(array('action'=>'index','controller'=>'dashboard','module'=>'admin'),'admin',true));
            exit; 
          }
          else{
            $this->view->login = false;
          }
        }

    }

    public function loginAction(){
        $this->_forward('index');
    }
    
    public function logoutAction(){

          //log login
          $log = new Cms_Log();
          $log->insert(array('type'=>'logout'));
          $this->auth->clearAuth();
          $this->_redirector->setGotoSimple('index','index','admin');

    }


}