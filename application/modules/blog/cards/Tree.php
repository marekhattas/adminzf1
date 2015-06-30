<?php
class Blog_Card_Tree extends Cms_CardTree
{


    protected $_actions = array('index'=>true,'edit'=>true,'delete'=>true,'new'=>true,'dndTreeSort'=>true);

    public function init(){
        $this->setTitle(text('Štruktúra'));
        $this->_options['defaultCard'] = true;
        $this->setModel('Blog_Model_Tree');
        $this->setOption('order',array('order_num desc'));

    }


    public function indexAction($render = true){

        $container = $this->defaultTemplate();
        $container->add('closeButton', $this->show('button',array('title'=>text('Zavrieť'),'url'=>'/admin')));

        //add button
        if($this->getAction('new') == true){
            $container->add('newButton', $this->show('button',array('title'=>text('Nový záznam'),'url'=>$this->getNewLink())));
        }

        //table
        $tableDb = $this->show('Blog_View_TreeTable',
            array('data'=>$this->indexData(),
                  'orderColumn'=>$this->getOption('orderColumn'),
                  'order'=>$this->getOption('order'),
                  'card' => $this->getCardName()
            ));
        $container->add('table_Db', $tableDb);


        if($this->getAction('dndTreeSort') == true){
            $container->add('dndTreeSort', $this->show('dndTreeSort',array('card'=>$this->getCardName())));
        }

        $this->getZca()->view->htmlContainer = $container;
        if($render){
          $this->getZca()->renderScript('render.phtml');
        }
    }

    public function indexData(){

        $data = $this->getTreeModel()->getTreeChilds();
        
        return $data;
    }
    
}