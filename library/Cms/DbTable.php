<?php
class Cms_DbTable extends Zend_Db_Table_Abstract
{
    public $_parentColumn = null;
    protected $_parentClassName = null;
    protected $_log = true;
    public $_langColumns = null;

    public function setLogEnable($flag){
        $this->_log = $flag;
        return $this;
    }
    public function getLogEnable(){
        return $this->_log;
    }


    public function getName(){
        return $this->_name;
    }

        public function  delete($where) {

        $result = parent::delete($where);

        if($this->getLogEnable()){
            if(!is_string($where)){
                $where = serialize($where);
            }
            $log = new Cms_Log();
            $logData = array('note'=>$where,'table_name'=>$this->_name,'type'=>'delete');
            $log->insert($logData);
         }
         return $result;
    }

    public function  update(array $data, $where) {

        if(count($data) == 0){
            return 0;
        }

        //automatic null
        foreach($data as $key=>$value){
           if(isset($value) and $value==""){
            $data[$key] = new Zend_Db_Expr("NULL");
          }
        }

        //insert files
        if(!empty($this->_fileColumns)){
            foreach($this->_fileColumns as $fileColumn){
                $delName = 'delete_'.$fileColumn;
                if(!empty($data[$fileColumn])){
                    $this->deleteFile($where,$fileColumn);
                    $newName = $this->uploadFile(array('column'=>$fileColumn));
                    if($newName != false){
                        $data[$fileColumn] = $newName;
                    }
                }
                elseif(!empty($_POST[$delName])){
                    $data[$fileColumn] = '';
                }
                else{
                    unset($data[$fileColumn]);
                }
            }
        }

        //automatic system values
        $data['updated_on'] = date("Y-m-d H:i:s");
        
        $auth = new Cms_Auth();
        $user = $auth->getUser();
        if(isset($user->id)){
            $data['updated_by'] = $user->id;
        }

        //lang columns
        if(!empty($this->_langColumns)){

            $langData = array();
            foreach ($this->_langColumns as $col) {
                if(isset($data[$col])){
                    $langData[$col] = $data[$col];
                    unset($data[$col]);
                }
            }

            if(count($langData)>0){
                $parentData = $this->fetchRow($where);
                
                $langData['updated_on'] = $data['updated_on'];
                if(isset($data['updated_by'])){
                    $langData['updated_by'] = $data['updated_by'];
                }
                $lang = Zend_Registry::get('Language');
                $tableSpec = ($this->_schema ? $this->_schema . '.' : '') . $this->_name.'_lang';
                $this->_db->update($tableSpec, $langData, $this->_name.'_id="'.$parentData['id'].'" and lang_id="'.$lang['id'].'"');
            }
       }

        //clean array
        foreach($data as $key=>$value){
            if(!in_array($key, $this->info('cols'))){
                unset($data[$key]);
            }
        }
        $result = parent::update($data, $where);





        //automatic system values

        /*if(empty($data['url_name'])){
            if(in_array('url_name', $this->info('cols')) and !empty($data['name']) and !empty($tableId)){
                $url = new Cms_Url();
                $url->setModel($this);
                $data['url_name'] = $url->uniqueUrlName($data['name'],$tableId);
                //save it with url_name
                $result = parent::update($data, $where);
            }
        }*/


        if($this->_log){
            if(!is_string($where)){
                $where = serialize($where);
            }
            $log = new Cms_Log();
            $note = $where;
            $tableId = null;

            $logData = array('note'=>$note,'table_name'=>$this->_name,'table_id'=>$tableId,'type'=>'update');
            $log->insert($logData);
         }

         return $result;
    }

    public function insert(array $data){

        //order_num new maximum
        if(!isset($data['order_num']) and in_array('order_num', $this->info('cols'))){
            $select = $this->select();
            $select->from($this, array('max' => new zend_db_expr('max(order_num)+1')));

            if($this->_parentColumn != null){
              //order_num new maximum
              if(empty($data[$this->_parentColumn])){
                $select->where($this->_parentColumn.' is null');
              }else{
                $select->where($this->_parentColumn.' = ?',$data[$this->_parentColumn]);
              }
            }

            $result = $select->query()->fetchobject();
            $data['order_num'] = $result->max;
            if($data['order_num'] == null){
               $data['order_num'] = 1;
            }

        }

        //insert files
        if(!empty($this->_fileColumns)){
            foreach($this->_fileColumns as $fileColumn){
                if(!empty($data[$fileColumn])){
                    $newName = $this->uploadFile(array('column'=>$fileColumn));
                    if($newName != false){
                        $data[$fileColumn] = $newName;
                    }
                }
            }
        }


        //automatic null
        foreach($data as $key=>$value){
            if(isset($value) and $value==""){
                $data[$key] = new Zend_Db_Expr("NULL");
            }
        }

        //automatic system values
        $data['created_on'] = date("Y-m-d H:i:s");
        $data['updated_on'] = date("Y-m-d H:i:s");
        if(empty($data['url_name'])){
            if(in_array('url_name', $this->info('cols')) and !empty($data['name'])){
                $url = new Cms_Url();
                $url->setModel($this);
                $data['url_name'] = $url->uniqueUrlName($data['name']);
            }
        }
        
        $auth = new Cms_Auth();
        $user = $auth->getUser();
        if(isset($user->id)){
            $data['created_by'] = $user->id;
            $data['updated_by'] = $user->id;
        }
        
        //lang columns
        if(!empty($this->_langColumns)){
            $langData = array();
            foreach ($this->_langColumns as $value) {
                $langData[$value] = $data[$value];
            }
        }

        //clean array
        foreach($data as $key=>$value){
            if(!in_array($key, $this->info('cols'))){
                unset($data[$key]);
            }
        }

        $id = parent::insert($data);

        //lang columns
        if(!empty($this->_langColumns)){
            $lang = Zend_Registry::get('Language');

            $langData[$this->_name.'_id'] = $id;
            $langData['lang_id'] = $lang['id'];
            $langData['created_on'] = $data['created_on'];
            $langData['updated_on'] = $data['created_on'];
            
            if(isset($data['created_by'])){
                $langData['created_by'] = $data['created_by'];
                $langData['updated_by'] = $data['created_by'];
            }

            $tableSpec = ($this->_schema ? $this->_schema . '.' : '') . $this->_name.'_lang';
            $this->_db->insert($tableSpec, $langData);

        }



        if($this->_log){
           $log = new Cms_Log();
           $logData = array('table_id'=>$id,'table_name'=>$this->_name,'type'=>'insert');
           $log->insert($logData);
        }

        return $id;

    }

    public function fetchRow($where = null, $order = null, $offset = null) {

        if (!($where instanceof Zend_Db_Table_Select)) {
            $select = $this->select();

            if ($where !== null) {
                $this->_where($select, $where);
            }

            if ($order !== null) {
                $this->_order($select, $order);
            }

            $select->limit(1, ((is_numeric($offset)) ? (int) $offset : null));

        } else {
            $select = $where->limit(1, $where->getPart(Zend_Db_Select::LIMIT_OFFSET));
        }

        /*
        if(!empty($this->_langColumns)){
            $select->setIntegrityCheck(false)
                    ->from($this->_name)
                    ->joinLeft($this->_name.'_lang',
                            $this->_name.'.id = '.$this->_name.'_lang.'.$this->_name.'_id',
                            $this->_langColumns )
                    ->where($this->_name.'_lang.lang_id = "'.$lang['id'].'"');
        }
         */
        if(!empty($this->_langColumns)){
            $lang = Zend_Registry::get('Language');
            $cols = array();
            $table = $this->_name;
            foreach($this->_langColumns as $col){
                $sql = '(select '.$col.' from '.$table.'_lang where '
                        . $table.'.id = '.$table.'_id and '
                        .'( lang_id = "'.$lang['id'].'" or lang_id = 1) and '
                        . $col.' is not null'
                        . ' order by lang_id desc '
                        . ' limit 1'
                        . ')';
                $cols[$col] =  new Zend_Db_Expr($sql);
            }
            $select->from($this->_name)
                    ->columns($cols);
        }
        $rows = $this->_fetch($select);

        if (count($rows) == 0) {
            return null;
        }

        $data = array(
            'table'   => $this,
            'data'     => $rows[0],
            'readOnly' => $select->isReadOnly(),
            'stored'  => true
        );

        $rowClass = $this->getRowClass();
        if (!class_exists($rowClass)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($rowClass);
        }
        return new $rowClass($data);
    }

    public function fetchAll($where = null, $order = null, $count = null, $offset = null) {

        if (!($where instanceof Zend_Db_Table_Select)) {
            $select = $this->select();

            if ($where !== null) {
                $this->_where($select, $where);
            }

            if ($order !== null) {
                $this->_order($select, $order);
            }

            if ($count !== null || $offset !== null) {
                $select->limit($count, $offset);
            }

        } else {
            $select = $where;
        }


        /*
        if(!empty($this->_langColumns)){
            $select->setIntegrityCheck(false)
                    ->from($this->_name)
                    ->joinLeft($this->_name.'_lang',
                            $this->_name.'.id = '.$this->_name.'_lang.'.$this->_name.'_id',
                            $this->_langColumns )
                    ->where($this->_name.'_lang.lang_id = "'.$lang['id'].'"');
        }*/

        if(!empty($this->_langColumns)){
            $lang = Zend_Registry::get('Language');
            $cols = array();
            $table = $this->_name;
            foreach($this->_langColumns as $col){
                $sql = '(select '.$col.' from '.$table.'_lang where '
                        . $table.'.id = '.$table.'_id and '
                        .'( lang_id = "'.$lang['id'].'" or lang_id = 1) and '
                        . $col.' is not null'
                        . ' order by lang_id desc '
                        . ' limit 1'
                        . ')';
                $cols[$col] =  new Zend_Db_Expr($sql);
            }
            $select->from($this->_name)
                    ->columns($cols);
        }


        return parent::fetchAll($select);
    }


        public function getRowByUrlName($url){

        $select= $this->select();
        $select->where('url_name = ?',(string)$url);
        return $this->fetchRow($select);
    }

    public function getRow($id){

        $select = $this->select();
        $select->where($this->_name.'.id = ?',(int)$id);
        return  $this->fetchRow($select);
    }


    public function getChilds($id,$options = array('active'=>1,'order'=>'order_num ASC')){
        if($this->_parentColumn != null){
          $select= $this->select();
          $select->from($this,array('id','tree_id','name','order_num','meta_title',
            'meta_description','meta_keywords','url_name','controller','directory',
            'deleteable','active','created_on','updated_on','main'));

          $select->where($this->_parentColumn.' = ?',(int)$id)->order($options['order']);
          if($options['active']!= null  and in_array('active', $this->info('cols'))){
            $select->where($this->_parentColumn.' = "'.(int)$id.'" and active = "'.(int)$options['active'].'"')->order($options['order']);
          }
          return $this->fetchAll($select);
        }
        return null;
    }

    public function getParentId($id){
        $parentColumn = $this->_parentColumn;
        if($parentColumn == null){
            return null;
        }

        $parentColumn = $this->_parentColumn;
        $row = $this->getRow($id);
        if($row == null){
            return null;
        }

        $id = $row->$parentColumn;
        return $id;
    }



    public function uploadFile($options){
        //$options column, width, height,
        if(!empty($_FILES[$options['column']]['name'])){

            $fileName = strtolower($_FILES[$options['column']]['name']);
            $fileInfo = pathinfo($fileName);

            if($fileInfo['extension'] == 'php'){
                exit;
            }

            $textSafe = new Cms_TextSafe();
            $saveName = $textSafe->url($fileInfo['filename']);
            $newName = $saveName.'-'.substr(md5(time().rand(3, 8)),0,10).'.'.$fileInfo['extension'];

            $targetFile = PUBLIC_PATH.'/'.$this->_filePath.'/'.$newName;

            //upload
            $move = move_uploaded_file($_FILES[$options['column']]['tmp_name'],$targetFile);
            if(!$move){
                throw new Cms_Exception('Move upload file - failed');
            }
            
            //resize
            //var_dump($fileInfo['extension']);exit();

            if(in_array($fileInfo['extension'], array('jpg', 'jpeg', 'gif', 'png'))){
                
                $gd = new Cms_Gd;
                $gd->load($targetFile);
                if(empty($options['width'])){
                    $options['width'] = 1600;
                }
                if(empty($options['height'])){
                    $options['height'] = 1600;
                }
                $gd->autoResize($options['width'],$options['height']);
                $gd->save($targetFile);
                
            }else if(in_array($fileInfo['extension'], array('avi', 'm4v', '3g2', 'wmv', 'mov', '3gp', 'mp4', 'mxf', 'asf', 'flv'))){
                
                $filePath = PUBLIC_PATH.'/'.$this->_filePath.'/';
                $ffmpeg = new Cms_Ffmpeg();
                $ffmpeg->createThumbnail($filePath, $newName);
                $newName = $ffmpeg->convertVideo($filePath, $newName);
            }

            return $newName;
        }
        return false;

    }

    //delete old file from server
    public function deleteFile($where,$column){
        $row = $this->fetchRow($where);
        if(!empty($row->$column) and $row->$column != '.'){
            $oldFile = PUBLIC_PATH.'/'.$this->_filePath.'/'.$row->$column;
            if(file_exists($oldFile)){
                unlink($oldFile);
            }
        }
    }


}
