<?php
class Cms_View_Helper_ArrayToHtml extends Cms_View_Helper_Abstract{

    /*
     * @param card Cms_Card $card
     */
    public function arrayToHtml($card,$data){
        $html = '';
        if(count($data)>0){
            $html .= '<table class="table table-bordered table-striped table-condensed table-hover">';
            foreach($data as $row){
                $html .= '<tr><td>'.$row['label'].'</td><td>'.$row['value'].'</td></tr>';
            }
            $html .= '</table>';
        }
        return $html;
    }

}