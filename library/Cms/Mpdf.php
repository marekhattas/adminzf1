<?php

require_once(APPLICATION_PATH.'/../library/Mpdf/mpdf.php');

class Cms_Mpdf extends mPDF
{

    public function __construct() {
        
        parent::mPDF('utf-8', 'A4', 12, '', 5, 5, 5, 15);
        
        $this->SetCreator('mPDF generator');
        $this->SetAuthor('GQsystems');
        $this->SetDisplayMode('fullpage');
        $this->SetBasePath((!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/');
        $this->use_kwt = true;
        $this->img_dpi = 96;
        //$this->showImageErrors = true;

        $css = file_get_contents(PUBLIC_PATH.'/admin/stylesheets/pdfexport.css');
        $this->WriteHTML($css ,1);
        
        $footer = '<div style="text-align:center;font-size:10px;">Automatically generated by <a href="https://gqsystems.eu">GQsystems.eu</a> - '
        . 'Quality software for your bussines : : Exported on {DATE j/M/Y} : : Page {PAGENO}/{nbpg}</div>';
        $this->SetHTMLFooter($footer);
        
    }

}