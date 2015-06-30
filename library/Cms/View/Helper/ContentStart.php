<?php
class Cms_View_Helper_ContentStart extends Cms_View_Helper_Abstract{

    public function contentStart($card){
        $html = '';
        $data = $card->getBreadCrumb();

        //messages
        $message = $card->pushMessage();
        if(!empty($message)){
            $html .= '<div class="cms-message">'.$message.'</div>';
        }

        if(count($data)>0){
            foreach($data as $row){
                $url = $this->view->url();
                if(!empty($row['url'])){
                    $url = $this->view->url($row['url'][0],$row['url'][1],$row['url'][2]);
                }
                $card = '#card_'.$this->view->controllerClassName();
                $html .= '<div class="breadcrumbx hide">'
                        . '<h5>'
                        . '<a cms-url="'.$url.'" cms-card="'.$card.'" class="link">'.$row['name'].'</a>'
                        . '</h5>'
                        . '</div>';
            }
        }
        return $html;
    }
}