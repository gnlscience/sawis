<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `tblLocation` drop FOREIGN KEY `tblLocation.strGeo`;
ALTER TABLE `tblLocation` ADD CONSTRAINT `tblLocation.strGeo` FOREIGN KEY (`refGeoID`) REFERENCES `tblGeo` (`GeoID`) ON UPDATE CASCADE ON DELETE CASCADE 
*/

//Additional / page specific translations

class MemberType extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";  

      $this->Filters[frCategory]->tag = "select";
      $this->Filters[frCategory]->html->value = "-1";
      $this->Filters[frCategory]->html->class = "controlText";
      $this->Filters[frCategory]->sql = " SELECT -1 AS ControlValue, '- All -' AS ControlText
          UNION ALL 
          (SELECT tblMemberType.strCategory AS 'ControlValue', tblMemberType.strCategory AS 'ControlText'
            FROM tblMemberType
            GROUP BY tblMemberType.strCategory, tblMemberType.strCategory)
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
         $Where .= " AND (tblMemberType.strCategory $like
                            OR tblMemberType.strMemberTypeCode $like
                            OR tblMemberType.strMemberType $like
                            OR tblMemberType.EN_strMemberType $like
                            OR tblMemberType.AF_strMemberType $like
                            OR tblMemberType.txtNotes $like)";
      }

      if($this->Filters[frCategory]->html->value != -1)
      {
        $Where .= " AND strCategory = '". $this->Filters[frCategory]->html->value ."'";
      }

      $this->ListSQL("
      SELECT tblMemberType.MemberTypeID, tblMemberType.strMemberTypeCode AS 'Type Code', tblMemberType.strCategory AS 'Category', tblMemberType.strMemberType AS 'Member Type', blnActive AS 'Active', tblMemberType.strLastUser AS 'Last User', tblMemberType.dtLastEdit AS 'Last Edit'
      FROM tblMemberType
      WHERE 1=1 $Where
      ORDER BY tblMemberType.strMemberTypeCode",0);

      return $this->renderTable("Member Type List");
   }

   public static function Save(&$MemberTypeID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblMemberType", $MemberTypeID, null, 0);
//print_rr($db->Fields);
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

//print_rr($db->Fields);
      $result = $db->Save(0,0);
      //die;
      
      if($MemberTypeID == 0) 
      {
         $MemberTypeID = $db->ID[MemberTypeID];
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
         $xdb->doQuery("UPDATE tblMemberType SET blnActive = 0 WHERE MemberTypeID = ". $xdb->qs($key));
      }
         return "Records deactivated. ";
      }
   }
}
?>
