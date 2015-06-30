<?php
/**
 * Abstract class for extension
 */
require_once 'Zend/View/Helper/FormElement.php';

/**
 * Helper to generate a "plaintext" element
 *
 * @category   ZExt
 * @package    ZExt_View
 * @subpackage ZExt_View_Helper
 * @author     Sean P. O. MacCath-Moran
 * @email      zendcode@emanaton.com
 * @website    http://www.emanaton.com
 * @copyright  This work is licenced under a Attribution Non-commercial Share Alike Creative Commons licence
 * @license    http://creativecommons.org/licenses/by-nc-sa/3.0/us/
*/
class Cms_View_Helper_FormInlineUpload extends Zend_View_Helper_FormElement {
  /**
   * Generates text.
   *
   * @access public
   *
   * @param string|array $name If a string, is set as the "value" and rendered. The
   * real "value" setting will take precidence if set. In effect, the "name" value
   * become the default for "value" when "value" is not set. If an array, all other
   * parameters are ignored, and the array elements are extracted in place of added
   * parameters.
   *
   * @param mixed $value The element value.
   *
   * @param array $attribs Attributes for the element tag.
   *
   * @return string The element XHTML.
  */
  public function formInlineUpload($name, $value = null, $attribs = null) {

    $info = $this->_getInfo($name, $value, $attribs);
    extract($info); // name, value, attribs, options, listsep, disable
    //if (null === $value) {$value = $name;}

    $html = '';

    if(isset($attribs['model']) and isset($attribs['parent_id'])){
        $model = new $attribs['model'];
        $id = $attribs['parent_id'];
        $parentColumn = $model->_parentColumn;
        $rows = $model->fetchAll($parentColumn.' = "'.$id.'"');
        if(count($rows)>0){
            $html .= '<table class="inlineFiles table table-bordered table-striped table-condensed table-hover">';
            foreach($rows as $row){
                $html .= '<tr>';
                /*$html .= '<td>
                <a class="btn btn-xs btn-danger" href="javascript:void(0)" title="Delete">
                <li class="glyphicon glyphicon-remove glyphicon-white"></li></a>
                </td>';*/
                $html .= '<td><a href="/'.$model->_filePath.'/'.$row->file_name.'" target="_blank">'.$row->file_name.'</td>';
                $html .= '<td>'.$row->created_on.'</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
        }
    }
    $html .= '
        <div id="inlineUploadcontainer">
            <div id="filelist"></div>

            <a class="btn btn-success" id="pickfiles" href="#"><i class="glyphicon glyphicon-upload glyphicon-white"></i> '.Cms_T::_('cms_select_files_for_upload').'</a>
            <a id="uploadfiles" href="#" style="display:none">[Upload files]</a>
        </div>
        ';
    $html .= "<script>
    // Custom example logic
    $(function() {
        if($('#inlineUploadcontainer').length>0){
	var uploader = new plupload.Uploader({

                url : '/admin/javascripts/plupload/upload.php?PHPSESSID='+sessionId,
                flash_swf_url : '/admin/javascripts/plupload/js/plupload.flash.swf',
                silverlight_xap_url : '/admin/javascripts/plupload/js/plupload.silverlight.xap',
                autostart : true,

                // General settings
                runtimes : 'html5,gears,flash,silverlight',
    		browse_button : 'pickfiles',
		container : 'inlineUploadcontainer',
		max_file_size : '300mb',
                resize : {width : 1680, height : 1200, quality : 90}


	});

	$('#uploadfiles').click(function(e) {
		uploader.start();
		e.preventDefault();
	});

	uploader.init();

	uploader.bind('FilesAdded', function(up, files) {
		$.each(files, function(i, file) {
			$('#filelist').append(
				'<div id=\"' + file.id + '\">' +
				file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
			'</div>');
		});
                $('#uploadfiles').click();

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#' + file.id + ' b').html(file.percent + '%');
	});

	uploader.bind('Error', function(up, err) {
		$('#filelist').append('<div>Error: ' + err.code +
			', Message: ' + err.message +
			(err.file ? ', File: ' + err.file.name : '') +
			'</div>');

		up.refresh(); // Reposition Flash/Silverlight
	});

	uploader.bind('FileUploaded', function(up, file) {
		$('#' + file.id + ' b').html('100%');
                if(file.status == '5' && file.percent == '100'){
                    url = '".$attribs['url']."/?name='+file.name;
                    $.ajax({url: url});
                }
	});

        }
    });


    </script>";

    $xhtml =  $html;

    /*$xhtml =  $html.'<input type="hidden"'
            . ' name="' . $this->view->escape($name) . '"'
            . ' id="' . $this->view->escape($id) . '"'
            . ' value="' . $this->view->escape($value) . '" />';
    */

    return $xhtml;
  }
}