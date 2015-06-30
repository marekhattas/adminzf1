<?php
class Blog_Bootstrap extends Zend_Application_Module_Bootstrap
{

    protected function _initAutoload() {
        $dir = realpath(dirname(__FILE__));

        $autoloader = new Zend_Application_Module_Autoloader(array(
                'namespace' => 'Blog_',
                'basePath'  => $dir,
                'resourceTypes' => array(
                        'card'=>array(
                            'path'=>'/cards',
                            'namespace'=>'Card_'),
                        'view'=>array(
                            'path'=>'/views',
                            'namespace'=>'View_'),
                        'config'=>array(
                            'path'=>'/configs',
                            'namespace'=>'Config_')
                )
            )
        );

    }


}
