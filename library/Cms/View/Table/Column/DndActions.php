<?php
class Cms_View_Table_Column_DndActions extends  Cms_View_Table_Column_Default
{

    public $action = 'dnd';

    public function getThClass() {
        return 'btn-1';
    }

    public function render(){
        $html = '';


        $html .= '<td class="btn-1">';
        //
        // sort
        //
        $data = $this->getData();
        if($this->card->isAllowed('dndSort',array('row'=>$data))){
            $html .= '<a class="btn btn-xs btn-handle handle" title="'.Cms_T::_('cms_sort').'"><span class="glyphicon glyphicon-sort "></span></a> ';
        }

        $html .= '</td>';

        return $html;
    }


}


