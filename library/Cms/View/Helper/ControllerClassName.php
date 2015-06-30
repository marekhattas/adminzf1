<?php

class Cms_View_Helper_ControllerClassName extends Cms_View_Helper_Abstract {

    public function controllerClassName(){

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $modul = $request->getParam('module');
        $controller = $request->getParam('controller');
        $controllerClassName = ucfirst($modul).'_'.ucfirst($controller).'Controller';

        return $controllerClassName;
    }
}