<?php
class Blog_Card_TreeSeo extends Cms_Card
{
    protected $_actions = array('edit' =>true,'index'=>false);

    public function init(){
        $this->setTitle(text("SEO"));
        $this->setModel("Blog_Model_Tree");
    }

    public function editForm(){
        $form = parent::editForm();

        $elm = new Zend_Form_Element_Text('meta_title', array("label"=> text('Meta title')));
        $elm->setDescription(text('Názov, ktorý sa bude zobrazovať v hlavičke na výstupe tejto strany.'));
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Textarea('meta_keywords', array("label"=> text('Meta keywords'), 'rows' => 5));
        $elm->setDescription(text('Kľúčové slová, najlepšie oddelené čiarkov, ktoré slúžia pre vyhľadávače'));
        $form->addElement($elm);

        $elm = new Zend_Form_Element_Textarea('meta_description', array("label"=> text('Meta description'), 'rows' => 5));
        $elm->setDescription(text('Popis vašej stránky, ktorý slúži pre vyhľadávače'));
        $form->addElement($elm);

        return $form;
    }

}
