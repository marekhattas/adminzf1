<?php
class Cms_DbTableUpdateParent extends Cms_DbTable
{
    public function insert(array $data) {
        $ret = parent::insert($data);

        if(!empty($data[$this->_parentColumn])){
            $id = $data[$this->_parentColumn];
            $class = new $this->_parentClassName();
            $class->update(array(),'id = "'.$id.'"');
        }

        return $ret;
    }

    public function update(array $data, $where) {
        $ret = parent::update($data, $where);

        $data = $this->fetchAll($where);
        if(count($data)>0){
            $class = new $this->_parentClassName();
            foreach($data as $val){
                $pc = $this->_parentColumn;
                $id = $val->$pc;
                $class->update(array(),'id = "'.$id.'"');
            }
        }

        return $ret;
    }

    public function delete($where) {
        $data = $this->fetchAll($where);
        $ret = parent::delete($where);

        if($ret>0){
            if(count($data)>0){
                $class = new $this->_parentClassName();
                foreach($data as $val){
                    $pc = $this->_parentColumn;
                    $id = $val->$pc;
                    $class->update(array(),'id = "'.$id.'"');
                }
            }
        }

        return $ret;
    }

}
