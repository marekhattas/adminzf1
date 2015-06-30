<?php
class Cms_View_Table_Column_ForeignKey extends  Cms_View_Table_Column_Default
{

  	protected $_enableHtml = true;
  	public $column = 'name';

    public function render(){
        $foreignId = $this->getValue();
        $value = ' - ';
        if($foreignId != null){
            $row = $this->model->getRow($foreignId);
            $c = $this->column;
            if(isset($row->$c)){
                $value = $row->$c;
            }
        }
        return '<td>'.$value.'</td>';
    }
}
