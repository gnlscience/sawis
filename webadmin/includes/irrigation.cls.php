<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `tblLocation` drop FOREIGN KEY `tblLocation.strGeo`;
ALTER TABLE `tblLocation` ADD CONSTRAINT `tblLocation.strGeo` FOREIGN KEY (`refGeoID`) REFERENCES `tblGeo` (`GeoID`) ON UPDATE CASCADE ON DELETE CASCADE 
*/

//Additional / page specific translations

class Irrigation extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";  

      parent::__construct($DataKey);
   }

   public function getList()
   {
      global $xdb;
      
      //Build where clauses  
      if($this->Filters[frSearch]->html->value != "")
      {//TODO: expand Search WHERE
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")"; 
         $Where .= " AND (tblIrrigation.strIrrigation $like
                            OR tblIrrigation.EN_strIrrigation $like
                            OR tblIrrigation.AF_strIrrigation $like
                            OR tblIrrigation.txtNotes $like)";
      }

      $this->ListSQL("
                SELECT tblIrrigation.IrrigationID, tblIrrigation.strIrrigation AS 'Irrigation', tblIrrigation.IrrigationCode AS 'Code',
                 tblIrrigation.txtNotes AS 'Notes', blnActive AS 'Active',
                 tblIrrigation.strLastUser AS 'Last User', tblIrrigation.dtLastEdit AS 'Last Edit'
                FROM tblIrrigation
                WHERE 1=1 $Where
                ORDER BY tblIrrigation.strIrrigation",0);

      return $this->renderTable("Irrigation List");
   }

   public static function Save(&$IrrigationID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblIrrigation", $IrrigationID, null, 0);
//print_rr($db->Fields);
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

//print_rr($db->Fields);
      $result = $db->Save(0,0);
      //die;
      
      if($IrrigationID == 0) 
      {
         $IrrigationID = $db->ID[IrrigationID];
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
         $xdb->doQuery("UPDATE tblIrrigation SET blnActive = 0 WHERE IrrigationID = ". $xdb->qs($key));
      }
         return "Records deactivated. ";
      }
   }
}
?>
