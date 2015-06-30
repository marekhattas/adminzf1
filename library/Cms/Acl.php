<?php

class Cms_Acl extends Zend_Acl{

    public $_resourceList;
    public $_superRole = 1;
    public function __construct(){
    }

    public function isSuperUser($userId = null){
        if($userId == null){
            $auth= new Cms_Auth();
            $userId = $auth->getData()->id;
        }
        $model = new Admin_Model_UserGroup();
        $row = $model->fetchRow('admin_user_id = "'.$userId.'" and admin_group_id = "'.$this->_superRole.'" ');

        if($row != null){
            return true;
        }
        return false;
    }

    public function isSuperRole($id){
        if($id == $this->_superRole){
            return true;
        }
        return false;
    }

    public function  isUserAllowed($resource,$userId = null) {
        if($userId == null){
            $auth = new Cms_Auth();
            if($auth->getData() != null){
                $userId = $auth->getData()->id;
            }
        }
        if(is_numeric($userId)){
            if($this->isSuperUser($userId)){
                return true;
            }
            $model = new Admin_Model_UserGroup();
            $select = $model->select(Zend_Db_Table::SELECT_WITH_FROM_PART);
            $select->setIntegrityCheck(false)
                    ->join('admin_acl','admin_user_group.admin_group_id =  admin_acl.admin_group_id',null)
                    ->where('admin_user_group.admin_user_id = ?', $userId)
                    ->where('admin_acl.resource = ?', $resource);
            $row = $model->fetchRow($select);
            if($row != null){
                return true;
            }
        }
        return false;

    }

    public function  isAllowed($role = null, $resource = null, $privilege = null) {
        $model = new Admin_Model_Acl();
        $select = $model->select();
        $select = $select->where('admin_group_id = "'.$role.'" and resource="'.$resource.'" ');
        $row = $model->fetchRow($select);
        if($row != null){
            return true;
        }
        return false;
    }

    public function  getResourceList() {

        $dir0 = APPLICATION_PATH."/modules/";
        // Open a known directory, and proceed to read its contents
        if (is_dir($dir0) and $dh0 = opendir($dir0)) {
            while (($file0 = readdir($dh0)) !== false) {
                $dir = $dir0.$file0;

                //echo $dir . $file.'<br />';
                if(file_exists($dir.'/configs/Options.php')){
                    $module = $file0;
                    $objName = ucfirst($module).'_Config_Options';
                    $obj = new $objName;
                    $controllers = $obj->getOptions();

                    foreach($controllers as $name=>$controller){
                        if(empty($controller['resources'][$name])){
                            $controller['resources'][ucfirst($module).'_'.ucfirst($name).'Controller'] = ucfirst($module).'_'.ucfirst($name).'Controller';

                            if(isset($controller['cards']) and !empty($controller['cards'][0])){
                                $card = new $controller['cards'][0];
                                $title = $card->getTitle();
                                $controller['resources'][ucfirst($module).'_'.ucfirst($name).'Controller'] = ucfirst($module).' :: '.$title;
                            }
                        }
                        foreach($controller['resources'] as $key=>$resource){
                            $this->_resourceList[$key] = $resource;
                            $this->addResource($key);
                        }


                    }
                }
            }
            closedir($dh0);
        }
        ksort($this->_resourceList);
        return $this->_resourceList;
    }
}