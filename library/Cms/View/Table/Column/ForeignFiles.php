<?php
class Cms_View_Table_Column_File extends  Cms_View_Table_Column_Default
{

  	protected $_enableHtml = true;

    public function render(){

        $modelFiles = $this->model;
        $rows = $modelFiles->fetchAll('issue_id = "'.$id.'"');
        if(count($rows)>0){
            $html .= '<table class="inlineFiles table table-bordered table-striped table-condensed table-hover">';
            foreach($rows as $row2){
                $html .= '<tr>';
                $html .= '<td><a href="'.'/'.$modelFiles->_filePath.'/'.$row2->file_name.'" target="_blank">'.$row2->file_name.'</td>';
                $html .= '<td>'.$row2->created_on.'</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
        }


        $filePath = $this->filePath;
        $oldFileName = $this->getValue();
        if(!empty($oldFileName) and file_exists(PUBLIC_PATH.'/'.$filePath.'/'.$oldFileName)){
          return '<td><a href="'.'/'.$filePath.'/'.$oldFileName.'" target="_blank">'.$oldFileName.'</a></td>';
        }
        return '<td></td>';
    }
}
