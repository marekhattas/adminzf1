<?php

class Cms_View_Helper_CardEdit extends Cms_View_Helper_Abstract {

    public function cardEdit($card,$data){

        $container = new Cms_Container();

        $title = $this->view->title($card);
        $container->add('title',$title);

        if(!empty($data->html)){
            $container->add('html',$data->html);
        }

        $data->form->setAction($this->view->url());
        $container->add('form',$data->form);


        //helper
        if($card->getOption('editHelper') == true){
            $cardName = $card->getCardName();
            $e = explode('_',$cardName);
            $card2 = $e[2];
            $helperName = lcfirst($card2).'Edit';

            $helper = $this->view->$helperName($card);
            $container->add('helper',$helper);
        }


        return $container;
    }
}