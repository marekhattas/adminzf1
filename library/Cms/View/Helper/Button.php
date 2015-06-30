<?php
class Cms_View_Helper_Button   extends Cms_View_Helper_Abstract  {

    public function button($options){

        $ajax = false;
        if(isset($options['link']['ajax'])){
            $ajax = $options['link']['ajax'];
        }
        $reset = false;
        if(isset($options['link']['reset'])){
            $reset = $options['link']['reset'];
        }


        if($options['modal'] == true){
            $link = 'onClick="ajaxModal(\''.$this->view->url($options['link']['url'],'admin',$reset).'\')"';
        }else{

            if(is_string($options['link'])){
                $link = 'onClick="ajaxGetOnClick(\''.$options['link'].'\',\'#card_'.$this->view->controllerClassName().'\',true)"';
            }elseif($ajax){
                $link = 'onClick="ajaxGetOnClick(\''.$this->view->url($options['link']['url'],'admin',$reset).'\',\'#card_'.$this->view->controllerClassName().'\')"';
            }else{
                $link = 'onClick="ajaxGetOnClick(\''.$this->view->url($options['link']['url'],'admin',$reset).'\',\'#card_'.$this->view->controllerClassName().'\',true)"';
            }
        }
        $html ='<button '.$link.' class="pull-right btn-add btn btn-success">'.$options['title'].'</button> ';

        return $html;
    }

}