<?php
/*
ALTER TABLE `tblEmail` DROP FOREIGN KEY `tblEmail.strEmailTemplate`;

NB! SET FOREIGN_KEY_CHECKS=0; refETID is not always set
ALTER TABLE `tblEmail` ADD CONSTRAINT `tblEmail.strEmailTemplate` FOREIGN KEY ( `refEmailTemplateID` ) REFERENCES `tblEmailTemplate` ( `EmailTemplateID` ) ON DELETE CASCADE ON UPDATE CASCADE ;

*BR: only Security.Special to send New Emails.
*/

include_once("_framework/_nemo.list.cls.php");

class Email extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      //filters
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";

      $this->Filters[frTemplate]->tag = "select";
      $this->Filters[frTemplate]->html->value = "-2";
      $this->Filters[frTemplate]->html->class = "controlText";
      $this->Filters[frTemplate]->sql = "SELECT -2 AS ControlValue, '- All -' AS ControlText
                        UNION ALL
                        SELECT 0 AS ControlValue, '- No Template -' AS ControlText
                        UNION ALL
                        SELECT EmailTemplateID AS ControlValue, strEmailTemplate AS ControlText
                        FROM tblEmailTemplate INNER JOIN tblEmail ON refEmailTemplateID = EmailTemplateID
                        GROUP BY strEmailTemplate
                        ORDER BY ControlText ASC";

      $this->Filters[frStatus]->tag = "select";
      $this->Filters[frStatus]->html->value = "";
      $this->Filters[frStatus]->html->class = "controlText";
      $this->Filters[frStatus]->sql = "SELECT '' AS ControlValue, '- All -' AS ControlText
                        UNION ALL
                        SELECT strStatus AS ControlValue, strStatus AS ControlText
                        FROM tblEmail
                        GROUP BY strStatus
                        ORDER BY ControlText ASC";

      $this->Filters[frIgnoreDate]->tag = "input";
      $this->Filters[frIgnoreDate]->html->value = "checked";
      $this->Filters[frIgnoreDate]->html->type = "checkbox";
      $this->Filters[frIgnoreDate]->html->onclick = "d('frStartDate').disabled=this.checked;d('frEndDate').disabled=this.checked;";

      $this->Filters[frStartDate]->tag = "input";
      $this->Filters[frStartDate]->html->value = date("Y-m-01");
      $this->Filters[frStartDate]->html->type = "text";
      $this->Filters[frStartDate]->html->class = "controlText controlNumeric datePicker";

      $this->Filters[frEndDate]->tag = "input";
      $this->Filters[frEndDate]->html->value = date("Y-m-d");
      $this->Filters[frEndDate]->html->type = "text";
      $this->Filters[frEndDate]->html->class = "controlText controlNumeric datePicker";

      //construct
      parent::__construct($DataKey);

   }

   public function getList()
   {
      global $SystemSettings, $DT, $xdb;

      $Where = "";

      if($this->Filters[frSearch]->html->value != "")
      {
         $like = "LIKE (". $xdb->qs("%". $this->Filters[frSearch]->html->value ."%") .")";
         $Where .= " AND (tblEmail.strSubject $like
                        OR tblEmail.strTo $like
                        OR tblEmail.UniqueID $like
                        OR tblEmail.txtBody $like) ";
      }

      if($this->Filters[frTemplate]->html->value != "-2")
      {
         $Where .= " AND refEmailTemplateID = '" . $this->Filters[frTemplate]->html->value . "'";
      }

      if($this->Filters[frStatus]->html->value != "")
      {
         $Where .= " AND strStatus = '" . $this->Filters[frStatus]->html->value . "'";
      }

      if($this->Filters[frIgnoreDate]->html->checked != "checked")
      {
         $Where .= " AND left(dtEmail,10) >= '". $this->Filters[frStartDate]->html->value ."' AND left(dtEmail,10) <= '". $this->Filters[frEndDate]->html->value ."'";
      }

      $this->ListSQL("
         SELECT tblEmail.EmailID, tblEmailTemplate.strEmailTemplate AS Template, tblEmail.strSubject AS Subject, tblEmail.strTo AS 'To',tblEmail.strStatus AS Status, tblEmail.dtEmail AS 'Timestamp', tblEmail.strLastUser AS 'Last User', tblEmail.dtLastEdit AS 'Last Edit'
         FROM tblEmail INNER JOIN tblEmailTemplate ON tblEmailTemplate.EmailTemplateID = tblEmail.refEmailTemplateID
         WHERE 1=1 $Where
         ORDER BY dtEmail DESC ", 0);

      return $this->renderTable("Email List");
   }

   public static function Save(&$EmailID)
   {
      global $DT, $SystemSettings;

      $db = new NemoDatabase("tblEmail", $EmailID, null, 0);


      //$db->SetValues($_POST);
      //ONLY save txtNotes

      $db->Fields[txtNotes] = $_POST[txtNotes];

      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

      $result = $db->Save();
      //print_rr($result);


      if($result->Error == 1)
         return $result->Message;
      else
         return "Notes Saved. ";
   }

   public static function Delete($chkSelect)
   {
      global $xdb;
      //print_rr($chkSelect);
      if(count($chkSelect) > 0){
      foreach($chkSelect as $key => $value)
      {
         $xdb->doQuery("DELETE FROM tblEmail WHERE EmailID = ". $xdb->qs($key));
      }
         return "Records Deleted. ";
      }
   }

   public static function CreateEmail($EmailID)
   {
      global $xdb, $SystemSettings, $DT;


      //windowLocation("email.php?Action=CreateEmail&EmailID=$newEmailID");
   }

   public static function Preview($EmailID)
   {
      global $xdb, $SystemSettings, $DT, $SP, $TR, $BR, $HR;

      $rowEmail = $xdb->getRowSQL("SELECT * FROM tblEmail WHERE EmailID = ". $xdb->qs($EmailID));
      if($rowEmail->strCC != "")
         $strCC = "
         <span class='textGraphite'>CC:</span> ". htmlentities($rowEmail->strCC) ."$BR";

      $strEmailHeader = "
         <span class='textHeading textColour' style='width: 100%;'>$rowEmail->strSubject</span>
         $BR
         $BR
         <span class='textGraphite'>From:</span> ". htmlentities($rowEmail->strFrom) ."$BR
         <span class='textGraphite'>To:</span> ". htmlentities($rowEmail->strTo) ."$BR
         $strCC
         <span class='textGraphite'>Date:</span> $rowEmail->dtEmail$BR
         <span class='textGraphite'>Status:</span> $rowEmail->strStatus$BR
         $BR";
      $strEmailBody = $rowEmail->txtBody;

      //replacing using regex between two words: http://stackoverflow.com/questions/30407850/deleting-text-between-two-strings-in-php-using-preg-replace
      //remove multipart boundry from readonline for header
      $strEmailBody = preg_replace('/--==Multipart_Boundary_x[\s\S]+?Encoding: 7bit/', '', $strEmailBody);

      //remove multipart boundry from readonline for attachments
      $strEmailBody = preg_replace('/--==Multipart_Boundary_x[\s\S]+.*/', '', $strEmailBody); //dont use ? to limit it as we want the regex to be greedy

      return "
      <div xstyle='background: none; width: inherit; height: inherit !important;' class='blokkie' >
         <p style='$ifEmailFoundStyle'>
            $strEmailHeader
            $strEmailBody
         </p>
      </div>
      ";

   }

   public static function getResendControls($strTo)
   {
      global $xdb, $SystemSettings, $DT, $SP, $TR, $BR, $HR;

      return "$BR
      <div class='blokkie'>
         <p >
            Resend this email to: <input type=text name=strResendTo id=strResendTo value=\"$strTo\" class='controlText controlWide'/>
            $SP$SP<input type=submit name=Action value='Resend' class='btn controlButton'/>
         </p>
      </div>
      ";
   }

   public static function renderSubstitutions($strSubstitutions)
   {
      global $xdb, $SystemSettings, $DT, $SP, $TR, $BR, $HR;

      $strSubstitutions .= ",ReadOnline,ReadReceipt";
      $arrSubstitution = explode(",", $strSubstitutions);
      if(is_array($arrSubstitution)){
      foreach($arrSubstitution as $strSubstitution)
      {
         $Output .= "$SP<a href='#' onclick='jsSubstitute(\"$strSubstitution\"); return false;' style=''>$strSubstitution</a>";
      }
      }

      return $Output
      .js("
         function jsSubstitute(strSubstitution)
         {
            txtBody = d('txtBody');
            strSubstitution = '['+ strSubstitution +']';

            //IE support
            if (document.selection)
            {
               txtBody.focus();
               sel = document.selection.createRange();
               sel.text = strSubstitution;
            }
            //MOZILLA/NETSCAPE support
            else if (txtBody.selectionStart || txtBody.selectionStart == txtBody.selectionStart == '0')
            {
               var startPos = txtBody.selectionStart;
               var endPos = txtBody.selectionEnd;
               txtBody.value = txtBody.value.substring(0, startPos)
               + strSubstitution
               + txtBody.value.substring(endPos, txtBody.value.length);
            }
            else
            {
               txtBody.value += strSubstitution;
            }
         }
         ");

   }

   public static function renderAttachments($strAttachments)
   {
      global $xdb, $SystemSettings, $DT, $SP, $TR, $BR, $HR;

      $arrAttachment = explode(",", $strAttachments);
      if(is_array($arrAttachment)){
      foreach($arrAttachment as $strAttachment)
      {
         $Output .= "<a href=\"".$SystemSettings[InvoicePdfDirAdmin]."$strAttachment\" target=_blank >$strAttachment</a>$SP$SP"; //<a href=\"?Action=RemoveAttachment&EmailID=$EmailID&strAttachment=$strAttachment\" onclick='' style=''>- Remove -</a>";
         $br = $BR;
      }
      }

      return $Output;

   }

   public static function RemoveAttachment($EmailID, $strAttachment)
   {
      global $xdb, $SystemSettings, $DT, $SP, $TR, $BR, $HR;

      $row = $xdb->getRowSQL("SELECT * FROM tblEmail WHERE EmailID = ". $xdb->qs($EmailID));
      $arrAttachment = array_flip(explode(",", $row->arrAttachments));
      unset($arrAttachment[$strAttachment]);
      //print_rr($arrAttachment);

      $strAttachments = implode(",", array_flip($arrAttachment));
      $xdb->doQuery("UPDATE tblEmail SET arrAttachments = ". $xdb->qs($strAttachments) ." WHERE EmailID = ". $xdb->qs($EmailID));

      return "Attachment Removed. ";
   }

}
?>
