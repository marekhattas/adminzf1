<?php

class Cms_View_Helper_CardDetail extends Cms_View_Helper_Abstract {

    public function cardDetail($card,$data){

        $container = new Cms_Container();

        $title = $this->view->title($card);
        $container->add('title',$title);

        if(!empty($data->data)){
            $arrayToHtml= $this->view->arrayToHtml($card,$data->data);
            $container->add('arrayToHtml',$arrayToHtml);
        }

        if(!empty($data->html)){
            $container->add('html',$data->html);
        }


        //helper
        if($card->getOption('detailHelper') == true){
            $cardName = $card->getCardName();
            $e = explode('_',$cardName);
            $card2 = $e[2];
            $helperName = lcfirst($card2).'Detail';

            $helper = $this->view->$helperName($card);
            $container->add('helper',$helper);
        }


        return $container;
    }
}