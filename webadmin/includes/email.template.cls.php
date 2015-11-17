<?php
/*
  * 20130731 - Added CourseType - pj
  *2013-11-20 - added Filters for Search, type, Course type, status -christiaan

ALTER TABLE `tblEmailTemplate` DROP FOREIGN KEY `tblEmailTemplate.strCourseType`;
ALTER TABLE `tblEmailTemplate` ADD CONSTRAINT `tblEmailTemplate.strCourseType` FOREIGN KEY ( `refCourseTypeID` ) REFERENCES `tblCourseType` ( `CourseTypeID` ) ON DELETE CASCADE ON UPDATE CASCADE ;


*/
include_once("_framework/_nemo.list.cls.php");

class EmailTemplate extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      //filters //2013-11-20 - added Filters for Search, type, Course type, status -christiaan
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->type = "text";
      $this->Filters[frSearch]->html->class = "controlText";

      $this->Filters[frStatus]->tag = "select";
      $this->Filters[frStatus]->html->value = "1";
      $this->Filters[frStatus]->html->class = "controlText";
      $this->Filters[frStatus]->sql = "SELECT -1 AS ControlValue, '- All -' AS ControlText
                        UNION ALL
                        SELECT 1 AS ControlValue, 'Active' AS ControlText
                        UNION ALL
                        SELECT 0 AS ControlValue, 'Inactive' AS ControlText

                        ORDER BY ControlText ASC";

      //construct
      parent::__construct($DataKey);

   }

   public function getList()
   {
      global $SystemSettings, $DT;

      //2013-11-20 - added Filters for Search, type, Course type, status -christiaan
      $Where = "";

      if($this->Filters[frSearch]->html->value != "")
      {
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")";
         $Where .= " AND (tblEmailTemplate.strEmailTemplate $like OR tblEmailTemplate.strSubject $like OR tblEmailTemplate.txtBody $like  OR tblEmailTemplate.txtNotes $like)";
      }
    
      if($this->Filters[frStatus]->html->value != -1)
      {
         $Where .= " AND tblEmailTemplate.blnActive = ". $this->Filters[frStatus]->html->value;
      }

      //20130731 - Added CourseType - pj
      $this->ListSQL("
         SELECT tblEmailTemplate.EmailTemplateID, tblEmailTemplate.strEmailTemplate AS 'Email Template', tblEmailTemplate.strSubject AS Subject, tblEmailTemplate.blnActive AS Active, tblEmailTemplate.strLastUser AS 'Last User', tblEmailTemplate.dtLastEdit AS 'Last Edit'
         FROM tblEmailTemplate
         WHERE 1=1 $Where
         ORDER BY strEmailTemplate ", 0);

      return $this->renderTable("Email Template List");
   }

   public static function Save(&$EmailTemplateID)
   {
      global $DT, $SystemSettings;

      $db = new NemoDatabase("tblEmailTemplate", $EmailTemplateID, null, 0);


      $db->SetValues($_POST);
      if($_POST[refCourseTypeID] == 0) $db->Fields[refCourseTypeID] = "NULL";

      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

      $result = $db->Save();
      //print_rr($result);

      if($EmailTemplateID == 0){
         $EmailTemplateID = $db->ID[EmailTemplateID];
      }else{

      }

      if($result->Error == 1)
         return $result->Message;
      else
         return "Details Saved. ";
   }

   public static function Delete($chkSelect)
   {
      global $xdb;
      //print_rr($chkSelect);
      if(count($chkSelect) > 0){
      foreach($chkSelect as $key => $value)
      {
         $xdb->doQuery("DELETE FROM tblEmailTemplate WHERE EmailTemplateID = ". $xdb->qs($key));
      }
         return "Records Deleted. ";
      }
   }

   public static function Preview($EmailTemplateID)
   {
      global $xdb, $SystemSettings, $DT, $SP, $TR, $BR, $HR;

      return "Preview...";

   }


   public static function renderSubstitutions($strSubstitutions)
   {
      global $xdb, $SystemSettings, $DT, $SP, $TR, $BR, $HR;

      $strSubstitutions .= ",Logo,ReadOnline,ReadReceipt";
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

   public function renderAttachments($EmailTemplateID, $strAttachments)
   {
      global $xdb, $SystemSettings, $DT, $SP, $TR, $BR, $HR;

      $arrAttachment = explode(",", $strAttachments);
      if(is_array($arrAttachment))
      {
         foreach($arrAttachment as $strAttachment)
         {
            $Output .= "$br$strAttachment$SP$SP<a href=\"".$SystemSettings[InvoicePdfDirAdmin]."$strAttachment\" target='blank'>- View -</a>$SP$SP<a href=\"?Action=RemoveAttachment&EmailTemplateID=$EmailTemplateID&strAttachment=$strAttachment\" onclick='' style=''>- Remove -</a>";
            //print_rr($SystemSettings);
            $br = $BR;
         }
      }
      return $Output;
   }

   public static function RemoveAttachment($EmailTemplateID, $strAttachment)
   {
      global $xdb, $SystemSettings, $DT, $SP, $TR, $BR, $HR;

      $row = $xdb->getRowSQL("SELECT * FROM tblEmailTemplate WHERE EmailTemplateID = ". $xdb->qs($EmailTemplateID));
      $arrAttachment = array_flip(explode(",", $row->arrAttachments));
      unset($arrAttachment[$strAttachment]);
      //print_rr($arrAttachment);

      $strAttachments = implode(",", array_flip($arrAttachment));
      $xdb->doQuery("UPDATE tblEmailTemplate SET arrAttachments = ". $xdb->qs($strAttachments) ." WHERE EmailTemplateID = ". $xdb->qs($EmailTemplateID));

      return "Attachment Removed. ";
   }
}
?>