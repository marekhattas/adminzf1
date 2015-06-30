<?php
class Admin_Card_Log extends Cms_Card
{

    protected $_actions = array('index'=>true);

    public function init(){

        $this->setTitle(text("Log"));
        $this->_options['defaultCard'] = false;
        $this->setModel("Admin_Model_Log");
        $this->setOption('order','desc');
        $this->setOption('orderColumn','id');

    }

    public function indexColumns(){
        $columns = array();
        $columns[] = new Cms_View_Table_Column_Id('id',array('label'=>text('id')));
        $columns[] = new Cms_View_Table_Column_ForeignKey('user_id',array('label'=>text('Užívateľ'),'model'=>new Admin_Model_Users()));
        $columns[] = new Cms_View_Table_Column_Default('type',array('label'=>text('Udalosť')));
        $columns[] = new Cms_View_Table_Column_Default('table_name',array('label'=>text('Tabuľka')));
        $columns[] = new Cms_View_Table_Column_Default('table_id',array('label'=>text('Id záznamu')));
        $columns[] = new Cms_View_Table_Column_Default('note',array('label'=>text('Poznámka')));
        $columns[] = new Cms_View_Table_Column_Date('created_at',array('label'=>text('Dátum')));

        return $columns;
    }

    public function indexData(){

        $select = $this->getModel()->select();
        $select->order($this->getOption('orderColumn').' '.$this->getOption('order'));
        $parentColumn = $this->getModel()->_parentColumn;
        if($parentColumn != null){
          if($this->getParam('id') != null){
            $select->where($parentColumn.' = "'.$this->getParam('id').'"');
          }else{
            $select->where($parentColumn.' is null');
          }
        }
        $select->limit(30);
        $data = $this->getModel()->fetchAll($select);
        return $data;
    }

}