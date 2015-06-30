<?php
class Cms_View_Table_Column_TimeDiff extends  Cms_View_Table_Column_Default
{

    protected $_enableHtml = true;

    public function render(){

        $date = $this->getValue();
        $datetime1 = new DateTime($date);
        $datetime2 = new DateTime(date('Y-m-d').'23:59:59');
        $interval = $datetime1->diff($datetime2);
        return '<td><a class="tooltips label" data-original-title="'.$date.'">'.$interval->format('%a').' '.Cms_T::_('cms_days_ago').'</a></td>';
    }
}
