<?php

class Admin_View_Helper_SystemInfos  extends Zend_View_Helper_Abstract
{
    function systemInfos($column)
    {
      $model = new Admin_Model_SystemInfos();
      $data = $model->getRow(1);
      return $data->$column;

    }
}