<?php
class Blog_Config_Options extends Cms_Controller_Config
{

    public function init() {
        $options = array();

        $i = 'tree';
        $options[$i] = parent::init();
        $options[$i]['title'] = text('Obsah');
        $options[$i]['controller'] = 'Blog_TreeController';
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];
        $options[$i]['showInMenu'] = true;
        $options[$i]['showInMenuOrder'] = 10;
        $options[$i]['icon'] = 'tree';
        $options[$i]['cardList'][0] = array('name'=>"Blog_Card_Tree",'editAction'=>'edit');


        $i = 'productCategories';
        $options[$i] = parent::init();
        $options[$i]['title'] = text('Produktové kategórie');
        $options[$i]['controller'] = 'Blog_ProductCategoryController';
        $options[$i]['isDirectory'] = true;
        $options[$i]['new'] = true;
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_ProductCategories",'editAction'=>'edit');
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_TreeSeo",'editAction'=>'edit');


        $i = 'textPictures';
        $options[$i] = parent::init();
        $options[$i]['title'] = text('Text s obrazkami alebo len galéria');
        $options[$i]['controller'] = 'Blog_TextPicturesController';
        $options[$i]['new'] = true;
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_Texts",'editAction'=>'edit');
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_TextPictures",'editAction'=>'index');
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_TreeSeo",'editAction'=>'edit');

        $i = 'contact';
        $options[$i] = parent::init();
        $options[$i]['title'] = text('Kontakt');
        $options[$i]['new'] = false;
        $options[$i]['controller'] = 'Blog_ContactController';
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_Contacts",'editAction'=>'edit');
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_TreeSeo",'editAction'=>'edit');

        $i = 'dir';
        $options[$i] = parent::init();
        $options[$i]['title'] = text('Priečinok');
        $options[$i]['controller'] = 'Blog_DirController';
        $options[$i]['parentNodes'] = array('Blog_DirController');
        $options[$i]['isDirectory'] = true;
        $options[$i]['new'] = false;
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_Dir",'editAction'=>'edit');
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_TreeSeo",'editAction'=>'edit');

        $i = 'home';
        $options[$i] = parent::init();
        $options[$i]['title'] = text('Úvod');
        $options[$i]['controller'] = 'Blog_HomeController';
        $options[$i]['isDirectory'] = false;
        $options[$i]['new'] = false;
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_Home",'editAction'=>'edit');
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_TextPictures",'editAction'=>'index');
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_TreeSeo",'editAction'=>'edit');

        $i = 'productHome';
        $options[$i] = parent::init();
        $options[$i]['title'] = text('Products');
        $options[$i]['controller'] = 'Blog_ProductHomeController';
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];
        $options[$i]['cardList'][] = array('name'=>"Blog_Card_ProductHome",'editAction'=>'edit');


        $i = 'infos';
        $options[$i] = parent::init();
        $options[$i]['title'] = text('Základné informácie o stránke');
        $options[$i]['controller'] = 'Blog_InfosController';
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];
        $options[$i]['cardList'][0] = array('name'=>"Blog_Card_Infos",'editAction'=>'edit');

        $i = 'footer';
        $options[$i] = parent::init();
        $options[$i]['title'] = text('Pätička');
        $options[$i]['controller'] = 'Blog_FooterController';
        $options[$i]['resources'][$options[$i]['controller']] = text('Prístup k modulu:').' '.$options[$i]['title'];
        $options[$i]['cardList'][0] = array('name'=>"Blog_Card_Texts",'editAction'=>'edit');



        return $options;
    }

}
