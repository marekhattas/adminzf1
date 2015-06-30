<?php
class Cms_View_Helper_DndSort  extends Cms_View_Helper_Abstract  {

    public $card;

    public function dndSort($options){
        return '
            <script>
            $("#table_'.$options['card'].'.dndSort tbody").sortable({
                axis: "y",
                cursor: "move",
                cancel: "",
                handle: "a.handle",
                stop: function(event, ui) {
                    var sorted = $(this).sortable("serialize");
                    $.ajax({ url: "'.$this->view->url(array('action'=>'dndSort')).'/?" + sorted,
                        success: function(data) {if(data.length>1){alert(data)}}
                    });
                }
            });
           </script>';

    }

}