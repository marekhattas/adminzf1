<?php
class Cms_View_Table_Column_EditLinkMultiValue extends  Cms_View_Table_Column_Default
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
    
    
    public function setData($dbData){
        $this->_dbData = $dbData;
        if(isset($dbData->id)){
            $this->_id = $dbData->id;
        }
        $names = $this->getName();
        $values = array();
        if (is_array($names)) {
            foreach ($names as $name) {
                if(!isset($dbData->$name)){
                    $dbData->$name = null;
                }
                $values[$name] = $dbData->$name;
            }
        } else {
            if(!isset($dbData->$names)){
                $dbData->$names = null;
            }
            $values[$names] = $dbData->$names;
        }
        
        $this->setValue($values);
        return $this;
    }
    
    public function getValue(){
        if(!$this->escape){
            return $this->_value;
        }
        else{
            $values = array();
            foreach ($this->_value as $value) {
                $values[] = strip_tags($value);
            }
            return $values;
        }
    }
    
    public function render(){

        $html = '<td>';
        //
        // edit
        //
        $data = $this->getData();
        $values = $this->getValue();

        if(count($values)>1){
            $html .= '<ul>';
            foreach ($values as $val) {
                $html .= '<li>';
                $html .= $this->getLink($data, $val);
                $html .= '</li>';
            }
            $html .= '</ul>';
        } else {
            $html .= $this->getLink($data, $values[0]);
        }

        $html .= '</td>';

        return $html;
    }
    
    private function getLink($data,$val){
        
        $html = '';
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
            
        return $html;
    }


}


