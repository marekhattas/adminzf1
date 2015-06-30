<?php
class Admin_View_GroupEdit extends Cms_View_Abstract{
    public $id = null;
    public function render(){

        $html ='<script>
            $(".fakeButton").click(function(){
                $(this).removeClass("active");
                $(this).html("<img src=\''.$this->view->baseUrl().'/admin/images/icons/waiting.gif\' />");
                button = this;
                resource = $(this).attr("title");
                $.get("'.$this->view->baseUrl().'/admin/admin/user/acl/card/Admin_Card_Groups/groupId/'.$this->id.'/resource/"+resource, function(data) {
                    if(data == "0"){
                        $(button).html("'.text('nepovolený').'");
                    }
                    else if(data == "1"){
                        $(button).html("'.text('povolený').'");
                        $(button).addClass("active");
                    }
                    else{
                        alert("'.text('Nastala chyba').': "+data);
                    }
                });
            });

        ';

        $html .='</script>';

        return $html;
    }
}