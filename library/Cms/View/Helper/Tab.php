<?php

class Cms_View_Helper_Tab extends Cms_View_Helper_Abstract {

    public $navClass = 'tabsMe';
    public $targetClass = 'tabMe';
    public $allowed = false;

    public function getBackController(){
        return $this->view->controllerClassName();
    }

    public function getData($id){
        $data = array();
        $data[] = array(
            'controller'=>'',
            'url'=>'',
            'name'=> '',
            'icon'=>'glyphicon-info-sign');
        return $data;
    }

    public function render($card){
        $id = $card->getParam('id');

        $data = $this->getData($id);

        $html = '';

        $html .= '<div class="col-md-3 sidebar-nav">';


        if($card->getOption('showBackButton')){
            $html .=' <ul class="nav nav-tabs">'
                    . '<li id="nav_'.$this->navClass.'_back">'
                    . '<a class="link"><i class="glyphicon glyphicon-hand-left"></i> '
                    . ''.Cms_T::_('cms_back').'</a></li>'
                    . '</ul>';
            $controller = $this->getBackController();
            $html .= '<script>'
                    . '$(function(){'
                    . ' $("#nav_'.$this->navClass.'_back a").click(function(){'
                    . '      '
                    . '      firstLi = $("#'.$this->navClass.' li").first().hasClass("active");'
                    . '      if(firstLi == true){'
                    . '         url = $("#card_'.$controller.' .breadcrumbx a").first().attr("cms-url");'
                    . '         card = $("#card_'.$controller.' .breadcrumbx a").first().attr("cms-card");'
                    . '         ajaxGet(url,card);'
                    . '      }else{'
                    . '         $("#'.$this->navClass.' a").first().click();'
                    . '      }'
                    . ' });'
                    . '});'
                    . '</script>';
        }


        $html .= '
        <ul class="nav nav-tabs" id="'.$this->navClass.'" name="'.$this->navClass.'">';

        $acl = new Cms_Acl();

        foreach($data as $i=>$item){
            if($this->allowed or $acl->isUserAllowed($item['controller'])){
                $c = '';
                if(!empty($item['class'])){
                    $c = $item['class'];
                }
                if(isset($item['ajaxModal']) and $item['ajaxModal'] === true){
                $html .= '<li id="nav_'.$i.'_'.$item['controller'].'" class="'.$c.'">'
                        . '<a onclick="ajaxModal(\''.$item['url'].'\')" class="link">'
                        . '<i class="glyphicon '.$item['icon'].'"></i> '.$item['name'].'</a></li>';
                }else{
                    $html .= '<li id="nav_'.$i.'_'.$item['controller'].'" class="'.$c.'">'
                            . '<a cms-url="'.$item['url'].'" cms-card="'.$item['controller'].'" class="link">'
                            . '<i class="glyphicon '.$item['icon'].'"></i> '.$item['name'].'</a></li>';
                }
            }
        }
        $html .= '</ul>';
        $html .= '</div>';

        $html .= '<div class="col-md-9 tab-content"  id="'.$this->targetClass.'"><div></div></div>';
        $html .= '<script>
                    $(function(){
                        $("#'.$this->navClass.' a").click(function(){
                            var url = $(this).attr("cms-url");
                            var card = $(this).attr("cms-card");
                            if(url != undefined){
                                $("#'.$this->navClass.' li").removeClass("active");
                                $(this).parent().addClass("active");
                                $("#'.$this->targetClass.'").html(\'<div id="card_\'+card+\'" name="card_\'+card+\'"  class="card_content"></div>\');

                                ajaxGet(url,"#card_"+card);
                            }
                        });
                    });
                    </script>';

        //tab notification
        $tab = $card->getParam('tab');
        if($tab != null){
            $html .= '<script>
                    $(function(){
                        $("a[cms-card=\"'.$tab.'\"]").click();
                    });
                    </script>';
        }else{
            $html .= '<script>
                    $(function(){
                        $("#'.$this->navClass.' a").first().click();
                    });
                    </script>';
        }

        return $html;

    }

}