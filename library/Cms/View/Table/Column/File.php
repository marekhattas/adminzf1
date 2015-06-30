<?php
class Cms_View_Table_Column_File extends  Cms_View_Table_Column_Default
{

  	protected $_enableHtml = true;

    public function render(){

        $filePath = $this->filePath;
        $oldFileName = $this->getValue();
        if(!empty($oldFileName) and file_exists(PUBLIC_PATH.'/'.$filePath.'/'.$oldFileName)){
          return '<td><a href="/'.$filePath.'/'.$oldFileName.'" target="_blank">'.$oldFileName.'</a></td>';
        }
        return '<td></td>';
    }
}
