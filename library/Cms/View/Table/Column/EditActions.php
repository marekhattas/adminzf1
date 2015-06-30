<?php
class Cms_View_Table_Column_EditActions extends  Cms_View_Table_Column_Default
{
    public function getThClass() {
        return 'btn-1';
    }
    public function isAllowed() {

        $allowed = true;

        if($this->card!=null){
            $action = 'edit';
            $allowed = $this->card->isAllowed($action,$this->card->getParams());
            if(!$allowed){
                $action = 'view';
                $allowed = $this->card->isAllowed($action,$this->card->getParams());
            }
            if(!$allowed){
                $action = 'detail';
                $allowed = $this->card->isAllowed($action,$this->card->getParams());
            }
        }
        return $allowed;
    }

    public function render(){
        $html = '';

        $html .= '<td class="btn-1">';
        //view
        $data = $this->getData();
        if($this->card->isAllowed('view',array('row'=>$data))){
            if($this->card->getOption('viewModal')){
                $url['id'] = $this->_id;
                $url['parentId'] = null;
                $url['action'] = 'view';
                $ajax = true;
                $link = $this->view->url($url);
                $href = 'href="javascript:ajaxModal(\''.$link.'\')"';
            }else{
                $url['id'] = $this->_id;
                $url['parentId'] = null;
                $url['action'] = 'view';
                $ajax = true;
                $link = $this->view->url($url);

                $href = 'href="javascript:ajaxGetOnClick(\''.$link.'\',\'#card_'.$this->view->controllerClassName().'\')"';
            }
            $html .= '<a  class="btn-edit btn-xs  btn-warning"  '.$href.' title="'.Cms_T::_('cms_edit').': '.$url['id'].'"><span class="glyphicon glyphicon-pencil glyphicon-pencil"></span></a>';
        }
        elseif($this->card->isAllowed('edit',array('row'=>$data))){
            if($this->card->getOption('editModal')){
                $url['id'] = $this->_id;
                $url['parentId'] = null;
                $url['action'] = 'edit';
                $ajax = true;
                $link = $this->view->url($url);
                $href = 'href="javascript:ajaxModal(\''.$link.'\')"';
            }else{
                $url['id'] = $this->_id;
                $url['parentId'] = null;
                $url['action'] = 'edit';
                $ajax = true;
                $link = $this->view->url($url);
                $controllerClassName = $this->view->controllerClassName();
                $href = 'href="javascript:ajaxGetOnClick(\''.$link.'\',\'#card_'.$controllerClassName.'\')"';
            }
            $val = $this->getValue();
            if(empty($val)){
                $val = '<i>'.Cms_T::_('cms_null').'</i>';
            }
            $html .= '<a  class="btn-edit btn btn-xs"  '.$href.' title="'.Cms_T::_('cms_edit').': '.$url['id'].'"><span class="glyphicon glyphicon-pencil"></span></a> ';

        }
        elseif($this->card->isAllowed('detail',array('row'=>$data))){
            if($this->card->getOption('detailModal')){
                $url['id'] = $this->_id;
                $url['parentId'] = null;
                $url['action'] = 'detail';
                $ajax = true;
                $link = $this->view->url($url);
                $href = 'href="javascript:ajaxModal(\''.$link.'\')"';
            }else{
                $url['id'] = $this->_id;
                $url['parentId'] = null;
                $url['action'] = 'detail';
                $ajax = true;
                $link = $this->view->url($url);
                $controllerClassName = $this->view->controllerClassName();
                $href = 'href="javascript:ajaxGetOnClick(\''.$link.'\',\'#card_'.$controllerClassName.'\')"';
            }
            $val = $this->getValue();
            if(empty($val)){
                $val = '<i>'.Cms_T::_('cms_null').'</i>';
            }
            $html .= '<a  class="btn-edit btn btn-xs"  '.$href.' title="'.Cms_T::_('cms_show').': '.$url['id'].'"><span class="glyphicon glyphicon-eye-open"></span></a> ';

        }

        $html .= '</td>';

        return $html;
    }


}


