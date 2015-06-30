<?php

class BLog_PublicController extends Zend_Controller_Action
{

    public function init(){
        $this->_helper->layout->setLayout('default');
        $this->view->currentCategories = array();
        $this->view->info = array();
        $this->view->info['meta_title'] = '';
        $this->view->info['meta_description'] = '';
        $this->view->info['meta_keywords'] = '';
        $this->view->isDir = false;
        $this->view->homepage = false;
    }
    public function testAction(){
/*
        $tatra = new TatraPay (4172, 'cj8i1;jT');
        $tatra->set_cs('0308');
        $tatra->set_vs(11121211);

        $tatra->set_amt(20.30);
        $tatra->set_curr(978);
        $tatra->set_rurl('http://'.$_SERVER["HTTP_HOST"].'/eshop/checkout/');
        $sign = $tatra->calculate_send_sign();
*/
        require(APPLICATION_PATH.'/../library/Cms/TatraBanka.php');
        $objTP = new TatraPay(20.30, '978', 11121211, 'http://'.$_SERVER["HTTP_HOST"].'/eshop/checkout/');
        echo $objTP->GetUrl();
        exit;
    }

    public function initNode($settings = array()){

        if(!isset($settings['id'])){
            $id = $this->_getParam('id');
        }else{
            $id = $settings['id'];
        }
        $model = new Blog_Model_Tree();

        $row = $model->getRow($id);
        $this->view->tree = $row;

        $config = new Blog_Config_Options();
        $options = $config->init();
        $node = $options[$row->controller];
        $cardName = $node['cardList'][0]['name'];
        $card = new $cardName();
        $model1 = $card->getModel();
        $texts = $model1->getRow($id);
        $this->view->data = $texts;


        //meta informations

        $this->view->info['meta_title'] = $row->meta_title;
        $this->view->info['meta_description'] = $row->meta_description;
        $this->view->info['meta_keywords'] = $row->meta_keywords;
        if(empty($row->meta_title)){
            $this->view->info['meta_title'] = $row->name;
        }

        //current categories
        $this->view->currentCategories[$id] = true;
        while($row!=null and $row->tree_id != null){
            $this->view->currentCategories[$row->tree_id] = true;
            $row = $model->getRow($row->tree_id);
        }






        $model3 = new Blog_Model_TextPictures();
        $pictures = $model3->fetchAll('text_id = "'.$id.'" ','order_num ASC');
        $this->view->pictures = $pictures;

        //childs
        if(isset($settings['childs']) and $settings['childs']===true){
            $childs = $model->getChilds($id);
            if($childs != null){
                $this->view->childs =array();
                foreach($childs as $child){
                    $data = array();
                    $data['tree'] = $child;

                    $node = $options[$row->controller];
                    $cardName = $node['cardList'][0]['name'];
                    $card = new $cardName();
                    $model1 = $card->getModel();

                    $texts = $model1->getRow($child->id);
                    $data['data'] = $texts;

                    $pictures = $model3->fetchAll('text_id = "'.$child->id.'" ','order_num ASC');
                    $data['pictures'] = $pictures;
                    $this->view->childs[] = $data;
                }
            }
        }
    }

    public function urlAction(){
        $urlName = $this->_getParam('urlName');

        $model = new Blog_Model_Tree();
        $row = $model->getRowByUrlName($urlName);
        if($row == null){
            throw new Cms_Exception('Stránka sa nenašla');
        }
        $this->_setParam('id',$row->id);

        $action = lcfirst($row->controller);
        if(method_exists($this, $action.'Action')){
            $this->_forward($action);
        }else{
            $this->initNode(array('id'=>$row->id,'childs'=>true));
        }

    }


    public function indexAction(){
        $this->view->homepage = true;
        $this->initNode(array('id'=>17));

    }

    public function searchAction(){

        $this->view->info['meta_title'] = text('Search');

        $model = new Blog_Model_Search();
        $this->view->results = $model->search($this->_getParam('search'));
        $this->view->val = $this->_getParam('search');

    }

    public function dirAction()
    {
        $this->initNode(array('childs'=>true));

    }
    public function contactAction()
    {

        $this->initNode();

        $model = new Blog_Model_Contacts();

        $request = $this->getRequest();
        if ($request->isPost()) {
            if($model->mail($request->getPost())){
                $this->view->ok = true;
            }
            else{
                $this->view->error = true;
            }
        }

        $this->view->form =  $model->getForm();


        // action body
    }

    public function formAction()
    {
        $this->initNode();

        $form = $this->form();

        $request = $this->getRequest();
        if ($request->isPost()) {
            if($this->mailForm($request->getPost(),$form,'info@MerkurReality.sk')){
                $this->view->ok = true;
            }
            else{
                $this->view->error = true;
            }
        }

        $this->view->form = $form;
    }


    public function form(){
        $form = new Zend_Form();

        $elm = new Zend_Form_Element_Text('name',array('label'=>'Meno:*'));
        $elm->setAttrib('class', 'text');
        $elm->setRequired(true);
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Text('email',array('label'=>'Email:'));
        $elm->setAttrib('class', 'text');
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Text('phone',array('label'=>'Tel.Číslo:*'));
        $elm->setAttrib('class', 'text');
        $elm->setRequired(true);
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Select('type',array('label'=>'Vaša požiadavka:*'));
        $elm->addMultiOption('Chcem predať / ponúkam prenájom nehnuteľnosti','Chcem predať / ponúkam prenájom nehnuteľnosti');
        $elm->addMultiOption('Chcem kúpiť / hľadám prenájom nehnuteľnosti','Chcem kúpiť / hľadám prenájom nehnuteľnosti');
        $elm->addMultiOption('Iné','Iné');
        $elm->setRequired(true);
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Textarea('text',array('label'=>'Text správy'));
        $elm->setAttrib('rows', '3');
        $elm->setAttrib('cols', '40');
        if(!empty($_GET['text'])){
            $elm->setValue($_GET['text']);
        }
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Submit('submit',array('label'=>'Odoslať formulár'));
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Text('iamrobot',array('label'=>'Toto pole nechajte prosím prázdne'));
        $elm->setAttrib('size', '30');
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Text('iamhuman',array('label'=>'Do tohto poľa napíšte "ano"'));
        $elm->setAttrib('size', '30');
        //$elm->setValue('ano');
        $form->addElement($elm);

        return $form;
    }

    public function mailForm($post,$form,$email){

            if ($form->isValid($post) and empty($post['iamrobot']) and $post['iamhuman'] == 'ano') {
                $data = $form->getValues();
                $text = $data['name']."\n".$data['email']."\n".$data['phone']."\n".$data['type']."\n".$data['text']."\n\n ip:".$_SERVER['REMOTE_ADDR'];

                $mail = new Zend_Mail('UTF-8');
                $mail->setSubject($data['name'].' - '.$data['type']);
                $mail->setBodyText($text);

                if(!empty($data['email'])){
                    $mail->setFrom($data['email']);
                }
                $mail->addTo($email);
                return $mail->send();
            }
      return false;
    }

}

