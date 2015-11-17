<?php

/*
   # 20131126 - UNLIKELY EVENT OF DUPLICATE UNIQUE ID'S BEING GENERATED - PJ
   //20150702 - Add MIME Encode to fix issue with invalid characters in the email header and subject- Christiaan   
   //20150706 - correcting header.Date on resend - cl
*/
@include_once("../system.php");
include_once("system.functions.inc.php");

class NemoEmail{
   var $UID             = "";
   var $EmailTemplateID = 0;
   var $EmailTemplate = "";
   var $Subject         = "";
   var $to              = "";

   var $header          = "";
   var $arrBody         = array(); // arrBody[0] : text; arrBody[1] : html

   var $arrAttachment   = array();
   var $strAttachment   = "";

   var $arrSubstitution = array();

   var $embed           = 0;
   var $embedImages     = array();
   var $preferences     = array();


   var $eol             = PHP_EOL;
   var $style           = "";
   var $htmlBodyHeader  = "";
   var $htmlBodyFooter  = "";
   var $warnings        = "";
   var $blnSaveEmail    = 1;
   var $result; //object for storing email SaveResults

   function __construct($to,$Subject,$embed=null)
   {
      $this->to      = $to;
      $this->Subject = $Subject;
      $this->embed   = $embed;

      //20150702 - Add MIME Encode to fix issue with invalid characters in the email header and subject- Christiaan
      $this->preferences = array(
             "input-charset" => "UTF-8",
             "output-charset" => "UTF-8",
             "line-length" => 76,
             "line-break-chars" => "\n",
             "scheme" => "Q"
         );
   }

   function addStyleSheet($fileAtt)
   {
      if(file_exists($fileAtt)){
         $this->style = file_get_contents($fileAtt);
      }else{
         $this->warnings .= "Style Sheet not Found!";
      }
   }

   function addHeader($type,$value)
   {
      $type = strtolower($type);
      switch($type)
      {
         case "reply-to":
            $type = "replyto";
         break;
         case "from":
            //20150702 - Add MIME Encode to fix issue with invalid characters in the email header and subject- Christiaan

            //find email from mail address
            preg_match('~<([^>]+)>~',$value,$arrFrom);
            //if this matches nothing (returns 0) or has an error (returns false)
            if(is_array($arrFrom) && trim($arrFrom[1]) != "")
            {
             //print_rr($arrFrom); 
               $strFrom = trim(str_replace("<".$arrFrom[1].">", "", $value));
               $strFrom = iconv_mime_encode("From", $strFrom, $this->preferences); //note: does not add phpEOL.          
               //the mime_encode has its input char type as ISO-8859-1

               $this->$type = $value;

               $this->header .= trim($strFrom)." <".$arrFrom[1].">".$this->eol;
   
               return null;
            }
         case "bcc":
            //20150702 - Add MIME Encode to fix issue with invalid characters in the email header and subject- Christiaan

            //find email from mail address
            preg_match('~<([^>]+)>~',$value,$arrBcc);
            //if this matches nothing (returns 0) or has an error (returns false)
            if(is_array($arrBcc) && trim($arrBcc[1]) != "")
            {
             //print_rr($arrFrom); 
               $strBcc = trim(str_replace("<".$arrBcc[1].">", "", $value));
               $strBcc = iconv_mime_encode("bcc", $strBcc, $this->preferences); //note: does not add phpEOL.          
               //the mime_encode has its input char type as ISO-8859-1

               $this->$type = $value;

               $this->header .= trim($strBcc)." <".$arrBcc[1].">".$this->eol;
   
               return null;
            }
         break;
      }
      $this->$type = $value;
      $this->header .= $type.":".$value.$this->eol;

   }

   function addBody($type,$body){
      switch($type){
         case "html":
            $this->arrBody[1] = "<style>".$this->style."</style>".$this->htmlBodyHeader.$body.$this->htmlBodyFooter;
         break;
         default:
            $this->arrBody[0] = $body;
      }
   }

   function addAttachment($fileAtt,$ext){
      $fileName = basename($fileAtt);
      $this->arrAttachment[$fileName][$ext] = $fileAtt;
   }

   function Save($arrFields=null)
   {
      global $SystemSettings;

      

//$this->UID = "51NA8K"; //TEST VALUE
# 20131126 - UNLIKELY EVENT OF DUPLICATE UNIQUE ID'S BEING GENERATED - PJ       
      $edb = new NemoDatabase("tblEmail", 0, array("EmailID"),$blnDebug);
      $row = $edb->getRowSQL("SELECT * FROM tblEmail WHERE UniqueID = '$this->UID'");
      if($row)
      {         
         $this->UID = substr($this->UID,0,strlen($this->UID)-2). rand(10,99);
      }

//vd($this->to);
      $edb->Fields["UniqueID"] = $this->UID;

      $edb->Fields["refEmailTemplateID"]  = $this->EmailTemplateID;
      $edb->Fields["strTo"]               = $this->to;
      $edb->Fields["strCC"]               = $this->cc;
      $edb->Fields["strFrom"]             = $this->from;
      $edb->Fields["strSubject"]          = $this->Subject;
      $edb->Fields["txtHeaders"]          = str_replace('"','|', $this->header);
      $edb->Fields["txtBody"]             = $this->arrBody[1];
      $edb->Fields["dtEmail"]             = date("Y-m-d H:i:s");
      $edb->Fields["arrAttachment"]       = $this->strAttachment;
      $edb->Fields["strLastUser"]         = $this->EmailTemplate;

      $edb->Fields["strStatus"]           = "Sending";


      # Save Custom Fields and Overrride Fields
      if(isset($this->arrFields))
         $arrFields = $this->arrFields;
      if(is_array($arrFields)){
         foreach($arrFields as $Field => $value){
            $edb->Fields[$Field]  = $value;
         }
      }

      $result = $edb->Save(0,0,"",1);
      return $result;
   }

   function Send()
   {
      $num = md5(time());
      $eol = $this->eol;

      //make changes to body[1] here
      /*****************************************************************************************************************************************************************************************************************/
      
      $this->arrBody[1] = "<font style='font-size:11.0pt; font-family:Calibri,sans-serif,Arial;'>". $this->arrBody[1] ."</font>";
      
      /*****************************************************************************************************************************************************************************************************************/
      //stop making changes to body[1]
      
      //HANDLING 998CHAR.PER.LINE ISSUE. see http://www.faqs.org/rfcs/rfc2822.html ref 2.1.1 - line length limits
      //breaking the body text into blocks of text at the next whitespace after the CharLineMax
      //NOTE that it is done in reverse because we don't want a whitespace break occuring during a footer img tag , eg. logo, or a tags which are more likely to appear at the end of the email
      $txtBody = $this->arrBody[1];
      $txtBody = strrev($txtBody);
      $intCharLineMax = 777;
      
         //echo strlen($txtBody)." $idxWhite $idxNL $idxCR $idxBR";
      while(strlen($txtBody) > $intCharLineMax)
      {
         $idxWhite = strpos($txtBody, chr(32), $intCharLineMax);
         
         $arrHTML[] = strrev(substr($txtBody, 0, $idxWhite+1));
         $txtBody = substr($txtBody, $idxWhite+1);
         //echo strlen($txtBody)." $idxWhite $idxNL $idxCR $idxBR";
      }
      
      $arrHTML[] = strrev($txtBody);
      $this->arrBody[1] = implode($eol, array_reverse($arrHTML));
      

      $headers = $this->header;
      $headers .= "MIME-Version: 1.0".$eol;
      $headers .= "Date: ". date("r").$eol; //D d M Y H:m:i
      $headers .= "Content-type: multipart/mixed; boundary=\"".$num."\"".$eol.$eol;
      $headers .= "Content-Transfer-Encoding: 7bit".$eol;
      $headers .= "".$this->arrBody[0]."".$eol.$eol;

      if($this->embed == 1)
      {
         if(is_array($this->embedImages)){
            foreach($this->embedImages as $CID=>$fileAtt){
               if(file_exists($fileAtt)){
                  $fileName = basename($fileAtt);
                  $attachment = file_get_contents($fileAtt);
                  $attachment = chunk_split(base64_encode($attachment));

                  # Get extension
                  $path_info = pathinfo($fileName);

                  $headers .= "--".$num.$eol;
                  $headers .= "Content-Type: image/".$path_info['extension']."; name=\"".$fileName."\"".$eol;
                  $headers .= "Content-Transfer-Encoding: base64".$eol;
                  $headers .= "Content-ID: <$CID>".$eol;
                  $headers .= "Content-Disposition: inline; filename=\"".$fileName."\"".$eol.$eol;
                  $headers  .= $attachment.$eol.$eol;
               }else{
                  $this->warnings .="Embed Image $fileAtt not found|";
               }
            }
         }
      }

      if(is_array($this->arrAttachment))
      {// If there are Attachments.
         $headers .= "--".$num.$eol;
         $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
         $headers .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
         $headers .= "".$this->arrBody[1].$eol.$eol;

         # Loop Through arrAttachment;         
         foreach($this->arrAttachment as $fileName => $arrExt){
            foreach($arrExt as $ext=>$fileAtt){//echo "$fileAtt :"; vd(file_exists($fileAtt));
               if(file_exists($fileAtt)){
                  $attachment = file_get_contents($fileAtt);
                  $attachment = chunk_split(base64_encode($attachment));

                  $headers .= "--".$num.$eol;
                  $headers .= "Content-Type: application/$ext; name=\"".$fileName."\"".$eol;
                  $headers .= "Content-Transfer-Encoding: base64".$eol;
                  $headers .= "Content-Disposition: attachment".$eol.$eol;
                  $headers .= $attachment.$eol.$eol;
               }else{
                  $this->warnings .="File $fileName not found|";
               }
               $this->strAttachment .= $fileAtt."|";
            }
         }
      }else{

         $headers .= "Content-Type: text/plain; charset=\"iso-8859-1\"".$eol;
         $headers .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
         $headers .= "".$this->arrBody[0].$eol.$eol;
         $headers .= "--".$num.$eol;

         $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
         $headers .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
         $headers .= "".$this->arrBody[1]."".$eol.$eol;
         $headers .= "--".$num."--";
      }

      $this->header = $headers;

      $this->setUID();
      if($this->blnSaveEmail == 1)
         $this->result = $this->Save(0,1);

//print_rr($this); die;
      //20150702 - Add MIME Encode to fix issue with invalid characters in the email header and subject- Christiaan  
      //$this->preferences["input-charset"] = "ISO-8859-1";  
      $strSubject = iconv_mime_encode("Subject",$this->Subject, $this->preferences);
      $strSubject = str_replace("Subject: ", "", $strSubject);

      $result = mail($this->to, $strSubject , null, $this->header);
      $this->warnings .= $result?"":"Mail not sent: ". $result->Message;

      $this->UpdateEmailResult();

      if($this->blnSaveEmail == 1){
         //print_rr($this->result);
         return $this->result;
      }else{
         return $result;
      }

   }

   public function UpdateEmailResult()
   {
      $edb = new NemoDatabase("tblEmailTemplate", 0, array("EmailTemplateID"),$blnDebug);
      if($this->warnings)
         $edb->doQuery("UPDATE tblEmail SET strStatus = 'Error', txtNotes = ". $edb->qs($this->warnings) ." WHERE UniqueID = '$this->UID'");
      else
         $edb->doQuery("UPDATE tblEmail SET strStatus = 'Sent' WHERE UniqueID = '$this->UID'");
   }

   public function LoadEmailTemplate($TemplateName)
   {
      global $SystemSettings;
      
      $edb = new NemoDatabase("tblEmailTemplate", 0, array("EmailTemplateID"),$blnDebug);
      $row = $edb->getRowSQL("SELECT * FROM tblEmailTemplate WHERE strEmailTemplate = '$TemplateName'");
      $this->EmailTemplate = $TemplateName;
      if($row)
      {
         if($row->arrSubstitutions != "")
         {
            foreach(explode(",", $row->arrSubstitutions) as $i => $key)
            {
               $this->arrSubstitution[$key] = "";
            }
         }

         if(is_dir($SystemSettings[InvoicePdfDirAdmin]))
            $dir = $SystemSettings[InvoicePdfDirAdmin];
         else
            $dir = str_replace("../","",$SystemSettings[InvoicePdfDirAdmin]);

         if($row->arrAttachments != "")
         {//new 20120817 - added template default attachments
            foreach(explode(",", $row->arrAttachments) as $i => $filename)
            {               
               $this->addAttachment($dir.$filename, 1);
            }
         }//print_rr($this->arrAttachment);

         $this->EmailTemplateID = $row->EmailTemplateID;

         $this->Subject = $row->strSubject;
         $this->arrBody[0] = strip_tags($row->txtBody);
         $this->arrBody[1] = mynl2br($row->txtBody);

      }else{
         $this->warnings = "Email Template, '$TemplateName', could not be found";
      }
   }

   public function Substitute($arrValues)
   {
      global $HTTP, $SystemSettings;
      
      if(count($arrValues)){
         foreach($arrValues as $key => $value)
         {
            $this->arrBody[0] = str_replace("[$key]", $value, $this->arrBody[1]);
            $this->arrBody[1] = str_replace("[$key]", $value, $this->arrBody[1]);
         }
      
      }elseif(count($this->arrSubstitution))
      {
         foreach($this->arrSubstitution as $key => $null)
         {
            $this->arrSubstitution[$key] = $arrValues[$key];
            $this->arrBody[0] = str_replace("[$key]", $arrValues[$key], $this->arrBody[1]);
            $this->arrBody[1] = str_replace("[$key]", $arrValues[$key], $this->arrBody[1]);
         }
      }

      $href = $SystemSettings[BASE_URL]."/index.php";
      $src = $SystemSettings[BASE_URL]."/images/logo_email.jpg";
      
      $this->arrBody[0] = 
      $this->arrBody[1] = str_replace("[Logo]", "<a href='$href'><img src='$src' ></a>", $this->arrBody[1]);
   }

   //20150702 - Add MIME Encode to fix issue with invalid characters in the email header and subject- Christiaan
   //20150706 - correcting header.Date on resend - cl
   public function SendFromDatabase($EmailID, $To="")
   {
      global $SystemSettings;
      $eol = $this->eol;
      $edb = new NemoDatabase("tblEmail", $EmailID, array("EmailID"),$blnDebug);
      //print_rr($SystemSettings); die;

      $this->EmailTemplateID = $edb->Fields["refEmailTemplateID"];
      $this->from = $edb->Fields["strFrom"];     
      $this->to = $edb->Fields["strTo"];
      $this->cc = $edb->Fields["strCC"];
      $this->Subject = $edb->Fields["strSubject"];
      
      $this->header = str_replace("|",'"', $edb->Fields["txtHeaders"]);

      //20150706 - correcting header.Date on resend - cl
      $intDateStartPos = strpos( $this->header , "Date: ") + 6;
      $this->header = substr_replace($this->header, date("r"), $intDateStartPos, 31);

      $this->arrBody[1] = $edb->Fields["txtBody"];
      $this->strAttachment = $edb->Fields["arrAttachment"];
      $this->EmailTemplate = $edb->Fields["strLastUser"];

      if($To != "")
         $this->to = $To;

//vd($this->to);

      $this->unsetUID($edb->Fields["UniqueID"]);
      $this->setUID();
      $result2 = $this->Save(array("strLastUser"=>"Resend"));

      $strSubject = iconv_mime_encode("Subject", $edb->Fields["strSubject"] , $this->preferences);
      $strSubject = str_replace("Subject: ", "", $strSubject);

      //echo $strSubject;

      $result = mail($this->to, $strSubject, null, $this->header);
      $this->warnings .= $result?"":"Mail not sent: $result";
      $this->UpdateEmailResult();

      return $result2;

   }

   private function setUID()
   {
      global $SystemSettings;

      $this->UID = Obfuscate();
      //substitute UID
      $this->header = str_replace("[ReadOnline]", $SystemSettings[EmailReadOnlineURL] ."?UID=$this->UID", $this->header);
      $this->arrBody[1] = str_replace("[ReadOnline]", $SystemSettings[EmailReadOnlineURL] ."?UID=$this->UID", $this->arrBody[1]);

      $this->header = str_replace("[ReadReceipt]","<img src='".$SystemSettings[EmailReadReceiptURL]."?UID=$this->UID' />",$this->header);//header only!
      $this->arrBody[1] = str_replace("[ReadReceipt]","", $this->arrBody[1]);//header only!

   }

   private function unsetUID($UID)
   {
      global $SystemSettings;

      //$this->UID = Obfuscate();

      //substitute UID
      $this->header = str_replace($SystemSettings[EmailReadOnlineURL] ."?UID=$UID", "[ReadOnline]", $this->header);
      $this->arrBody[1] = str_replace($SystemSettings[EmailReadOnlineURL] ."?UID=$UID", "[ReadOnline]", $this->arrBody[1]);

      $this->header = str_replace("<img src='".$SystemSettings[EmailReadReceiptURL]."?UID=$UID' />","[ReadReceipt]",$this->header);//header only!
   }

   function EmailForm($frEmailTemplateID)
   {
      global $xdb, $BR, $SP, $TR, $HR, $DT, $SystemSettings;
      include_once("_nemo.details.cls.php");
      //ini
      $page = new NemoDetails();

      $acData =
      $comma = "";
      //acData in this format:
      /* $acData = "[
            { label: 'anders', category: '' },
            { label: 'andreas', category: '' },
            { label: 'andreas andersson', category: 'People' },
            { label: 'andreas johnson', category: 'People' }
         ]";
      */

      $xdb->doQuery("SELECT vieEmailContacts.ViewID, vieEmailContacts.strView, LEFT(UPPER(vieEmailContacts.strView), 1) as strCategory
         FROM vieEmailContacts
         ORDER BY strView");
      $i=0;
      while($row = $xdb->fetch())
      {
         $acData .= "$comma{ label: \"$row->strView ($row->ViewID)\", category: '$row->strCategory', value: '$row->ViewID'}
         ";
         $comma = ",";
      }
      $acData = "[$acData]";


      //load
      $page->Message->Text = $Message;
      $page->AssimulateTable("tblEmail", $EmailID, "UniqueID");

      $page->Fields["EmailID"]->Control->type = "hidden";
      $page->Fields["refEmailTemplateID"]->Control->onchange = "ajaxGetTemplate(this.value);";
//print_rr($page->Fields["refEmailTemplateID"]);
      if($frEmailTemplateID != 0)
      {
         $page->Fields["refEmailTemplateID"]->Control->value = $frEmailTemplateID;
         $jsOnLoad = "ajaxGetTemplate($frEmailTemplateID);";
      }

      if($SystemSettings[AllowIndividualEmails])
      {
         $page->Fields["blnIndividual"]->Control->tag = "input";
         $page->Fields["blnIndividual"]->Control->type = "checkbox";
         $page->Fields["blnIndividual"]->Control->value = "1";
         $page->Fields["blnIndividual"]->Control->name =
         $page->Fields["blnIndividual"]->Control->id = "blnIndividual";
         $page->Fields["blnIndividual"]->Control->comment = "Personalize and Send emails individually";
         $page->Fields["blnIndividual"]->Label = "Individualize";
         $page->Fields["blnIndividual"]->ORDINAL_POSITION = $page->Fields["refEmailTemplateID"]->ORDINAL_POSITION +0.5;
      }

      $page->Fields["strTo"]->Control->class =
      $page->Fields["strCC"]->Control->class =
      $page->Fields["strSubject"]->Control->class = "controlText controlWideMax";

      $page->Fields["strFrom"]->Control->class = "controlText controlLabel controlWideMax";
      $page->Fields["strFrom"]->Control->value = $SystemSettings["SMTP Send As"];

      $page->Fields["strTo"]->Control->comment = "$BR<input type='text' name='strSearchEmail_strTo' id='strSearchEmail_strTo' class='controlText'> <input type='button' name='Add' value='Add' onclick=\"jsAppentEmail('strTo','strSearchEmail_strTo');\" class='btn controlButton'>";

      $page->Fields["strCC"]->Control->comment = "$BR<input type='text' name='strSearchEmail_strCC' id='strSearchEmail_strCC' class='controlText'> <input type='button' name='Add' value='Add' onclick=\"jsAppentEmail('strCC','strSearchEmail_strCC');\" class='btn controlButton'>";
      $page->Fields["strCC"]->Control->value = $_SESSION[USER]->USERNAME ." <".$_SESSION[USER]->EMAIL.">";

      $page->Fields["txtBody"]->Control->style = "height: 350px;";

      $page->Fields["arrAttachments"]->Control->style = "display: none;";
      $page->Fields["arrAttachments"]->Control->readonly = "readonly";
      $page->Fields["arrAttachments"]->Control->class = "controlText controlLabel controlWideMax";
      $page->Fields["arrAttachments"]->Control->comment = Email::renderAttachments($page->Fields["arrAttachments"]->VALUE);

      //print_rr($page->Fields["strTo"]);

      if($_GET[strTo] != "")
         $page->Fields["strTo"]->Control->value = $_GET[strTo];


      if($EmailID == 0)
      {

      }else{

         $preview = "<div class='divPreview'>". Email::Preview($EmailID) ."</div>";

         //$page->ToolBar->Buttons[btnNew2]->blnShow = 1;
         //$page->ToolBar->Buttons[btnNew2]->Control->value = "New Email";
      }

      unset($page->Fields["UniqueID"]);
      unset($page->Fields["txtHeaders"]);
      unset($page->Fields["strStatus"]);
      unset($page->Fields["dtEmail"]);

      $page->renderControls();

      //print_rr($page->Fields);
      return $page->renderTable($page->ToolBar->Label)
         . $page->getJsNemoValidateSave()
         . js("

            function jsAppentEmail(ControlToID, ControlFromID)
            {
               var op = '';
               if($('#'+ControlToID).val().length > 0)
               {
                  op = '; ';
               }
               $('#'+ControlToID).val($('#'+ControlToID).val() + op + $('#'+ControlFromID).val());
               $('#'+ControlFromID).val('');
            }

            function ajaxGetTemplate(templateID)
            {
               if(templateID == 0){
                  $('#strSubject').hide('fast');
                  $('#txtBody').hide('fast');
                  //$('#arrAttachments').hide('fast');

                  $('#strSubject').val('');
                  $('#txtBody').val('');
                  $('#arrAttachments').val('');

                  $('#strSubject').show('fast');
                  $('#txtBody').show('fast');
                  //$('#arrAttachments').show('fast');

                  return true;
               }
               $.ajax({type: 'POST',
                  url: '_framework/_nemo.email.cls.php',
                  data: 'header=text&ajaxRequest=GetTemplate&TemplateID='+templateID,
                  success: function(data)
                  {
                     try
                     {
                        arrData = new Array;


                        arrData = jQuery.parseJSON(data);
                        if(data != null)
                        {

                           $('#strSubject').hide('fast');
                           $('#txtBody').hide('fast');
                           $('#arrAttachments').hide('fast');

                           $('#strSubject').val(data[0]);
                           $('#txtBody').val(data[1]);
                           $('#arrAttachments').val(data[2]);

                           //dont flash
                           $('#strSubject').show('fast');
                           $('#txtBody').show('fast');
                           $('#arrAttachments').show('fast');
                        }
                        else
                        {//deselect
                           $('#strSubject').hide('fast');
                           $('#txtBody').hide('fast');
                           $('#arrAttachments').hide('fast');

                           $('#strSubject').val('');
                           $('#txtBody').val('');
                           $('#arrAttachments').val('');

                           $('#strSubject').show('fast');
                           $('#txtBody').show('fast');
                           $('#arrAttachments').show('fast');
                        }
                     }catch(e){alert('Javascript exception in ajaxGetTemplate(): '+e);}
                  },
                  error: function(data)
                  {
                     alert('ajaxGetTemplate: error');
                  }
               });
            }

            $.widget( 'custom.catcomplete', $.ui.autocomplete, {
               _renderMenu: function( ul, items ) {
                  var self = this,
                     currentCategory = '';
                  $.each( items, function( index, item ) {
                     if ( item.category != currentCategory ) {
                        ul.append( '<li class=\"ui-autocomplete-category\">' + item.category + '</li>' );
                        currentCategory = item.category;
                     }
                     self._renderItem( ul, item );
                  });
               }
            });

            $(function() {
               var acData = $acData;
               $('#strSearchEmail_strTo').catcomplete({delay: 0, source: acData });
               $('#strSearchEmail_strCC').catcomplete({delay: 0, source: acData });
            });

            $jsOnLoad

            ");
   }
}


//AJAX routine
if(isset($_REQUEST['ajaxRequest']))
{
   @include_once("../system.php");
   switch($_REQUEST['ajaxRequest'])
   {
      case "GetTemplate":
         $strOutput = GetTemplate($_REQUEST[TemplateID]);
         header("Content-type: application/json");
         //print_rr($strOutput);
         echo json_encode($strOutput);
         die;
         break;
   }


   if($_REQUEST['header'] == "")
      header("Content-type: text/xml");

   $strOutput = str_replace("&" , "&amp;" , $strOutput);
   echo $strOutput;
   die;
}

//AJAX Functions
function GetTemplate($TemplateID)
{
   global $xdb;

   $row = $xdb->getRowSQL("SELECT * FROM tblEmailTemplate WHERE EmailTemplateID = " . $xdb->qs($TemplateID));
   if($row)
      return array($row->strSubject,$row->txtBody,$row->arrAttachments,$row->arrSubstitutions);
}

   //testing
   if($Action == "Test")
   {
      /*$nemoEmail = new NemoEmail("exiledbandit@gmail.com" , "" , 0);

      //$nemoEmail->SentFromDatabase(10,"exiledbandit@gmail.com"); die;

      //$nemoEmail->LoadEmailTemplate("Course Start");
      //$nemoEmail->LoadEmailTemplate("New Module");
      //$nemoEmail->LoadEmailTemplate("Assignment Reminder");
      //$nemoEmail->LoadEmailTemplate("Course End");
      $nemoEmail->LoadEmailTemplate("Course Start");

      $arrValues[DisplayName] = "PJ Testing";
      $arrValues[DisplayName] = "Jackie";
      $arrValues[Email] = "exiledbandit@gmail.com";
      $arrValues[Password] = "123";
      $arrValues[Module] = "1: History";
      $arrValues[Course] = "ChemTest 101 [2012/CHM/n]";
      $arrValues[Course] = "Executive Assistant Course PILOT1 [2012/TEA/4]";
      $arrValues[StartDate] = date("j F Y");
      $arrValues[DueDate] = date("j F Y");
      $arrValues[SurveyToken] = "UID123";

      $nemoEmail->Substitute($arrValues);

      $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
      $nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
      $nemoEmail->Bcc = $SystemSettings["SMTP BCC"];
      $nemoEmail->From = $SystemSettings["SMTP Send As"];

      //print_rr($nemoEmail);

      print_rr($nemoEmail->Send());*/
   }

?>
