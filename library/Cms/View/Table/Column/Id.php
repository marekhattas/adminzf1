<?php
class Cms_View_Table_Column_Id extends  Cms_View_Table_Column_Default
{

    public function render(){
        return "<td style='width:20px' align='right'>".$this->getValue()."</td>";
    }
}
