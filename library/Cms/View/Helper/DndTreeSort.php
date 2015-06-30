<?php
class Cms_View_Helper_DndTreeSort  extends Cms_View_Helper_Abstract  {

    public $card;

    public function dndTreeSort($options){


    return '
    <script>
    $(function(){
    $("#table_'.$options['card'].'dndTreeSort")

    .bind("loaded.jstree", function (e, data) {

        data.inst.open_all(-1); // -1 opens all nodes in the container
    })
    .jstree({

        "plugins" : [ "themes", "html_data", "dnd","crrm" ],
        "themes" : {"theme" : "apple","icons" : false},
        //"core" : { "initially_open" : [ "135","138","139" ] },
        crrm:{
            move:{
                check_move: function(data){

                    if(data.p != "inside"){
                        return true;
                    }else if(data.r.attr("class").substr(0,3) == "dir"){
                        return true;
                    }else{
                        return false;
                    }
                }

            }
        }
    })

    .bind("move_node.jstree",function(event, data){
        //alert(data.rslt.o.attr("id")+" :: "+data.rslt.p+" :: "+data.rslt.r.attr("id"));
         id = data.rslt.o.attr("id");
         parent = $("#"+id).parent();
         parent_id = $("#"+id).parent().parent().attr("id");
         if(parent_id == "table_'.$options['card'].'dndTreeSort"){
             parent_id = 0;
         }

         var result = new Array();
         var i=0;
         parent.children().each(function(index) {
            if($(this).attr("id").length>0){
                result[i] = $(this).attr("id");
                i++;
            }
         });

         $.ajax({
            url: "'.$this->view->url(array('action'=>'dndSort')).'/parentId/"+parent_id+"/",
            data: {"sort[]" : result},
            type: "GET",
            success: function(data) {if(data.length>1){alert(data)}}
         });

    });
});

</script>';

    }

}