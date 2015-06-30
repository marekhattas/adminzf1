<?php
class Cms_View_Helper_ContentEnd extends Cms_View_Helper_Abstract{

    public function contentEnd($card){
        $html = '';
        $data = $card->getBreadCrumb();
        
        if(count($data)>0){
            $html .= '<script>'
                    . '$(document).ready(function() { '
                    . ' var h = "";'
                    . ' var c = "";'
                    . ' var a = 0;'
                    . ' $(".card_content .breadcrumbx").each(function(){'
                    . '     a = a+1;'
                    . '     h = h + c + $(this).html();'
                    . '     c = \'<i class="glyphicon glyphicon-chevron-right glyphicon-white"></i>\';'
                    . ' });'
                    . ' $(".breadcrumbs").html(h);'
                    . ' if(a <= 1){'
                    . '     $(".breadcrumbs").html("");'
                    . ' }'
                    . ' $(".breadcrumbs a").click(function(){'
                    . '     var url = $(this).attr("cms-url");'
                    . '     var card = $(this).attr("cms-card");'
                    . '     ajaxGet(url,card);'
                    . ' });'
                    . '});'
                    . '</script>';
        }

        //FIXME - iam not sure if this is the best position for calculating time end
        //time generated  - in seconds
        $timeEnd = microtime(true);
        $time = $timeEnd - TIME_START;
        $html .= '<script>
             if (window.console){
                console.log("PHP render time:","'.$time.'s");
             }
              </script>';
        
        return $html;
    }
}