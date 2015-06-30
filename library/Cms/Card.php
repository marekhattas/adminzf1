<?php

class Cms_Card
{

    protected $_options = array(
        'cardType'=>'normal', //normal, sub, tree
        'showTitle'=>true,
        'showTitleChild'=>false,
        'showBackButton'=>true,
        'showNewButton'=>true,
        'order'=>array('id asc'),
        'rightBar'=>false,
        'navBar'=>false,
        'detailModal'=>true,
        'editModal'=>true,
        'editHelper'=>false,
        'viewModal'=>true,
        'viewHelper'=>false,
        'indexHelper'=>false,
        'indexTable'=>'table',
        'newModal'=>true,
        'newHelper'=>false);

    protected $_model;
    protected $_title;
    protected $_titleTag;
    protected $_request;
    protected $_defaultTemplate;
    protected $_actions = array();
    protected $_params = array();
    protected $_indexColumns;

    const MSG_OPERATION_OK = 'MSG_OPERATION_OK';
    const MSG_OPERATION_ERROR = 'MSG_OPERATION_ERROR';


    public function __construct(){
        $this->init();
    }

    public function init(){
    }

    public function setTitleTag($val){
        $this->_titleTag =  $val;
        return $this;
    }
    public function getTitleTag(){
        if($this->_titleTag == null){
            $this->_titleTag =  'h1';
            if($this->getOption('cardType') == 'sub'){
                $this->_titleTag =  'h2';
            }
        }
        return $this->_titleTag;
    }

    public function getBreadCrumb(){
        $id = $this->getParam('id');
        $data = array();
        $model = $this->getModel();
        if($id == null or $model == null){
            $data[] = array('name'=>$this->_title);
        }elseif($id!=null){
            $row = $this->getModel()->getRow($id);
            if($row != null){
                if($this->getOption('cardType')=='sub'){
                    $parentColumn = $this->getModel()->_parentColumn;
                    $data[] = array('url'=>array(array('action'=>'index','parentId'=>$row->$parentColumn,'id'=>null),'admin',false),'name'=>$this->_title);
                }else{
                    $data[] = array('url'=>array(array('action'=>'index','id'=>null),'admin',false),'name'=>$this->_title);
                }
                $name = $row->id;
                if($row!= null and isset($row->name)){
                    $name = $row->name;
                }
                $data[] = array('name'=>$name);
            }else{
                $data[] = array('name'=>$this->_title);
            }
        }
        return $data;
    }

    public function removeAllowedAction($name){
        unset($this->_actions[$name]);
        return $this;
    }

    public function removeAllowedActions(){
        $this->_actions = null;
        return $this;
    }

    public function setAllowedActions($array) {
        $this->_actions = $array;
        return $this;
    }

    public function getAllowedActions() {
        //array('index'=>true,'edit'=>true,'new'=>true,'delete'=>true,'confirmDelete'=>true,'dndSort'=>false,'plupload'=>false);
        return $this->_actions;
    }

    public function setAllowedAction($name) {
        $this->_actions[] = $name;
        return $this;
    }

    /*
     * @param $name
     * @param $data [id,card]
     */
    public function isAllowed($name,$data = null) {
        return in_array($name,$this->_actions);
    }


    public function setOptions($array) {
        $this->_options = $array;
        return $this;
    }

    public function getOptions() {
        return $this->_options;
    }

    public function setOption($name,$value) {
        $this->_options[$name] = $value;
        return $this;
    }

    public function getOption($name,$default = null) {
        if(isset($this->_options[$name])){
            return $this->_options[$name];
        }
        return $default;
    }


    public function setParams($array) {
        $this->_params = $array;
        return $this;
    }

    public function getParams() {
        return $this->_params;
    }

    public function setParam($name,$value) {
        $this->_params[$name] = $value;
        return $this;
    }

    public function getParam($name,$default = null) {
        if(isset($this->_params[$name])){
            return $this->_params[$name];
        }
        return $default;
    }

    public function setTitle($value) {
        $this->_title = $value;
        return $this;
    }

    public function getTitle() {
        return $this->_title;
    }

    public function setRequest($value) {
        $this->_request = $value;
        return $this;
    }

    public function getRequest() {
        return $this->_request;
    }


    public function setModel($value) {
        $this->_model = $value;
        return $this;
    }
    /**
     * @return Cms_DbTable
     */
    public function getModel(){
      if(is_string($this->_model)){
        $this->_model = new $this->_model;
      }
      return $this->_model;
    }


    public function getCardName(){
      return get_class($this);
    }


    /**
     * Get message and delete it from sessions
     * @return string message
     */
    public function pushMessage() {

        $message = new Zend_Session_Namespace('message');
        $m = $message->id;
        $message->id = null;

        switch($m) {
            case (self::MSG_OPERATION_OK) : return Cms_T::_('cms_operation_success');
            case (self::MSG_OPERATION_ERROR) : return Cms_T::_('cms_error_something_wrong');
        }

        return $m;
    }


    /**
     * Get message
     * @return string
     */
    public function getMessage() {
        $message = new Zend_Session_Namespace('message');
        return $message->id;
    }

    /**
     * Set message
     * @param string $msgId constant
     * @return Cms_Card_Helper
     */
    public function setMessage($msgId) {
        $message = new Zend_Session_Namespace('message');
        $message->id = $msgId;
        return $this;
    }


    public function indexData(){

        if($this->getOption('cardType') == 'normal'){
            $select = $this->getModel()->select();
            $select->order($this->getOption('order'));
            $data = $this->getModel()->fetchAll($select);
        }
        elseif($this->getOption('cardType') == 'sub'){
            $select = $this->getModel()->select();
            $select->order($this->getOption('order'));
            $parentColumn = $this->getModel()->_parentColumn;
            $select->where($parentColumn.' = ?',$this->getParam('parentId'));
            $data = $this->getModel()->fetchAll($select);
        }
        return $data;
    }

    public function setIndexColumns($columns){
        $this->_indexColumns = $columns;
        return $this;
    }

    public function indexColumns(){
        $columns = array();
        $columns[] = new Cms_View_Table_Column_Id('id',array('label'=>Cms_T::_('cms_id')));
        return $columns;
    }

    public function getIndexColumns(){
        $columns = $this->_indexColumns;
        if($columns == null){
            $columns = $this->indexColumns();
            $this->setIndexColumns($columns);
        }

        $c = array();
        foreach($columns as $column){
            if($column->isAllowed()){
                $c[] = $column;
            }
        }
        return $c;
    }

    public function indexAction(){

        return $this->indexData();

    }
    public function viewAction(){

    }

    public function viewColumns(){
      $columns = array(array('name'=>'id','title'=>'ID'));
      return $columns;
    }



    /**
     * Default form with buttons
     * @return Zend_Form
     */
    public function newForm(){
        $form = new Zend_Form();

        $form->setAttrib('class','ajaxPost');


        $elm = new Zend_Form_Element_Submit('saveAndClose');
        $elm->setValue('1');
        $elm->setOrder(10020);
        $elm->setLabel(Cms_T::_('cms_save'));
        $elm->setAttrib('class','btn btn-success saveAndCloseButton');
        $form->addElement($elm);

        return $form;

    }

    public function insert($data){

        $form = $this->newForm();
        foreach ($data as $key => $val) {
            $elm = $form->getElement($key);
            if($elm != null and strpos($elm->getAttrib('class'), 'date') === 0){
                if($val != ''){
                    $d = new Cms_Date();
                    $data[$key] = $d->dateToDb($val);
                }
            }
        }

        $id =  $this->getModel()->insert($data);
        return $id;
    }

    public function newAction(){


        $form = $this->newForm();

        $request = $this->getRequest();
        if ($request->isPost() and $form->isValid($request->getPost())) {
            $data = $form->getValues();

            if($this->getOption('cardType') == 'sub' and $this->getParam('parentId') != null){
                $parentColumn = $this->getModel()->_parentColumn;
                $data[$parentColumn] = $this->getParam('parentId');
            }
            $id = $this->insert($data);
            $this->setParam('id',$id);
            //save message to  session
            $this->setMessage('MSG_OPERATION_OK');

            //redirect
            $link = $this->getRedirectNewLink();
            $data = new stdClass();
            $data->redirect = true;
            $data->link = $link;
            return $data;


        }
        else{

            $html = '';
            if($request->isPost()){
                $html .= '<div class="alert alert-dismissable alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                ';

                $m = $form->getMessages();
                $br = '';
                foreach($m as $key => $value){
                $elm = $form->getElement($key);
                $elm->getLabel();
                $html.= $br.$elm->getLabel().' - ';
                $br = '<br />';
                $c = '';
                foreach($value as $val){
                    $html.=  $c.$val;
                    $c = ', ';
                }
                }
                $html .= '</div>';
                $html .= '<script>$("#'.$this->getCardName().'_modalForm").modal();</script>';
            }

            $data = new stdClass();
            $data->html = $html;
            $data->form = $form;
            return $data;

        }
    }

    public function getRedirectNewLink(){

        if($this->getOption('newModal') == true){
            $m = ucfirst($this->getParam('module'));
            $c = ucfirst($this->getParam('controller'));
            $cardName = '#card_'.$m.'_'.$c.'Controller';

            echo '<script>
                $(function(){
                    rcard = "'.$cardName.'";
                    rlink = $(rcard).attr("cms-url");
                    ajaxGet(rlink,rcard);
                    clearModal();
                });
                </script>';
            exit;
        }


        $url = array();
        $url['module'] = $this->getParam('module');
        $url['controller'] = $this->getParam('controller');
        $ajax = false;
        $reset = true;

        if($this->getOption('cardType') == 'normal'){
            $url['action'] = 'index';
            $url['id'] = null;
            if($this->getParam('saveAndEdit') != null){
                $url['action'] = 'edit';
                $url['id'] = $this->getParam('id');
            }
            elseif($this->getParam('saveAndView') != null){
                $url['action'] = 'view';
                $url['id'] = $this->getParam('id');
            }
        }
        elseif($this->getOption('cardType') == 'sub'){
            $ajax = true;
            $url['card'] = $this->getParam('card');
            $url['action'] = 'index';
            $url['parentId'] = $this->getParam('parentId');
            if($this->getParam('saveAndEdit') != null){
                $url['action'] = 'edit';
                $url['id'] = $this->getParam('id');
                $url['parentId'] = null;
            }
            elseif($this->getParam('saveAndView') != null){
                $url['action'] = 'view';
                $url['id'] = $this->getParam('id');
                $url['parentId'] = null;
            }
        }

        return array('url'=>$url,'ajax'=>$ajax,'reset'=>$reset);

    }


    /**
     * Default form with buttons
     * @return Zend_Form
     */
    public function editForm(){

        return $this->newForm();
    }

    public function populateForm($form){

        $row = $this->getModel()->getRow($this->getParam('id'));
        if($row != null){
            foreach($row as $key=>$val){
                if($val != ''){
                    $elm = $form->getElement($key);
                    if($elm != null and strpos($elm->getAttrib('class'), 'date') === 0){
                        if($val == '0000-00-00 00:00:00'){
                            $val = '';
                        } else {
                            $d = new Cms_Date();
                            $val = $d->dateFromDb($val);
                        }
                    }
                    $form->populate(array($key=>$val));
                }
            }
        }
        return $form;

    }

    public function update($data,$where){
        $result =  $this->getModel()->update($data,$where);
        return $result;
    }

    public function detailAction(){

        $row = $this->getModel()->getRow($this->getParam('id'));

        $form = $this->editForm();
        $form = $this->populateForm($form);
        $elements = $form->getElements();
        $data = array();
        $date = new Cms_Date();
        if(count($elements)>0){
            foreach($elements as $elm){
                if($elm->getName() != 'saveAndClose'){
                    $value = $elm->getValue();
                    if(!empty($value) and method_exists($elm,'getMultiOptions')){
                        $options = $elm->getMultiOptions();
                        if($options!=null and isset($options[$value])){
                            $value = $options[$value];
                        }else{
                            $value = ' - ';
                        }

                    }
                    if(empty($value)){
                        $value = ' - ';
                    }
                    $data[] = array('label'=>$elm->getLabel(),'value'=>$value);
                }
            }
            $data[] = array('label'=>Cms_T::_('cms_recorded_on'),'value'=>$date->dateFromDb($row->created_on));
        }
        $s = new stdClass();
        $s->data = $data;
        return $s;
    }


    public function editAction(){


        $form = $this->editForm();
        $html = '';
        $request = $this->getRequest();
        if ($request->isPost()){
            if($form->isValid($request->getPost())) {
                $data = $form->getValues();
                $where = $this->getModel()->getName().'.id = "'.$this->getParam('id').'"';

                foreach ($data as $key => $val) {
                   $elm = $form->getElement($key);
                   if($elm != null and strpos($elm->getAttrib('class'), 'date') === 0){
                       if($val != ''){
                           $data[$key] = Cms_Date::dateToDb($val);
                       }
                   }
                }
                /*//ak este neexistuje tak ho vlozim
                if($this->getModel()->getRow($this->getParam('id')) == null ){
                  $data['id'] = $this->getParam('id');
                  $this->insert($data);
                }else{
                  $this->update($data,$where);
                }*/
                $this->update($data,$where);
                //save message to  session
                $this->setMessage('MSG_OPERATION_OK');

                //redirect
                $link = $this->getRedirectEditLink();
                $data = new stdClass();
                $data->redirect = true;
                $data->link = $link;
                return $data;
            }else{

                $m = $form->getMessages();
                $br = '';
                if(count($m)>0){
                    $html .= '<div class="alert alert-dismissable alert-danger">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    ';

                    foreach($m as $key => $value){
                      $elm = $form->getElement($key);
                      $elm->getLabel();
                      $html.= $br.'<b>'.$elm->getLabel().'</b> - ';
                      $br = '<br />';
                      $c = '';
                      foreach($value as $val){
                        $html.=  $c.$val;
                        $c = ', ';
                      }
                    }
                    $html .= '</div>';
                }
                $html .= '</div>';
            }
        }else{
          $form = $this->populateForm($form);
        }

        $data = new stdClass();
        $data->html = $html;
        $data->form = $form;
        return $data;
    }
    public function getRedirectEditLink(){

        if($this->getOption('editModal') == true){
            $m = ucfirst($this->getParam('module'));
            $c = ucfirst($this->getParam('controller'));
            $cardName = '#card_'.$m.'_'.$c.'Controller';

            echo '<script>
                $(function(){
                    rcard = "'.$cardName.'";
                    rlink = $(rcard).attr("cms-url");
                    ajaxGet(rlink,rcard);
                    clearModal();
                });
                </script>';
            exit;
        }


        $url = array();
        $url['module'] = $this->getParam('module');
        $url['controller'] = $this->getParam('controller');
        $ajax = false;
        $reset = true;

        if($this->getOption('cardType') == 'normal'){
            $url['action'] = 'index';
            $url['id'] = null;
            if($this->getParam('saveAndEdit') != null){
                $ajax = true;
                $url['action'] = 'edit';
                $url['id'] = $this->getParam('id');
                $url['card'] = $this->getCardName();
            }
        }
        elseif($this->getOption('cardType') == 'sub'){
            $ajax = true;
            $url['card'] = $this->getParam('card');
            $url['action'] = 'index';
            $url['parentId'] = $this->getModel()->getParentId($this->getParam('id'));
            if($this->getParam('saveAndEdit') != null){
                $url['parentId'] = null;
                $url['action'] = 'edit';
                $url['id'] = $this->getParam('id');
            }
        }
        return array('url'=>$url,'ajax'=>$ajax,'reset'=>$reset);

    }

    public function getDeleteName($id){
        $row = $this->getModel()->getRow($id);
        $name = $row->id;
        if(isset($row->name)){
            $name = $row->name;
        }
        return $name;
    }

    public function confirmdeleteAction(){
        $id = $this->getParam('id');

        $name = $this->getDeleteName($id);

        $data = new stdClass();
        $data->name = $name;
        $data->id = $id;
        return $data;

    }

    public function deleteAction(){

        $parentId = null;
        if($this->getOption('cardType') == 'sub'){
            $parentId =  $this->getModel()->getParentId($this->getParam('id'));
        }

        $where = $this->getModel()->getAdapter()->quoteInto('id = ?', (int) $this->getParam('id'));
        $this->getModel()->delete($where);

        $this->setMessage('MSG_OPERATION_OK');
        //redirect
        $link = $this->getRedirectDeleteLink($parentId);
        $data = new stdClass();
        $data->redirect = true;
        $data->link = $link;
        return $data;




    }

    public function getRedirectDeleteLink($parentId){
        echo '<script>
            $(function(){
                clearModal();
                $(".card_'.$this->getCardName().'-row_'.$this->getParam('id').'").remove();
            });
            </script>';
        exit;

        /*

        $url = array();
        $url['module'] = $this->getParam('module');
        $url['controller'] = $this->getParam('controller');
        $ajax = false;
        $reset = true;

        if($this->getOption('cardType') == 'normal'){
            $url['action'] = 'index';
        }
        elseif($this->getOption('cardType') == 'sub'){
            $ajax = true;
            $url['card'] = $this->getParam('card');
            $url['action'] = 'index';
            $url['parentId'] = $parentId;
        }

        $link = array('url'=>$url,'ajax'=>$ajax,'reset'=>$reset);

        return $link;

         */
    }


    /**
     * Set default template
     * @param Cms_Container
     * @return Cms_Card
     */

    public function setDefaultTemplate($container){
        $this->_defaultTemplate = $container;
        return $this;
    }


    public function isAllowedRightBar() {
        if($this->getOption('rightBar') == true and $this->getParam('action') == 'index'){
            return true;
        }
        return false;
    }
    public function isAllowedNavBar() {
        if($this->getOption('navBar') == true and $this->getParam('action') != 'index'){
            return true;
        }
        return false;
    }

    public function dndSortAction(){
        try{

            $sort = $this->getParam('sort');

            $order = $this->getOption('order');
            $table = $this->getModel();
            if(empty($sort[0])){
                unset($sort[0]);
            }

            $i=1;

            if(is_array($order)){
                $order = $order[0];
            }
            $explode = explode(' ',$order);
            //var_dump($explode);
            $orderColumn = $explode[0];
            if(stristr($order, ' desc') !== FALSE) {
              $i=count($sort);
            }

            $table->setLogEnable(false);
            foreach($sort as $value){
                if(!empty($value)){
                    //echo $i.' - '.$value.'<br />';
                    if(isset($table->_parentColumn) and $table->_parentColumn !== null){
                        if($this->getParam('parentId') == '0'){
                            $this->setParam('parentId',null);
                        }
                        if($value != $this->getParam('parentId')){
                        $table->update(array($orderColumn =>$i,$table->_parentColumn =>$this->getParam('parentId')), $this->getModel()->getName().'_id = '. $value.'');
                        }
                    }else{
                        $table->update(array($orderColumn =>$i), $this->getModel()->getName().'_id = '. $value.'');
                    }
                    if(stristr($order, ' desc') !== FALSE) {
                        $i--;
                    }
                    else{$i++;}
                }
            }
        }
        catch(Exception $e){
            throw new Cms_Exception($e);
            $this->setMessage('MSG_OPERATION_ERROR');

        }
        exit;
    }


    public function getCloseLink(){
        $url = array();
        $url['module'] = $this->getParam('module');
        $url['controller'] = $this->getParam('controller');
        $ajax = false;
        $reset = true;

        if($this->getOption('cardType') == 'normal'){
            $url['action'] = 'index';
            $url['card'] = null;
            $url['id'] = null;
        }
        elseif($this->getOption('cardType') == 'sub'){
            $url['id'] = null;
            $url['card'] = $this->getParam('card');
            $ajax = true;
            $url['action'] = 'index';
            if($this->getParam('action')=='new'){
                $url['parentId'] = $this->getParam('parentId');
            }else{
                $url['parentId'] = $this->getModel()->getParentId($this->getParam('id'));
            }
        }
        return array('url'=>$url,'ajax'=>$ajax,'reset'=>$reset);


    }


    public function pluploadAction(){
        if(!empty($_GET['name'])){

            $fileName = preg_replace('/[^\w\._]+/', '_', $_GET['name']);

            $options = array();
            $options['fileName'] = $fileName;
            $options['filePath'] = $this->getModel()->_filePath;
            //$upload = new Cms_Upload($options);
            //$upload->unique();
            $file = explode('.',$fileName);
            if(!isset($file[1]) or $file[1]=='php'){
                $file[1] = '';
            }
            $file[1] = strtolower($file[1]);

            //safety issue
            $newName = $file[0].'_'.substr(md5(time().rand(3, 8)),0,10).'.'.$file[1];

            $data = array();
            $data['file_name'] = $newName;

            if($this->getOption('cardType') == 'sub'){
                $parentColumn = $this->getModel()->_parentColumn;
                $data[$parentColumn] = $this->getParam('parentId');
            }


            $targetFile = PUBLIC_PATH . '/' . $this->getModel()->_filePath . '/' . $newName;
            $tmpDir = ini_get('upload_tmp_dir') . DIRECTORY_SEPARATOR . 'plupload';


            $c = copy($tmpDir.'/'.$fileName, $targetFile);
            if($c){
                $id = $this->insert($data);
            }else{
                throw new Cms_Exception('Copy file failed. File: '.$targetFile.' From:'.$tmpDir.'/'.$fileName);
            }

            echo $id;
            exit;
        }
        return null;
    }


    public function uploadFile($id,$options){
        //$options column, width, height,
        if(!empty($_FILES[$options['column']]['name'])){

            $fileName = strtolower($_FILES[$options['column']]['name']);
            $fileInfo = pathinfo($fileName);

            if($fileInfo['extension'] == 'php'){
                exit;
            }

            $newName = $fileInfo['filename'].'-'.substr(md5(time().rand(3, 8)),0,10).'.'.$fileInfo['extension'];
            $targetFile = PUBLIC_PATH . '/' . $this->getModel()->_filePath . '/' . $newName;

            //delete
            $this->deleteFile($id,$options['column']);

            //insert into db
            $this->getModel()->update(array($options['column']=>$newName),$this->getModel()->getName().'_id = "'.$id.'"');

            //upload
            $move = move_uploaded_file($_FILES[$options['column']]['tmp_name'],$targetFile);
            if(!$move){
                throw new Cms_Exception('Move upload file - failed');
            }
            
            //resize
            if(in_array($file[1], array('jpg', 'jpeg', 'gif', 'png'))){
                $gd = new Cms_Gd;
                $gd->load($targetFile);
                if(empty($options['width'])){
                    $options['width'] = 1600;
                }
                if(empty($options['height'])){
                    $options['height'] = 1600;
                }
                $gd->autoResize($options['width'],$options['height']);
                $gd->save($targetFile);
            }
        }

    }

}
