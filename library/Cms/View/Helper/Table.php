<?php
class Cms_View_Helper_Table extends Cms_View_Helper_Abstract  {

    public function table($card){

        $options = array('data'=>$card->indexData(),
                  'columns'=>$card->getIndexColumns(),
                  'dndSort' => $card->isAllowed('dndSort'),
                  'card' => $card->getCardName(),
                  'cardType'=>$card->getOption('cardType'));

        $html = '';

        if(!isset($options['dndSort'])){
            $options['dndSort'] = false;
        }
        $columns = $options['columns'];
        $data = $options['data'];

        // Table body
        $class = '';
        if($options['dndSort'] == true){
            $class = 'dndSort';
        }

        $class2 = '';
        if($options['cardType']=='sub'){
            //$class2 = ' hide';
        }
        $html .= '<div class="table-responsive">';
        $html .= '<table id="table_'.$options['card'].'" class="table table-bordered table-striped table-condensed table-hover dt-responsive no-wrap '.$class.$class2.'">';
        $html .= '<thead>';
        $html .= '<tr>';

        //hlavicka - th
        foreach($columns as $column){
            $orderLinkStart = '';
            $orderLinkEnd = '';
            if($column->isOrderAble()){
                $type = 'asc';
                $icon = null;
                if(isset($_GET['columnOrderName']) and $_GET['columnOrderName'] == $column->getName()){
                    if($_GET['columnOrderType']== 'asc'){
                        $type = 'desc';
                        $icon = ' <i class="glyphicon glyphicon-chevron-up"></i>';
                    }else{
                        $icon = ' <i class="glyphicon glyphicon-chevron-down"></i>';
                    }
                }
                $orderLinkStart = '<a href="'.$this->view->url().'/?columnOrderName='.$column->getName().'&columnOrderType='.$type.'">';
                $orderLinkEnd = $icon.'</a>';
            }
            $html .= '<th class="'.$column->getClass().'">'.$orderLinkStart.$column->getLabel().$orderLinkEnd.'</th>';
        }
        $html .='</tr></thead><tbody>';
        if(count($options['data'])>0){
            $result = $data;
            if($result != null){
                $i = 0;
                foreach ($result as $obj) {
                    $i++;
                    if(!isset($obj->id)){
                        $obj->id = $i;
                    }
                    $html .='<tr id="sort-'.$obj->id.'" class="card_'.$options['card'].'-row_'.$obj->id.' edit">';
                    $i=0;
                    foreach($columns as $column){
                        $column->setView($this->view);
                        $dataCol = $column->setData($obj);
                        $html .= $dataCol;
                        $i++;
                    }
                    $html .='</tr>';
                }
            }
        }else{
            $html .='<tr>';
            foreach($columns as $column){
                $html .= '<td> - </td>';
            }
            $html .='</tr>';
        }

        $html .='</tbody></table></div>';
        if(count($options['data'])==0){
            $html .= ' -- '.Cms_T::_('cms_no_records_found');
        }



         return $html;
    }

}