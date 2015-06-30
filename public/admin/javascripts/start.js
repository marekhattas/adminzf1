//videojs.options.flash.swf = "/admin/javascripts/video/video-js.swf";
var sessionId = document.cookie.split('HPSESSID=')[1].split('; ')[0];

baseUrl = ''

var formSubmit = new Array();

function nl2br(str, is_xhtml) {
  var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br ' + '/>' : '<br>'; // Adjust comment to avoid issue on phpjs.org display
  return (str + '')
    .replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}


$(document).ready(function(){


    var test = document.createElement('input');
    if (!('placeholder' in test)) {
        $('input').each(function () {
            if ($(this).attr('placeholder') != "" && this.value == "") {
                $(this).val($(this).attr('placeholder'))
                       .css('color', 'grey')
                       .on({
                           focus: function () {
                             if (this.value == $(this).attr('placeholder')) {
                               $(this).val("").css('color', '#000');
                             }
                           },
                           blur: function () {
                             if (this.value == "") {
                               $(this).val($(this).attr('placeholder'))
                                      .css('color', 'grey');
                             }
                           }
                       });
            }
        });
    }

});

function clearModal(){
    $("#cmsModal .modal-header span").html("");
    $("#cmsModal .modal-body").html("");
    $("#cmsModal .modal-footer").html("");
    $("#cmsModal").modal("hide");
    console.log('cmsModal: clear');
}

function showModal(){
    $("#cmsModal").modal();
    console.log('cmsModal: show');
}

function elmModal(header, body, footer){
    clearModal();
    if(header != null){
        $("#cmsModal .modal-header span").html($("#"+header).html());
    }
    if(body != null){
        $("#cmsModal .modal-body").html($("#"+body).html());
    }
    if(footer != null){
        $("#cmsModal .modal-footer").html($("#"+footer).html());
    }
    start('#cmsModal');
    showModal();
}

function ajaxModal(link){
    id = "#card_cmsModalBody";
    $("#cmsModal .modal-header span").html("");
    $("#cmsModal .modal-body").html("");
    $("#cmsModal .modal-footer").html("");
    ajaxGet(link,id);
    showModal();
}

function ajaxGet(link,cardId,pushState){
    //if(pushState != undefined){
    //    window.history.pushState({page: 'ajaxGeneral'}, "", link);
    //}

    addLoading(cardId);

    $.get(link, function(data) {
        str = 'redirect: ';
        str2 = 'windowReset: ';
        str3 = 'windowNew: ';
        if(data.substr(0,str.length) == str){
            ajaxGet(data.substr(str.length),"#card_general",true);
        }else if(data.substr(0,str2.length) == str2){
            window.location = data.substr(str2.length);
        }else if(data.substr(0,str3.length) == str3){
            window.open(data.substr(str3.length),'_blank');
        }else{
            $(cardId).attr("cms-url",link);
            $(cardId).html(data);
            removeLoading(cardId);
            start(cardId,'ajaxGet');
        }
     });
}

function addLoading(cardId){
    offset = $(cardId).offset();
    if(offset != undefined){
        img = '<img id="'+cardId.substr(1)+'-loading" class="loading" src="/admin/images/ajax-loader-white.gif" />';
        $(cardId).parent().append(img);
        $(cardId).addClass('transbox');
    }
}
function addUploadBar(cardId){
    offset = $(cardId).offset();
    if(offset != undefined){
        html = '<div id="'+cardId.substr(1)+'-uploadbar" class="progress"><div class="bar"></div><div class="percent"></div></div>';
        $(cardId).parent().append(html);
        $(cardId+"-uploadbar").hide();
    }
}
function removeLoading(cardId){
    $(cardId+"-loading").remove();
    $(cardId).removeClass('transbox');
}
function removeUploadBar(cardId){
    $(cardId+"-uploadbar").remove();
}

function ajaxGetOnClick(link,cardId,pushState){

    addLoading(cardId);

    $.get(link, function(data) {
        str = 'redirect: ';
        str2 = 'windowReset: ';
        str3 = 'windowNew: ';
        if(data.substr(0,str.length) == str){
            ajaxGet(data.substr(str.length),"#card_general",true);
            //window.location = data.substr(str.length);
        }else if(data.substr(0,str2.length) == str2){
            window.location = data.substr(str2.length);
        }else if(data.substr(0,str3.length) == str3){
            window.open(data.substr(str3.length),'_blank');
        }else{
            $(cardId).attr("cms-url",link);
            $(cardId).html(data);
            removeLoading(cardId);
            start(cardId,'ajaxGetOnClick');
        }
    });
}


function start(cardId,note){

    formSubmit[cardId] = 0;

    //$(".ajaxGeneral").click(function(){
   //     link = $(this).attr('href');
    //    ajaxGetOnClick(link,'general',true);
    //    return false;
    //});

    if (window.console){
        console.log('Start: ' + cardId + ' | Note:' + note);
    }

    //skryvanie flash sprav
    $(cardId+' .flashes').slideDown();
    $(cardId+' .required').append('&nbsp;*');
    //$(cardId+' .tooltips').tooltip();
    $(cardId+' dd input:text').addClass('form-control');
    $(cardId+' dd input:password').addClass('form-control');
    $(cardId+' dd textarea').addClass('form-control');
    $(cardId+' dd select').addClass('form-control');

    options = {
        beforeSubmit: function(arr, $form, options) {
            if(formSubmit[cardId] == 0){
                formSubmit[cardId] = 1;
                addLoading(cardId);
                return true;
            }
            return false;
        },
        success:  function(data, status, options){
            removeLoading(cardId);
            removeUploadBar(cardId);
            
            str = 'redirect: ';
            str2 = 'windowReset: ';
            str3 = 'windowNew: ';
            if(data.substr(0,str.length) == str){
               ajaxGet(data.substr(str.length),"#card_general",true);
            }else if(data.substr(0,str2.length) == str2){
                window.location = data.substr(str2.length);
            }else if(data.substr(0,str3.length) == str3){
                window.open(data.substr(str3.length),'_blank');
            }else{
                var cardDiv = $(cardId);
                if(typeof cardDiv.offset() != 'undefined'){
                    $('html,body').animate({scrollTop: cardDiv.offset().top-120},'slow');
                }
                $(cardId).html(data);
                start(cardId,'post');
            }

        },
        beforeSend: function() {
            
            addUploadBar(cardId);
            var percentVal = "0%";
            $(".bar").width(percentVal);
            $(".percent").html(percentVal);
        },
        uploadProgress: function(event, position, total, percentComplete) {
            
            $(cardId+"-uploadbar:hidden").show();
            var percentVal = percentComplete + "%";
            $(".bar").width(percentVal);
            $(".percent").html(percentVal);
        }
    };

    $(cardId+' .ajaxPost').ajaxForm(options);


    tiny = $(cardId+' .tiny');
    for (i=0;i<tiny.length;i++){
        a = $(tiny[i]).tinymce();
        if(a != undefined){
            a.remove();
        }
    }


    $(cardId+' .tiny.normal').tinymce({
    relative_urls : false,
    remove_script_host : true,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code hr",
        "insertdatetime media table contextmenu paste filemanager"
    ],
    image_advtab: true,
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"


    });





        $(cardId+' .tiny.small').tinymce({

    relative_urls : false,
    remove_script_host : true,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code hr",
        "insertdatetime media table contextmenu paste filemanager"
    ],
    image_advtab: true,
    toolbar: "bold,italic,underline,strikethrough,sub,sup,charmap,|,cut,copy,paste,|,undo,redo,|,code",
    menubar: false,
    valid_elements : "strong/b,br,p,italic,i,em,sub,sup"

    });



    $(cardId+' .tiny.source').tinymce({

    relative_urls : false,
    remove_script_host : true,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code hr",
        "insertdatetime media table contextmenu paste filemanager"
    ],
    image_advtab: true,
    menubar: false,
    toolbar: "code"
    });



    //---------------
  // load Date Time input
  //---------------
  // ak je nastaveny flag load_libraries_date
    // tak sa nabinduje jquery date input komponent
    // ktory sa aktivuje pri vstupe do inputu s classou date
    // s prednastavenymi hodnotami
    // zobrazenie casu vypnute
    // format datumu den.mesiac.rok
  // set options for date component as 20.1.2010


    $(cardId+' .datetime').each(function(){
        if($(this).val() == '0000-00-00 00:00:00'){
            $(this).val('');
        }
    });

    $(cardId+' .datetime').datetimepicker({
        format:'d/M/Y H:i:00',
        dayOfWeekStart: 1
    });

    $(cardId+' .datetime2').datetimepicker({
        format:'d/M/Y H:i:00',
        dayOfWeekStart: 1
    });

    $(cardId+' .datetimeFromNow').datetimepicker({
        format:'d/M/Y H:i:00',
        dayOfWeekStart: 1,
        minDate: 0
    });

    $(cardId+' .datetimeFromNow2').datetimepicker({
        format:'d/M/Y H:i:00',
        dayOfWeekStart: 1,
        minDate: 0
    });

    $(cardId+' .date').each(function(){
        if($(this).val() == '0000-00-00'){
            $(this).val('');
        }
    });
    $(cardId+' .date').datetimepicker({
        format:'d/M/Y',
        dayOfWeekStart: 1,
        timepicker:false
    });
    $(cardId+' .date2').datetimepicker({
        format:'d/M/Y',
        dayOfWeekStart: 1,
        timepicker:false
    });

    $(cardId+' .dateFromNow').datetimepicker({
        format:'d/M/Y',
        dayOfWeekStart: 1,
        timepicker:false,
        minDate: 0
    });
    $(cardId+' .dateFromNow2').datetimepicker({
        format:'d/M/Y',
        dayOfWeekStart: 1,
        timepicker:false,
        minDate: 0
    });
    $(function() {
        $(cardId+" .multiUpload").pluploadQueue({

            url : '/admin/javascripts/plupload/upload.php?PHPSESSID='+sessionId,
            flash_swf_url : '/admin/javascripts/plupload/js/plupload.flash.swf',
            silverlight_xap_url : '/admin/javascripts/plupload/js/plupload.silverlight.xap',

            // General settings
            runtimes : 'html5,gears,flash,silverlight,browserplus',
            autostart : true,
            max_file_size : '10mb',
            chunk_size : '1mb',
            unique_names : true,
            // Resize images on clientside if we can
            resize : {width : 1680, height : 1200, quality : 90},
            // Specify what files to browse for
            filters : [
                {title : "Image files", extensions : "jpg"}
            ]
        });


    });


    $("div.cms-message").each(function () {
        setTimeout(function(){ $("div.cms-message").hide(); }, 4000);
    });


}
