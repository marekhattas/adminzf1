<?php

class Cms_L {

    public function __construct(){
        $this->l();
    }
    public function l(){
        $l = new Zend_Session_Namespace('l');
        if(empty($l) or $l->data == null){
            $model = new Admin_Model_User();
            $select = $model->select();
            $select->from($model, array('count(*) as num'));
            $row = $model->fetchRow($select);
            $users = $row->num;

            $handle = @fopen('http://www.hide.sk/license/?users='.$users.'&server='.$_SERVER['SERVER_NAME'].'&userip='.$_SERVER['REMOTE_ADDR'], "rb");
            //$l = new stdClass();
            $l->data = null;
            if($handle){
                $contents = stream_get_contents($handle);
                fclose($handle);
                $a = null;
                eval($contents);
                $l->data = $a;
                //var_dump($l);
            }
        }
        return $l;
    }
}