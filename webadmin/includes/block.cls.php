<?php
include_once("_framework/_nemo.list.cls.php");

/*

ALTER TABLE `tblBlockMovement` drop FOREIGN KEY `tblBlockMovement.strUser`;
ALTER TABLE `tblBlockMovement` ADD CONSTRAINT `tblBlockMovement.strUser` FOREIGN KEY (`refUserID`) REFERENCES `sysUser` (`UserID`) ON UPDATE CASCADE ON DELETE CASCADE


ALTER TABLE `tblBlockMovement` drop FOREIGN KEY `tblBlockMovement.strBlock`;
ALTER TABLE `tblBlockMovement` ADD CONSTRAINT `tblBlockMovement.strBlock` FOREIGN KEY (`refBlockID`) REFERENCES `tblBlock` (`BlockID`) ON UPDATE CASCADE ON DELETE CASCADE

 
*/

//Additional / page specific translations
//    private $ID = 0;

//    public static function sqlInspectorDDL()
//    {      
//       return "
//          SELECT -1 AS ControlValue,  '- None -' AS ControlText
//       UNION ALL
//          SELECT InspectorID AS ControlValue, strInspector AS ControlText
//          FROM vieInspector 
//          WHERE blnActive = 1
//       ORDER BY ControlText ASC";
//    }

//    public static function sqlFarmDDL()
//    {
//       return "SELECT 0 AS ControlValue, '- All -' AS ControlText, '' AS strOrder
//          UNION ALL 
//             SELECT FarmID AS 'ControlValue', concat(LPAD(FarmID,8,'0') ,' - ', strFarm) AS 'ControlText', strFarm AS strOrder
//             FROM tblFarm
//             WHERE strStatus = 'Active'
//          ORDER BY strOrder, ControlText";
//    }

//    public function __construct($DataKey)
//    {
//       $this->Filters[frSearch]->tag = "input";
//       $this->Filters[frSearch]->html->value = "";
//       $this->Filters[frSearch]->html->class = "controlText";  

//       $this->Filters[frInspector]->tag = "select";
//       $this->Filters[frInspector]->html->value = "-2";
//       $this->Filters[frInspector]->html->class = "controlText";
//       $this->Filters[frInspector]->sql = "SELECT -2 AS ControlValue, '- All -' AS ControlText
//                         UNION ALL ". self::sqlInspectorDDL();   

//       $this->Filters[frStatus]->tag = "select";
//       $this->Filters[frStatus]->html->value = "-1";
//       $this->Filters[frStatus]->html->class = "controlText";
//       $this->Filters[frStatus]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
//          UNION ALL 
//             SELECT strStatus AS 'ControlValue', strStatus AS 'ControlText'
//             FROM tblFarm
//             GROUP BY strStatus
//          ORDER BY ControlText";

//       parent::__construct($DataKey);
//    }

//    public function getList()
//    {
//       global $xdb;

//       //Build where clauses  
//       if($this->Filters[frSearch]->html->value != "")
//       {//TODO: expand Search WHERE
//          $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")"; 
//          $Where .= " AND (tblFarm.strFarm $like
//             OR tblFarm.strRegistrationNumber $like
//             OR tblFarm.strRegisteredBusinessName $like
//             OR tblFarm.strVATRegistrationNumber $like
//             OR tblFarm.strContact $like
//             OR tblFarm.strTel $like
//             OR tblFarm.strCell $like
//             OR tblFarm.strFax $like
//             OR tblFarm.strEmail $like
//             OR tblFarm.strWebsiteURL $like
//             OR tblFarm.strNearestTown $like
//             OR tblFarm.txtPhysicalAddress $like
//             OR tblFarm.txtPostalAddress $like
//             OR tblFarm.strContact $like)"; //MORE??
//       }

//       switch ($this->Filters[frInspector]->html->value) {
//          case '-2':  //All Inspectors
//             $Where .= "";
//             break;
//          case '-1':  //Inspectors not selected
//             $Where .= " AND (tblFarm.refInspectorID = '' OR isnull(tblFarm.refInspectorID))";
//             break;        
//          default:  //Selected Inspector
//             $Where .= " AND tblFarm.refInspectorID = ". $this->Filters[frInspector]->html->value;
//             break;
//       }

//       if($this->Filters[frStatus]->html->value != -1)
//       {
//          $Where .= " AND strStatus = '". $this->Filters[frStatus]->html->value ."'";
//       }

//       $this->ListSQL("
//                 SELECT tblFarm.FarmID, tblFarm.FarmID AS 'Farm ID', tblFarm.strFarm AS 'Farm', strInspector AS 'Inspector', tblFarm.dtRegistered AS 'Registration Date',
//                  tblFarm.strRegistrationNumber AS 'Registration No', tblFarm.strContact AS 'Contact', tblFarm.strEmail AS 'Email',
//                  tblFarm.strStatus AS 'Status', tblFarm.strLastUser AS 'Last User', tblFarm.dtLastEdit AS 'Last Edit'
//                 FROM vieInspector RIGHT JOIN tblFarm ON vieInspector.InspectorID = tblFarm.refInspectorID
//                 WHERE 1=1 $Where
//                 ORDER BY tblFarm.strFarm",0);
        
//       return $this->renderTable("Farm List");
//    }

//    public function getMemberFarms($MemberID)
//    {//MEMBER DETAILS SUB LIST ONLY!!
//       global $xdb;
      
//       //Build where clauses  

//       $this->ListSQL("
//          SELECT tblFarm.FarmID, tblFarm.FarmID AS 'Farm ID', tblFarm.strFarm AS Farm, vieInspector.strInspector AS Inspector, vieMemberRelationship.strMemberTypeCode AS RelationshipCode, vieMemberRelationship.strMemberType AS Relationship, tblFarm.strContact AS 'Contact', tblFarm.strEmail AS 'Email', tblFarm.strStatus AS 'Status', tblFarm.strLastUser AS 'Last User', tblFarm.dtLastEdit AS 'Last Edit'
//          FROM vieMemberRelationship INNER JOIN (vieInspector RIGHT JOIN tblFarm ON vieInspector.InspectorID = tblFarm.refInspectorID) ON vieMemberRelationship.EntityID = tblFarm.FarmID
//          WHERE vieMemberRelationship.MemberID='$MemberID' AND vieMemberRelationship.strMemberTypeCode Not In ('FRMBUY') AND vieMemberRelationship.strType = 'Farm to Member' 
//             $Where
//          ORDER BY strFarm",0,"farm.php","Edit&MemberID=$MemberID&RETURN_URL=".urlencode("member.php?Action=Edit")."&RETURN_VAR=MemberID");

//       return $this->renderTable("Member Farms");
//    }

//    public static function Save(&$FarmID)
//    {
//       global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
//       $db = new NemoDatabase("tblFarm", $FarmID, null, 0);
// //print_rr($db->Fields);
//       $db->SetValues($_POST);
//       $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

//       foreach ($db->FieldList as $i => $field) { //remove RegArgs from db object else resaving a serialized value will break it " > '
//          if($field->name == "RegistrationArgs"){
//             unset($db->FieldList[$i], $db->Fields["RegistrationArgs"]);
//             break;
//          }
//       }

//       if($_POST[refInspectorID] < 1){         
//          $db->Fields["refInspectorID"] = "NULL";
//       }
// //print_rr($db->Fields);
//       $result = $db->Save(0,1);
//       //die;
      
//       if($FarmID == 0) 
//       {
//          $FarmID = $db->ID[FarmID];
//       }

//       if($_POST[MemberID] != "") 
//       {
//          //print_rr($_POST); die;
//       }

//       //print_rr($result);
//       if($result->Error == 1){
//          return $result->Message;
//       }else{
//          return "Details Saved. ";
//       }
//    }

//    public static function xDelete($chkSelect)
//    {//todo
//       global $xdb;
//       //print_rr($chkSelect);
//       if(count($chkSelect) > 0){
//       foreach($chkSelect as $key => $value)
//       {
//          // $xdb->doQuery("DELETE FROM tblLocation WHERE LocationID = ". $xdb->qs($key));
//          //$xdb->doQuery("UPDATE tblFarm SET blnActive = 0 WHERE FarmID = ". $xdb->qs($key));
//       }
//          return "Records Deleted.";
//       }
//   }
//}
?>
<?php
class Block extends NemoList
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


   public function getFarmBlocks($FarmID,$blnApproved=0,$return_url)
   {
      global $xdb;
      if(!$return_url){
         $return_url = urlencode("farm.pending.php?FarmID=$FarmID&Action=Edit");
      }
      $table = 'tmpBlock';
      if($blnApproved!=0){
         $table = 'tblBlock';
      }

      //Build where clauses  
      $this->isSelectable = 0;
      $this->ListSQL("SELECT ID, strBlock AS Name, BlockID AS Reference,strLastUser, dtLastEdit
            FROM $table
            WHERE ((($table.refFarmID)=$FarmID))
         ORDER BY strBlock",  0,"block.php","Edit&RETURN_URL=".$return_url);

      return $this->renderTable("Farm Blocks");
   }



}
