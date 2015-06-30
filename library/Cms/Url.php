<?php

class Cms_Url
{

    protected $_model;
    protected $_data;


    /**
     * Vráti tabulku s ktorou tento mapper pracuje
     * @return class Zend_Db_Table_Abstract
     */
    public function getModel()
    {
        if (null === $this->_model) {
            throw new Exception('Žiadna tabuľka vybratá');
        }
        return $this->_model;
    }

    /**
     * Nastavi tabulku s ktorou tento mapper pracuje
     * @param class $model Zend_Db_Table_Abstract
     */
    public function setModel($model)
    {
        if (is_string($model)) {
            $model = new $model();
        }
        if (!$model instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Tabuľka neodpoveda danému typu');
        }
        $this->_model = $model;
        return $this;
    }

    /**
     * Return id of specified url name
     * @param $url string
     * @return id int or null
     */
    public function getIdByUrlName($urlName){
        $model = $this->getModel();
        $where =  $model->getAdapter()->quoteInto('url_name = ?',$urlName);
        $row = $model->fetchRow($where);
        if($row!=null){
            return $row->id;
        }
        else{
            return null;
        }
    }


    //@n Vyhodi z daneho textu diakritiku a medzery
    public function  stringToUrl($text){
        $textSafe = new Cms_TextSafe();
        return $textSafe->getText($text);
    }



    public function uniqueUrlName($name,$id = null){
        $model = $this->getModel();
        $url = $this->stringToUrl($name);
        $url2=$url;
        $seo = false;
        for($i=1; $seo!=true; $i++){
            $result2 = $model->fetchRow(" url_name='".$url2."' and id!='".$id."' ");
            if($result2 == null){
                $seo = true;
                break;
            }
            $url2 = $url.$i;
        }
        return $url2;

    }
}
