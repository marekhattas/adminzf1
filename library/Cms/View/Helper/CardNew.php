<?php

class Cms_View_Helper_CardNew extends Cms_View_Helper_Abstract {

    public function cardNew($card,$data){

        $container = new Cms_Container();

        $title = $this->view->title($card);
        $container->add('title',$title);

        $container->add('html',$data->html);

        $data->form->setAction($this->view->url());
        $container->add('form',$data->form);

        if($card->getOption('clearReload')){
            $clearReloadAfterAjaxPost =  $this->view->clearReloadAfterAjaxPost();
            $container->add('clearReloadAfterAjaxPost',$clearReloadAfterAjaxPost);
        }

        //helper
        if($card->getOption('newHelper') == true){
            $cardName = $card->getCardName();
            $e = explode('_',$cardName);
            $card2 = $e[2];
            $helperName = lcfirst($card2).'New';

            $helper = $this->view->$helperName($card);
            $container->add('helper',$helper);
        }


        return $container;
    }
}