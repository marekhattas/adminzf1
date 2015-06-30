<?php
class Blog_Card_TextPictures extends Cms_Card
{

    protected $_actions = array('index' =>true,'edit' =>true,'delete' =>true,'new' =>true,'dndSort' =>true, 'multiUpload'=>true);
    protected $_options = array('order'=>array('order_num ASC'),'defaultCard'=>false);

    public $filePath = '/files/gallery';

    public function init(){
        $this->setTitle(text("Obrázky"));
        $this->setModel("Blog_Model_TextPictures");
    }


    public function multiUploadAction($render = true){
        $form = new Zend_Form();
        $elm =  new Zend_Form_Element_File('file_name',array('class'=>'multiUpload'));
        $form->addElement($elm);

        if (!empty($_FILES)) {
          $_FILES['file_name'] = $_FILES['Filedata'];

          $request = $this->getZca()->getRequest();
          $parentColumn = $this->getModel()->_parentColumn;
          $data = array($parentColumn => $this->getParam('id'),'file_name'=>$_FILES['file_name']['name']);
          $id = $this->insert($data);
          echo "1";
          exit;

        }

        $form = new Zend_Form();
        $elm =  new Zend_Form_Element_File('file_name',array('class'=>'multiUpload'));
        $form->addElement($elm);
        $script = "
      <script type='text/javascript'>
        $(function() {
        $('.multiUpload').uploadify({
          'uploader'  : '".$this->getZca()->view->baseUrl()."/admin/javascripts/uploadify/uploadify.swf',
          'script'    : '".$this->getZca()->view->url()."?".session_name()."=".session_id()."',
          'cancelImg' :'".$this->getZca()->view->baseUrl()."/admin/javascripts/uploadify/cancel.png',
          'folder'    : '".$this->filePath."',
  'multi'          : true,
  'auto'           : true,
  'fileExt'        : '*.jpg;*.JPG',
  'fileDesc'       : 'Image Files (.JPG)',
  'queueID'        : 'custom-queue',
  'queueSizeLimit' : 50,
  'simUploadLimit' : 1,
  'removeCompleted': false,
  'onSelectOnce'   : function(event,data) {
      $('#status-message').text(data.filesSelected + ' files have been added to the queue.');
    },
  'onAllComplete'  : function(event,data) {
      $('#status-message').text(data.filesUploaded + ' files uploaded, ' + data.errors + ' errors.');
    }
});       });
        </script>
<div id='status-message'>Select some files to upload:</div>
<div id='custom-queue'></div>
";

        $container = new Cms_Container();
        $container = $this->defaultTemplate();
        $container->add('closeButton', $this->show('button',array('title'=>text('Zavrieť'),'url'=>$this->getCloseLink())));
        $container->add('panel', $this->show('panel',array('title'=>text('Nahrávanie orbázkov'),'text'=>$form.$script)));


        $this->getZca()->view->htmlContainer = $container;
        if($render){
          $this->getZca()->renderScript('render.phtml');
        }
    }

    public function indexColumns(){

        $columns = array();
        $columns[] = new Cms_View_Table_Column_DefaultActions('id',
                        array('actions'=>$this->_actions,
                              'options'=>$this->_options,
                              'card'=>$this->getCardName()
                              ));
//        $columns[] = new Cms_View_Table_Column_Id('id',array('label'=>text('id')));
        $columns[] = new Cms_View_Table_Column_Image('file_name',array('label'=>text('Obrázok'),'filePath'=>$this->filePath));
        $columns[] = new Cms_View_Table_Column_Default('name',array('label'=>text('Popisok k obrázku')));
//        $options = array('actions'=>$this->getActions(),'node'=>$this->getNodeName(),'ajax'=>true);
//        $columns[] = new Cms_View_Table_Column_DefaultActions('id',$options);


        return $columns;
    }

    public function newForm(){
        $form = parent::newForm();

        $elm = new Zend_Form_Element_Text('name', array("label"=> text('Popisok k obrázku')));
        $form->addElement($elm);

        //image
        $form->setEnctype('multipart/form-data');
        $obj = new Cms_Form_Element_Image();
        $elm = $obj->createElement('file_name','obrázok',$this->getZca()->view,$this->filePath);
        $form->addElement($elm);

        $parentColumn = $this->getModel()->_parentColumn;
        $elm = new Zend_Form_Element_Hidden($parentColumn);
        $elm->setValue($this->getParam('id'));
        $elm->setDecorators(array('ViewHelper'));
        $form->addElement($elm);

        return $form;
    }

    public function editForm(){
        $form = parent::editForm();

        //image
        $id = $this->getParam('id');
        $row = $this->getModel()->find($id);
        $oldFileName = null;
        if(!empty($row[0])){
            $oldFileName = $row[0]['file_name'];
        }
        $obj = new Cms_Form_Element_Image();
        $elm = $obj->createElement('file_name','obrázok',$this->getZca()->view,$this->filePath,$oldFileName);
        $form->addElement($elm);

        return $form;
    }

    public function insert($data){
        $form = $this->newForm();
        if(!empty($data['file_name'])){

            $options = array();
            $options['model'] = $this->getModel();
            $options['column'] = 'file_name';
            $options['fileName'] = $data['file_name'];
            $options['form'] = $form;

            $upload = new Cms_Upload($options);
            $data['file_name'] = $upload->uploadImage(1024,800);

        }
        else{
            unset($data['file_name']);
        }
        return parent::insert($data);

     }

    public function update($data,$where){

        $form = $this->editForm();
        if(!empty($data['file_name'])){

            $options = array();
            $options['model'] = $this->getModel();
            $options['where'] = $where;
            $options['column'] = 'file_name';
            $options['fileName'] = $data['file_name'];
            $options['form'] = $form;

            $upload = new Cms_Upload($options);
            //don't optimize pictures
            $data['file_name'] = $upload->uploadImage(1024,800);

        }
        else{
            unset($data['file_name']);
        }

        return parent::update($data,$where);
    }


}