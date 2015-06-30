<?php
class Blog_Model_Search
{

    public function search($val){
        
        $val = htmlspecialchars($val);
        
        $tables = array(
            'Shop_Model_Products'=>array('route'=>'product','columns'=>array('name','text','code','specification')),
            'Blog_Model_Tree'=>array('route'=>'url','columns'=>array('name'))
        );

        $results = array();
        if($val != null and mb_strlen($val)>=3){
            foreach($tables as $table=>$options){
                $model = new $table;
                $select = $model->select();
                foreach($options['columns'] as $column){
                    $select->orWhere($column.' like "%'.$val.'%" ');
                }
                $results[$options['route']] = $model->fetchAll($select,'id desc','30');
            }
        }
        return $results;
    }
}
