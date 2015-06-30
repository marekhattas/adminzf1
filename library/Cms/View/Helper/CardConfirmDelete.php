<?php

class Cms_View_Helper_CardConfirmDelete extends Cms_View_Helper_Abstract {

    public function cardConfirmDelete($card,$data){

        $container = new Cms_Container();

        $dialog = $this->view->deleteConfirmDialog(array('id'=>$data->id,'name'=>$data->name));
        $container->add('dialog',$dialog);

        return $container;
    }
}