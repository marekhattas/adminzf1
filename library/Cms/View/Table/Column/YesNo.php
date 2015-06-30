<?php
class Cms_View_Table_Column_YesNo extends  Cms_View_Table_Column_Default
{
    public $success = 1;

    public function render(){
        $val = $this->getValue();

        $label =  'label-danger';

        if($this->success == $val){
            $label = 'label-success';
        }
        if($val == null){
            return '<td></td>';
        }
        elseif($val == 1){
            return '<td><span class="label '.$label.'">'.Cms_T::_('cms_yes').'</span></td>';
        }
        elseif($val == 0){
            return '<td><span class="label '.$label.'">'.Cms_T::_('cms_no').'</span></td>';
        }
    }
}
