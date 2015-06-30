<?php
class Admin_Config_Options extends Cms_Controller_Config
{

    public function init() {
        $options = array();

        $i = 'index';
        $options[$i] = parent::init();
        $options[$i]['controller'] = 'Admin_IndexController';
        $options[$i]['title'] = text('Prihlásenie');
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];        

        $i = 'dashboard';
        $options[$i] = parent::init();
        $options[$i]['controller'] = 'Admin_DashboardController';
        $options[$i]['title'] = text('Základné informácie');
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];        

        $i = 'profile';
        $options[$i] = parent::init();
        $options[$i]['title'] = text('Váš Profil');
        $options[$i]['controller'] = 'Admin_ProfileController';
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];
        $options[$i]['cardList'][0] = array('name'=>"Admin_Card_Profiles",'editAction'=>'edit');
    
        $i = 'user';
        $options[$i] = parent::init();
        $options[$i]['title'] = text('Užívatelia');
        $options[$i]['controller'] = 'Admin_UserController';
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];        
        $options[$i]['showInMenu'] = true;
        $options[$i]['showInMenuOrder'] = 200;
        $options[$i]['icon'] = 'people';
        $options[$i]['cardList'][0] = array('name'=>"Admin_Card_Users",'editAction'=>'edit');
        $options[$i]['cardList'][1] = array('name'=>"Admin_Card_Groups",'editAction'=>'edit');
        $options[$i]['cardList'][2] = array('name'=>"Admin_Card_Log",'editAction'=>'edit');        

        return $options;
    }

}
