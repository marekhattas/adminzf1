<?php
class Default_Bootstrap extends Zend_Application_Module_Bootstrap
{

    protected function _initAutoload() {
        $dir = realpath(dirname(__FILE__));

        $autoloader = new Zend_Application_Module_Autoloader(array(
                'namespace' => 'Default_',
                'basePath'  => $dir,
                'resourceTypes' => array(
                        'node'=>array(
                            'path'=>'/nodes',
                            'namespace'=>'Node_'),
                        'card'=>array(
                            'path'=>'/nodes/cards',
                            'namespace'=>'Card_')
                )
            )
        );

    }


}
