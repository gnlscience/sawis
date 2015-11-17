<?php
include_once("_framework/_nemo.list.cls.php");
include_once("includes/farm.cls.php"); 
/*

ALTER TABLE `tblFarm` drop FOREIGN KEY `tblFarm.strUser`;
ALTER TABLE `tblFarm` ADD CONSTRAINT `tblFarm.strUser` FOREIGN KEY (`refInspectorID`) REFERENCES `sysUser` (`UserID`) ON UPDATE CASCADE ON DELETE NO ACTION

ALTER TABLE `tblLocation` drop FOREIGN KEY `tblLocation.strGeo`;
ALTER TABLE `tblLocation` ADD CONSTRAINT `tblLocation.strGeo` FOREIGN KEY (`refGeoID`) REFERENCES `tblGeo` (`GeoID`) ON UPDATE CASCADE ON DELETE NO ACTION 
*/

//Additional / page specific translations

   $_TRANSLATION["EN"]["Farm ID"] = "Farm ID";
   $_TRANSLATION["AF"]["Farm ID"] = "AF: Farm ID";
   $_TRANSLATION["EN"]["My Farm"] = "My Farm";
   $_TRANSLATION["AF"]["My Farm"] = "AF: My Farm";
   $_TRANSLATION["EN"]["Type"] = "Type";
   $_TRANSLATION["AF"]["Type"] = "AF: Type";
   $_TRANSLATION["EN"]["Farm Status"] = "Status";
   $_TRANSLATION["AF"]["Farm Status"] = "AF: Status";
   $_TRANSLATION["EN"]["Vine Status"] = "Vine Status";
   $_TRANSLATION["AF"]["Vine Status"] = "AF: Vine Status";
   $_TRANSLATION["EN"]["Inspector"] = "Inspector";
   $_TRANSLATION["AF"]["Inspector"] = "AF: Inspector";
   $_TRANSLATION["EN"]["Location"] = "Location";
   $_TRANSLATION["AF"]["Location"] = "AF: Location";
   $_TRANSLATION["EN"]["Buyer"] = "Buyer";
   $_TRANSLATION["AF"]["Buyer"] = "AF: Buyer";
   $_TRANSLATION["EN"]["New Farm"] = "New Farm";
   $_TRANSLATION["AF"]["New Farm"] = "AF: New Farm";
   $_TRANSLATION["EN"]["Tel"] = "Tel";
   $_TRANSLATION["AF"]["Tel"] = "AF: Tel";
   $_TRANSLATION["EN"]["Cell"] = "Cell";
   $_TRANSLATION["AF"]["Cell"] = "AF: Cell";
   $_TRANSLATION["EN"]["Fax"] = "Fax";
   $_TRANSLATION["AF"]["Fax"] = "AF: Fax";
   $_TRANSLATION["EN"]["Email"] = "Email";
   $_TRANSLATION["AF"]["Email"] = "AF: Email";
   $_TRANSLATION["EN"]["Registration Comments"] = "Registration Comments";
   $_TRANSLATION["AF"]["Registration Comments"] = "AF: Registration Comments";
   $_TRANSLATION["EN"]["Notes"] = "Notes";
   $_TRANSLATION["AF"]["Notes"] = "AF: Notes";
   $_TRANSLATION["EN"]["Registration Date"] = "Registration Date";
   $_TRANSLATION["AF"]["Registration Date"] = "AF: Registration Date";
   $_TRANSLATION["EN"]["Contact"] = "Contact";
   $_TRANSLATION["AF"]["Contact"] = "AF: Contact";
   $_TRANSLATION["EN"]["Nearest Town"] = "Nearest Town";
   $_TRANSLATION["AF"]["Nearest Town"] = "AF: Nearest Town";
   $_TRANSLATION["EN"]["Estate"] = "Estate";
   $_TRANSLATION["AF"]["Estate"] = "AF: Estate";
   $_TRANSLATION["EN"]["txtPostalAddress"] = "Postal Address";
   $_TRANSLATION["AF"]["txtPostalAddress"] = "AF: Postal Address";
   

class FarmPending extends Farm
{
   private $ID = 0;
   public static $NEW_DOCUMENT = "New Document";
   public static $APPROVED_IN_PRINCIPLE = "Approved In Principle";
   public static function sqlInspectorDDL()
   {      
      return "
         SELECT -1 AS ControlValue,  '- None -' AS ControlText
      UNION ALL
         SELECT InspectorID AS ControlValue, strInspector AS ControlText
         FROM vieInspector 
         WHERE blnActive = 1
      ORDER BY ControlText ASC";
   }

   public static function sqlFarmDDL()
   {
      return "SELECT 0 AS ControlValue, '- All -' AS ControlText, '' AS strOrder
         UNION ALL 
            SELECT FarmID AS 'ControlValue', concat(LPAD(FarmID,8,'0') ,' - ', strFarm) AS 'ControlText', strFarm AS strOrder
            FROM tblFarm
            WHERE strStatus = 'Active'
         ORDER BY strOrder, ControlText";
   }

   public function __construct($DataKey)
   {
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";  

      $this->Filters[frInspector]->tag = "select";
      $this->Filters[frInspector]->html->value = "-2";
      $this->Filters[frInspector]->html->class = "controlText";
      $this->Filters[frInspector]->sql = "SELECT -2 AS ControlValue, '- All -' AS ControlText
                        UNION ALL ". self::sqlInspectorDDL();   

      $this->Filters[frStatus]->tag = "select";
      $this->Filters[frStatus]->html->value = "-1";
      $this->Filters[frStatus]->html->class = "controlText";
      $this->Filters[frStatus]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
         UNION ALL 
            SELECT strStatus AS 'ControlValue', strStatus AS 'ControlText'
            FROM tblFarm
            GROUP BY strStatus
         ORDER BY ControlText";

      parent::__construct($DataKey);
   }

   public function getList()
   {
      global $xdb;

      //Build where clauses  
      if($this->Filters[frSearch]->html->value != "")
      {//TODO: expand Search WHERE
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")"; 
         $Where .= " AND (tmpFarm.strFarm $like
            OR tmpFarm.strRegistrationNumber $like
            OR tmpFarm.strRegisteredBusinessName $like
            OR tmpFarm.strVATRegistrationNumber $like
            OR tmpFarm.strContact $like
            OR tmpFarm.strTel $like
            OR tmpFarm.strCell $like
            OR tmpFarm.strFax $like
            OR tmpFarm.strEmail $like
            OR tmpFarm.strWebsiteURL $like
            OR tmpFarm.strNearestTown $like
            OR tmpFarm.txtPhysicalAddress $like
            OR tmpFarm.txtPostalAddress $like
            OR tmpFarm.strContact $like)"; //MORE??
      }

      switch ($this->Filters[frInspector]->html->value) {
         case '-2':  //All Inspectors
            $Where .= "";
            break;
         case '-1':  //Inspectors not selected
            $Where .= " AND (tmpFarm.refInspectorID = '' OR isnull(tmpFarm.refInspectorID))";
            break;        
         default:  //Selected Inspector
            $Where .= " AND tmpFarm.refInspectorID = ". $this->Filters[frInspector]->html->value;
            break;
      }

      if($this->Filters[frStatus]->html->value != -1)
      {
         $Where .= " AND strStatus = '". $this->Filters[frStatus]->html->value ."'";
      }

      $this->ListSQL("
         SELECT tmpMemberRelationship.ID, tmpFarm.FarmID, tmpFarm.FarmID AS 'Farm ID', tmpFarm.strFarm AS 'Farm', strInspector AS 'Inspector', tmpFarm.dtRegistered AS 'Registration Date',
            tmpFarm.strRegistrationNumber AS 'Registration No', tmpFarm.strContact AS 'Contact', tmpFarm.strEmail AS 'Email',
            tmpFarm.strStatus AS 'Status', tmpFarm.strLastUser AS 'Last User', tmpFarm.dtLastEdit AS 'Last Edit'
         FROM vieInspector 
         RIGHT JOIN tmpFarm ON vieInspector.InspectorID = tmpFarm.refInspectorID
         Inner JOIN tmpMemberRelationship ON tmpFarm.FarmID = tmpMemberRelationship.refEntityID
         Inner JOIN tblMember ON tblMember.MemberID = tmpMemberRelationship.refMemberID
         WHERE 1=1 $Where
         ORDER BY tmpFarm.strFarm",0);
        
      return $this->renderTable("Pending Farm List");
   }

   public function getMemberFarms($MemberID)
   {//MEMBER DETAILS SUB LIST ONLY!!
      global $xdb;
      
      //Build where clauses  

      $this->ListSQL("
         SELECT tmpFarm.FarmID, tmpFarm.FarmID AS 'Farm ID', tmpFarm.strFarm AS Farm, vieInspector.strInspector AS Inspector, vieMemberRelationship.strMemberTypeCode AS RelationshipCode, vieMemberRelationship.strMemberType AS Relationship, tmpFarm.strContact AS 'Contact', tmpFarm.strEmail AS 'Email', tmpFarm.strStatus AS 'Status', tmpFarm.strLastUser AS 'Last User', tmpFarm.dtLastEdit AS 'Last Edit'
         FROM vieMemberRelationship INNER JOIN (vieInspector RIGHT JOIN tmpFarm ON vieInspector.InspectorID = tmpFarm.refInspectorID) ON vieMemberRelationship.EntityID = tmpFarm.FarmID
         WHERE vieMemberRelationship.MemberID='$MemberID' AND vieMemberRelationship.strMemberTypeCode Not In ('FRMBUY') AND vieMemberRelationship.strType = 'Farm to Member' 
            $Where
         ORDER BY strFarm",0,"farm.php","Edit&MemberID=$MemberID&RETURN_URL=".urlencode("member.php?Action=Edit")."&RETURN_VAR=MemberID");

      return $this->renderTable("Member Farms");
   }

   public static function Save($FarmID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tmpFarm", $FarmID, null, 0);
//print_rr($db->Fields);
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

      foreach ($db->FieldList as $i => $field) 
      { 
         //remove RegArgs from db object else resaving a serialized value will break it " > '
         if($field->name == "RegistrationArgs")
         {
            unset($db->FieldList[$i], $db->Fields["RegistrationArgs"]);
            break;
         }
      }

      if($_POST[refInspectorID] < 1){         
         $db->Fields["refInspectorID"] = "NULL";
      }
//print_rr($db->Fields);
      $result = $db->Save(0,0);
      //die;
      
      if($FarmID == 0) 
      {
         $FarmID = $db->ID[FarmID];
      }

      if($_POST[MemberID] != "") 
      {
         //print_rr($_POST); die;
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
         $xdb->doQuery("Delete from tmpFarm WHERE FarmID = ". $xdb->qs($key));
         $xdb->doQuery("Delete from tmpBlock WHERE refFarmID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }


   public function getFarmSubdivisions($MemberID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings, $_TRANSLATION;
      
      //ini
      $arrData = array();
      $type = "SubDiv";

      //process
      $this->ListSQL("
         SELECT EntityID AS FarmID
            , EntityID AS '". $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Farm ID"] ."'
            , strEntity AS '".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["My Farm"]."'
            , strMemberType AS '".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Type"]."'
            , strStatus AS '".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Farm Status"]."'
            , '' AS '".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Buyer"]."'
            , '' as '".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["New Farm"]."'
            , '' AS '".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Tel"]."'
            , '' AS '".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Cell"]."'
            , '' AS '".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Email"]."'
            , '' AS '".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Registration Comments"]."'
            , '' AS '".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Notes"]."'
            , '' AS '".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Registration Date"]."'
         FROM vieMemberRelationship
         WHERE MemberID=$MemberID AND strMemberTypeCode In ('FRMOWN','FRM000','FRMPRD')
         ORDER BY strEntity;",0);
      foreach($this->Data as $i => $arrFarm)
      {//print_rr($arrFarm);

         //get all requests          
         $rst = $xdb->doQuery("SELECT * FROM tmpFarm WHERE RegistrationType = '$type' AND RegistrationArgs LIKE ('%\"". $arrFarm[FarmID] ."\"%') AND strStatus NOT IN('Active') ORDER BY dtRegistered DESC, strFarm");
         while($row = $xdb->fetch())
         {//print_rr($row);
            $arrFarm[$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Buyer"]] = $row->strContact ;
            $arrFarm[$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["New Farm"]] = $row->strFarm ;
            $arrFarm[$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Tel"]] = $row->strTel ;
            $arrFarm[$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Cell"]] = $row->strCell ;
            $arrFarm[$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Email"]] = $row->strEmail ;
            $arrFarm[$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Registration Comments"]] = $row->txtRegistrationNotes ;
            $arrFarm[$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Notes"]] = $row->txtNotes ;
            $arrFarm[$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Registration Date"]] = $row->dtRegistered ;

            $arrData[] = nCopy($arrFarm);
         }

      }//print_rr($arrData);

      $this->Data = $arrData;      
      return $this->renderTable("Pending Farm Subdivision");
   }


   public static function ApproveInPrinciple($tmpFarmID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DT, $DATABASE_SETTINGS, $SystemSettings; 

      // 0 - get next FarmID
      // 1 - update tmp records
      // 2 - move tmp records
      // 3 - update records
      // 4 - delete tmp
      // 5 - email user, cc member

      // 0 - get next FarmID
      $FarmID = GetNewFarmID(); 


      // 1 - update tmp records
            

      // 2 - move tmp records
      ## Changed Status to Active Ftom Approved in Principle :: JACQUES :: 17 Nov 2015
      $xdb->doQuery("INSERT INTO tblFarm ( 
         FarmID, refInspectorID, strFarm, strRegisteredBusinessName, strRegistrationNumber, strVATRegistrationNumber, refLocationID, WOCode, WOCODECertifyBlendAs, strContact, 
         strTitle, strName, strSurname, strTel, strCell, strFax, strEmail, strWebsiteURL, strNearestTown, txtPhysicalAddress, txtPostalAddress, dblArea, arrActNumbers, 
         arrActDescription, blnEstate, strVineStatus, strStatus, txtRegistrationNotes, txtNotes, RegistrationStatus, RegistrationType, RegistrationArgs, dtRegistered, strLastUser )
         SELECT 
         $FarmID, refInspectorID, strFarm, strRegisteredBusinessName, strRegistrationNumber, strVATRegistrationNumber, refLocationID, WOCode, WOCODECertifyBlendAs, strContact, 
         strTitle, strName, strSurname, strTel, strCell, strFax, strEmail, strWebsiteURL, strNearestTown, txtPhysicalAddress, txtPostalAddress, dblArea, arrActNumbers, 
         arrActDescription, blnEstate, strVineStatus, 'Active', txtRegistrationNotes, txtNotes, RegistrationStatus, RegistrationType, RegistrationArgs, dtRegistered, 'S2.Approved: ".$_SESSION['USER']->USERNAME."'
         FROM tmpFarm
         WHERE (((tmpFarm.FarmID)=$tmpFarmID));"
      ,0);

      
      // 3 - update records : 
         //TODO: strLastUseer = 'S2.Approved: ".$_SESSION['USER']->USERNAME."'


      //TODO: update docs
      //update documents
      $xdb->doQuery("UPDATE tblDocument SET tblDocument.refEntityID = $FarmID, tblDocument.EntityType = 'Farm'
         WHERE (((tblDocument.refEntityID)=$tmpFarmID))",0);


      //TODO: move tmpMemberRelationship
      $xdb->doQuery("INSERT INTO tblMemberRelationship ( strType, refMemberID, refEntityID, refMemberTypeID, strApprovedBy, dtApproved, dtLastEdit )
      SELECT 'Farm to Member', refMemberID, $FarmID, refMemberTypeID, strApprovedBy, dtApproved, dtLastEdit
      FROM tmpMemberRelationship
      WHERE tmpMemberRelationship.refEntityID=$tmpFarmID 
      AND tmpMemberRelationship.strType='Farm to Member'");

      //TODO: move tmpBlocks
      $xdb->doQuery("INSERT INTO tblBlock ( 
      refFarmID, intYear, BlockID, strBlock, strDescription, refSingleVineyardID, refCultivarID, refCultivarRootstockID, refIrrigationID, refTrellisID, 
      intYearPlanted, dblHectare, dblRowSpacingWide, dblRowSpacingNarrow, dblVineSpacing, dblVineDensity, strGPS, strStatus, intVinesOpening, intVinesUprooted, 
      intVinesGrafted, intVinesAmmended, intVinesClosing, dblHectareUprooted, dblHectareGrafted, dblHectareAmmended, intYearUprooted, intYearGrafted, intYearAmmended, 
      txtNotes, RegistrationArgs, dtLastEdit )
      SELECT 
      $FarmID, intYear, BlockID, strBlock, strDescription, refSingleVineyardID, refCultivarID, refCultivarRootstockID, refIrrigationID, refTrellisID, 
      intYearPlanted, dblHectare, dblRowSpacingWide, dblRowSpacingNarrow, dblVineSpacing, dblVineDensity, strGPS, strStatus, intVinesOpening, intVinesUprooted, 
      intVinesGrafted, intVinesAmmended, intVinesClosing, dblHectareUprooted, dblHectareGrafted, dblHectareAmmended, intYearUprooted, intYearGrafted, intYearAmmended, 
      txtNotes, RegistrationArgs, dtLastEdit
      FROM tmpBlock
      WHERE (((tmpBlock.refFarmID)=$tmpFarmID))");




      // 4 - delete tmp
      //GetNewFarmID breaks when doing this
      //$xdb->doQuery("DELETE FROM tmpFarm WHERE FarmID = $tmpFarmID",0);

      //TODO: delete tmpMemberRelationship
      $xdb->doQuery("DELETE FROM tmpMemberRelationship WHERE refEntityID=$tmpFarmID",0);
      $xdb->doQuery("DELETE FROM tmpBlock WHERE refFarmID=$tmpFarmID",0);


      // 5 - email user, cc farm
      $rowUser = $xdb->getRowSQL("SELECT sysUser.*, tblFarm.strEmail AS strEmailCC, tblFarm.strFarm 
                     FROM tblMember INNER JOIN ((sysUser INNER JOIN tblMemberRelationship ON sysUser.refMemberID = tblMemberRelationship.refMemberID) INNER JOIN tblFarm ON tblMemberRelationship.refEntityID = tblFarm.FarmID) ON tblMember.MemberID = sysUser.refMemberID
                     WHERE (tblFarm.FarmID=$FarmID)",0);
      $strLanguage = "strSetting:Language"; $strLanguage = strtoupper(substr($rowUser->$strLanguage,0,2));

   //create S2 Document
      $strFilename = "";

   //send email to User, cc member: AIP    
      include_once("_framework/_nemo.email.cls.php");  
      $nemoEmail = new NemoEmail($rowUser->strEmail, "" , 0);
      //$nemoEmail = new NemoEmail("clayton@overdrive.co.za" , "" , 0);
      $nemoEmail->LoadEmailTemplate("Approval New Farm $strLanguage");

      $arrValues[DisplayName] = $rowUser->strName;
      $arrValues[FarmID] = $FarmID;
      $arrValues[FarmName] = $rowUser->strFarm;
      $arrValues[FAX_SAWIS3] = $SystemSettings["Fax S2 Applications To"]; 
      $arrValues[EMAIL_SAWIS3] = $SystemSettings["Email S2 Applications To"]; 

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


      //print_rr($result);
      if($result->Error == 1){
         return $result->Message;
      }else{
         return "Membership Approved In Principle. ";
      }
   }











}
?>