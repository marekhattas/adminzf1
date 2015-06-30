<?php

class Admin_View_Helper_Menu  extends Zend_View_Helper_Abstract
{
    function menu()
    {

        $dir0 = APPLICATION_PATH."/modules/";
        $menu = array();
        $acl = new Cms_Acl();
        // Open a known directory, and proceed to read its contents
        if (is_dir($dir0) and $dh0 = opendir($dir0)) {
            while (($file0 = readdir($dh0)) !== false) {
                $dir = $dir0.$file0;

                //echo $dir . $file.'<br />';
                if(file_exists($dir.'/configs/Options.php')){
                    $objName = ucfirst($file0).'_Config_Options';
                    $obj = new $objName;
                    $controllers = $obj->init(); 

                    foreach($controllers as $value){
                        if($value['showInMenu'] and $acl->isUserAllowed($value['controller'])){                            
                            $menu[$value['showInMenuOrder']] = $value;                                      
                        }
                    }
                }
            }
            closedir($dh0);
        }
        ksort($menu);
        return $menu;
    }
}