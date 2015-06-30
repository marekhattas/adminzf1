<?php
class Cms_Log extends Admin_Model_Log
{
    public function insert(array $data){
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
        if(!isset($data['admin_user_id'])){
            $auth= new Cms_Auth();
            if($auth->isAuth()){
                $data['admin_user_id'] = $auth->getData()->id;
            }
        }
        if(!empty($data['admin_user_id'])){
            return parent::insert($data);            
        }
    }
}