<?php

include_once("_framework/_nemo.list.cls.php");
include_once("includes/member.cls.php");
include_once("includes/farm.cls.php");  // << gets the Farm::sqlInspectorDDL()


//Additional / page specific translations
//20151110 - added tmp Docs functions - pj

class Document extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {

      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";

      $this->Filters[frType]->tag = "select";
      $this->Filters[frType]->html->value = "-1";
      $this->Filters[frType]->html->class = "controlText";
      $this->Filters[frType]->sql = "SELECT -1 AS ControlValue, '- All -' AS ControlText
         UNION ALL 
            SELECT EntityType AS ControlValue, EntityType AS ControlText
            FROM tblDocument
            GROUP BY EntityType
         ORDER BY ControlText";

      $this->Filters[frDocType]->tag = "select";
      $this->Filters[frDocType]->html->value = "-1";
      $this->Filters[frDocType]->html->class = "controlText";
      $this->Filters[frDocType]->sql = "SELECT -1 AS ControlValue, '- All -' AS ControlText
         UNION ALL 
            SELECT strDocumentType AS ControlValue, strDocumentType AS ControlText
            FROM tblDocument
            WHERE strDocumentType != ''
            GROUP BY strDocumentType
         ORDER BY ControlText";

      $this->Filters[frMember]->tag = "select";
      $this->Filters[frMember]->html->value = "0";
      $this->Filters[frMember]->html->class = "controlText";
      $this->Filters[frMember]->sql = "
            SELECT 0 AS ControlValue, '- All -' AS ControlText, '' AS strOrder 
         UNION ALL 
            SELECT MemberID AS 'ControlValue', concat(LPAD(MemberID,5,'0') ,' - ', strMember) AS 'ControlText', strMember AS strOrder 
            FROM tblMember INNER JOIN vieDocumentMember ON refMemberID = MemberID
            GROUP BY MemberID, concat(LPAD(MemberID,5,'0') ,' - ', strMember)
         UNION ALL 
            SELECT MemberID AS 'ControlValue', concat(LPAD(MemberID,5,'0') ,' - ', strMember, ' (TMP)') AS 'ControlText', strMember AS strOrder 
            FROM tmpMember INNER JOIN vieDocumentMemberTMP ON refMemberID = MemberID
            GROUP BY MemberID, concat(LPAD(MemberID,5,'0') ,' - ', strMember, ' (TMP)')
         ORDER BY strOrder, ControlText";


      $this->Filters[frFarm]->tag = "select";
      $this->Filters[frFarm]->html->value = "0";
      $this->Filters[frFarm]->html->class = "controlText";
      $this->Filters[frFarm]->sql = "
            SELECT 0 AS ControlValue, '- All -' AS ControlText, '' AS strOrder 
         UNION ALL 
            SELECT FarmID AS 'ControlValue', concat(LPAD(FarmID,5,'0') ,' - ', strFarm) AS 'ControlText', strFarm AS strOrder 
            FROM tblFarm INNER JOIN vieDocumentFarm ON refFarmID = FarmID
            GROUP BY FarmID, concat(LPAD(FarmID,5,'0') ,' - ', strFarm)
         UNION ALL 
            SELECT FarmID AS 'ControlValue', concat(LPAD(FarmID,5,'0') ,' - ', strFarm, ' (TMP)') AS 'ControlText', strFarm AS strOrder 
            FROM tmpFarm INNER JOIN vieDocumentFarmTMP ON refFarmID = FarmID
            GROUP BY FarmID, concat(LPAD(FarmID,5,'0') ,' - ', strFarm, ' (TMP)')
         ORDER BY strOrder, ControlText";



      parent::__construct($DataKey);
   }

   public function getList()
   {
     
      global $xdb;
      
      //Build where clauses  
      if($this->Filters[frSearch]->html->value != "")
      {//TODO: expand Search WHERE
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")"; 
         $Where .= " AND (EntityID $like
            OR Entity $like
            OR Type $like
            OR Filename $like)";
      }
      if($this->Filters[frType]->html->value != -1)
      {//TODO: expand Search WHERE
         $Where .= " AND (EntityType = '". $this->Filters[frType]->html->value ."')";
      }

      if($this->Filters[frDocType]->html->value != -1)
      {//TODO: expand Search WHERE
         $Where .= " AND (`Type` = '". $this->Filters[frDocType]->html->value ."')";
      }

      if($this->Filters[frMember]->html->value != 0)
      {//TODO: expand Search WHERE
         $Where .= " AND (EntityID = ". $this->Filters[frMember]->html->value ." AND `Entity Type` LIKE('Member%'))";
      }

      if($this->Filters[frFarm]->html->value != 0)
      {//TODO: expand Search WHERE
         $Where .= " AND (EntityID = ". $this->Filters[frFarm]->html->value ." AND `Entity Type` LIKE('Farm%'))";
      }


//20151110 - added tmp Docs functions - pj
      $this->ListSQL("
         SELECT * FROM (
         SELECT vieDocumentMember.DocumentID, EntityType AS 'Entity Type', vieDocumentMember.refMemberID AS EntityID, tblMember.strMember AS Entity, vieDocumentMember.strDocumentType AS Type, vieDocumentMember.strFilename AS Filename, dtUploaded AS Uploaded, vieDocumentMember.strLastUser as 'Last User', vieDocumentMember.dtLastEdit as 'Last Edit'
         FROM vieDocumentMember LEFT JOIN tblMember ON vieDocumentMember.refMemberID = tblMember.MemberID         

         UNION ALL

         SELECT vieDocumentFarm.DocumentID, EntityType AS 'Entity Type', vieDocumentFarm.refFarmID AS EntityID, tblFarm.strFarm AS Entity, vieDocumentFarm.strDocumentType AS Type, vieDocumentFarm.strFilename AS Filename, dtUploaded AS Uploaded, vieDocumentFarm.strLastUser as 'Last User', vieDocumentFarm.dtLastEdit as 'Last Edit'
         FROM vieDocumentFarm LEFT JOIN tblFarm ON vieDocumentFarm.refFarmID = tblFarm.FarmID         

         UNION ALL

         SELECT vieDocumentMemberTMP.DocumentID, EntityType AS 'Entity Type', vieDocumentMemberTMP.refMemberID AS EntityID, tmpMember.strMember AS Entity, vieDocumentMemberTMP.strDocumentType AS Type, vieDocumentMemberTMP.strFilename AS Filename, dtUploaded AS Uploaded, vieDocumentMemberTMP.strLastUser as 'Last User', vieDocumentMemberTMP.dtLastEdit as 'Last Edit'
         FROM vieDocumentMemberTMP LEFT JOIN tmpMember ON vieDocumentMemberTMP.refMemberID = tmpMember.MemberID         

         UNION ALL

         SELECT vieDocumentFarmTMP.DocumentID, EntityType AS 'Entity Type', vieDocumentFarmTMP.refFarmID AS EntityID, tmpFarm.strFarm AS Entity, vieDocumentFarmTMP.strDocumentType AS Type, vieDocumentFarmTMP.strFilename AS Filename, dtUploaded AS Uploaded, vieDocumentFarmTMP.strLastUser as 'Last User', vieDocumentFarmTMP.dtLastEdit as 'Last Edit'
         FROM vieDocumentFarmTMP LEFT JOIN tmpFarm ON vieDocumentFarmTMP.refFarmID = tmpFarm.FarmID
         
         ) AS derDocs
         WHERE 1=1 $Where
         ORDER BY Filename",0);
      
      return $this->renderTable("Documents");
   }

   //MEMBER DETAILS SUB LIST
   public function getMemberDocs($MemberID, $strReturnPage, $Action="Edit")
   {     
      global $xdb;
      //Build where clauses  
      
      $this->isSelectable = 0;

      $this->ListSQL("
         SELECT DocumentID, strDocumentType AS Type, strFilename AS Filename, dtUploaded AS Uploaded, strLastUser AS 'Last User', dtLastEdit AS 'Last Edit'
         FROM vieDocumentMember
         WHERE refMemberID = '$MemberID'
         ORDER BY strFilename",0,"document.php","$Action&MemberID=$MemberID&RETURN_URL=". urlencode("".$strReturnPage."?Action=Edit&")."&RETURN_VAR=MemberID");
       
      return $this->renderTable("Member Documents");
   }

   public function getLoggedMemberDocs($strReturnPage)
   {     
      global $xdb;
      //Build where clauses  
      
      $this->isSelectable = 0;

      $this->ListSQL("
         SELECT DocumentID, strDocumentType AS Type, strFilename AS Filename, dtUploaded AS Uploaded, strLastUser AS 'Last User', dtLastEdit AS 'Last Edit'
         FROM vieDocumentMember
         WHERE refMemberID = ".$_SESSION['USER']->MEMBERID."
         ORDER BY strFilename",0,"member.profile.php","New Document&MemberID=".$_SESSION['USER']->MEMBERID."&RETURN_URL=". urlencode("".$strReturnPage."?Action=Edit&")."&RETURN_VAR=MemberID");
       
      return $this->renderTable("Member Documents");
   }

   //TMP_MEMBER DETAILS SUB LIST //20151110 - added tmp Docs functions - pj
   public function getMemberDocsTMP($MemberID, $strReturnPage)
   {     
      global $xdb;
      //Build where clauses  
      
      $this->isSelectable = 0;

      $this->ListSQL("
         SELECT DocumentID, strDocumentType AS Type, strFilename AS Filename, dtUploaded AS Uploaded, strLastUser AS 'Last User', dtLastEdit AS 'Last Edit'
         FROM vieDocumentMemberTMP
         WHERE refMemberID = '$MemberID'
         ORDER BY strFilename",0,"document.php","Edit&MemberID=$MemberID&EntityType=MemberTMP&EntityID=$MemberID&RETURN_URL=". urlencode("".$strReturnPage."?Action=Edit&")."&RETURN_VAR=MemberID");
       
      return $this->renderTable("Member Documents (TMP)");
   }

   //Farm DETAILS SUB LIST //20151110 - added tmp Docs functions - pj
   public function getFarmDocs($FarmID, $strReturnPage, $path="document.php", $action='Edit')
   {     
      global $xdb;
      //Build where clauses  
      
      $this->isSelectable = 0;

      $this->ListSQL("
         SELECT DocumentID, strDocumentType AS Type, strFilename AS Filename, dtUploaded AS Uploaded, strLastUser AS 'Last User', dtLastEdit AS 'Last Edit'
         FROM vieDocumentFarm
         WHERE refFarmID = '$FarmID'
         ORDER BY strFilename",0,$path,"$action&FarmID=$FarmID&RETURN_URL=". urlencode("".$strReturnPage)."&RETURN_VAR=FarmID");
       
      return $this->renderTable("Farm Documents");
   }

   //TMP_Farm DETAILS SUB LIST //20151110 - added tmp Docs functions - pj
   public function getFarmDocsTMP($FarmID, $strReturnPage)
   {     
      global $xdb;
      //Build where clauses  
      
      $this->isSelectable = 0;

      $this->ListSQL("
         SELECT DocumentID, strDocumentType AS Type, strFilename AS Filename, dtUploaded AS Uploaded, strLastUser AS 'Last User', dtLastEdit AS 'Last Edit'
         FROM vieDocumentFarmTMP
         WHERE refFarmID = '$FarmID'
         ORDER BY strFilename",0,"document.php","Edit&FarmID=$FarmID&EntityID=$FarmID&EntityType=FarmTMP&RETURN_URL=". urlencode("".$strReturnPage."?Action=Edit&")."&RETURN_VAR=FarmID");
       
      return $this->renderTable("Farm Documents (TMP)");
   }

//REVIEW
   public static function Save()
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblDocument", $DocumentID, null, 0);

//if $_FILES assign new filename (obf()) and upload


      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

      $result = $db->Save(0,0);
      //die;
      
      //print_rr($result);
      if($result->Error == 1){
         return $result->Message;
      }else{
         return "Details create. ";
      }
   }

//REVIEW
   public static function Delete($chkSelect)
   {
      global $xdb;
      //print_rr($chkSelect);
      if(count($chkSelect) > 0){
      foreach($chkSelect as $key => $value)
      {
         $xdb->doQuery("DELETE FROM tblDocument WHERE ID = ". $xdb->qs($key)); 
//remove from file server        
      }
         return "Records Deleted. ";
      }
   }

   public static function getUploadForm($EntityType, $EntityID){
      global $xdb;
      $JS = "  <script>

               var uniqueID = -1;

               function jsCheckFile(inputId)
               {


                  var uploadedFile = document.getElementById('strFile_' + inputId);
                  var fileType = uploadedFile.files[0].type;
                  var fileSize = uploadedFile.files[0].size;

                  if((fileType != 'application/pdf') && (fileType != 'image/png') && (fileType != 'image/jpeg'))
                  {
                     $('#ErrorFileType_EN').html(\"".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_strFileType_Invalid"]."\")
                     $('#ErrorFileType_EN').slideDown('fast');
                     msg = 'random'
                  }
                  else
                  {
                     $('#ErrorFileType_EN').slideUp('fast');
                  }

                  if(fileSize > 5500000)
                  {
                     $('#ErrorFileSize_EN').html(\"".$_TRANSLATION[$_SESSION[LANGUAGE]]["valid_strFileSize_Invalid"]."\")
                     $('#ErrorFileSize_EN').slideDown('fast');
                     msg = 'random'
                  }
                  else
                  {
                     $('#ErrorFileSize_EN').slideUp('fast');
                  }

               }

               function jsAddItem()
               {
                  var copy = $('#extraRow').clone(true).insertAfter('#extraRow');
                  var trID = 'extraRow_'+ uniqueID;
                  copy.attr('id', trID);

                  $('#extraRow_'+uniqueID).find('#strID').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arrID['+uniqueID+']');
                     $(this).attr('id', Id+'_'+uniqueID);
                     $('#'+Id+'_'+uniqueID).val(uniqueID)
                     $(this).addClass('strID');
                  });

                  $('#extraRow_'+uniqueID).find('#ddlType').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arr_ddlType['+uniqueID+']');
                     $(this).attr('id', Id+'_'+uniqueID);
                  });

                  $('#extraRow_'+uniqueID).find('#strFile').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arr_strFile['+uniqueID+']');
                     $(this).attr('id', Id+'_'+uniqueID);
                     $(this).attr('onChange', 'jsCheckFile('+uniqueID+')');
                  });

                  $('#extraRow_'+uniqueID).find('#strUploadDate').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arr_strUploadDate['+uniqueID+']');
                     $(this).attr('id', Id+'_'+uniqueID);
                  });

                  $('#extraRow_'+uniqueID).find('#chkRemove').each(function(){
                     var Id = $(this).attr('id');
                     $(this).attr('name', 'arr_chkRemove['+uniqueID+']');
                     $(this).attr('id', Id+'_'+uniqueID);
                     $(this).attr('class', 'chkRemove');
                  });


                  $('#extraRow_'+uniqueID).css('display','');
                  uniqueID--
               }



               function jsRemoveItem(DocRow, IDx)
               {
                  if(IDx == 0)
                  {
                     $(DocRow).parent().parent().remove();
                  }
                  else
                  {
                     $.ajax(
                     {
                        type: 'POST',
                        url: 'ajaxfunctions.php',
                        data: 'header=text&type=DeleteDocument&DocumentID=' + IDx,
                        success: function(data)
                        {
                           try {}
                           catch(e) {}
                           finally {}
                        }
                     });

                     $(DocRow).parent().parent().remove();
                  }

               }

               function jsValidation()
               {
                  $('.Error_Text').slideUp('fast');
                  msg = '';

                  $('.docTable').find('.strID').each(function(){
                     inputId = $(this).val()
                     return jsCheckFile(inputId)
                  });
         
                  
                  if(msg != '')
                  {
                     return false;
                  }
                  return true;
               }

               </script>";

         
    
         ## FILE UPLOAD WIDGET HTML
         $NewContent  = "  <link rel='stylesheet' href='js/jQuery-File-Upload-9.11.2/CUSTOM/bootstrap.css'>  
                        <link rel='stylesheet' href='http://blueimp.github.io/Gallery/css/blueimp-gallery.min.css'> 
                        <link rel='stylesheet' href='js/jQuery-File-Upload-9.11.2/css/jquery.fileupload.css'>
                        <link rel='stylesheet' href='js/jQuery-File-Upload-9.11.2/css/jquery.fileupload-ui.css'> 
                        <noscript><link rel='stylesheet' href='js/jQuery-File-Upload-9.11.2/css/jquery.fileupload-noscript.css'></noscript>
                        <noscript><link rel='stylesheet' href='js/jQuery-File-Upload-9.11.2/css/jquery.fileupload-ui-noscript.css'></noscript> 


                        <form id='fileupload' action='https://jquery-file-upload.appspot.com/' method='POST' enctype='multipart/form-data'>
                           
                            
                           <div class='row fileupload-buttonbar'>
                              <div class='col-lg-9' style='width:500'> 
                                 <span class='btn btn-success fileinput-button'>
                                    <i class='glyphicon glyphicon-plus'></i>
                                    <span>Add file...</span>
                                    <input type='file' name='files[]'>
                                 </span>
                                 <button type='submit' class='btn btn-primary start'>
                                    <i class='glyphicon glyphicon-upload'></i>
                                    <span>Start upload</span>
                                 </button>
                                 <button type='reset' class='btn btn-warning cancel'>
                                    <i class='glyphicon glyphicon-ban-circle'></i>
                                    <span>Cancel upload</span>
                                 </button>
                                  
                              </div>

                              <div class='col-lg-12 fileupload-progress fade'>
                                 <div class='progress progress-striped active' role='progressbar' aria-valuemin='0' aria-valuemax='100'>
                                    <div class='progress-bar progress-bar-success' style='width:0%;''></div>
                                 </div>
                                 <div class='progress-extended'>&nbsp;</div>
                              </div>
                           </div> 
                           <table role='presentation' class='table table-striped'><tbody class='files'></tbody></table>
                        </form>
                        <form name='frmNemo' enctype='multipart/form-data' action='cvbxcvb' method='post'>
 
                        <script id='template-upload' type='text/x-tmpl'>
                        
                           {% for (var i=0, file; file=o.files[i]; i++) { %}

                              <tr class='template-upload fade'>
                                 <td width='15%'>
                                    <span class='preview'></span>
                                    <input type='hidden' name='title[]' value='$EntityID' class='form-control'> 
                                    <input type='hidden' name='description[]' value='$EntityType' class='form-control'> 
                                 </td>
                                 <td width='35%'>
                                    <p class='name'>{%=file.name%}</p>
                                    <strong class='error text-danger'></strong>
                                 </td>
                                 <td width='15%'>
                                    <p class='size'>Processing...</p>
                                    <div class='progress progress-striped active' role='progressbar' aria-valuemin='0' aria-valuemax='100' aria-valuenow='0'><div class='progress-bar progress-bar-success' style='width:0%;'></div></div>
                                 </td>
                                 <td width='35%' align='right'>
                                    {% if (!i && !o.options.autoUpload) { %}
                                        <button class='btn btn-primary start' disabled>
                                            <i class='glyphicon glyphicon-upload'></i>
                                            <span>Start</span>
                                        </button>
                                    {% } %}
                                    {% if (!i) { %}
                                        <button class='btn btn-warning cancel'>
                                            <i class='glyphicon glyphicon-ban-circle'></i>
                                            <span>Cancel</span>
                                        </button>
                                    {% } %}
                                 </td>
                              </tr>
                           {% } %}
                        
                        </script>

   
                        <script id='template-download' type='text/x-tmpl'>
                           {% for (var i=0, file; file=o.files[i]; i++) { %}
                              <tr class='template-download fade'>
                                 <td width='15%'>
                                    <span class='preview'>
                                       {% if (file.thumbnailUrl) { %}
                                          <a href='{%=file.url%}' title='{%=file.name%}' download='{%=file.name%}' data-gallery><img src='{%=file.thumbnailUrl%}'></a>
                                       {% } %}
                                    </span>
                                 </td>
                                 <td width='35%'>
                                    <p class='name'>
                                       {% if (file.url) { %}
                                          <a href='{%=file.url%}' title='{%=file.name%}' download='{%=file.name%}' {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                                       {% } else { %}
                                          <span>{%=file.name%}</span>
                                       {% } %}
                                    </p>
                                    {% if (file.error) { %}
                                       <div><span class='label label-danger'>Error</span> {%=file.error%}</div>
                                    {% } %}
                                 </td>
                                 <td width='15%'>
                                    <span class='size'>{%=o.formatFileSize(file.size)%}</span>
                                 </td>
                                 <td width='35%' align='right'>
                                    {% if (file.deleteUrl) { %}
                                       <button class='btn btn-danger delete' data-type='{%=file.deleteType%}' data-url='{%=file.deleteUrl%}'{% if (file.deleteWithCredentials) { %} data-xhr-fields='{\"withCredentials\":true}'{% } %}>
                                          <i class='glyphicon glyphicon-trash'></i>
                                          <span>Delete</span>
                                       </button> 
                                    {% } else { %}
                                       <button class='btn btn-warning cancel'>
                                          <i class='glyphicon glyphicon-ban-circle'></i>
                                          <span>Cancel</span>
                                       </button>
                                    {% } %}
                                 </td>
                              </tr>
                           {% } %}
                        </script>
                        <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/vendor/jquery.ui.widget.js'></script> 
                        <script src='http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js'></script> 
                        <script src='http://blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js'></script> 
                        <script src='http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js'></script> 
                        <script src='http://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script> 
                        <script src='http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js'></script>
 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.iframe-transport.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-process.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-image.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-audio.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-video.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-validate.js'></script> 
                        <script src='js/jQuery-File-Upload-9.11.2/js/jquery.fileupload-ui.js'></script>
                        <script src='js/jQuery-File-Upload-9.11.2/js/main.js'></script>";

                        $rstDocs = $xdb->doQuery("SELECT * FROM tblDocument WHERE refEntityID = '$EntityID' AND EntityType = '$EntityType'");
                        while($rowDocs = $xdb->fetch_object($rstDocs))
                        {  

                           if($rowDocs->type == "application/pdf")
                           {
                              $thumbnail = "<img height='60px' src='images/pdf_icon.png'>";
                           }
                           else if($rowDocs->type == "image/tiff")
                           {
                              $thumbnail = "<img height='60px' src='images/tiff_icon.png'>";
                           }
                           else
                           {  
                              $thumbnail = "<img src='js/jQuery-File-Upload-9.11.2/server/php/files/2thumbnail/$rowDocs->strFilename'>";
                           }
                           $rowDocs->size = round($rowDocs->size/1024, 2);
                           $CurrentContent .= " <tr class='template-download'>
                                                   <td width='15%' style='padding: 8px; border-top: 1px solid #ddd;background-color: #f9f9f9;'><span class='preview'> <a href='#' title='' download=''>$thumbnail </a></span></td>
                                                   <td width='35%' style='padding: 8px; border-top: 1px solid #ddd;background-color: #f9f9f9;'><p class='name'> <a target='blank' href='js/jQuery-File-Upload-9.11.2/server/php/files/2$rowDocs->strFilename'><span>$rowDocs->strFilename</span></a> </p></td>
                                                   <td width='15%' style='padding: 8px; border-top: 1px solid #ddd;background-color: #f9f9f9;'><span class='size'>$rowDocs->intSize</span> </td>
                                                   <td align='right' width='35%' style='padding: 8px; border-top: 1px solid #ddd;background-color: #f9f9f9;'> 
                                                      <div class='btn btn-danger delete' onClick='jsRemoveFile(this,$rowDocs->DocumentID);' > <i class='glyphicon glyphicon-trash'></i> <span>Delete</span> </div> 
                                                   </td>
                                                </tr> ";
                        }


      
         $JS = "<script>

               function jsRemoveFile(ctrl, idx)
               {      
                  $(ctrl).parent().parent().hide('slow');
                  
                  $.ajax(
                  {
                     type: 'POST',
                     url: 'ajaxfunctions.php',
                     data: 'header=text&type=removeFile&DocID=' + idx,
                     success: function(data)
                     { 
                       $(ctrl).parent().parent().remove();
                     }
                  });
               }
               </script>";


         return "<table class='dora-DetailsTable' border='0' cellpadding='2' cellspacing='1' width='100%'>
                     <tr>
                        <td>$NewContent
                           <table width='100%'>$CurrentContent</table>
                        </td>
                     </tr>
                  </table>" . $JS;



   }


}
?>