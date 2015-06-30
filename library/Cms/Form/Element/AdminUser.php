<?php
class Cms_Form_Element_AdminUser extends Zend_Form_Element_Select{

    protected $_enableAddButton = false;
    protected $_users;

    public function enableAddButton($val = true){
        $this->_enableAddButton = $val;
    }
    
    public function getUsers(){
        if($this->_users == null){
            $modelUsers = new Admin_Model_User();
            $select = $modelUsers->select()
                    ->setIntegrityCheck(false)
                    ->from($modelUsers)
                    ->joinLeft('admin_userfunction','admin_userfunction.id = admin_user.admin_userfunction_id',array('function'=>'name'))
                    ->order(array('function ASC','admin_user.name ASC'))
                    ->where('admin_userfunction_id is not null')
                    ;
            $users = $modelUsers->fetchAll($select);
            $this->setUsers($users);
        }
        return $this->_users;
    }

    public function setUsers($users){
        $this->_users = $users;
        return $this;
    }

    public function getDescription(){
        $description = $this->getDecorator('description');
        $description->setEscape(false);
        $description->setTag(null);

        $html = '';

        $users = $this->getUsers();
        $users2 = array();
        foreach($users as $user){
            $users2[] = '{value: "'.$user->name.'", id: "'.$user->id.'", category: "'.$user->function.'"} ';
        }

        $html = '<div class="hide addUser'.$this->getName().'">'
                . ' <input type="text" name="userAutoComplet" class="userAutoComplet'.$this->getName().'" value="">';

        if($this->_enableAddButton){
            $html .=  ' <span class="newUser'.$this->getName().' btn btn-xs btn-warning">'
                . ' <i class="glyphicon glyphicon-plus"></i> <i class="glyphicon glyphicon-user"></i>'
                . ' </span>';
        }

        $html .= '</div>'
                ;
 $html .= '
<style>

.ui-menu-item{
border-top:1px solid whitesmoke;
}
.ui-menu .ui-menu-item a.ui-state-focus{
padding:2px;
margin:2px 2px 2px 10px;
}
.ui-menu .ui-menu-item a{
padding:2px;
margin:2px 2px 2px 10px;
}
.ui-autocomplete{
z-index:10000;
background-color:white;
border:1px solid silver;
}
.ui-autocomplete-category {
border-top:1px solid grey;
font-weight: bold;
padding: .2em .4em;
margin: .8em 0 .2em;
line-height: 1.5;
}
a.ui-state-focus {
    font-weight:bold  !important;
}
</style>
';


        $html .= '
<script>

  $.widget( "custom.catcomplete", $.ui.autocomplete, {
    _renderMenu: function( ul, items ) {
      var that = this,
        currentCategory = "";
      $.each( items, function( index, item ) {
        if ( item.category != currentCategory ) {
          ul.append( "<li class=\'ui-autocomplete-category\'>" + item.category + "</li>" );
          currentCategory = item.category;
        }
        that._renderItemData( ul, item );
      });
    }
  });

var data = ['.implode(','."\n",$users2).'];



$(function(){


    $("#'.$this->getName().'").addClass("hide");
    name = $("#'.$this->getName().'").val();

    html =  $(".addUser'.$this->getName().'").html();
    $("#'.$this->getName().'-element").append(html);

    $("form .newUser'.$this->getName().'").click(function(){
        link = "/tracker/user/new/";
        id = "#card_cmsModal2Body";
        $("#cmsModal2 .modal-header span").html("");
        $("#cmsModal2 .modal-body").html("");
        $("#cmsModal2 .modal-footer").html("");
        ajaxGet(link,id);
        $("#cmsModal2").modal();

    });


    $("form .userAutoComplet'.$this->getName().'").catcomplete({
      delay: 0,
      source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
        response( $.grep( data, function( value ) {
          value = value.label || value.value || value;
          return matcher.test( value ) || matcher.test( accent_fold( value ) );
        }) );
      },
      select: function( event, ui ) {
         $("#'.$this->getName().'").val(ui.item.id);
      },
      change:  function( event, ui ) {
         if(ui.item == null){
             $("#'.$this->getName().'").val("");
             $(this).val("");
         }
      }
    });
    if(name.length > 0){
        name = $("#'.$this->getName().'").find(":selected").text();
        $("form .userAutoComplet'.$this->getName().'").val(name);
    }
});


</script>
';

        return  $html;

    }
}