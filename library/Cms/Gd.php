<?php

/**
 * Trieda, ktora zapuzdruje manipulaciu s obrazkami formatov, JPEG, GIF, PNG
 * http://www.white-hat-web-design.co.uk/articles/php-image-resizing.php
 *
 */
class Cms_Gd {

    protected $image;
    protected $image_type;

    /**
     * Nahra obrazok do bufferu
     * @param string $filename nazov suboru
     */
    function load($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if( $this->image_type == IMAGETYPE_JPEG ) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif( $this->image_type == IMAGETYPE_GIF ) {
            $this->image = imagecreatefromgif($filename);
            imagealphablending($this->image, false);
            imagesavealpha($this->image, true);
        } elseif( $this->image_type == IMAGETYPE_PNG ) {
            $this->image = imagecreatefrompng($filename);
            imagealphablending($this->image, false);
            imagesavealpha($this->image, true);
        } else {
        	return false;
        }
        return true;
    }

    function getImageType() {
        return $this->image_type;
    }

    /**
     * Ulozi obrazok vo formate, kompresii s moznostou pridani prav
     * @param string $filename nazov suboru
     * @param int $image_type (optional) format
     * @param int $compression (optional) kompresia
     * @param int $permissions (optional) prava (chmod)
     */
    function save($filename, $image_type=null, $compression = 95, $permissions=null, $autoExtension = false) {
        if($image_type == null){
            $image_type = $this->image_type;
        }
        
        if( $image_type == IMAGETYPE_JPEG ) {
           $filename = $autoExtension ? $filename.'.jpg' : $filename;
           imagejpeg($this->image,$filename,$compression);
        } elseif( $image_type == IMAGETYPE_GIF ) {
           $filename = $autoExtension ? $filename.'.gif' : $filename;
           imagegif($this->image,$filename);
        } elseif( $image_type == IMAGETYPE_PNG ) {
           $filename = $autoExtension ? $filename.'.png' : $filename;
           imagepng($this->image,$filename);
        }
        if( $permissions != null) {
           chmod($filename,$permissions);
        }
        return $filename;
     }


    /**
     * Odosle data obrazku na klienta
     * @param int $image_type format
     */
    function output($image_type=IMAGETYPE_JPEG) {
        if( $image_type == IMAGETYPE_JPEG ) {
    		header('Content-type: image/jpeg');
        	imagejpeg($this->image);
        } elseif( $image_type == IMAGETYPE_GIF ) {
    		header('Content-type: image/gif');
        	imagegif($this->image);
        } elseif( $image_type == IMAGETYPE_PNG ) {
    		header('Content-type: image/png');
        	imagepng($this->image);
        }
    }

    /**
     * Vrati sirku obrazku
     */
    function getWidth() {
        return imagesx($this->image);
    }

    /**
     * Vrati vysku obrazku
     */
    function getHeight() {
        return imagesy($this->image);
    }

    /**
     * Zmeni vysku (proporcionalne)
     * @param int $height vyska
     */
    function resizeToHeight($height) {
        if($height < $this->getHeight()){
            $ratio = $height / $this->getHeight();
            $width = $this->getWidth() * $ratio;
            $this->resize($width,$height);
        }
    }

    /**
     * Zmeni sirku (proporcionalne)
     * @param int $width sirka
     */
    function resizeToWidth($width) {
        if($width < $this->getWidth()){
            $ratio = $width / $this->getWidth();
            $height = $this->getheight() * $ratio;
            $this->resize($width,$height);
        }
    }

    /**
     * Zmensenie
     * @param float $scale ratio
     */
    function scale($scale) {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        $this->resize($width,$height);
    }

    /**
     * Orezanie obrazku
     * @param int $width sirka
     * @param int $height vyska
     * @param bool $center (default = false) centrovat vysledny raster?
     */
    function crop($width,$height, $center = false) {

        $imgWidth = $this->getWidth();
        $imgHeight = $this->getHeight();
        if ($center){
            $dstX = floor($imgWidth/2) - floor($width/2);
            $dstY = floor($imgHeight/2) - floor($height/2);
        } else {
          $dstX = 0;
          $dstY = 0;
        }
        $dstX = ($dstX < 0 ? 0 : $dstX);
        $dstY = ($dstY < 0 ? 0 : $dstY);

        if (($imgWidth - $dstX) < $width){
          $width = $imgWidth - $dstX;
        }
        if (($imgHeight - $dstY) < $height){
          $height = $imgHeight - $dstY;
        }

        if($width < $imgWidth or $height < $imgHeight){
            $new_image = imagecreatetruecolor($width, $height);
            imagealphablending( $new_image, false );
            imagesavealpha( $new_image, true );
            imagecopy($new_image, $this->image, 0, 0, $dstX, $dstY, $width, $height);
            $this->image = $new_image;
        }
    }

    /**
     * Zmenenie proporcii obrazku
     * @param int $width sirka
     * @param int $height vyska
     */
    function resize($width, $height) {
        
        $imgWidth = $this->getWidth();
        $imgHeight = $this->getHeight();
        if($width < $imgWidth or $height < $imgHeight){
            $new_image = imagecreatetruecolor($width, $height);
            imagealphablending( $new_image, false );
            imagesavealpha( $new_image, true );
            imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $imgWidth, $imgHeight);
            $this->image = $new_image;
        }
    }
    /**
     * Zmeni sirku a vysku - zachová pomer
     * @param int $width sirka
     * @param int $height vyska
     */
    function autoResize($width, $height) {
        //zmeni jedine ze obrazok je vacsii ako pozadujem
        $imgWidth = $this->getWidth();
        $imgHeight = $this->getHeight();
        if($width < $imgWidth or $height < $imgHeight){
            
            $w=$width / $imgWidth;
            $h=$height / $imgHeight;

            if($w<=$h){
                $r = $w;
            }else{
                $r = $h;
            }
            $width = round($imgWidth*$r);
            $height = round($imgHeight*$r);
                           
            $this->resize($width,$height);
        }
    }

    /**
     * Rotacia rastra
     * @param int $degrees pocet stupnov (pri zapornej hodnote, je obrazok otacany proti rucickam hodin)
     */
    function rotate($degrees) {
        $this->image = imagerotate($this->image, $degrees, 0);
    }

    /**
     * Uplatni grayscale filter
     */
    function grayScale() {
        imagefilter($this->image, IMG_FILTER_GRAYSCALE);
    }

    /**
     * Funkcia zisti pomer rozmerov rastru k pozadovanym velkostiam a k najvacsiemu pomeru
     * zmeni proporcie pomocou resize, k zvysnemu pomeru raster oreze a podla argumentu vycentruje
     * @param int $width nova sirka rastra
     * @param int $height nova vyska rastra
     * @param boolean $center (optional, default = true) argument urcuje, ci sa metoda crop centruje
     */
    function resizeCrop($width, $height, $center = true) {

        $w=$this->getWidth() / $width;
        $h=$this->getHeight() / $height;

        if($w<=$h){
          $this->resizeToWidth($width);
        } else{
          $this->resizeToHeight($height);
        }
	$this->crop($width, $height, $center);
    }
    
    /**
     * Zluci povodny obrazok s inym v poradi: povodny dole
     * @param string $filename transparentny obrazok
     */
    function mergeImage($filename){
        
        $secondImage = imagecreatefrompng($filename);
        $secondImageInfo = getimagesize($filename);
        $w = $this->getWidth() / $secondImageInfo[0];
        $h = $this->getHeight() / $secondImageInfo[1];
        if($w<=$h){
            $r = $w;
        }else{
            $r = $h;
        }
        
        $width = round($secondImageInfo[0]*$r);
        $height = round($secondImageInfo[1]*$r);
        
        $dstX = floor($this->getWidth()/2) - floor($width/2);
        $dstY = floor($this->getHeight()/2) - floor($height/2);
  
        $dstX = ($dstX < 0 ? 0 : $dstX);
        $dstY = ($dstY < 0 ? 0 : $dstY);
        
        imagealphablending($this->image, true);
        imagesavealpha($this->image, true);
        imagecopyresampled($this->image, $secondImage, 0, 0, $dstX, $dstY, $width, $height, $secondImageInfo[0], $secondImageInfo[1]);
    }

}