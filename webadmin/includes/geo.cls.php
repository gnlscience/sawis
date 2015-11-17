<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `tblGLAccountName` drop FOREIGN KEY `tblGLAccountName.strCompany`;
ALTER TABLE `tblGLAccountName` ADD CONSTRAINT `tblGLAccountName.strCompany` FOREIGN KEY (`refCompanyID`) REFERENCES `tblCompany` (`CompanyID`) ON UPDATE CASCADE ON DELETE CASCADE 
*/

//Additional / page specific translations

class Geo extends NemoList
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

      if($this->Filters[frSearch]->html->value != "")
      {
         $like = "LIKE(". $this->db->qs("%".$this->Filters[frSearch]->html->value."%") .")";
         $Where .= " AND (strGeo $like OR EN_strGeo $like OR AF_strGeo $like)";
      } 
//tblGeo.GeoID, tblGeo.strTitle AS 'Title' , tblGeo.strTags AS 'Tags', tblGeo.strLastUser AS 'Last User', tblGeo.dtLastEdit AS 'Last Edit'
      $this->ListSQL("SELECT tblGeo.GeoID, tblGeo.GeoCode AS Code, tblGeo.strGeo AS 'Geographical Location', tblGeo.EN_strGeo AS 'Description EN', tblGeo.AF_strGeo AS 'Description AF', tblGeo.blnActive AS Active, tblGeo.strLastUser AS 'Last User', tblGeo.dtLastEdit AS 'Last Edit'
                     FROM tblGeo
                     WHERE 1=1 $Where
                     ORDER BY strGeo",1);


      return $this->renderTable("Geo List");
   }

   public static function Save(&$GeoID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblGeo", $GeoID, null, 0);
//print_rr($db->Fields);
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

//print_rr($db->Fields);
      $result = $db->Save(0,1);
      //die;
      
      if($GeoID == 0) 
      {
         $GeoID = $db->ID[GeoID];
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
         $xdb->doQuery("DELETE FROM tblGeo WHERE GeoID = ". $xdb->qs($key));
         //$xdb->doQuery("UPDATE tblGLAccountName SET blnActive = 0 WHERE GLAccountNameID = ". $xdb->qs($key));
      }
         return "Records Deleted.";
      }
   }
}
?>
