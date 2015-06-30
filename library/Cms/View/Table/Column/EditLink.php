<?php
class Cms_View_Table_Column_EditLink extends  Cms_View_Table_Column_Default
{

    public $action = 'edit';

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

        $html = '<td>';

        //
        // edit
        //
        $data = $this->getData();
        $val = $this->getValue();
        if($this->card->isAllowed('edit',array('row'=>$data))){
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

            if(empty($val)){
                $val = '<i>'.Cms_T::_('cms_null').'</i>';
            }

            $html .= '<a '.$href.' class="" title="'.Cms_T::_('cms_edit').': '.$url['id'].'">'.$val.'</a>';
        }elseif($this->card->isAllowed('view',array('row'=>$data))){
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
                $controllerClassName = $this->view->controllerClassName();
                $href = 'href="javascript:ajaxGetOnClick(\''.$link.'\',\'#card_'.$controllerClassName.'\')"';
            }

            if(empty($val)){
                $val = '<i>'.Cms_T::_('cms_null').'</i>';
            }

            $html .= '<a '.$href.' class="" title="'.Cms_T::_('cms_edit').': '.$url['id'].'">'.$val.'</a>';
        }elseif($this->card->isAllowed('detail',array('row'=>$data))){
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

            if(empty($val)){
                $val = '<i>'.Cms_T::_('cms_null').'</i>';
            }

            $html .= '<a '.$href.' class="" title="'.Cms_T::_('cms_edit').': '.$url['id'].'">'.$val.'</a>';
        }else{
            $html .= $val;
        }
        $html .= '</td>';


        return $html;
    }


}


