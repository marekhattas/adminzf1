<?php
class Blog_Model_Tree extends Cms_DbTable
{
    /** Table name */
    protected $_name    = 'blog_tree';
    public $_parentColumn = 'tree_id';


    public function insert(array $data) {
        $config = new Blog_Config_Options();
        $options = $config->init();
        $node = $options[$data['controller']];
        
        $data['directory'] = '0';
        if(isset($node['isDirectory']) and $node['isDirectory'] === true){
            $data['directory'] = '1';                
        }
              
        $id =  parent::insert($data);
        $card = new $node['cardList'][0]['name'];
        //pozor insert musim volat uz priamo cez model,, inak by som sa zacyklil
        $card->getModel()->insert(array('id'=>$id));
        
        return $id;
    }
    
    public function update(array $data, $where) {
        
        $result = parent::update($data, $where);
        //toto by sice nemalo nastať ale tak pre istotu aby som sa potom nedivil
        if($result == 1 and !empty($data['controller'])){
            $config = new Blog_Config_Options();
            $options = $config->init();
            $controller = $options['controller'];

            $data['directory'] = '0';
            if($controller['isDirectory'] === true){
                $data['directory'] = '1';                
            }
            $result = parent::update($data, $where);
        }
        
        return $result;
    }
    
    public function getAllParents($id,&$data = array()){
        $row = $this->getRow($id);
        $data[] = $row;
        if($row->tree_id != null){
            return $this->getAllParents($row->tree_id,$data);
        }
        return $data;
    }


    public function getChilds($id,$options = array('active'=>'1','order'=>'order_num DESC')){
        return parent::getChilds($id,$options);
    }

    //fixme
    public function getMainCategory($id,$toId = 7){
        $node = $this->_treeMapper->getParentNode($id);
        if($node->getId() == $toId){
            $seo = new Structure_Model_Seo();
            $row = $seo->getDbTable()->fetchRow("id='".$id."'");
            $url = $row['url'];
            $card = new Menu_Card_Top();
            $row = $card->getTableMapper()->fetchRow(array('where'=>'id="'.$id.'"'));
            if($row == null){
                return array('id'=>$id,'title'=>null,'url'=>$url);
            }
            return array('id'=>$id,'title'=>$row->title,'url'=>$url);
        }
        else{
            return $this->getMainCategory($node->getId(),$toId);
        }
    }



    public function getTreeChilds($id = null, $options = array()){       
        $maxLevel = null;
        if(isset($options['maxLevel'])){
            $maxLevel = $options['maxLevel'];            
        }

        $onlyDir = false;
        if(isset($options['onlyDir'])){
            $onlyDir = $options['onlyDir'];            
        }
        
        $dir = '';
        if($onlyDir){
            $dir = ' and directory = "1"';
        }
        
        $data = array();        
        if($id == null){
            $rows = $this->fetchAll('tree_id is null'.$dir,'order_num DESC');
        }else{
            $rows = $this->fetchAll('tree_id = "'.$id.'"'.$dir,'order_num DESC');
        }
        if($rows != null and ($maxLevel===null or $maxLevel>0)){
            if($maxLevel != null){
                $maxLevel--;
            }
            foreach($rows as $row){
                $data[] = array('id'=>$row->id,'row'=>$row,'sub'=>$this->getTreeChilds($row->id,$options));
            }
        }
        return $data; 
    }

    public function treeList($childs, $distance = "- "){
        $result = array();
        foreach($childs as $value){
            $result[$value['id']] = array('id'=>$value['id'], 'row'=>$value['row'],'distance'=>$distance);

            if (!empty($value['sub'])){
                $result = $result + $this->treeList($value['sub'],$distance."- ");
            }
        }
        return $result;

    }

}