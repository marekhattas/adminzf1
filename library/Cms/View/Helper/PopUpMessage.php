<?php
class Cms_View_Helper_PopUpMessage  extends Cms_View_Helper_Abstract {
    public function popUpMessage(){
        $html = '';

        return $html;

        if(!empty($this->text)){

          $html =   '<div class="alert alert-success flashes">
              '.$this->view->escape($this->text).' <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
        }
        return $html;
    }

}
