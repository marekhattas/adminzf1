<?php

class Cms_View_Helper_CardView extends Cms_View_Helper_Abstract {

    public function cardView($card,$data){

        $container = new Cms_Container();

        $title = $this->view->title($card);
        $container->add('title',$title);

        if(is_string($data)){
            $container->add('html',$data);
        }
        elseif(!empty($data->html)){
            $container->add('html',$data->html);
        }


        //helper
        if($card->getOption('viewHelper') == true){
            $cardName = $card->getCardName();
            $e = explode('_',$cardName);
            $card2 = $e[2];
            $helperName = lcfirst($card2).'View';

            $helper = $this->view->$helperName($card);
            $container->add('helper',$helper);
        }


        return $container;
    }
}