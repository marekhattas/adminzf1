<?php
class Blog_Card_ProductCategories extends Blog_Card_ProductHome
{

    public function init(){

        $this->setTitle(text("Product category"));
        $this->_options['defaultCard'] = true;
        $this->setModel("Blog_Model_Texts");

    }

}