<?php
include_once("_framework/_nemo.list.cls.php");

/*
ALTER TABLE `tblLocation` drop FOREIGN KEY `tblLocation.strGeo`;
ALTER TABLE `tblLocation` ADD CONSTRAINT `tblLocation.strGeo` FOREIGN KEY (`refGeoID`) REFERENCES `tblGeo` (`GeoID`) ON UPDATE CASCADE ON DELETE CASCADE 
*/

//Additional / page specific translations

class Trellising extends NemoList
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
         $Where .= " AND (tblTrellis.strTrellis $like
                            OR tblTrellis.EN_strTrellis $like
                            OR tblTrellis.AF_strTrellis $like
                            OR tblTrellis.EN_strDescription $like
                            OR tblTrellis.AF_strDescription $like
                            OR tblTrellis.txtNotes $like)";
      }

      $this->ListSQL("
         SELECT tblTrellis.TrellisID, tblTrellis.TrellisCode AS 'Code', tblTrellis.strTrellis AS 'Trellis',
            tblTrellis.EN_strDescription AS 'Description', strFilename as Filename, tblTrellis.txtNotes AS 'Notes',
            tblTrellis.blnActive AS 'Active', tblTrellis.strLastUser AS 'Last User',
            tblTrellis.dtLastEdit AS 'Last Edit'
         FROM tblTrellis
         WHERE 1=1 $Where
         ORDER BY tblTrellis.TrellisCode",0);

      return $this->renderTable("Trellis List");
   }

   public static function Save(&$TrellisID)
   {
      global $xdb, $arrSys, $TR, $SP, $HR, $DATABASE_SETTINGS, $SystemSettings;
      
      $db = new NemoDatabase("tblTrellis", $TrellisID, null, 0);
//print_rr($db->Fields);
      $db->SetValues($_POST);
      $db->Fields[strLastUser] = $_SESSION['USER']->USERNAME;

//print_rr($db->Fields);
      $result = $db->Save(0,0);
      //die;
      
      if($TrellisID == 0) 
      {
         $TrellisID = $db->ID[TrellisID];
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
         return "Records Deleted.";
      }
   }
}
?>
