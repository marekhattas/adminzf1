<?php
class Cms_View_Helper_Title extends Cms_View_Helper_Abstract{

    /*
     * @param card Cms_Card $card
     */
    public function title($card){
        $html = '';
        if($card->getOption('showTitle') == true){
            $title = $card->getTitle();

            $tag = $card->getTitleTag();
            $child = '';
            $id = $card->getParam('id');
            if($id != null and $card->getOption('showTitleChild')){
                $row = $card->getModel()->getRow($id);
                if(isset($row->name)){
                    $child = ' <b>'.$row->name.'</b>';
                }
            }
            $html = '<'.$tag.'>'.$title.$child.'</'.$tag.'>';
        }
        return $html;
    }

}