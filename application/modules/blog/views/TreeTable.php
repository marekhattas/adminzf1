<?php
class Blog_View_TreeTable extends Cms_View_Abstract  {

    public function render(){

       $data = $this->data;

        $html="";

        $html .= '
            <div class="paginated_collection">
            <div class="paginated_collection_contents">
            <div class="index_content">
            <div class="index_as_table">
            ';

        $html .= '<div id="table_'.$this->card.'dndTreeSort">
                    '.$this->treeList($data).'</div>';

        $html .='
        </div>
        </div>
        </div>
        </div>
        ';


         return $html;
    }

    public function treeList($childs){
        $html = '<ul>';
        foreach($childs as $value){
            $class = '';
            if($value['row']->directory == '1'){
                $class = ' class="dir"';
            }
            $active = "aktívny ";
            if($value['row']->active == '0'){
                $active = "neaktívny ";
            }

            $html .= '
                <li id="'.$value['row']->id.'"'.$class.' style="width:400px;">
                <a style="width:300px" href="'.$this->view->url(array('action'=>'edit','id'=>$value['row']->id,'card'=>null)).'">'.$value['row']->name.'</a> <a href="/'.$value['row']->url_name.'" target="_blank">/'.$value['row']->url_name.'</a>
                <span style="position:absolute;left:45px;text-align:right;width:100px">'.$active.'
                <a title="" class="view_link status" href="'.$this->view->url(array('action'=>'edit','id'=>$value['row']->id,'card'=>null)).'">E</a>';

            if($value['row']->deleteable == 1){
            $html .= '
                <a title="" class="view_link status red" href="javascript:void(0)" onclick="confirmDelete(\''.$this->view->url(array('action'=>'delete','id'=>$value['row']->id)).'\',\''.$this->card.'\',\''.$value['row']->name.'\')">x</a>
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