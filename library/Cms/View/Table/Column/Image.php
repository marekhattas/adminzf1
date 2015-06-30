<?php
class Cms_View_Table_Column_Image extends  Cms_View_Table_Column_Default
{

  	protected $_enableHtml = true;

    public function render(){

        $filePath = $this->filePath;
        $oldFileName = $this->getValue();
        if(!empty($oldFileName) and file_exists(PUBLIC_PATH.'/'.$filePath.'/'.$oldFileName)){
          return '<td><img src="'.$this->view->baseUrl().'/img/folder/'.urlencode($filePath).'/size/adminthumb/image/'. $oldFileName.'"  /></td>';
        }
        return '<td></td>';
    }
}