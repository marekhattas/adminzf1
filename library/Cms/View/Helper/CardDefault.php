<?php

class Cms_View_Helper_CardDefault extends Cms_View_Helper_Abstract {

    public function cardDefault($card,$data = null){

        $container = new Cms_Container();

        $title = $this->view->title($card);
        $container->add('title',$title);

        if(!is_object($data) and !empty($data)){
            $container->add('html',$data);
        }else{
            if(isset($data->html)){
                $container->add('html',$data->html);
            }
            if(isset($data->form)){
                $data->form->setAction($this->view->url());
                $container->add('form',$data->form);
            }
        }

        return $container;
    }
}