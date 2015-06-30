<?php

class Zend_View_Helper_Info  extends Zend_View_Helper_Abstract
{
    function info($type,$val = null)
    {
        $model = new Blog_Model_Infos();
        $data = $model->getRow(1);
        
        $return  = $data->$type;            
        if($type == 'meta_title'){
            if($val == null){
                $model2 = new Blog_Model_Tree();
                $data2 = $model2->getRow(1);
                $return .= ' | '. $data2->meta_title;
            }else{
                $return .= ' | '. $val;
            }
        }elseif($val != null){
            $return = $val;
        }
        
        return $return;
    }
}