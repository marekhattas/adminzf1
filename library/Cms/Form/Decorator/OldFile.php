<?php

/** Zend_Form_Decorator_Abstract */
require_once 'Zend/Form/Decorator/Abstract.php';

class Cms_Form_Decorator_OldFile extends Zend_Form_Decorator_Abstract
{

    /**
     * Render a description
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $separator = $this->getSeparator();
        $placement = $this->getPlacement();

        $html = '';

        $name = $element->getName();
        $oldFileName = $element->getOldFileName('oldFileName');
        $filePath = $element->getModel()->_filePath;

        if(!empty($oldFileName)){
            $delete = '<br /><a class="btn btn-success btn-xs" onclick="javascript:deleteFile(\''.$name.'\')">'
                    . '<span class="glyphicon glyphicon-trash"></span> '.Cms_T::_('cms_delete_file').'</a>
                    <input type="hidden" name="delete_'.$name.'" id="delete_'.$name.'" value="0">
                    <script>function deleteFile(name){
                        $("#file_"+name).hide();
                        $("#delete_"+name).val(1);
                    }; </script>';
			$imageExtensions = array(".jpg", ".png", ".gif");
			if (in_array(strtolower(mb_substr($oldFileName,-4,4)), $imageExtensions, true)) {
				$resize = ' class="img-responsive" ';
				$html = '<br /><div id="file_'.$name.'"><a target="_blank" href="/'.$filePath ."/". $oldFileName.'"><img ' . $resize . ' src="/'.$filePath .'/'.$oldFileName.'" /></a>'.$delete.'</div>';
			} else {
				$html = '<br /><div id="file_'.$name.'"><a target="_blank" href="/'.$filePath ."/". $oldFileName.'">'.$oldFileName.'</a>'.$delete.'</div>';
			}
        }

        switch ($placement) {
            case self::PREPEND:
                return $html . $separator . $content;
            case self::APPEND:
            default:
                return $content . $separator . $html;
        }
    }
}
