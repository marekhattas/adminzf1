<?php

class Admin_ProfileController extends Cms_Controller_Admin
{

    public function indexAction(){
        $container = $this->defaultTemplate();

        $url = array();
        $this->_cardList = $this->getOption('cardList');
        $auth= new Cms_Auth();

        $url['card'] = $this->_cardList[0]['name'];
        $url['action'] = 'edit';
        $url['id'] = $auth->getData()->id;
        $container->add($this->_cardList[0]['name'],$this->show('ajax',array('url'=>$url)),200);

        $this->view->metaTitle = $this->getTitle();
        $this->view->htmlContainer = $container;
        
        $this->renderScript('render.phtml');
        
    }

}