<?php
include_once("_framework/_nemo.list.cls.php");


include_once("includes/member.cls.php");
include_once("includes/farm.cls.php");  // << gets the Farm::sqlInspectorDDL()

/*
ALTER TABLE `tblMember` drop FOREIGN KEY `tblMember.strUser`;
ALTER TABLE `tblMember` ADD CONSTRAINT `tblMember.strUser` FOREIGN KEY (`refInspectorID`) REFERENCES `sysUser` (`UserID`) ON UPDATE CASCADE ON DELETE CASCADE

ALTER TABLE `tblMember` drop FOREIGN KEY `tblMember.strMemberType`;
ALTER TABLE `tblMember` ADD CONSTRAINT `tblMember.strMemberType` FOREIGN KEY (`refMemberTypeID`) REFERENCES `tblMemberType` (`MemberTypeID`) ON UPDATE CASCADE ON DELETE CASCADE
*/

//Additional / page specific translations
//20151109 - changed to use tmpMember instead of tblMember - CA

class MemberPending extends Member
{
   private $ID = 0;

   public static $APPROVED_IN_PRINCIPLE = "Approved In Principle";
   
   public function __construct($DataKey)
   {
      $this->Filters[frSearch] = null;
      $this->Filters[frMemberType] = null; 
      $this->Filters[frInspector] = null; //define place holders

      $this->Filters[frStatus]->tag = "select";
      $this->Filters[frStatus]->html->value = "1.2 - Final Submission";
      $this->Filters[frStatus]->html->class = "controlText";
      $this->Filters[frStatus]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
         UNION ALL 
            SELECT tmpMember.strStatus AS 'ControlValue', tmpMember.strStatus AS 'ControlText'
            FROM tmpMember
            WHERE (tmpMember.strStatus LIKE '1.%')
            GROUP BY tmpMember.strStatus, tmpMember.strStatus
         ORDER BY ControlText";

      $this->Filters[frInspector]->tag = "select";
      $this->Filters[frInspector]->html->value = "-2";
      $this->Filters[frInspector]->html->class = "controlText";
      $this->Filters[frInspector]->sql = "SELECT -2 AS ControlValue, '- All -' AS ControlText
                        UNION ALL ". Farm::sqlInspectorDDL(); 

      parent::__construct($DataKey);
   }

   //20151109 - changed to use tmpMember instead of tblMember - CA
   public function getList()
   {
      global $xdb;
      //Build where clauses  
      if($this->Filters[frSearch]->html->value != "")
      {//TODO: expand Search WHERE
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")"; 
         $Where .= " AND (tmpMember.strMember $like
                        OR tmpMember.strRegistrationNumber $like
                        OR tmpMember.strVATRegistrationNumber $like
                        OR tmpMember.strEstateName $like
                        OR tmpMember.strTel $like
                        OR tmpMember.strCell $like
                        OR tmpMember.strEmail $like
                        OR tmpMember.MemberID $like
                        OR tmpMember.txtNotes $like
                        OR tmpMember.txtRegistrationNotes $like)"; //MORE??
      }

      if($this->Filters[frMemberType]->html->value != '-1')
      {
         if(is_numeric($this->Filters[frMemberType]->html->value))
            $Where .= " AND tmpMember.refMemberTypeID = '". $this->Filters[frMemberType]->html->value ."'";
         else
            $Where .= " AND tblMemberType.strCategory = '". $this->Filters[frMemberType]->html->value ."'";
      }

      switch ($this->Filters[frInspector]->html->value) {
         case null:
         case '-2':  //All Inspectors
            
            break;
         case '-1':  //Inspectors not selected
            $Where .= " AND (tmpMember.refInspectorID = '' OR isnull(tmpMember.refInspectorID))";
            break;        
         default:  //Selected Inspector
            $Where .= " AND tmpMember.refInspectorID = ". $this->Filters[frInspector]->html->value;
            break;
      }
      
      if($this->Filters[frStatus]->html->value != -1)
      {
        $Where .= " AND strStatus = '". $this->Filters[frStatus]->html->value ."'";
      }

      $this->ListSQL("
         SELECT tmpMember.MemberID, tmpMember.MemberID AS 'Member ID', tmpMember.strMember AS 'Member', CONCAT(tblMemberType.strCategory, ' - ', tblMemberType.strMemberType) AS 'Member Type'
         , vieInspector.strInspector AS 'Inspector', CONCAT(tmpMember.strName,' ',tmpMember.strSurname, ' - ', tmpMember.strTel) AS 'Contact', tmpMember.strEmail AS Email
         , tmpMember.strStatus AS 'Status'
         , tmpMember.txtNotes as 'Notes', tmpMember.txtRegistrationNotes AS 'User Notes', tmpMember.strLastUser AS 'Last User', tmpMember.dtLastEdit AS 'Last Edit'
         FROM (tmpMember LEFT JOIN tblMemberType ON tmpMember.refMemberTypeID = tblMemberType.MemberTypeID) LEFT JOIN vieInspector ON tmpMember.refInspectorID = vieInspector.InspectorID
         WHERE 1=1 $Where
         ORDER BY tmpMember.strMember",0); //w. (tmpMember.strStatus LIKE '1.%')

      return $this->renderTable("Pending Member List");
   }

   //20151110 - move from tblMember to tblMember - ca
   public static function ApproveInPrinciple($tmpMemberID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DT, $DATABASE_SETTINGS, $SystemSettings;   

      // 1 - update tmp records
      // 2 - move tmp records
      // 3 - update records
      // 4 - delete tmp
      // 5 - email user, cc member



      // 1 - update tmp records
      

      // 2 - move tmp records
      ## Changed Status to Active Ftom Approved in Principle :: JACQUES :: 17 Nov 2015
      $xdb->doQuery("
         INSERT INTO tblMember ( refInspectorID, refMemberTypeID, strMember, strRegistrationNumber, strVATRegistrationNumber, strEstateName, strWOLCode, strStatus, strIPWNumber, strCertificateRID, 
                  strCertificateWarningCode, strTitle, strName, strSurname, strTel, strCell, strFax, strEmail, strWebsiteURL, txtPhysicalAddress, txtPostalAddress, strLegalEntityType, 
                  strSARSPackhouseNumber, blnVinPro, blnWineRoute, blnVolARA, blnVolWOL, dtRegistration, txtRegistrationNotes, txtNotes, `strSetting:CorrespondenceLanguage`, RegistrationStatus, 
                  RegistrationArgs, strFirstUser, dtFirstEdit, strLastUser )
         SELECT refInspectorID, refMemberTypeID, strMember, strRegistrationNumber, strVATRegistrationNumber, strEstateName, strWOLCode, 'Active', strIPWNumber, strCertificateRID, 
               strCertificateWarningCode, strTitle, strName, strSurname, strTel, strCell, strFax, strEmail, strWebsiteURL, txtPhysicalAddress, txtPostalAddress, strLegalEntityType, 
               strSARSPackhouseNumber, blnVinPro, blnWineRoute, blnVolARA, blnVolWOL, dtRegistration, txtRegistrationNotes, txtNotes, `strSetting:CorrespondenceLanguage`, RegistrationStatus, 
               RegistrationArgs, strFirstUser, dtFirstEdit, 'S3.Approved: ".$_SESSION['USER']->USERNAME."'
         FROM tmpMember
         WHERE (((MemberID)=$tmpMemberID))"
      ,0);
      $MemberID = $xdb->getLastID();


      // 3 - update records
      $xdb->doQuery("UPDATE sysUser 
         SET refMemberID  = $MemberID, refSecurityGroupID = 11, strLastUser = 'S3.Approved: ".$_SESSION['USER']->USERNAME."' 
         WHERE refMemberID = $tmpMemberID AND refSecurityGroupID = 13",0); //update Unapproved Users to Superusers & NewMemberID

      $xdb->doQuery("UPDATE tmpMemberRelationship SET refMemberID=$MemberID, strLastUser = 'S3.Approved: ".$_SESSION['USER']->USERNAME."'
          WHERE (((refMemberID)=$tmpMemberID))",0); //, strApprovedBy = '".$_SESSION['USER']->USERNAME."', dtApproved  = '$DT' MOVED TO S2.Approve()

      $xdb->doQuery("UPDATE tblDocument SET tblDocument.refEntityID = $MemberID, tblDocument.EntityType = 'Member'
         WHERE (((tblDocument.refEntityID)=$tmpMemberID))",0);


      // 4 - delete tmp
      $xdb->doQuery("DELETE FROM tmpMember WHERE MemberID = $tmpMemberID",0);


      // 5 - email user, cc member
      $rowUser = $xdb->getRowSQL("SELECT sysUser.*, tblMember.strEmail as strEmailCC, tblMember.strMember, vieMemberType_EN.strCategoryMemberType, vieMemberType_EN.strMemberTypeCode
         FROM vieMemberType_EN INNER JOIN (sysUser INNER JOIN tblMember ON sysUser.refMemberID = tblMember.MemberID) ON vieMemberType_EN.MemberTypeID = tblMember.refMemberTypeID
         WHERE refMemberID = $MemberID",0);
      $strLanguage = "strSetting:Language"; $strLanguage = strtoupper(substr($rowUser->$strLanguage,0,2));


   //create S3 Document
      $strFilename = "";
/*print_rr($strLanguage);
print_rr($rowUser);
print_rr($SystemSettings);
print_rr($arrValues);
die;*/
   //send email to User, cc member: AIP    
      include_once("_framework/_nemo.email.cls.php");  
      $nemoEmail = new NemoEmail($rowUser->strEmail, "" , 0);
      //$nemoEmail = new NemoEmail("clayton@overdrive.co.za" , "" , 0);
      $nemoEmail->LoadEmailTemplate("Approval New Member $strLanguage");

      $arrValues[DisplayName] = $rowUser->strName;
      $arrValues[FAX_SAWIS3] = $SystemSettings["Fax S3 Applications To"]; 
      $arrValues[EMAIL_SAWIS3] = $SystemSettings["Email S3 Applications To"]; 

      $nemoEmail->Substitute($arrValues); 

      $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
      $nemoEmail->From = $SystemSettings["SMTP Send As"];
      $nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
      $nemoEmail->Bcc = $SystemSettings["SMTP BCC"];
      $nemoEmail->addHeader("CC", $rowUser->strEmailCC);
      $nemoEmail->Cc = $rowUser->strEmailCC;

      //$nemoEmail->addAttachment($strFilename,"PDF");

//print_rr($nemoEmail); die;
      $nemoEmail->Send();
      unset($nemoEmail);
//(strApprovedBy = null OR strApprovedBy = '') AND (dtApproved = null OR dtApproved = '')
      $dtDate = date("Y-m-d");


      //print_rr($result);
      if($result->Error == 1){
         return $result->Message;
      }else{
         return "Membership Approved In Principle. ";
      }
   }

   public static function Save(&$MemberID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tmpMember", $MemberID, null, 0);     

      foreach ($db->FieldList as $i => $field) { //remove RegArgs from db object else resaving a serialized value will break it " > '
         if($field->name == "RegistrationArgs"){
            unset($db->FieldList[$i], $db->Fields["RegistrationArgs"]);
            break;
         }
      }

//print_rr($db->Fields);      
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

      if($_POST[refInspectorID] < 1){         
         $db->Fields["refInspectorID"] = "NULL";
      }
      //print_rr($_POST);
      //print_rr($db->Fields);
      $result = $db->Save(0,0);
      //die;      
      if($MemberID == 0){
         $MemberID = $db->ID[MemberID];
      }
      //print_rr($result);
      if($result->Error == 1){
         return $result->Message;
      }else{
         return "Details Saved. ";
      }
   }


   public static function Delete($chkSelect)
   {
      global $xdb;
      //print_rr($chkSelect);

      if(count($chkSelect) > 0){
         foreach($chkSelect as $key => $value)
         {
            // $xdb->doQuery("DELETE FROM tblLocation WHERE LocationID = ". $xdb->qs($key));
            //$xdb->doQuery("UPDATE tmpMember SET strStatus = 'Closed' WHERE MemberID = ". $xdb->qs($key));

            $documents = $xdb->doQuery("SELECT * FROM vieDocumentMemberTMP WHERE refMemberID = ". $xdb->qs($key));
            while($document = $xdb->fetch_object($documents))
            {  
               $file = "documents/$document->strFilename";
               unlink($file);
            }

            $xdb->doQuery("DELETE FROM vieDocumentMemberTMP WHERE refMemberID = ". $xdb->qs($key));

            $xdb->doQuery("DELETE FROM tmpMember WHERE MemberID = ". $xdb->qs($key));

         }
         return "Records Deleted.";
      }
   }
   


}//eoClass
?>