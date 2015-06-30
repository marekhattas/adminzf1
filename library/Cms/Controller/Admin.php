<?php

class Cms_Controller_Admin extends Zend_Controller_Action
{

    public function init()
    {

        //check login
        $auth = new Cms_Auth();
        if(!$auth->isAuth() and $this->_getParam('action')!='login'){
            //save lost params
            $session = new Zend_Session_Namespace('lostParams');
            $session->params = $this->getAllParams();
            header('location: '.$this->view->url(array(),'admin',true));
            exit;
        }else{
            //set admin layout
            $this->_helper->layout->setLayout('admin');
        }

        $this->view->contentLayout = true;
        if($this->getRequest()->isXmlHttpRequest() or $this->_getParam('noLayout',null) == 'true'){
            $this->view->contentLayout = false;
            $this->_helper->layout->setLayout('cardajax');
        }

        //check permissions
        $acl = new Cms_Acl();
        if(!$acl->isUserAllowed(get_class($this))){
            throw new Cms_Exception(Cms_T::_('cms_access_forbidden_for_this_section').': '.get_class($this));
        }
        
        //user alternation end -> logout
        $modelOutofoffice = new Admin_Model_OutOfOffice();
        if($auth->getData()->id != $auth->getRealIdentity() and !$acl->isSuperUser($auth->getRealIdentity())){
            $outofoffice = $modelOutofoffice->fetchRow('admin_user_id_alternate = "'.$auth->getRealIdentity().'" and admin_user_id = "'.$auth->getData()->id.'" and date_start < NOW() and date_end > NOW()');
            if($outofoffice == null){
                header('location: '.$this->view->url(array('action'=>'logout','controller'=>'index','module'=>'admin'),'admin',true));
                exit;
            }
        }
    }
}

