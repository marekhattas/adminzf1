<?php
class Cms_Form_Element_Image extends Cms_Form_Element_File{


    public function myHelperx(){
        $name = $this->_name;

        $oldFileName = $this->oldFileName;
        $filePath = $this->filePath;
        if(!empty($filePath)){
            $this->setDestination(PUBLIC_PATH.'/'.$filePath);
        }
        $this->setValueDisabled(true);
        //$this->setMaxFileSize(1024*1024*1.5); //1.5MB
        $this->addValidator('Extension', false, 'jpg,jpeg');

        if(!empty($oldFileName) and file_exists(PUBLIC_PATH.'/'.$filePath.'/'.$oldFileName)){
            $delete = '<br /><a class="btn btn-success btn-xs"  href="javascript:deleteFile(\''.$name.'\')">'
                    . '<span class="glyphicon glyphicon-trash"></span> '.Cms_T::_('cms_delete_file').'</a>
                        <input type="hidden" name="delete_'.$name.'" id="delete_'.$name.'" value="0">
                        <script>function deleteFile(name){
                            $("#file_"+name).hide();
                            $("#delete_"+name).val(1);
                        }; </script>';
            $resize = ' style="max-width: 500px;max-height:400px"';
            $this->setDescription('<div id="file_'.$name.'"><img ' . $resize . ' src="/'.$filePath .'/'.$oldFileName.'" />'.$delete.'</div>');
        }
    }

}
