<?php
include_once("_framework/_nemo.list.cls.php");

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
   

class Farm extends NemoList
{
   private $ID = 0;

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
         $Where .= " AND (tblFarm.strFarm $like
            OR tblFarm.strRegistrationNumber $like
            OR tblFarm.strRegisteredBusinessName $like
            OR tblFarm.strVATRegistrationNumber $like
            OR tblFarm.strContact $like
            OR tblFarm.strTel $like
            OR tblFarm.strCell $like
            OR tblFarm.strFax $like
            OR tblFarm.strEmail $like
            OR tblFarm.strWebsiteURL $like
            OR tblFarm.strNearestTown $like
            OR tblFarm.txtPhysicalAddress $like
            OR tblFarm.txtPostalAddress $like
            OR tblFarm.strContact $like)"; //MORE??
      }

      switch ($this->Filters[frInspector]->html->value) {
         case '-2':  //All Inspectors
            $Where .= "";
            break;
         case '-1':  //Inspectors not selected
            $Where .= " AND (tblFarm.refInspectorID = '' OR isnull(tblFarm.refInspectorID))";
            break;        
         default:  //Selected Inspector
            $Where .= " AND tblFarm.refInspectorID = ". $this->Filters[frInspector]->html->value;
            break;
      }

      if($this->Filters[frStatus]->html->value != -1)
      {
         $Where .= " AND strStatus = '". $this->Filters[frStatus]->html->value ."'";
      }

      $this->ListSQL("
                SELECT tblFarm.FarmID, tblFarm.FarmID AS 'Farm ID', tblFarm.strFarm AS 'Farm', strInspector AS 'Inspector', tblFarm.dtRegistered AS 'Registration Date',
                 tblFarm.strRegistrationNumber AS 'Registration No', tblFarm.strContact AS 'Contact', tblFarm.strEmail AS 'Email',
                 tblFarm.strStatus AS 'Status', tblFarm.strLastUser AS 'Last User', tblFarm.dtLastEdit AS 'Last Edit'
                FROM vieInspector RIGHT JOIN tblFarm ON vieInspector.InspectorID = tblFarm.refInspectorID
                WHERE 1=1 $Where
                ORDER BY tblFarm.strFarm",0);
        
      return $this->renderTable("Farm List");
   }

   public function getMemberFarms($MemberID,$path='farm.php',$action=0)
   {//MEMBER DETAILS SUB LIST ONLY!!
      global $xdb;
      //Build where clauses  

      if($action==0){
         $action="Edit&MemberID=$MemberID&RETURN_URL=".urlencode("member.php?Action=Edit")."&RETURN_VAR=MemberID";
      }

      $this->ListSQL("
         SELECT tblFarm.FarmID, tblFarm.FarmID AS 'Farm ID', tblFarm.strFarm AS Farm, vieInspector.strInspector AS Inspector, vieMemberRelationship.strMemberTypeCode AS RelationshipCode, vieMemberRelationship.strMemberType AS Relationship, tblFarm.strContact AS 'Contact', tblFarm.strEmail AS 'Email', tblFarm.strStatus AS 'Status', tblFarm.strLastUser AS 'Last User', tblFarm.dtLastEdit AS 'Last Edit'
         FROM vieMemberRelationship INNER JOIN (vieInspector RIGHT JOIN tblFarm ON vieInspector.InspectorID = tblFarm.refInspectorID) ON vieMemberRelationship.EntityID = tblFarm.FarmID
         WHERE vieMemberRelationship.MemberID='$MemberID' AND vieMemberRelationship.strMemberTypeCode Not In ('FRMBUY') AND vieMemberRelationship.strType = 'Farm to Member' 
            $Where
         ORDER BY strFarm",0,$path,$action);

      return $this->renderTable("Member Farms");
   }

   public static function Save($FarmID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblFarm", $FarmID, null, 0);
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
      $result = $db->Save(0,1);
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
         $xdb->doQuery("UPDATE tblFarm SET blnActive = 0 WHERE FarmID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }

   public function getFarmTransferList($MemberID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings, $_TRANSLATION;
      
      //ini
      $arrData = array();
      $type = "Buy";

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
//echo json_encode($this->Data);exit;

      return $this->renderTable("Pending Farm Transfers");
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


   public function sendTransferAcceptNotification($FarmID)
   {
      global $xdb, $TR, $SP, $HR, $BR, $DT, $SystemSettings;
      //print_rr($FarmID);die;
      $xdb->doQuery("UPDATE tblFarm SET txtNotes = concat(txtNotes,\"\n\rTransfer Accepted - ".$_SESSION[USER]->USERNAME." - $DT\")
         WHERE FarmID = '$FarmID'",1);

      $msg = "";
      include_once("_framework/_nemo.email.cls.php");

      /* Get farm Name to use in email*/
      $row = $xdb->getRowSQL("SELECT strFarm FROM tblFarm WHERE FarmID = '$FarmID'");
       
      $arrValues[FarmName] = $row->strFarm; //Name of farm to transfer
 
      $arrValues[DisplayName] = "Admin"; //admin name


      $nemoEmail = new NemoEmail($SystemSettings["Email S2 Applications To"], "" , 0);
      $nemoEmail->LoadEmailTemplate("Farm Transfer Accepted EN");

      
      $arrValues[FarmID] = $FarmID; //Farm going to be sold

      $nemoEmail->Substitute($arrValues);

      $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
      $nemoEmail->From = $SystemSettings["SMTP Send As"];
      $nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
      $nemoEmail->Bcc = $SystemSettings["SMTP BCC"];
      $nemoEmail->Send();
      unset($nemoEmail);

      $msg = "Test email was successfully sent to " + $arrValues[DisplayName];

      return $msg;
   }


   public function sendTransferDeclineNotification($FarmID)
   {
      global $xdb, $TR, $SP, $HR, $BR, $DT, $SystemSettings;

      $xdb->doQuery("UPDATE tblFarm SET txtNotes = concat(txtNotes,\"\n\rTransfer Declined - ".$_SESSION[USER]->USERNAME." - $DT\")
         WHERE FarmID = '$FarmID'",1);

      $msg = "";
      //Send test email
      include_once("_framework/_nemo.email.cls.php");

      /* Get farm Name to use in email*/
      $rst = $xdb->doQuery("SELECT strFarm FROM tblFarm WHERE FarmID = '$FarmID'");
      while($row = $xdb->fetch())
      {//print_rr($row);
         $arrValues[FarmName] = $row->strFarm; //Name of farm to decline
      }

      $AdminEmailAddress = $SystemSettings["Email S3 Applications To"]; //admin email
      $arrValues[DisplayName] = $SystemSettings["SMTP Send As"]; //admin name
      
      $arrValues[FarmID] = $FarmID; //Farm to be declined

      $nemoEmail = new NemoEmail($AdminEmailAddress, "" , 0);
      $nemoEmail->LoadEmailTemplate("Farm Transfer Declined EN");

      
      $nemoEmail->Substitute($arrValues);

      $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
      $nemoEmail->From = $SystemSettings["SMTP Send As"];
      //$nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
      //$nemoEmail->Bcc = "";//$SystemSettings["SMTP BCC"];
      //$nemoEmail->addHeader("CC","");
      //$nemoEmail->Cc = "";
      $nemoEmail->Send();
      unset($nemoEmail);

      $msg = "Test email was successfully sent to " + $AdminEmailAddress;

       return $msg;
   }

   public function sendSubdivisionAcceptNotification($FarmID)
   {


      //echo "stopped"; exit;

      global $xdb, $TR, $SP, $HR, $BR, $DT, $SystemSettings;

      $xdb->doQuery("UPDATE tblFarm SET txtNotes = concat(txtNotes,\"\n\rSubdivision Accepted - ".$_SESSION[USER]->USERNAME." - $DT\")
         WHERE FarmID = '$FarmID'",0);

      $msg = "";
      //Send test email
      include_once("_framework/_nemo.email.cls.php");

      /* Get farm Name to use in email*/
      $rst = $xdb->doQuery("SELECT strFarm FROM tblFarm WHERE FarmID = '$FarmID'");
      while($row = $xdb->fetch())
      {//print_rr($row);
         $arrValues[FarmName] = $row->strFarm; //Name of farm to accept
      }

      $AdminEmailAddress = $SystemSettings["Email S3 Applications To"]; //admin email
      $arrValues[DisplayName] = $SystemSettings["SMTP Send As"]; //admin name
      
      $arrValues[FarmID] = $FarmID; //Farm to accept

      $nemoEmail = new NemoEmail($AdminEmailAddress, "" , 0);

      $nemoEmail->LoadEmailTemplate("Farm Subdivision Accepted EN");

      $nemoEmail->Substitute($arrValues);

      $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
      $nemoEmail->From = $SystemSettings["SMTP Send As"];
      //$nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
      //$nemoEmail->Bcc = "";//$SystemSettings["SMTP BCC"];
      //$nemoEmail->addHeader("CC","");
      //$nemoEmail->Cc = "";
      $nemoEmail->Send();
      unset($nemoEmail);

      $msg = "Test email was successfully sent to " + $AdminEmailAddress;

       return $msg;
   }

   public function sendSubdivisionDeclineNotification($FarmID)
   {
      global $xdb, $TR, $SP, $HR, $BR, $DT, $SystemSettings;

      $xdb->doQuery("UPDATE tblFarm SET txtNotes = concat(txtNotes,\"\n\rSubdivision Declined - ".$_SESSION[USER]->USERNAME." - $DT\")
         WHERE FarmID = '$FarmID'",1);

      $msg = "";
      //Send test email
      include_once("_framework/_nemo.email.cls.php");

      /* Get farm Name to use in email*/
      $rst = $xdb->doQuery("SELECT strFarm FROM tblFarm WHERE FarmID = '$FarmID'");
      while($row = $xdb->fetch())
      {//print_rr($row);
         $arrValues[FarmName] = $row->strFarm; //Name of farm to decline
      }

      $AdminEmailAddress = $SystemSettings["Email S3 Applications To"]; //admin email
      $arrValues[DisplayName] = $SystemSettings["SMTP Send As"]; //admin name
      
      $arrValues[FarmID] = $FarmID; //Farm to decline

      $nemoEmail = new NemoEmail($AdminEmailAddress, "" , 0);


      $nemoEmail->LoadEmailTemplate("Farm Subdivision Declined EN");

      
      $nemoEmail->Substitute($arrValues);

      $nemoEmail->addHeader("FROM", $SystemSettings["SMTP Send As"]);
      $nemoEmail->From = $SystemSettings["SMTP Send As"];
      //$nemoEmail->addHeader("BCC", $SystemSettings["SMTP BCC"]);
      //$nemoEmail->Bcc = "";//$SystemSettings["SMTP BCC"];
      //$nemoEmail->addHeader("CC","");
      //$nemoEmail->Cc = "";
      $nemoEmail->Send();
      unset($nemoEmail);

      $msg = "Test email was successfully sent to " + $AdminEmailAddress;

       return $msg;
   }

   public function getFarmDetails($FarmID)
   {
      global $xdb, $_TRANSLATION;
      //print_rr($_TRANSLATION[$_SESSION[USER]->LANGUAGE]);

      $row = $xdb->getRowSQL("SELECT tblFarm.*, vieLocation.EN_strLocation, vieLocation.AF_strLocation, vieInspector.strInspector
                              FROM vieLocation RIGHT JOIN (tblFarm LEFT JOIN vieInspector ON tblFarm.refInspectorID = vieInspector.InspectorID) ON vieLocation.LocationID = tblFarm.refLocationID
                              WHERE tblFarm.FarmID = $FarmID",0);
   }



   public function getFarmDetailsForm($FarmID)
   {
      global $xdb, $_TRANSLATION;
      //print_rr($_TRANSLATION[$_SESSION[USER]->LANGUAGE]);

      $row = $xdb->getRowSQL("SELECT tblFarm.*, vieLocation.EN_strLocation, vieLocation.AF_strLocation, vieInspector.strInspector
                              FROM vieLocation RIGHT JOIN (tblFarm LEFT JOIN vieInspector ON tblFarm.refInspectorID = vieInspector.InspectorID) ON vieLocation.LocationID = tblFarm.refLocationID
                              WHERE tblFarm.FarmID = $FarmID",0);

      $strInspector = "- n/a -";
      $strLocation = "- n/a -";

      if($row->refInspectorID != 0)
      {
         $strInspector = $row->strInspector;
      }

      if($row->refLocationID != 0)
      {
         $strLocation = $_TRANSLATION[$_SESSION[USER]->LANGUAGE]=="EN"?$row->EN_strLocation:$row->AF_strLocation;
      }



      return " 
         <table class='dora-DetailsTable' border='0' cellpadding='2' cellspacing='1' width='100%''>
            <caption><span class='textColour'>$row->FarmID</span>: <span class='textColour'>$row->strFarm</span></caption>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Inspector"].": </label></td><td>$strInspector</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Location"].": </label></td><td>$strLocation</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Contact"].": </label></td><td>$row->strContact</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Tel"].": </label></td><td>$row->strTel</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Cell"].": </label></td><td>$row->strCell</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Fax"].": </label></td><td>$row->strFax</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Email"].": </label></td><td>$row->strEmail</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Nearest Town"].": </label></td><td>$row->strNearestTown</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["txtPhysicalAddress"].": </label></td><td valign??>$row->txtPhysicalAddress</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["txtPostalAddress"].": </label></td><td valign??>$row->txtPostalAddress</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Estate"].": </label></td>
               <td>".($row->blnEstate == 1 ? $_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Yes"]:$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["No"])."</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Vine Status"].": </label></td><td>$row->strVineStatus</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["Farm Status"].": </label></td><td>$row->strStatus</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["strLastUser"].": </label></td><td>$row->strLastUser</td>
            </tr>
            <tr>
               <td><label>".$_TRANSLATION[$_SESSION[USER]->LANGUAGE]["dtLastEdit"].": </label></td><td>$row->dtLastEdit</td>
            </tr>
         </table>";
      
   }
}
?>