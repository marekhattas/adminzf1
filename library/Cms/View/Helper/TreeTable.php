<?php
class Cms_View_Helper_TreeTable extends Cms_View_Helper_Abstract  {

    public function treeTable(){

       $data = $this->data;

        $html='';


        $html .= '<div id="table_'.$this->card.'dndTreeSort">
                    '.$this->treeList($data).'</div>
                  <script>
                  position = $("h3").position();
                  $(".defaultTreeButtons").css("position","absolute");
                  $(".defaultTreeButtons").css("left",position.left+7);
                  $(".defaultTreeButtons").css("text-align","right");
                  $(".defaultTreeButtons").css("width","100px");

                  </script>

        ';




         return $html;
    }

    public function treeList($childs){
        $html = '<ul>';
        foreach($childs as $value){

            $class = ' class="dir"';
            if(isset($value['row']->directory) and $value['row']->directory != '1'){
                    $class = '';
            }

            $active = '';
            if(isset($value['row']->active)){
                $active = 'active ';
                if($value['row']->active == '0'){
                    $active = 'inactive ';
                }
            }

            $html .= '
                <li id="'.$value['row']->id.'" '.$class.'>';

            $html .= '<a href="'.$this->view->url(array('action'=>'edit','id'=>$value['row']->id,'card'=>null)).'">'.mb_substr($value['row']->name,0,50).'</a>';

            $html .= '
                <span class="defaultTreeButtons">'.$active.'
                <a  class="btn btn-xs  btn-warning"   href="'.$this->view->url(array('action'=>'edit','id'=>$value['row']->id,'card'=>null)).'" title="'.Cms_T::_('cms_edit').': '.strip_tags($value['row']->name).'"><span class="glyphicon glyphicon-pencil glyphicon-white"></span></a>
                ';

            if(isset($value['row']->deleteable) and $value['row']->deleteable == 1){
                $url = array();
                $url['id'] = $value['row']->id;
                $url['action'] = 'delete';
                $url['card'] = $this->card;
                $name = $value['row']->name;

                $html .= '
                <a class="btn btn-xs btn-danger" href="javascript:void(0)"  onClick="confirmDelete(\''.$this->view->url($url).'\',\''.$url['card'].'\',\''.$name.'\')" title="'.Cms_T::_('cms_delete').'"><span class="glyphicon glyphicon-remove glyphicon-white"></span></a>
                ';
            }else{
                $html .= '
                <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                ';
            }
            $html .= '</span>';

            if (!empty($value['sub'])){
                $html .=  $this->treeList($value['sub']);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;

    }
}