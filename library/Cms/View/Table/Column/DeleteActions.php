<?php
class Cms_View_Table_Column_DeleteActions extends  Cms_View_Table_Column_Default
{

    public $action = 'confirmDelete';

    public function getThClass(){
        return 'btn-1';
    }

    public function render(){
        $html = '';
        $html .= '<td class="btn-1">';
        //
        // delete
        //
        $data = $this->getData();
        if($this->card->isAllowed('confirmDelete',array('row'=>$data)) and (!isset($data->deleteable) or $data->deleteable==1)){
            $url = array();
            $url['id'] = $this->getValue();
            $url['action'] = 'confirmDelete';
            $html .= '<a class="btn btn-xs btn-delete"  onClick="ajaxModal(\''.$this->view->url($url).'\')" title="'.Cms_T::_('cms_delete').': '.$url['id'].'"><span class="glyphicon glyphicon-trash "></span></a> ';
        }
        $html .= '</td>';

        return $html;
    }


}


