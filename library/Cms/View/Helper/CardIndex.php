<?php

class Cms_View_Helper_CardIndex extends Cms_View_Helper_Abstract {

    public function cardIndex($card,$data){

        $container = new Cms_Container();

        $title = $this->view->title($card);
        $container->add('title',$title);

        //add button
        if($card->isAllowed('new',$card->getParams()) and $card->getOption('showNewButton')){
            $link = array();
            $link['url'] = array();
            $link['url']['action'] = 'new';
            $link['url']['card'] = null;
            if($card->getOption('cardType') == 'sub'){
                $link['url']['card'] = $card->getCardName();
                $link['ajax'] = true;
            }
            $title = '<i class="glyphicon glyphicon-plus-sign "></i> '.Cms_T::_('cms_new_record');
            $modal = $card->getOption('newModal');

            $button = $this->view->button(array('title'=>$title,'link'=>$link,'modal'=>$modal));
            $container->add('button',$button);
        }

        //table
        $table = $card->getOption('indexTable');
        $tableDb = $this->view->$table($card);
        $container->add('table',$tableDb);

        if($card->isAllowed('dndSort')){
            if($card->getOption('cardType') == 'tree'){
                $dndTreeSort =  $this->view->dndTreeSort(array('card'=>$card->getCardName()));
                $container->add('dndTreeSort',$dndTreeSort);
            }else{
                $dndSort =  $this->view->dndSort(array('card'=>$card->getCardName()));
                $container->add('dndSort',$dndSort);
            }
        }


        //helper
        if($card->getOption('indexHelper') == true){
            $cardName = $card->getCardName();
            $e = explode('_',$cardName);
            $card2 = $e[2];
            $helperName = lcfirst($card2).'Index';

            $helper = $this->view->$helperName($card);
            $container->add('helper',$helper);
        }

        return $container;
    }
}