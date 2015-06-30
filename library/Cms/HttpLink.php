<?php

class Cms_HttpLink {

    public function getLink($link){
        $linkNew = "javascript:void(0)";
        if(!empty($link)){
            if(substr($link,0,1)=='/'){
                $linkNew = $link;
            }
            elseif(substr($link,0,7)!='http://'){
                $linkNew = 'http://'.$link;
            }else{
                $linkNew = $link;
            }
        }
        return $linkNew;
    }

}