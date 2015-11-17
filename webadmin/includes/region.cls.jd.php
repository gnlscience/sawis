<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `tblRegion` drop FOREIGN KEY `tblRegion.strGeo`;
ALTER TABLE `tblRegion` ADD CONSTRAINT `tblRegion.strGeo` FOREIGN KEY (`refGeoID`) REFERENCES `tblGeo` (`GeoID`) ON UPDATE CASCADE ON DELETE CASCADE 
*/

//Additional / page specific translations

class Region extends NemoList
{
   private $ID = 0;

   public function __construct($DataKey)
   {
      
      $this->Filters[frGeographicalLocation]->tag = "select";
      $this->Filters[frGeographicalLocation]->html->value = "0";
      $this->Filters[frGeographicalLocation]->html->class = "controlText";   
      $this->Filters[frGeographicalLocation]->sql = "SELECT 0 AS ControlValue, '- All -' AS ControlText
                        UNION ALL
                        SELECT GeoID AS ControlValue, strGeo AS ControlText
                        FROM tblGeo
                        ORDER BY ControlText ASC";

      $this->Filters[frSearch]->tag = "input";
      $this->Filters[frSearch]->html->value = "";
      $this->Filters[frSearch]->html->class = "controlText";  

      parent::__construct($DataKey);
   }

   public function getList()
   {
      global $xdb;


      if($this->Filters[frGeographicalLocation]->html->value != 0)
      {
         $Where .= " AND refGeoID = ". $this->Filters[frGeographicalLocation]->html->value;
      }
      
      if($this->Filters[frSearch]->html->value != "")
      {
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")";
         $Where .= " AND (strRegion $like OR EN_strRegion $like OR AF_strRegion $like)";
      } 
//tblRegion.RegionID, tblRegion.RegionCode AS Code, tblRegion.strRegion AS 'Region', tblRegion.EN_strRegion AS 'Description EN', tblRegion.AF_strRegion AS 'Description AF', tblGeo.strGeo AS GeoRegion tblRegion.blnActive AS Active, tblRegion.strLastUser AS 'Last User', tblRegion.dtLastEdit AS 'Last Edit''
      $this->ListSQL("SELECT tblRegion.RegionID, tblRegion.RegionCode AS Code, tblRegion.strRegion AS 'Region', tblRegion.EN_strRegion AS 'Description EN', tblRegion.AF_strRegion AS 'Description AF', tblGeo.strGeo AS GeoRegion, tblRegion.blnActive AS Active, tblRegion.strLastUser AS 'Last User', tblRegion.dtLastEdit AS 'Last Edit'
                     FROM tblRegion, tblGeo
                     WHERE tblRegion.refGeoID = tblGeo.GeoID
                     ORDER BY strRegion",1);

      return $this->renderTable("Region List");
   }

   public static function Save(&$RegionID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblRegion", $RegionID, null, 0);
//print_rr($db->Fields);
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

//print_rr($db->Fields);
      $result = $db->Save(0,1);
      //die;
      
      if($RegionID == 0) 
      {
         $RegionID = $db->ID[RegionID];
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
         $xdb->doQuery("DELETE FROM tblRegion WHERE RegionID = ". $xdb->qs($key));
         //$xdb->doQuery("UPDATE tblGLAccountName SET blnActive = 0 WHERE GLAccountNameID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }
}
?>
