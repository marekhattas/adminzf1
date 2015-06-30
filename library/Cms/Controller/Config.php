<?php

class Cms_Controller_Config
{
    public function getMenuItems(){
        //array('name'=>null,'icon'=>nul,'sub'=>array(null,null))
        return array();
    }

    public function init() {
        $options = array(
        'resources'=>array(), //automatic
        'cards'=>array()
     );

        return $options;
    }

}

