<?php
class Cms_View_Helper_DeleteConfirmDialog  extends Cms_View_Helper_Abstract  {

    public function deleteConfirmDialog($options){

        $html = '
<script>

header = \'<h3>'.Cms_T::_('cms_delete').'</h3>\';
body = \''.Cms_T::_('cms_are_you_sure_that_you_want_to_permanently_delete_record').' <b>'.$options['name'].'</b>\';
footer = \'<a class="btn" data-dismiss="modal" aria-hidden="true">'.Cms_T::_('cms_cancel').'</a><a class="btn btn-success">'.Cms_T::_('cms_delete').'</a>\';

$("#cmsModal .modal-header span").html(header);
$("#cmsModal .modal-body").html(body);
$("#cmsModal .modal-footer").html(footer);

$("#cmsModal .btn-success").click(function(){
    ajaxModal("'.$this->view->url(array('action'=>'delete')).'");
});

</script>
';

        return $html;
    }

}