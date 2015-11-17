<?php
include_once("_framework/_nemo.list.cls.php");
include_once("includes/farm.cls.php");  // << gets the Farm::sqlInspectorDDL()

/*
ALTER TABLE `tblMember` drop FOREIGN KEY `tblMember.strUser`;
ALTER TABLE `tblMember` ADD CONSTRAINT `tblMember.strUser` FOREIGN KEY (`refInspectorID`) REFERENCES `sysUser` (`UserID`) ON UPDATE CASCADE ON DELETE CASCADE

ALTER TABLE `tblMember` drop FOREIGN KEY `tblMember.strMemberType`;
ALTER TABLE `tblMember` ADD CONSTRAINT `tblMember.strMemberType` FOREIGN KEY (`refMemberTypeID`) REFERENCES `tblMemberType` (`MemberTypeID`) ON UPDATE CASCADE ON DELETE CASCADE


ALTER TABLE `tmpMember` drop FOREIGN KEY `tmpMember.strUser`;
ALTER TABLE `tmpMember` ADD CONSTRAINT `tmpMember.strUser` FOREIGN KEY (`refInspectorID`) REFERENCES `sysUser` (`UserID`) ON UPDATE CASCADE ON DELETE CASCADE

ALTER TABLE `tmpMember` drop FOREIGN KEY `tmpMember.strMemberType`;
ALTER TABLE `tmpMember` ADD CONSTRAINT `tmpMember.strMemberType` FOREIGN KEY (`refMemberTypeID`) REFERENCES `tblMemberType` (`MemberTypeID`) ON UPDATE CASCADE ON DELETE CASCADE

*/

//Additional / page specific translations

class Member extends NemoList
{
   private $ID = 0;

   public static $NEW_FARM = "New Farm";
   public static $NEW_USER = "New User";
   public static $NEW_BUSINESS_RELATIONSHIP = "New B.Relationship";
   public static $NEW_DOCUMENT = "New Document";

   public static function sqlMemberLegalEntityTypeDDL()
   {
      return "
         SELECT '-1' AS ControlValue, '- Select -' AS ControlText
         UNION ALL 
            SELECT MemberID AS ControlValue, strLegalEntityType AS ControlText FROM tblMember          
         ORDER BY ControlText";
   }

   public static function sqlMemberTypeDDL()
   {
      return "
         SELECT '-1' AS ControlValue, '- All -' AS ControlText
         UNION ALL 
            SELECT vieMemberType_EN.MemberTypeID AS 'ControlValue', vieMemberType_EN.strCategoryMemberType AS 'ControlText'
            FROM vieMemberType_EN
            WHERE blnActive = 1
         UNION ALL 
            SELECT vieMemberType_EN.strCategory AS 'ControlValue', concat(vieMemberType_EN.strCategory, ' *') AS 'ControlText'
            FROM vieMemberType_EN
            WHERE blnActive = 1
            GROUP BY vieMemberType_EN.strCategory
         ORDER BY ControlText";
   }

   public static function sqlMemberDDL()
   {
      return "SELECT 0 AS ControlValue, '- All -' AS ControlText, '' AS strOrder
         UNION ALL 
            SELECT MemberID AS 'ControlValue', concat(LPAD(MemberID,5,'0') ,' - ', strMember) AS 'ControlText', strMember AS strOrder
            FROM tblMember
            WHERE strStatus = 'Active'
         ORDER BY strOrder, ControlText";
   }

   public function __construct($DataKey)
   {
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";

      $this->Filters[frMemberType]->tag = "select";
      $this->Filters[frMemberType]->html->value = "-1";
      $this->Filters[frMemberType]->html->class = "controlText";
      $this->Filters[frMemberType]->sql = self::sqlMemberTypeDDL();


      $this->Filters[frInspector]->tag = "select";
      $this->Filters[frInspector]->html->value = "-2";
      $this->Filters[frInspector]->html->class = "controlText";
      $this->Filters[frInspector]->sql = "SELECT -2 AS ControlValue, '- All -' AS ControlText
                        UNION ALL ". Farm::sqlInspectorDDL(); 

      if($this->Filters[frStatus] == null){
      $this->Filters[frStatus]->tag = "select";
      $this->Filters[frStatus]->html->value = "-1";
      $this->Filters[frStatus]->html->class = "controlText";
      $this->Filters[frStatus]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
         UNION ALL 
            SELECT tblMember.strStatus AS 'ControlValue', tblMember.strStatus AS 'ControlText'
            FROM tblMember
             WHERE (tblMember.strStatus NOT LIKE '1.%')
            GROUP BY tblMember.strStatus, tblMember.strStatus
         ORDER BY ControlText";
      }

      parent::__construct($DataKey);
   }

   public function getList()
   {
      global $xdb;
      
      //Build where clauses  
      if($this->Filters[frSearch]->html->value != "")
      {//TODO: expand Search WHERE
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")"; 
         $Where .= " AND (tblMember.strMember $like
                        OR tblMember.strRegistrationNumber $like
                        OR tblMember.strVATRegistrationNumber $like
                        OR tblMember.strEstateName $like
                        OR tblMember.strContact $like
                        OR tblMember.strTel $like
                        OR tblMember.strCell $like
                        OR tblMember.strEmail $like
                        OR tblMember.MemberID $like)"; //MORE??
      }

      if($this->Filters[frMemberType]->html->value != '-1')
      {
         if(is_numeric($this->Filters[frMemberType]->html->value))
            $Where .= " AND tblMember.refMemberTypeID = '". $this->Filters[frMemberType]->html->value ."'";
         else
            $Where .= " AND tblMemberType.strCategory = '". $this->Filters[frMemberType]->html->value ."'";
      }

      switch ($this->Filters[frInspector]->html->value) {
         case null:
         case '-2':  //All Inspectors
            
         break;
         case '-1':  //Inspectors not selected
            $Where .= " AND (tblMember.refInspectorID = '' OR isnull(tblMember.refInspectorID))";
         break;        
         default:  //Selected Inspector
            $Where .= " AND tblMember.refInspectorID = ". $this->Filters[frInspector]->html->value;
         break;
      }
      
      if($this->Filters[frStatus]->html->value != -1)
      {
        $Where .= " AND strStatus = '". $this->Filters[frStatus]->html->value ."'";
      }


      $this->ListSQL("
                  SELECT tblMember.MemberID, tblMember.MemberID AS 'Member ID', tblMember.strMember AS 'Member', CONCAT(tblMemberType.strCategory, ' - ', tblMemberType.strMemberType) AS 'Member Type'
                  , vieInspector.strInspector AS 'Inspector', CONCAT(tblMember.strName,' ',tblMember.strSurname, ' - ', tblMember.strTel) AS 'Contact', tblMember.strEmail AS Email
                  , tblMember.strStatus AS 'Status', tblMember.strLastUser AS 'Last User', tblMember.dtLastEdit AS 'Last Edit'
                  FROM (tblMember LEFT JOIN tblMemberType ON tblMember.refMemberTypeID = tblMemberType.MemberTypeID) LEFT JOIN vieInspector ON tblMember.refInspectorID = vieInspector.InspectorID
                  WHERE (tblMember.strStatus NOT LIKE '1.%') $Where
                  ORDER BY tblMember.strMember",0);

      $this->Columns["Member ID"]->html->align = "right";
      $this->Columns["Member ID"]->html->width = "50px";

      $this->Columns["Member Type"]->html->width = 
      $this->Columns["Member"]->html->width = "200px";

      $this->Columns["Last User"]->html->nowrap =
      $this->Columns["Last Edit"]->html->nowrap = "nowrap";

      $this->Columns["Last User"]->html->width =
      $this->Columns["Last Edit"]->html->width = "50px";

      // $this->Columns["Registration Date"]->html->width = 
      // $this->Columns["Contact"]->html->width = 
      // $this->Columns["Email"]->html->width = 
      // $this->Columns["Inspector"]->html->width = 
      // $this->Columns["Status"]->html->width = 
      // $this->Columns["Last User"]->html->width = 
      // $this->Columns["Last Edit"]->html->width = "50px";
      
      return $this->renderTable("Member List");
   }

   public function getBusinessRelationships($MemberID=null)
   {
      global $xdb;
      
      //Build where clauses  
      if($this->Filters[frSearch]->html->value != "")
      {//TODO: expand Search WHERE
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")"; 
         $Where .= " AND (tblMember.strMember $like
                            OR tblMember.strRegistrationNumber $like)";
      }

      if($this->Filters[frMemberType]->html->value != -1)
      {
        $Where .= " AND refMemberTypeID = '". $this->Filters[frMemberType]->html->value ."'";
      }

      switch ($this->Filters[frInspector]->html->value) {
        case '-2':  //All Inspectors
            $Where .= "";
          break;
        case '-1':  //Inspectors not selected
            $Where .= " AND (tblMember.refInspectorID = '' OR isnull(tblMember.refInspectorID))";
          break;        
        default:  //Selected Inspector
            $Where .= " AND tblMember.refInspectorID = ". $this->Filters[frInspector]->html->value;
          break;
      }

      if($this->Filters[frStatus]->html->value != -1)
      {
        $Where .= " AND strStatus = '". $this->Filters[frStatus]->html->value ."'";
      }

      $this->ListSQL("
                  SELECT tblMember.MemberID, tblMember.MemberID AS 'Member ID', tblMember.strMember AS 'Member', CONCAT(tblMemberType.strCategory, ' - ', tblMemberType.strMemberType) AS 'Member Type'
                  , vieInspector.strInspector AS 'Inspector', CONCAT(tblMember.strName,' ',tblMember.strSurname, ' - ', tblMember.strTel) AS 'Contact', tblMember.strEmail AS Email
                  , tblMember.strStatus AS 'Status', tblMember.strLastUser AS 'Last User', tblMember.dtLastEdit AS 'Last Edit'
                  FROM (tblMember LEFT JOIN tblMemberType ON tblMember.refMemberTypeID = tblMemberType.MemberTypeID) LEFT JOIN vieInspector ON tblMember.refInspectorID = vieInspector.InspectorID
                  WHERE tblMember.MemberID = $MemberID AND (tblMember.strStatus NOT LIKE '1.%') $Where
                  ORDER BY tblMember.strMember",0);

      $this->Columns["Member ID"]->html->align = "right";
      $this->Columns["Member ID"]->html->width = "50px";

      $this->Columns["Member Type"]->html->width = 
      $this->Columns["Member"]->html->width = "200px";

      $this->Columns["Last User"]->html->nowrap =
      $this->Columns["Last Edit"]->html->nowrap = "nowrap";

      $this->Columns["Last User"]->html->width =
      $this->Columns["Last Edit"]->html->width = "50px";

      // $this->Columns["Registration Date"]->html->width = 
      // $this->Columns["Contact"]->html->width = 
      // $this->Columns["Email"]->html->width = 
      // $this->Columns["Inspector"]->html->width = 
      // $this->Columns["Status"]->html->width = 
      // $this->Columns["Last User"]->html->width = 
      // $this->Columns["Last Edit"]->html->width = "50px";
      
      return $this->renderTable("Member List");
   }

public function getPendingBusinessRelationships($MemberID=null,$blnTemp=0)
   {
      global $xdb;
      

      if($blnTemp == 0)
      {
         $tableRequired = "tblMember";
         $Where .= " AND (tblMember.strStatus NOT LIKE '1.%')";
         $headerAppend = " <span class='textGreen'>Approved</span>";
         $path ="member.php";
         $action =  "Action=Edit&&MemberID=$MemberID";
      }
      else
      {
         $tableRequired = "tmpMember";
         $headerAppend = " <span class='textRed'>Pending</span>";
         $path ="member.pending.php";
         $action =  "Action=Edit&&MemberID=$MemberID";
      }

      //Build where clauses  
      if($this->Filters[frSearch]->html->value != "")
      {//TODO: expand Search WHERE
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")"; 
         $Where .= " AND ($tableRequired.strMember $like
                            OR $tableRequired.strRegistrationNumber $like)";
      }

      if($this->Filters[frMemberType]->html->value != -1)
      {
        $Where .= " AND refMemberTypeID = '". $this->Filters[frMemberType]->html->value ."'";
      }

      switch ($this->Filters[frInspector]->html->value) {
        case '-2':  //All Inspectors
            $Where .= "";
          break;
        case '-1':  //Inspectors not selected
            $Where .= " AND ($tableRequired.refInspectorID = '' OR isnull($tableRequired.refInspectorID))";
          break;        
        default:  //Selected Inspector
            $Where .= " AND $tableRequired.refInspectorID = ". $this->Filters[frInspector]->html->value;
          break;
      }

      if($this->Filters[frStatus]->html->value != -1)
      {
        $Where .= " AND strStatus = '". $this->Filters[frStatus]->html->value ."'";
      }


      $this->ListSQL("
                  SELECT $tableRequired.MemberID, $tableRequired.MemberID AS 'Member ID', $tableRequired.strMember AS 'Member', CONCAT(tblMemberType.strCategory, ' - ', tblMemberType.strMemberType) AS 'Member Type'
                  , vieInspector.strInspector AS 'Inspector', CONCAT($tableRequired.strName,' ',$tableRequired.strSurname, ' - ', $tableRequired.strTel) AS 'Contact', $tableRequired.strEmail AS Email
                  , $tableRequired.strStatus AS 'Status', $tableRequired.strLastUser AS 'Last User', $tableRequired.dtLastEdit AS 'Last Edit'
                  FROM ($tableRequired LEFT JOIN tblMemberType ON $tableRequired.refMemberTypeID = tblMemberType.MemberTypeID) LEFT JOIN vieInspector ON $tableRequired.refInspectorID = vieInspector.InspectorID
                  WHERE $tableRequired.MemberID = $MemberID $Where
                  ORDER BY $tableRequired.strMember",0,$path);

      $this->Columns["Member ID"]->html->align = "right";
      $this->Columns["Member ID"]->html->width = "50px";

      $this->Columns["Member Type"]->html->width = 
      $this->Columns["Member"]->html->width = "200px";

      $this->Columns["Last User"]->html->nowrap =
      $this->Columns["Last Edit"]->html->nowrap = "nowrap";

      $this->Columns["Last User"]->html->width =
      $this->Columns["Last Edit"]->html->width = "50px";

      // $this->Columns["Registration Date"]->html->width = 
      // $this->Columns["Contact"]->html->width = 
      // $this->Columns["Email"]->html->width = 
      // $this->Columns["Inspector"]->html->width = 
      // $this->Columns["Status"]->html->width = 
      // $this->Columns["Last User"]->html->width = 
      // $this->Columns["Last Edit"]->html->width = "50px";
      
      return $this->renderTable("Member List $headerAppend");
   }


   public static function Save(&$MemberID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblMember", $MemberID, null, 0);     

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
            $xdb->doQuery("UPDATE tblMember SET strStatus = 'Closed' WHERE MemberID = ". $xdb->qs($key));
         }
         return "Records Deleted.";
      }
   }
}
?>
